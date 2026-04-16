@extends('layouts.vendor.app')

@section('title', translate('messages.today_special'))

@push('css_or_js')
    <style>
        .day-card { margin-bottom: 1.5rem; }
        .day-card .card-header { display: flex; align-items: center; justify-content: space-between; }
        .day-card .day-title { font-size: 1rem; font-weight: 600; text-transform: capitalize; }
        .special-item { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
        .special-item:last-child { border-bottom: none; }
        .special-item img { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; }
        .special-item .item-info { flex: 1; }
        .special-item .item-name { font-weight: 500; font-size: 0.9rem; }
        .special-item .item-price { color: #888; font-size: 0.85rem; }
        .today-badge { background: #ff6b35; color: #fff; font-size: 0.7rem; padding: 2px 8px; border-radius: 10px; }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <i class="tio-star" style="font-size: 1.5rem;"></i>
                </span>
                <span>{{ translate('messages.today_special') }}</span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="row g-3">
            @foreach ($days as $day)
                @php
                    $daySpecials = $specials->get($day, collect());
                    $isToday = strtolower(now()->format('l')) === $day;
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card day-card h-100">
                        <div class="card-header">
                            <div class="d-flex align-items-center gap-2">
                                <span class="day-title">{{ ucfirst($day) }}</span>
                                @if ($isToday)
                                    <span class="today-badge">{{ translate('messages.today') }}</span>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn--primary" data-toggle="modal"
                                data-target="#addItemModal-{{ $day }}">
                                <i class="tio-add"></i> {{ translate('messages.add') }}
                            </button>
                        </div>
                        <div class="card-body">
                            @forelse ($daySpecials as $special)
                                @if ($special->item)
                                    <div class="special-item">
                                        <img class="onerror-image"
                                            data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                            src="{{ $special->item->image_full_url }}" alt="{{ $special->item->name }}">
                                        <div class="item-info">
                                            <div class="item-name">{{ Str::limit($special->item->name, 25, '...') }}</div>
                                            <div class="item-price">
                                                {{ \App\CentralLogics\Helpers::format_currency($special->item->price) }}
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('vendor.today-special.status', [$special->id, $special->status ? 0 : 1]) }}"
                                                class="btn btn-sm {{ $special->status ? 'btn-success' : 'btn-secondary' }}"
                                                title="{{ $special->status ? translate('messages.active') : translate('messages.inactive') }}">
                                                <i class="tio-{{ $special->status ? 'checkmark-circle' : 'clear-circle' }}"></i>
                                            </a>
                                            <a class="btn btn-sm btn--danger btn-outline-danger form-alert"
                                                href="javascript:" data-id="special-{{ $special->id }}"
                                                data-message="{{ translate('messages.Want_to_delete_this_item') }}"
                                                title="{{ translate('messages.delete') }}">
                                                <i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{ route('vendor.today-special.destroy', [$special->id]) }}"
                                                method="post" id="special-{{ $special->id }}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center text-muted py-3">
                                    <small>{{ translate('messages.no_special_items') }}</small>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Add Item Modal for {{ $day }} -->
                <div class="modal fade" id="addItemModal-{{ $day }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    {{ translate('messages.add_item_to') }} {{ ucfirst($day) }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('vendor.today-special.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="day" value="{{ $day }}">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('messages.select_item') }}</label>
                                        <select name="item_id" class="form-control js-select2-custom" required
                                            data-placeholder="{{ translate('messages.select_item') }}">
                                            <option value="" disabled selected>{{ translate('messages.select_item') }}</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }} - {{ \App\CentralLogics\Helpers::format_currency($item->price) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        {{ translate('messages.close') }}
                                    </button>
                                    <button type="submit" class="btn btn--primary">
                                        {{ translate('messages.add') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        "use strict";
        $(document).ready(function() {
            $('.js-select2-custom').select2();
        });
    </script>
@endpush
