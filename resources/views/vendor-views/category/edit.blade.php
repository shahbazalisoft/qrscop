
@extends('layouts.vendor.app')

@section('title', translate('messages.update_menu'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/edit.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{$category->position?translate('messages.sub').' ':''}}{{translate('messages.menu_update')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('vendor.category.update',[$category['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                                    <input type="text" name="name" class="form-control" placeholder="{{translate('messages.new_category')}}" value="{{$category['name']}}" maxlength="191">
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if ($category->position == 0)
                            <div class="h-100 d-flex flex-column">
                                <label class="mb-0">{{translate('messages.image')}}
                                    <small class="text-danger">* ( {{translate('messages.ratio')}} 1:1 )</small>
                                </label>
                                <center class="py-3 my-auto">
                                    <img class="img--100" id="viewer"
                                        src="{{asset('storage/app/public/category')}}/{{$category['image']}}"
                                        onerror='this.src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}"'
                                        alt=""/>
                                </center>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label mb-0" for="customFileEg1">{{translate('messages.choose_file')}}</label>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                    </div>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
    <script>
        $('#reset_btn').click(function(){
            $('#module_id').val("{{ $category->module_id }}").trigger('change');
            $('#viewer').attr('src', "{{asset('storage/app/public/category')}}/{{$category['image']}}");
        })
    </script>
@endpush
