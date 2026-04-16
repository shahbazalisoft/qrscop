<div class="package-cards-grid">
    @forelse ($packages as $key=> $package)
        <label class="package-card">
            <input type="radio" name="package_id" {{ $key == 0 ? 'checked' : '' }}
                id="package_id{{ $key }}" value="{{ $package->id }}">
            <div class="package-card-inner">
                <div class="package-check">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="package-header">
                    <h4 class="package-name">{{ $package->package_name }}</h4>
                    <div class="package-price">
                        {{ \App\CentralLogics\Helpers::format_currency($package->price) }}
                    </div>
                    <div class="package-validity">
                        <i class="bi bi-calendar3"></i>
                        {{ $package->validity }} {{ translate('messages.days') }}
                    </div>
                </div>
                <ul class="package-features">
                    @if ($package->pos)
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ translate('messages.POS') }}</span>
                        </li>
                    @endif
                    @if ($package->mobile_app)
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ translate('messages.mobile_app') }}</span>
                        </li>
                    @endif
                    @if ($package->chat)
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ translate('messages.chatting_options') }}</span>
                        </li>
                    @endif
                    @if ($package->review)
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ translate('messages.review_section') }}</span>
                        </li>
                    @endif
                    @if ($package->self_delivery)
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ translate('messages.self_delivery') }}</span>
                        </li>
                    @endif
                    @if ($package->max_order == 'unlimited')
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ isset($module) && $module == 'rental' ? translate('messages.Unlimited_Trips') : translate('messages.Unlimited_Orders') }}</span>
                        </li>
                    @else
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ $package->max_order }} {{ isset($module) && $module == 'rental' ? translate('messages.Trips') : translate('messages.Orders') }}</span>
                        </li>
                    @endif
                    @if ($package->max_product == 'unlimited')
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ translate('messages.Unlimited_uploads') }}</span>
                        </li>
                    @else
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ $package->max_product }} {{ translate('messages.uploads') }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </label>
    @empty
        <div class="no-packages">
            <i class="bi bi-box-seam"></i>
            <p>{{ translate('No packages available') }}</p>
        </div>
    @endforelse
</div>
