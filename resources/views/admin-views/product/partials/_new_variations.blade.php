

<div class="__bg-F8F9FC-card count_div view_new_option mb-2">
    <div>
        
        <div id="option_price_{{ $key }}">
            <div class="bg-white border rounded p-3 pb-0 mt-3">
                    <div id="option_price_view_{{ $key }}">
                @if (isset($item['values']))
                    @foreach ($item['values'] as $key_value => $value)
                        <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-md-0">
                            <div class="col-md-4 col-sm-6 error-wrapper">
                                <label for="">{{ translate('Option_name') }}</label>
                                <input class="form-control" required type="text"
                                    name="options[{{ $key }}][values][{{ $key_value }}][label]"
                                    value="{{ $value['label'] }}">
                            </div>
                            <div class="col-md-4 col-sm-6 error-wrapper">
                                <label for="">{{ translate('Additional_price') }}</label>
                                <input class="form-control" required type="number" min="0" step="0.01"
                                    name="options[{{ $key }}][values][{{ $key_value }}][optionPrice]"
                                    value="{{ request()->product_gellary == 1 ? null : $value['optionPrice'] }}">
                            </div>
                            <div class="col-sm-2 max-sm-absolute">
                                <label class="d-none d-md-block">&nbsp;</label>
                                <div class="mt-1">
                                    <button type="button" class="btn btn-danger btn-sm deleteRow"
                                        title="{{translate('Delete')}}">
                                        <i class="tio-add-to-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    @endforeach
                @endif
            </div>
                <div class="row mt-3 p-3 mr-1 d-flex" id="add_new_button_{{ $key }}">
                    <button type="button"
                        class="btn btn--primary btn-outline-primary add_new_row_button" data-count="{{ $key }}">{{ translate('Add_New_Option') }}</button>
                </div>

            </div>




        </div>
    </div>
</div>
