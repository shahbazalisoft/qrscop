@extends('layouts.admin.app')
@section('title',translate('messages.gallery'))
@section('content')
<div class="content container-fluid">

        <div class="page-header">

        </div>
    <!-- Page Heading -->
    <div class="page-header d-flex flex-wrap justify-content-between">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/folder-logo.png')}}" class="w--26" alt="">
            </span>
            <span>
                {{translate('messages.gallery')}}
            </span>
        </h1>
        <div class="d-flex flex-wrap justify-content-between">
            <button type="button" class="btn btn--primary modalTrigger mr-3" data-toggle="modal" data-target="#exampleModal">
                <i class="tio-add-circle"></i>
                <span class="text">{{translate('messages.add_new')}}</span>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                @php
                    $pwd = explode('/',base64_decode($folder_path));
                @endphp
                    <h5 class="card-title">{{end($pwd)}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{count($data)}}</span></h5>
                    <a class="btn btn-sm badge-soft-primary" href="{{url()->previous()}}"><i class="tio-arrow-long-left mr-2"></i>{{translate('messages.back')}}</a>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="gallery-container">
                        {{-- Folders render immediately --}}
                        @foreach($data as $key=>$file)
                            @if($file['type']=='folder')
                            <div class="col-6 col-sm-auto">
                                <a class="btn p-0 btn--folder" href="{{route('admin.gallery.index', base64_encode($file['path']))}}">
                                    <img class="img-thumbnail border-0 p-0" src="{{asset('public/assets/admin/img/folder.png')}}" alt="">
                                    <p>{{Str::limit($file['name'],10)}}</p>
                                </a>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <div id="scroll-loader" class="text-center py-3 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single reusable image preview modal --}}
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog max-w-640">
            <div class="modal-content">
                <button type="button" class="close right-top-close-icon" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <div class="modal-header p-1">
                    <div class="gallery-modal-header w-100">
                        <span id="preview-file-name"></span>
                        <a href="#" class="d-block ml-auto" id="preview-copy-link">
                            {{translate('Copy Path')}} <i class="tio-link"></i>
                        </a>
                        <a class="d-block" id="preview-download-link" href="#">
                            {{translate('Download')}} <i class="tio-download-to"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-body p-1 pt-0">
                    <img src="" id="preview-image" class="w-100">
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="indicator"></div>
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">{{translate('messages.upload_file')}} </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.gallery.image-upload')}}"  method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="path" value = "{{base64_decode($folder_path)}}" hidden>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="images[]" id="customFileUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" multiple>
                            <label class="custom-file-label" for="customFileUpload"></label>
                            <p class="subtxt">{{translate('messages.only_input_file')}}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="file" id="customZipFileUpload" class="custom-file-input" accept=".zip">
                            <label class="custom-file-label" id="zipFileLabel" for="customZipFileUpload">{{translate('messages.upload_zip_file')}}</label>
                            <p class="subtxt">{{translate('messages.input_file_zip')}}</p>
                        </div>
                    </div>

                    <div class="row" id="files"></div>
                    <div class="form-group mb-0">
                        <input class="btn btn--primary text-white" type="submit" value="{{translate('messages.upload')}}">
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>
</div>

@endsection

