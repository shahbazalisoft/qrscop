"use strict";

var GalleryPicker = (function () {
    var apiBaseUrl = '';
    var uploadUrl = '';
    var selectedImages = [];
    var maxSelect = 5;
    var pickerTarget = 'gallery'; // 'thumbnail' or 'gallery'
    var allFiles = [];
    var displayedCount = 0;
    var batchSize = 30;
    var breadcrumbStack = []; // [{label, encodedPath}]
    var currentDecodedPath = 'public'; // decoded folder path for uploads
    var customViewerId = ''; // custom viewer img ID (for category modals etc.)
    var customInputId = ''; // custom file input ID
    var customFormId = ''; // custom form ID

    function init(config) {
        apiBaseUrl = config.apiUrl || '';
        uploadUrl = config.uploadUrl || '';

        // Open modal
        $(document).on('click', '.btn-gallery-picker', function (e) {
            e.preventDefault();
            pickerTarget = $(this).data('picker-target') || 'gallery';
            maxSelect = parseInt($(this).data('max-select')) || 5;
            customViewerId = $(this).data('viewer-id') || '';
            customInputId = $(this).data('input-id') || '';
            customFormId = $(this).data('form-id') || '';
            selectedImages = [];
            allFiles = [];
            displayedCount = 0;
            currentDecodedPath = 'public';
            breadcrumbStack = [{ label: 'Gallery', encodedPath: '' }];

            $('#galleryPickerModal').attr('data-picker-mode', pickerTarget);
            updateSelectionUI();
            loadFolder('');
            $('#galleryPickerModal').modal('show');
        });

        // Confirm selection
        $('#galleryPickerConfirm').on('click', function () {
            if (pickerTarget === 'thumbnail') {
                applyThumbnailSelection();
            } else {
                applyGallerySelection();
            }
            $('#galleryPickerModal').modal('hide');
        });

        // Scroll lazy loading inside modal body
        $('#galleryPickerBody').on('scroll', function () {
            var el = this;
            if (el.scrollTop + el.clientHeight >= el.scrollHeight - 100) {
                renderMoreFiles();
            }
        });

        // Upload handler
        $('#galleryUploadInput').on('change', function () {
            var files = this.files;
            if (!files || files.length === 0) return;
            uploadFiles(files);
            $(this).val(''); // reset input
        });
    }

    function uploadFiles(files) {
        var formData = new FormData();
        for (var i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        formData.append('path', currentDecodedPath);

        $('#galleryUploadProgress').removeClass('d-none');
        $('#galleryUploadBar').css('width', '0%');
        $('#galleryUploadStatus').text('Uploading...');
        $('#galleryUploadBtn').addClass('disabled');

        $.ajax({
            url: uploadUrl,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        var pct = Math.round((e.loaded / e.total) * 100);
                        $('#galleryUploadBar').css('width', pct + '%');
                        $('#galleryUploadStatus').text('Uploading... ' + pct + '%');
                    }
                });
                return xhr;
            },
            success: function () {
                $('#galleryUploadBar').css('width', '100%');
                $('#galleryUploadStatus').text('Upload complete!');
                setTimeout(function () {
                    $('#galleryUploadProgress').addClass('d-none');
                    $('#galleryUploadBtn').removeClass('disabled');
                }, 800);
                // Reload current folder to show new images
                var currentEncoded = breadcrumbStack[breadcrumbStack.length - 1].encodedPath;
                loadFolder(currentEncoded);
            },
            error: function () {
                $('#galleryUploadStatus').text('Upload failed!');
                $('#galleryUploadBtn').removeClass('disabled');
                setTimeout(function () {
                    $('#galleryUploadProgress').addClass('d-none');
                }, 2000);
            }
        });
    }

    function loadFolder(encodedPath) {
        var url = apiBaseUrl;
        if (encodedPath) {
            url = apiBaseUrl.replace(/\/api\/?$/, '/api/' + encodedPath);
        }

        $('#galleryPickerContent').empty();
        $('#galleryPickerSpinner').removeClass('d-none');
        allFiles = [];
        displayedCount = 0;
        selectedImages = [];
        updateSelectionUI();

        $.get(url, function (data) {
            $('#galleryPickerSpinner').addClass('d-none');

            // Track decoded path for uploads
            currentDecodedPath = data.decoded_path || 'public';

            // Render breadcrumb
            renderBreadcrumb();

            // Render folders
            if (data.folders && data.folders.length > 0) {
                data.folders.forEach(function (folder) {
                    var folderHtml = '<div class="col-6 col-sm-4 col-md-3 col-lg-2 gallery-folder-item">' +
                        '<div class="card h-100 cursor-pointer border" data-folder-path="' + folder.path + '" style="cursor:pointer;">' +
                        '<div class="card-body text-center p-3">' +
                        '<i class="tio-folder-opened" style="font-size: 48px; color: #f5c542;"></i>' +
                        '<p class="mb-0 mt-2 text-truncate small" title="' + folder.name + '">' + folder.name + '</p>' +
                        '</div></div></div>';
                    $('#galleryPickerContent').append(folderHtml);
                });
            }

            // Store all files for lazy loading
            allFiles = data.files || [];
            renderMoreFiles();

            // Folder click
            $('#galleryPickerContent').off('click', '.gallery-folder-item');
            $('#galleryPickerContent').on('click', '.gallery-folder-item', function () {
                var folderPath = $(this).find('[data-folder-path]').data('folder-path');
                var folderName = $(this).find('.text-truncate').text();
                breadcrumbStack.push({ label: folderName, encodedPath: folderPath });
                loadFolder(folderPath);
            });

            // Image click (toggle select)
            $('#galleryPickerContent').off('click', '.gallery-image-item');
            $('#galleryPickerContent').on('click', '.gallery-image-item', function () {
                var path = $(this).data('file-path');
                var imgUrl = $(this).data('img-url');
                var idx = selectedImages.findIndex(function (s) { return s.path === path; });

                if (pickerTarget === 'thumbnail') {
                    // Single select mode
                    selectedImages = [];
                    $('.gallery-image-item').removeClass('border-primary shadow');
                    $('.gallery-image-item .gallery-check-icon').addClass('d-none');
                    if (idx === -1) {
                        selectedImages.push({ path: path, imgUrl: imgUrl });
                        $(this).addClass('border-primary shadow');
                        $(this).find('.gallery-check-icon').removeClass('d-none');
                    }
                } else {
                    // Multi select mode
                    if (idx > -1) {
                        selectedImages.splice(idx, 1);
                        $(this).removeClass('border-primary shadow');
                        $(this).find('.gallery-check-icon').addClass('d-none');
                    } else {
                        if (selectedImages.length >= maxSelect) {
                            toastr.warning('Maximum ' + maxSelect + ' images can be selected', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            return;
                        }
                        selectedImages.push({ path: path, imgUrl: imgUrl });
                        $(this).addClass('border-primary shadow');
                        $(this).find('.gallery-check-icon').removeClass('d-none');
                    }
                }
                updateSelectionUI();
            });

        }).fail(function () {
            $('#galleryPickerSpinner').addClass('d-none');
            $('#galleryPickerContent').html('<div class="col-12 text-center text-danger py-4">Failed to load gallery</div>');
        });
    }

    function renderMoreFiles() {
        var end = Math.min(displayedCount + batchSize, allFiles.length);
        for (var i = displayedCount; i < end; i++) {
            var file = allFiles[i];
            var imageHtml = '<div class="col-6 col-sm-4 col-md-3 col-lg-2">' +
                '<div class="card h-100 border gallery-image-item" data-file-path="' + file.path + '" data-img-url="' + file.img_url + '" style="cursor:pointer;">' +
                '<div class="position-relative">' +
                '<img src="' + file.img_url + '" class="card-img-top" style="height:120px; object-fit:cover;" alt="' + file.name + '" loading="lazy">' +
                '<span class="gallery-check-icon d-none position-absolute" style="top:5px;right:5px;background:rgba(255,255,255,0.9);border-radius:50%;padding:2px 6px;">' +
                '<i class="tio-checkmark-circle" style="color:#28a745;font-size:20px;"></i></span>' +
                '</div>' +
                '<div class="card-body p-2">' +
                '<p class="mb-0 text-truncate small" title="' + file.name + '">' + file.name + '</p>' +
                '</div></div></div>';
            $('#galleryPickerContent').append(imageHtml);
        }
        displayedCount = end;
    }

    function renderBreadcrumb() {
        var $bc = $('#galleryBreadcrumb');
        $bc.empty();
        breadcrumbStack.forEach(function (crumb, idx) {
            if (idx === breadcrumbStack.length - 1) {
                $bc.append('<li class="breadcrumb-item active">' + crumb.label + '</li>');
            } else {
                $bc.append('<li class="breadcrumb-item"><a href="#" class="gallery-breadcrumb-link" data-index="' + idx + '">' + crumb.label + '</a></li>');
            }
        });

        // Breadcrumb click
        $bc.off('click', '.gallery-breadcrumb-link');
        $bc.on('click', '.gallery-breadcrumb-link', function (e) {
            e.preventDefault();
            var idx = parseInt($(this).data('index'));
            var crumb = breadcrumbStack[idx];
            breadcrumbStack = breadcrumbStack.slice(0, idx + 1);
            loadFolder(crumb.encodedPath);
        });
    }

    function updateSelectionUI() {
        var count = selectedImages.length;
        if (count === 0) {
            $('#gallerySelectionCount').text('No images selected');
            $('#galleryPickerConfirm').prop('disabled', true);
        } else {
            $('#gallerySelectionCount').text(count + ' image' + (count > 1 ? 's' : '') + ' selected');
            $('#galleryPickerConfirm').prop('disabled', false);
        }
    }

    function applyThumbnailSelection() {
        if (selectedImages.length === 0) return;
        var img = selectedImages[0];

        // Determine which elements to target
        var viewerId = customViewerId || 'viewer';
        var inputId = customInputId || 'customFileEg1';

        // Update thumbnail preview
        $('#' + viewerId).attr('src', img.imgUrl);

        // Remove required from file input so form can submit without file
        var $fileInput = $('#' + inputId);
        $fileInput.removeAttr('required').val('');
        // Also remove jQuery Validation cached rule
        try { $fileInput.rules('remove', 'required'); } catch(e) {}

        // Determine which form to append to
        var $form;
        if (customFormId) {
            $form = $('#' + customFormId);
        } else {
            $form = $fileInput.closest('form');
        }

        // Remove any previous gallery thumbnail hidden input in this form
        $form.find('input[name="gallery_thumbnail"]').remove();

        // Add hidden input with the storage path
        $form.append('<input type="hidden" name="gallery_thumbnail" value="' + img.path + '">');
    }

    function applyGallerySelection() {
        if (selectedImages.length === 0) return;

        selectedImages.forEach(function (img) {
            // Create preview wrapper matching spartan style
            var key = 'gallery_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
            var previewHtml = '<div id="' + key + '" class="spartan_item_wrapper min-w-176px max-w-176px">' +
                '<img class="img--square" src="' + img.imgUrl + '" alt="Gallery image" style="height:176px;width:176px;object-fit:cover;">' +
                '<input type="hidden" name="gallery_images[]" value="' + img.path + '">' +
                '<a href="#" class="spartan_remove_row gallery-remove-img" data-target="#' + key + '">' +
                '<i class="tio-add-to-trash"></i></a>' +
                '</div>';
            // Insert before the spartan placeholders
            var $coba = $('#coba');
            var $firstSpartan = $coba.find('.spartan_item_wrapper input[type="file"]').first().closest('.spartan_item_wrapper');
            if ($firstSpartan.length) {
                $firstSpartan.before(previewHtml);
            } else {
                $coba.append(previewHtml);
            }
        });
    }

    // Remove gallery-picked images
    $(document).on('click', '.gallery-remove-img', function (e) {
        e.preventDefault();
        var target = $(this).data('target');
        $(target).remove();
    });

    return { init: init };
})();
