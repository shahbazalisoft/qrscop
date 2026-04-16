<!-- Step Indicator -->
@php
    $subscription_enabled = \App\CentralLogics\Helpers::subscription_check();
    $total_steps = $subscription_enabled ? 3 : 2;
@endphp
<div class="step-indicator">
    <div class="step {{ $current_step >= 1 ? ($current_step > 1 ? 'completed' : 'active') : '' }}">
        <div class="step-number">
            @if($current_step > 1)
                <i class="bi bi-check-lg"></i>
            @else
                1
            @endif
        </div>
        <span>{{ translate('Account') }}</span>
    </div>

    @if($subscription_enabled)
        <div class="step-line {{ $current_step > 1 ? 'completed' : '' }}"></div>
        <div class="step {{ $current_step >= 2 ? ($current_step > 2 ? 'completed' : 'active') : '' }}">
            <div class="step-number">
                @if($current_step > 2)
                    <i class="bi bi-check-lg"></i>
                @else
                    2
                @endif
            </div>
            <span>{{ translate('Business Plan') }}</span>
        </div>
    @endif

    <div class="step-line {{ $current_step >= ($subscription_enabled ? 3 : 2) ? 'completed' : '' }}"></div>
    <div class="step {{ $current_step >= ($subscription_enabled ? 3 : 2) ? (isset($payment_failed) && $payment_failed ? 'failed' : 'completed') : '' }}">
        <div class="step-number">
            @if(isset($payment_failed) && $payment_failed)
                <i class="bi bi-x-lg"></i>
            @elseif($current_step >= ($subscription_enabled ? 3 : 2))
                <i class="bi bi-check-lg"></i>
            @else
                {{ $subscription_enabled ? 3 : 2 }}
            @endif
        </div>
        <span>{{ translate('Complete') }}</span>
    </div>
</div>
