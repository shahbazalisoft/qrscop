<script src="{{ asset('public/assets/admin/js/toastr.js') }}"></script>
{!! Toastr::message() !!}
<script>
$(document).ready(function() {
    // Password Toggle
    $('.toggle-password').on('click', function() {
        const input = $(this).siblings('input');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });

    // OTP Field Auto-Focus & Combine
    $('.otp-field').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length === 1) {
            $(this).next('.otp-field').focus();
        }
        // Combine all OTP fields into hidden input
        var otp = '';
        $('.otp-field').each(function() {
            otp += $(this).val();
        });
        $('#otp-hidden').val(otp);
    });

    $('.otp-field').on('keydown', function(e) {
        if (e.key === 'Backspace' && this.value === '') {
            $(this).prev('.otp-field').focus();
        }
    });

    // Handle paste on OTP fields
    $('.otp-field').first().on('paste', function(e) {
        e.preventDefault();
        var pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
        $('.otp-field').each(function(index) {
            if (pastedData[index]) {
                $(this).val(pastedData[index]);
            }
        });
        var lastIndex = Math.min(pastedData.length, 4) - 1;
        $('.otp-field').eq(lastIndex).focus();
        $('#otp-hidden').val(pastedData.substring(0, 4));
    });

    // Email Unique Check
    var emailTimer;
    $('#email').on('keyup blur', function() {
        clearTimeout(emailTimer);
        var email = $(this).val().trim();
        var wrapper = $(this).closest('.input-wrapper');
        var msg = $('#email-msg');

        if (email === '' || !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            wrapper.removeClass('valid-unique invalid-unique');
            msg.removeClass('success error').hide();
            return;
        }

        emailTimer = setTimeout(function() {
            $.ajax({
                url: "{{ route('restaurant.checkEmailUnique') }}",
                type: 'POST',
                data: { email: email, _token: '{{ csrf_token() }}' },
                success: function(isUnique) {
                    if (isUnique) {
                        wrapper.removeClass('invalid-unique').addClass('valid-unique');
                        msg.removeClass('error').addClass('success').text('Email is available').show();
                    } else {
                        wrapper.removeClass('valid-unique').addClass('invalid-unique');
                        msg.removeClass('success').addClass('error').text('Email is already taken').show();
                    }
                }
            });
        }, 500);
    });

    // Phone Unique Check
    var phoneTimer;
    $('#phone').on('keyup blur', function() {
        clearTimeout(phoneTimer);
        var phone = $(this).val().trim();
        var wrapper = $(this).closest('.input-wrapper');
        var msg = $('#phone-msg');

        if (phone === '' || phone.length < 5) {
            wrapper.removeClass('valid-unique invalid-unique');
            msg.removeClass('success error').hide();
            return;
        }

        phoneTimer = setTimeout(function() {
            $.ajax({
                url: "{{ route('restaurant.checkPhoneUnique') }}",
                type: 'POST',
                data: { phone: phone, _token: '{{ csrf_token() }}' },
                success: function(isUnique) {
                    if (isUnique) {
                        wrapper.removeClass('invalid-unique').addClass('valid-unique');
                        msg.removeClass('error').addClass('success').text('Phone number is available').show();
                    } else {
                        wrapper.removeClass('valid-unique').addClass('invalid-unique');
                        msg.removeClass('success').addClass('error').text('Phone number is already taken').show();
                    }
                }
            });
        }, 500);
    });

    // Form Submit with Loading State
    $('form').on('submit', function() {
        const btn = $(this).find('button[type="submit"]');
        if (btn.length) {
            btn.find('.btn-text, .btn-arrow').hide();
            btn.find('.btn-loader').show();
            btn.prop('disabled', true);
        }
    });
});
</script>
<style>
.btn-loader .spin {
    display: inline-block;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
