<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentRequest;
use App\Traits\Processor;
use Illuminate\Support\Facades\Http;
use App\CentralLogics\Helpers;

class PhonePePaymentController extends Controller
{
    use Processor;

    private $config_values;
    private PaymentRequest $payment;
    private $base_url;

    public function __construct(PaymentRequest $payment)
    {
        $config = $this->payment_config('phonepe', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $this->config_values = json_decode($config->live_values);
            $this->base_url = 'https://api.phonepe.com/apis/hermes';
        } elseif (!is_null($config) && $config->mode == 'test') {
            $this->config_values = json_decode($config->test_values);
            $this->base_url = 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        }
        $this->payment = $payment;
    }

    public function index(Request $request): View|Factory|JsonResponse|Application
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
        }

        $data = $this->payment::where(['id' => $request['payment_id']])->where(['is_paid' => 0])->first();
        if (!isset($data)) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }

        $config = $this->config_values;

        return view('payment-views.phonepe', compact('data', 'config'));
    }

    public function initiate(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->payment::where(['id' => $request['payment_id']])->where(['is_paid' => 0])->first();
        if (!isset($data)) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }

        $merchant_id = $this->config_values->merchant_id;
        $salt_key = $this->config_values->salt_key;
        $salt_index = $this->config_values->salt_index;

        $transaction_id = 'TXN' . time() . rand(1000, 9999);
        $amount = round($data['payment_amount'] * 100); // PhonePe expects amount in paise

        $payload = [
            'merchantId' => $merchant_id,
            'merchantTransactionId' => $transaction_id,
            'merchantUserId' => 'USER' . $data->id,
            'amount' => $amount,
            'redirectUrl' => url('/payment/phonepe/callback?payment_id=' . $data->id),
            'redirectMode' => 'GET',
            'callbackUrl' => url('/payment/phonepe/callback?payment_id=' . $data->id),
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $json_payload = json_encode($payload);
        $base64_payload = base64_encode($json_payload);
        $checksum = hash('sha256', $base64_payload . '/pg/v1/pay' . $salt_key) . '###' . $salt_index;

        try {
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
                'X-VERIFY' => $checksum
            ])->post($this->base_url . '/pg/v1/pay', [
                'request' => $base64_payload
            ]);

            $result = $response->json();

            // Log the response for debugging
            \Log::info('PhonePe API Response', ['response' => $result, 'status' => $response->status()]);

            if (isset($result['success']) && $result['success'] && isset($result['data']['instrumentResponse']['redirectInfo']['url'])) {
                // Store transaction ID for later verification
                $this->payment::where(['id' => $data->id])->update([
                    'transaction_id' => $transaction_id
                ]);

                return redirect()->away($result['data']['instrumentResponse']['redirectInfo']['url']);
            } else {
                $errorMsg = $result['message'] ?? 'Payment initiation failed';
                if (isset($result['code'])) {
                    $errorMsg .= ' (Code: ' . $result['code'] . ')';
                }
                \Log::error('PhonePe Payment Failed', ['result' => $result]);
                return redirect()->back()->with('error', $errorMsg);
            }
        } catch (\Exception $e) {
            \Log::error('PhonePe Exception', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $data = $this->payment::where(['id' => $request['payment_id']])->first();

        if (!isset($data)) {
            \Log::error('PhonePe Callback: Payment record not found', ['payment_id' => $request['payment_id']]);
            return redirect('/');
        }

        $redirect_link = $data->external_redirect_link;
        $flag = 'fail';

        try {
            // If already paid, redirect to success directly
            if ($data->is_paid == 1) {
                $flag = 'success';
            } else {
                $merchant_id = $this->config_values->merchant_id;
                $salt_key = $this->config_values->salt_key;
                $salt_index = $this->config_values->salt_index;
                $transaction_id = $data->transaction_id;

                $checksum = hash('sha256', '/pg/v1/status/' . $merchant_id . '/' . $transaction_id . $salt_key) . '###' . $salt_index;

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $checksum,
                    'X-MERCHANT-ID' => $merchant_id
                ])->get($this->base_url . '/pg/v1/status/' . $merchant_id . '/' . $transaction_id);

                $result = $response->json();

                \Log::info('PhonePe Callback Status Check', ['payment_id' => $request['payment_id'], 'result' => $result]);

                if (isset($result['success']) && $result['success'] && isset($result['data']['state']) && $result['data']['state'] == 'COMPLETED') {
                    $this->payment::where(['id' => $request['payment_id']])->update([
                        'payment_method' => 'phonepe',
                        'is_paid' => 1,
                        'transaction_id' => $result['data']['transactionId'] ?? $transaction_id,
                    ]);

                    $data->refresh();
                    Helpers::apply_referral_reward($data);
                    if (function_exists($data->success_hook)) {
                        call_user_func($data->success_hook, $data);
                    }

                    $flag = 'success';
                } else {
                    if (function_exists($data->failure_hook)) {
                        call_user_func($data->failure_hook, $data);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('PhonePe Callback Exception', ['message' => $e->getMessage(), 'payment_id' => $request['payment_id'] ?? null]);

            if ($data->is_paid == 1) {
                $flag = 'success';
            } else if (function_exists($data->failure_hook)) {
                call_user_func($data->failure_hook, $data);
            }
        }

        // Always redirect to the original page that initiated payment
        $final_url = $redirect_link ? $redirect_link . '?flag=' . $flag : '/';

        return response('<html><head><meta http-equiv="refresh" content="0;url=' . e($final_url) . '"></head><body><script>window.location.href="' . e($final_url) . '";</script></body></html>');
    }

    public function thankYou(Request $request)
    {
        $status = $request->query('status', 'success');
        return view('payment-views.phonepe-thank-you', compact('status'));
    }

    public function canceled(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data, 'cancel');
    }
}
