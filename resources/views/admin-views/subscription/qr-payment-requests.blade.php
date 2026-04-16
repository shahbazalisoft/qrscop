@extends('layouts.admin.app')

@section('title', translate('QR Payment Requests'))

@section('qr_payment_requests')
active
@endsection

@push('css_or_js')
@endpush

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
            <div class="flex-grow-1">
                <h1 class="page-header-title">{{ translate('QR Payment Requests') }}</h1>
            </div>
        </div>
    </div>

    <!-- QR Payment Settings -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title"><i class="tio-settings"></i> {{ translate('QR Payment Settings') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.subscription.subscriptionackage.qrPaymentSettingsUpdate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label">{{ translate('QR Code Image') }}</label>
                            <div class="custom-file">
                                <input type="file" name="qr_payment_image" class="custom-file-input" accept="image/*" id="qr_settings_image_input">
                                <label class="custom-file-label" for="qr_settings_image_input">{{ translate('Choose file') }}</label>
                            </div>
                            @if($qr_payment_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/app/public/qr_payment/' . $qr_payment_image) }}" alt="QR" class="rounded border" style="max-height: 150px;">
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="input-label">{{ translate('Payment Details / Instructions') }}</label>
                            <textarea name="qr_payment_details" class="form-control" rows="4" placeholder="{{ translate('e.g., UPI ID: example@upi, Bank: XYZ Bank, A/C: 1234567890') }}">{{ $qr_payment_details }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn--primary">{{ translate('Save Settings') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="card">
        <div class="card-header border-0">
            <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
                <li class="nav-item">
                    <a href="{{ route('admin.settings.subscription.subscriptionackage.qrPaymentRequests', ['status' => 'pending']) }}" class="nav-link {{ $status == 'pending' ? 'active' : '' }}">
                        {{ translate('Pending') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.subscription.subscriptionackage.qrPaymentRequests', ['status' => 'approved']) }}" class="nav-link {{ $status == 'approved' ? 'active' : '' }}">
                        {{ translate('Approved') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.subscription.subscriptionackage.qrPaymentRequests', ['status' => 'rejected']) }}" class="nav-link {{ $status == 'rejected' ? 'active' : '' }}">
                        {{ translate('Rejected') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.subscription.subscriptionackage.qrPaymentRequests', ['status' => 'all']) }}" class="nav-link {{ $status == 'all' ? 'active' : '' }}">
                        {{ translate('All') }}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless table-thead-bordered table-align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('SL') }}</th>
                            <th>{{ translate('Store') }}</th>
                            <th>{{ translate('Plan') }}</th>
                            <th>{{ translate('Amount') }}</th>
                            <th>{{ translate('Sender') }}</th>
                            <th>{{ translate('Reference') }}</th>
                            <th>{{ translate('Screenshot') }}</th>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th class="text-center">{{ translate('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $key => $req)
                        <tr>
                            <td>{{ $requests->firstItem() + $key }}</td>
                            <td>
                                <strong>{{ $req->store?->name ?? '-' }}</strong>
                            </td>
                            <td>{{ $req->package?->package_name ?? '-' }}</td>
                            <td>{{ \App\CentralLogics\Helpers::format_currency($req->amount) }}</td>
                            <td>
                                <div>{{ $req->sender_name }}</div>
                                <small class="text-muted">{{ $req->sender_phone }}</small>
                            </td>
                            <td><code>{{ $req->transaction_reference }}</code></td>
                            <td>
                                @if($req->payment_screenshot)
                                <a href="{{ asset('storage/app/public/qr_payment/' . $req->payment_screenshot) }}" target="_blank">
                                    <img src="{{ asset('storage/app/public/qr_payment/' . $req->payment_screenshot) }}" class="rounded border" style="max-height: 50px; max-width: 50px;">
                                </a>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $req->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                @if($req->status == 'pending')
                                    <span class="badge badge-warning">{{ translate('Pending') }}</span>
                                @elseif($req->status == 'approved')
                                    <span class="badge badge-success">{{ translate('Approved') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ translate('Rejected') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($req->status == 'pending')
                                <div class="d-flex gap-2 justify-content-center">
                                    <form action="{{ route('admin.settings.subscription.subscriptionackage.qrPaymentRequestAction', $req->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn--primary" onclick="return confirm('{{ translate('Approve this payment and activate subscription?') }}')">
                                            <i class="tio-checkmark-circle"></i> {{ translate('Approve') }}
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn--danger" data-toggle="modal" data-target="#reject-modal-{{ $req->id }}">
                                        <i class="tio-clear-circle"></i> {{ translate('Reject') }}
                                    </button>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="reject-modal-{{ $req->id }}">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ translate('Reject Payment') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <form action="{{ route('admin.settings.subscription.subscriptionackage.qrPaymentRequestAction', $req->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>{{ translate('Reason') }}</label>
                                                        <textarea name="admin_note" class="form-control" rows="3" placeholder="{{ translate('Enter rejection reason...') }}"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                                                    <button type="submit" class="btn btn--danger">{{ translate('Reject') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @elseif($req->status == 'rejected' && $req->admin_note)
                                    <small class="text-danger">{{ $req->admin_note }}</small>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                {{ translate('No QR payment requests found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->count() > 0)
            <div class="card-footer border-0">
                {{ $requests->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
