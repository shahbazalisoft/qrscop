<div class="col-lg-12">
    <div class="general_wrapper">
        <div class="outline-wrapper">
            <div class="card shadow--card-2 border-0 bg-animate">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon mr-2">
                            <i class="tio-tune-horizontal"></i>
                        </span>
                        <span> {{ translate('Store_&_Category_Info') }} </span>
                    </h5>
                    @if (isset($openai_config) && data_get($openai_config, 'status') == 1)
                        <button type="button"
                            class="btn bg-white text-primary opacity-1 generate_btn_wrapper p-0 mb-2 general_setup_auto_fill"
                            id="general_setup_auto_fill"
                            data-route="{{ route('admin.product.general-setup-auto-fill') }}"
                            data-error="{{ translate('Please provide an item name and description so the AI can generate a suitable data.') }}"
                            data-restaurant-id="" data-lang="en">
                            <div class="btn-svg-wrapper">
                                <img width="18" height="18" class=""
                                    src="{{ asset('public/assets/admin/img/svg/blink-right-small.svg') }}"
                                    alt="">
                            </div>
                            <span class="ai-text-animation d-none" role="status">
                                {{ translate('Just_a_second') }}
                            </span>
                            <span class="btn-text">{{ translate('Generate') }}</span>
                        </button>
                    @endif

                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @php($column = 4)
                        @if (Auth::guard('admin')->check())
                            <div class="col-sm-6 col-lg-3">

                                <div class="form-group mb-0 error-wrapper">
                                    <label class="input-label" for="store_id">{{ translate('messages.store') }} <span
                                            class="form-label-secondary text-danger" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('messages.Required.') }}"> *
                                        </span><span class="input-label-secondary"></span></label>
                                    <select name="store_id" id="store_id"
                                        title="{{ translate('messages.select_store') }}"
                                        {{ isset(request()->product_gellary) == false ? 'required' : '' }}
                                        data-placeholder="{{ translate('messages.select_store') }}"
                                        class="js-data-example-ajax form-control">
                                        @if (isset($product->store) && request()->product_gellary != 1)
                                            <option value="{{ $product->store_id }}" selected="selected">
                                                {{ $product->store->name }}</option>
                                        @endif
                                    </select>
                                </div>


                            </div>
                            @php($column = 3)
                            <div class="col-sm-6 col-lg-{{ $column }}">
                                <div class="form-group mb-0 error-wrapper">
                                    <label class="input-label"
                                        for="exampleFormControlSelect1">{{ translate('messages.category') }}<span
                                            class="form-label-secondary text-danger" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('messages.Required.') }}"> *
                                        </span></label>
                                    <select name="category_id" id="category_id"
                                        data-placeholder="{{ translate('Select_Category') }}"
                                        @if (!Auth::guard('admin')->check()) data-url="{{ url('/') }}/vendor-panel/item/get-categories?parent_id=" data-id="sub-categories" @endif
                                        class="form-control js-data-example-ajax get-request" required>

                                        @if (isset($category))
                                            <option selected value="{{ $category['id'] }}">{{ $category['name'] }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="col-sm-6 col-lg-4">
                                <div class="form-group mb-0 error-wrapper">
                                    <label class="input-label"
                                        for="exampleFormControlSelect1">{{ translate('messages.category') }}<span
                                            class="input-label-secondary">*</span></label>
                                    <select name="category_id" id="category_id"
                                        class="form-control js-select2-custom get-request"
                                        data-url="{{ url('/') }}/vendor-panel/item/get-categories?parent_id="
                                        data-id="sub-categories">
                                        <option value="">---{{ translate('messages.select') }}---</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category['id'] }}"
                                                {{ isset($product) && $category->id == $product_category[0]->id ? 'selected' : '' }}>
                                                {{ $category['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        @endif
                        <div class="col-sm-6 col-lg-{{ $column }}">


                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label"
                                    for="exampleFormControlSelect1">{{ translate('messages.Seaech_Tags') }}<span
                                        class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('messages.add_keywords_customers_may_use_when_searching_for_this_product') }}"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.add_keywords_customers_may_use_when_searching_for_this_product') }}"></span></label>

                                <input type="text" class="form-control" id="tags" name="tags"
                                    placeholder="{{ translate('messages.search_tags') }}"
                                    @if (isset($product)) value="@foreach ($product->tags as $c) {{ $c->tag . ',' }} @endforeach" @endif
                                    data-role="tagsinput">
                            </div>


                        </div>

                        {{-- @if (Config::get('module.current_module_type') == 'food') --}}
                            <div class="col-sm-6 col-lg-{{ $column }}" id="veg_input">
                                <div class="form-group mb-0 error-wrapper">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.item_type') }} <span
                                            class="form-label-secondary text-danger" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('messages.Required.') }}"> *
                                        </span></label>
                                    <select name="veg" id="veg" class="form-control js-select2-custom"
                                        required>
                                        <option {{ isset($product) && $product->veg == 1 ? 'selected' : '' }}
                                            value="1">{{ translate('messages.veg') }}</option>
                                        <option {{ isset($product) && $product->veg == 0 ? 'selected' : '' }}
                                            value="0">{{ translate('messages.non_veg') }}</option>
                                    </select>
                                </div>
                            </div>
                        {{-- @endif --}}
{{-- 
                        <div class="col-sm-6 col-lg-4 error-wrapper" id="organic">
                            <div class="form-check mb-sm-2 pb-sm-1">
                                <input class="form-check-input" name="organic" type="checkbox" value="1"
                                    id="is_organic" {{ isset($product) && $product->organic == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_organic">
                                    {{ translate('messages.is_organic') }}
                                </label>
                            </div>
                        </div> --}}


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if (Config::get('module.current_module_type') == 'food')
    @if (Auth::guard('admin')->check())
        <div class="col-lg-6" id="addon_input">
            <div class="general_wrapper">
                <div class="outline-wrapper">
                    <div class="card shadow--card-2 border-0 bg-animate">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-header-icon mr-2">
                                    <i class="tio-dashboard-outlined"></i>
                                </span>
                                <span>{{ translate('messages.addon') }}</span>
                            </h5>
                        </div>
                        <div class="card-body error-wrapper">
                            <label class="input-label"
                                for="exampleFormControlSelect1">{{ translate('Select_Add-on') }}<span
                                    class="input-label-secondary" data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.The_selected_addon’s_will_be_displayed_in_this_food_details') }}"><img
                                        src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                        alt="{{ translate('messages.The_selected_addon’s_will_be_displayed_in_this_food_details') }}"></span></label>
                            <select name="addon_ids[]" class="form-control border js-select2-custom"
                                multiple="multiple" id="add_on">

                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-lg-6">
            <div class="general_wrapper">
                <div class="outline-wrapper">
                    <div class="card shadow--card-2 border-0 bg-animate">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-header-icon"><i class="tio-puzzle"></i></span>
                                <span>{{ translate('addon') }}</span>
                            </h5>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="form-group error-wrapper">
                                        <label class="input-label"
                                            for="exampleFormControlSelect1">{{ translate('messages.addon') }}<span
                                                class="input-label-secondary"></span></label>
                                        <select name="addon_ids[]" class="form-control js-select2-custom"
                                            id="add_on" multiple="multiple">
                                            @foreach (\App\Models\AddOn::where('store_id', \App\CentralLogics\Helpers::get_store_id())->orderBy('name')->get() as $addon)
                                                <option value="{{ $addon['id'] }}"
                                                    {{ isset($product) && in_array($addon->id, json_decode($product['add_ons'], true)) ? 'selected' : '' }}>
                                                    {{ $addon['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="col-lg-6" id="time_input">
        <div class="general_wrapper">
            <div class="outline-wrapper">
                <div class="card shadow--card-2 border-0 bg-animate">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon mr-2"><i class="tio-date-range"></i></span>
                            <span>{{ translate('time_schedule') }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="form-group mb-0 error-wrapper">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.available_time_starts') }}<span
                                            class="form-label-secondary text-danger" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('messages.Required.') }}">
                                            *
                                        </span></label>
                                    <input type="time" name="available_time_starts"
                                        value="{{ isset($product) ? $product?->available_time_starts : old('available_time_starts') }}"
                                        class="form-control" id="available_time_starts"
                                        placeholder="{{ translate('messages.Ex:_10:30_am') }} " required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-0 error-wrapper">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.available_time_ends') }}<span
                                            class="form-label-secondary text-danger" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('messages.Required.') }}">
                                            *
                                        </span></label>
                                    <input type="time" name="available_time_ends" class="form-control"
                                        value="{{ isset($product) ? $product?->available_time_ends : old('available_time_ends') }}"
                                        id="available_time_ends" placeholder="5:45 pm" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


{{-- <div class="col-lg-12">
    <div class="general_wrapper">
        <div class="outline-wrapper">
            <div class="card shadow--card-2 border-0 bg-animate">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon mr-2"><i class="tio-label"></i></span>
                        <span>{{ translate('Seaech_Tags') }}</span>
                    </h5>
                </div>
                <div class="card-body">


                    @if (isset($temp_product) && $temp_product == 1)
                        <div class="form-group error-wrapper">
                            @php($tags = \App\Models\Tag::whereIn('id', json_decode($product?->tag_ids))->get('tag'))
                            <input type="text" class="form-control" id="tags" name="tags"
                                placeholder="{{ translate('messages.search_tags') }}"
                                value="@foreach ($tags as $c) {{ $c->tag . ',' }} @endforeach"
                                data-role="tagsinput">
                        </div>
                    @else
                        <div class="form-group error-wrapper">
                            <input type="text" class="form-control" id="tags" name="tags"
                                placeholder="{{ translate('messages.search_tags') }}"
                                @if (isset($product)) value="@foreach ($product->tags as $c) {{ $c->tag . ',' }} @endforeach" @endif
                                data-role="tagsinput">
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div> --}}
