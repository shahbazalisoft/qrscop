
@extends('layouts.admin.app')

@section('title', translate('messages.update_menu_template'))

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
                    {{translate('messages.menu_template_update')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.menu-templates.update',[$template['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.title')}}</label>
                                    <input type="text" name="title" class="form-control" placeholder="{{translate('messages.title')}}" value="{{$template['title']}}" maxlength="191">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.template_no')}}</label>
                                    <input type="text" name="template_no" class="form-control" placeholder="{{translate('messages.template_no')}}" value="{{$template['template_no']}}" maxlength="191">
                            </div>
                        </div>
                        <div class="col-md-4">
                            
                            <div class="h-100 d-flex flex-column">
                                <label class="mb-0">{{translate('messages.template_image')}}
                                    <small class="text-danger">* ( {{translate('messages.ratio')}} 1:1 )</small>
                                </label>
                                <center class="py-3 my-auto">
                                    <img class="img--100" id="viewer"
                                        src="{{asset('storage/app/public/menu-template')}}/{{$template['template']}}"
                                        onerror='this.src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}"'
                                        alt=""/>
                                </center>
                                <div class="custom-file">
                                    <input type="file" name="template" id="template" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label mb-0" for="template">{{translate('messages.choose_file')}}</label>
                                </div>
                            </div>
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
            $('#module_id').val("{{ $template->module_id }}").trigger('change');
            $('#viewer').attr('src', "{{asset('storage/app/public/menu-template')}}/{{$template['template']}}");
        })
    </script>
@endpush