@push('script_2')
<script>
    "use strict";

    @php
        $galleryFiles = collect($data)->where('type', 'file')->values()->map(function($file) {
            return [
                'name' => $file['name'],
                'path' => $file['path'],
                'db_path' => $file['db_path'],
                'img_url' => url('storage/'.preg_replace('/^public\//', '', $file['path'])),
                'download_url' => route('admin.gallery.download', base64_encode($file['path'])),
                'delete_url' => route('admin.gallery.destroy', base64_encode($file['path'])),
            ];
        });
    @endphp
    var galleryFiles = {!! json_encode($galleryFiles) !!};

    var batchSize = 30;
    var loadedCount = 0;
    var isLoading = false;
    var csrfToken = '{{ csrf_token() }}';

    function renderBatch() {
        if (isLoading || loadedCount >= galleryFiles.length) return;
        isLoading = true;
        $('#scroll-loader').removeClass('d-none');

        var end = Math.min(loadedCount + batchSize, galleryFiles.length);
        var html = '';

        for (var i = loadedCount; i < end; i++) {
            var file = galleryFiles[i];
            var shortName = file.name.length > 10 ? file.name.substring(0, 10) + '...' : file.name;
            html += '<div class="col-6 col-sm-auto">' +
                '<div class="folder-btn-item mx-auto">' +
                    '<button class="btn p-0 w-100" title="' + file.name + '">' +
                        '<div class="gallary-card">' +
                            '<img src="' + file.img_url + '" alt="' + file.name + '" class="w-100 rounded" loading="lazy">' +
                        '</div>' +
                        '<small class="overflow-hidden text-title">' + shortName + '</small>' +
                    '</button>' +
                    '<div class="btn-items">' +
                        '<a href="#" title="{{translate("View Image")}}" data-toggle="tooltip" data-placement="left" onclick="previewImage(' + i + ')">' +
                            '<img src="{{asset("/public/assets/admin/img/download/view.png")}}" alt="">' +
                        '</a>' +
                        '<a href="#" title="{{translate("Copy Link")}}" data-toggle="tooltip" data-placement="left" onclick="copy_test(\'' + file.db_path + '\')">' +
                            '<img src="{{asset("/public/assets/admin/img/download/link.png")}}" alt="">' +
                        '</a>' +
                        '<a title="{{translate("Download")}}" data-toggle="tooltip" data-placement="left" href="' + file.download_url + '">' +
                            '<img src="{{asset("/public/assets/admin/img/download/download.png")}}" alt="">' +
                        '</a>' +
                        '<form action="' + file.delete_url + '" method="post" onsubmit="form_submit_warrning(event)">' +
                            '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                            '<input type="hidden" name="_method" value="delete">' +
                            '<button type="submit" title="{{translate("Delete")}}" data-toggle="tooltip" data-placement="left"><i class="tio-delete"></i></button>' +
                        '</form>' +
                    '</div>' +
                '</div>' +
            '</div>';
        }

        $('#gallery-container').append(html);
        loadedCount = end;
        isLoading = false;
        $('#scroll-loader').addClass('d-none');
        $('[data-toggle="tooltip"]').tooltip();
    }

    function previewImage(index) {
        var file = galleryFiles[index];
        $('#preview-file-name').text(file.name);
        $('#preview-image').attr('src', file.img_url);
        $('#preview-download-link').attr('href', file.download_url);
        $('#preview-copy-link').off('click').on('click', function(e) {
            e.preventDefault();
            copy_test(file.db_path);
        });
        $('#imagePreviewModal').modal('show');
    }

    // Initial batch
    renderBatch();

    // Load more on scroll
    $(window).on('scroll', function() {
        if (loadedCount >= galleryFiles.length) return;
        var scrollTop = $(window).scrollTop();
        var windowHeight = $(window).height();
        var docHeight = $(document).height();
        if (scrollTop + windowHeight >= docHeight - 300) {
            renderBatch();
        }
    });

    function readURL(input) {
        $('#files').html("");
        for( var i = 0; i<input.files.length; i++)
        {
            if (input.files && input.files[i]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#files').append('<div class="col-md-2 col-sm-4 m-1"><img class="initial--7" id="viewer" src="'+e.target.result+'"/></div>');
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    }

    $("#customFileUpload").change(function () {
        readURL(this);
    });

    $('#customZipFileUpload').change(function(e){
        var fileName = e.target.files[0].name;
        $('#zipFileLabel').html(fileName);
    });

    function copy_test(copyText) {
        navigator.clipboard.writeText(copyText);
        toastr.success('File path copied successfully!', {
            CloseButton: true,
            ProgressBar: true
        });
    }

    function form_submit_warrning(e) {
        e.preventDefault();
        Swal.fire({
            title: "{{translate('Are you sure?')}}",
            text: "{{translate('you_want_to_delete')}}",
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{translate('messages.no')}}',
            confirmButtonText: '{{translate('messages.yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                e.target.submit();
            }
        })
    };
</script>
@endpush
