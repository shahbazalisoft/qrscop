@extends('layouts.landing.app')

@section('title', $job->title . ' - ' . translate('messages.careers'))

@section('content')

<!-- Job Detail Hero -->
<section class="careers-hero text-center">
    <div class="container">
        <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-3">
            <ol class="breadcrumb" style="background:transparent;">
                <li class="breadcrumb-item"><a href="{{ route('careers') }}" class="text-warning">{{ translate('Careers') }}</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $job->title }}</li>
            </ol>
        </nav>
        <h1>{{ $job->title }}</h1>
        <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
            @if($job->department)
                <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 14px;">
                    <i class="bi bi-building me-1"></i> {{ $job->department }}
                </span>
            @endif
            @if($job->location)
                <span class="badge bg-light text-dark px-3 py-2" style="font-size: 14px;">
                    <i class="bi bi-geo-alt me-1"></i> {{ $job->location }}
                </span>
            @endif
            <span class="badge bg-light text-dark px-3 py-2" style="font-size: 14px;">
                <i class="bi bi-clock me-1"></i> {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
            </span>
            @if($job->salary_range)
                <span class="badge bg-light text-dark px-3 py-2" style="font-size: 14px;">
                    <i class="bi bi-cash me-1"></i> {{ $job->salary_range }}
                </span>
            @endif
        </div>
        <a href="#apply-section" class="btn btn-warning btn-lg mt-4">{{ translate('Apply Now') }}</a>
    </div>
</section>

<!-- Job Details & Apply Form -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Left Side: Description & Requirements -->
            <div class="col-lg-6">
                @if($job->description)
                    <div class="mb-4">
                        <h3 class="mb-3"><i class="bi bi-file-text text-warning me-2"></i>{{ translate('Job Description') }}</h3>
                        <div class="job-detail-content" style="line-height: 1.8;">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>
                @endif

                @if($job->requirements)
                    <div class="mb-4">
                        <h3 class="mb-3"><i class="bi bi-list-check text-warning me-2"></i>{{ translate('Requirements') }}</h3>
                        <div class="job-detail-content">
                            <ul class="culture-values" style="list-style: none; padding-left: 0;">
                                @foreach(array_filter(explode("\n", $job->requirements)) as $req)
                                    <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i> {{ trim($req) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if($job->salary_range)
                    <div class="mb-4">
                        <h3 class="mb-3"><i class="bi bi-cash-stack text-warning me-2"></i>{{ translate('Salary Range') }}</h3>
                        <p style="font-size: 18px;">{{ $job->salary_range }}</p>
                    </div>
                @endif
            </div>

            <!-- Right Side: Apply Form -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg position-sticky" id="apply-section" style="background: rgba(255,255,255,0.05); border-radius: 20px; color: #fff; top: 100px;">
                    <div class="card-body p-4">
                        <h3 class="mb-4" style="color: #fff;"><i class="bi bi-send text-warning me-2"></i>{{ translate('Apply for this Position') }}</h3>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form class="contact-form" action="{{ route('career.apply', $job->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label" style="color: #fff;">{{ translate('Full Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                            placeholder="{{ translate('Your full name') }}" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" style="color: #fff;">{{ translate('Email') }} <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                            placeholder="{{ translate('your@email.com') }}" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" style="color: #fff;">{{ translate('Phone') }}</label>
                                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="{{ translate('+91 98765 43210') }}" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label" style="color: #fff;">{{ translate('Resume') }} <span class="text-danger">*</span> <small style="color: #aaa;">(PDF, DOC, DOCX - Max 5MB)</small></label>
                                        <input type="file" name="resume" class="form-control @error('resume') is-invalid @enderror"
                                            accept=".pdf,.doc,.docx" required>
                                        @error('resume')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label" style="color: #fff;">{{ translate('Cover Letter') }}</label>
                                        <textarea name="cover_letter" class="form-control @error('cover_letter') is-invalid @enderror"
                                            rows="4" placeholder="{{ translate('Tell us why you are a great fit for this role...') }}">{{ old('cover_letter') }}</textarea>
                                        @error('cover_letter')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- Captcha --}}
                                <div class="col-12">
                                    @php($recaptcha_status = isset($recaptcha) && $recaptcha['status'] == 1)
                                    @if($recaptcha_status)
                                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                        <input type="hidden" name="set_default_captcha" id="set_default_captcha_value" value="0">
                                        <div class="row g-2 d-none mb-3" id="reload-captcha">
                                            <div class="col-6">
                                                <input type="text" class="form-control" name="custome_recaptcha" id="custome_recaptcha"
                                                    placeholder="{{ translate('Enter captcha value') }}" autocomplete="off">
                                            </div>
                                            <div class="col-5 rounded d-flex align-items-center">
                                                <img src="<?php echo $custome_recaptcha->inline(); ?>" class="rounded w-100" style="max-height:45px;" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center justify-content-center">
                                                <div class="reloadCaptcha" style="cursor:pointer; color:#10847E;" title="Reload captcha">
                                                    <i class="bi bi-arrow-clockwise fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row g-2 mb-3" id="reload-captcha">
                                            <div class="col-6">
                                                <input type="text" class="form-control" name="custome_recaptcha" id="custome_recaptcha"
                                                    required placeholder="{{ translate('Enter captcha value') }}" autocomplete="off">
                                            </div>
                                            <div class="col-5 rounded d-flex align-items-center">
                                                <img src="<?php echo $custome_recaptcha->inline(); ?>" class="rounded w-100" style="max-height:45px;" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center justify-content-center">
                                                <div class="reloadCaptcha" style="cursor:pointer; color:#10847E;" title="Reload captcha">
                                                    <i class="bi bi-arrow-clockwise fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <button type="submit" id="applyBtn" class="btn btn-warning btn-lg w-100">
                                        <i class="bi bi-send me-2"></i>{{ translate('Submit Application') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('script_2')
<script>
(function() {
    var form = document.getElementById('applyForm');
    if (!form) form = document.querySelector('#apply-section form');
    if (!form) return;

    // Reload captcha
    function reloadCaptchaImage() {
        $.ajax({
            url: "{{ route('reload-captcha') }}",
            type: "GET",
            dataType: 'json',
            success: function(data) {
                var tmp = $('<div>').html(data.view);
                var imgSrc = tmp.find('img').attr('src');
                var html = '<div class="col-6">' +
                    '<input type="text" class="form-control" name="custome_recaptcha" id="custome_recaptcha" required placeholder="{{ translate('Enter captcha value') }}" autocomplete="off">' +
                    '</div>' +
                    '<div class="col-5 rounded d-flex align-items-center">' +
                    '<img src="' + imgSrc + '" class="rounded w-100" style="max-height:45px;" />' +
                    '</div>' +
                    '<div class="col-1 d-flex align-items-center justify-content-center">' +
                    '<div class="reloadCaptcha" style="cursor:pointer; color:#10847E;" title="Reload captcha">' +
                    '<i class="bi bi-arrow-clockwise fs-4"></i>' +
                    '</div></div>';
                $('#reload-captcha').html(html);
            }
        });
    }

    $(document).on('click', '.reloadCaptcha', function() {
        reloadCaptchaImage();
    });

    @if(isset($recaptcha) && $recaptcha['status'] == 1)
    form.addEventListener('submit', function(e) {
        if (document.getElementById('set_default_captcha_value').value == '1') {
            return true;
        }
        e.preventDefault();
        if (typeof grecaptcha === 'undefined') {
            toastr.error('Invalid reCAPTCHA key. Please use the default captcha.');
            document.getElementById('reload-captcha').classList.remove('d-none');
            document.getElementById('set_default_captcha_value').value = '1';
            var captchaInput = document.getElementById('custome_recaptcha');
            if (captchaInput) captchaInput.required = true;
            return;
        }
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ $recaptcha["site_key"] ?? "" }}', { action: 'career_apply' }).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
                form.submit();
            });
        });
        window.onerror = function(message) {
            document.getElementById('reload-captcha').classList.remove('d-none');
            document.getElementById('set_default_captcha_value').value = '1';
            var captchaInput = document.getElementById('custome_recaptcha');
            if (captchaInput) captchaInput.required = true;
            toastr.error('reCAPTCHA error. Please use the default captcha.');
            return true;
        };
    });
    @endif
})();
</script>
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptcha['site_key'] ?? '' }}"></script>
@endif
@endpush
