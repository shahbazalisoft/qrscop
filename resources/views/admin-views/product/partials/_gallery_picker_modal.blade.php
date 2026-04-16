<div class="modal fade" id="galleryPickerModal" tabindex="-1" role="dialog" aria-labelledby="galleryPickerModalLabel" aria-hidden="true" data-picker-mode="gallery">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryPickerModalLabel">{{ translate('messages.Choose from Gallery') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="galleryPickerBody" style="min-height: 400px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <nav aria-label="breadcrumb" class="mb-0">
                        <ol class="breadcrumb mb-0" id="galleryBreadcrumb">
                            <li class="breadcrumb-item active">{{ translate('messages.Gallery') }}</li>
                        </ol>
                    </nav>
                    <div class="d-flex align-items-center gap-2">
                        <label class="btn btn-sm btn-outline-success mb-0" id="galleryUploadBtn" style="cursor:pointer;">
                            <i class="tio-cloud-upload"></i> {{ translate('messages.Upload') }}
                            <input type="file" id="galleryUploadInput" class="d-none" multiple accept="image/*">
                        </label>
                    </div>
                </div>
                <div id="galleryUploadProgress" class="mb-3 d-none">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="galleryUploadBar" style="width: 0%"></div>
                    </div>
                    <small class="text-muted" id="galleryUploadStatus">{{ translate('messages.Uploading') }}...</small>
                </div>
                <div id="galleryPickerContent" class="row g-3"></div>
                <div id="galleryPickerSpinner" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">{{ translate('messages.Loading') }}...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <span id="gallerySelectionCount" class="text-muted">{{ translate('messages.No images selected') }}</span>
                <div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.Cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="galleryPickerConfirm" disabled>{{ translate('messages.Select') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
