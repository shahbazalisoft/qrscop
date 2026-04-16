/* ===== AUTH PAGES JAVASCRIPT (Register & Admin Login) ===== */

document.addEventListener('DOMContentLoaded', function() {

    // ===== TOGGLE PASSWORD VISIBILITY =====
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');

    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });

    // ===== VALIDATION HELPERS =====

    // Email validation
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Phone validation (10 digits)
    function isValidPhone(phone) {
        const phoneRegex = /^[0-9]{10}$/;
        return phoneRegex.test(phone.replace(/\s/g, ''));
    }

    // Password validation (min 8 chars)
    function isValidPassword(password) {
        return password.length >= 8;
    }

    // Show error message
    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        const inputWrapper = input.closest('.input-wrapper');

        formGroup.classList.add('error');
        inputWrapper.classList.add('error');
        inputWrapper.classList.remove('success');

        const errorMsg = formGroup.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.textContent = message;
            errorMsg.style.display = 'block';
        }
    }

    // Clear error message
    function clearError(input) {
        const formGroup = input.closest('.form-group');
        const inputWrapper = input.closest('.input-wrapper');

        formGroup.classList.remove('error');
        inputWrapper.classList.remove('error');

        const errorMsg = formGroup.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.style.display = 'none';
        }
    }

    // Show success state
    function showSuccess(input) {
        const inputWrapper = input.closest('.input-wrapper');
        inputWrapper.classList.remove('error');
        inputWrapper.classList.add('success');
        clearError(input);
    }

    // ===== REGISTER FORM =====
    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('registerName');
            const restaurant = document.getElementById('restaurantName');
            const email = document.getElementById('registerEmail');
            const phone = document.getElementById('registerPhone');
            const password = document.getElementById('registerPassword');
            const confirmPass = document.getElementById('confirmPassword');
            const address = document.getElementById('restaurantAddress');
            const agreeTerms = document.getElementById('agreeTerms');
            const submitBtn = this.querySelector('.auth-submit-btn');
            let isValid = true;

            // Validate name
            if (!name.value.trim()) {
                showError(name, 'Name is required');
                isValid = false;
            } else if (name.value.trim().length < 3) {
                showError(name, 'Name must be at least 3 characters');
                isValid = false;
            } else {
                clearError(name);
            }

            // Validate restaurant name
            if (!restaurant.value.trim()) {
                showError(restaurant, 'Restaurant name is required');
                isValid = false;
            } else {
                clearError(restaurant);
            }

            // Validate email
            if (!email.value.trim()) {
                showError(email, 'Email is required');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError(email, 'Please enter a valid email');
                isValid = false;
            } else {
                clearError(email);
            }

            // Validate phone
            if (!phone.value.trim()) {
                showError(phone, 'Phone number is required');
                isValid = false;
            } else if (!isValidPhone(phone.value)) {
                showError(phone, 'Please enter a valid 10-digit phone number');
                isValid = false;
            } else {
                clearError(phone);
            }

            // Validate password
            if (!password.value) {
                showError(password, 'Password is required');
                isValid = false;
            } else if (!isValidPassword(password.value)) {
                showError(password, 'Password must be at least 8 characters');
                isValid = false;
            } else {
                clearError(password);
            }

            // Validate confirm password
            if (!confirmPass.value) {
                showError(confirmPass, 'Please confirm your password');
                isValid = false;
            } else if (confirmPass.value !== password.value) {
                showError(confirmPass, 'Passwords do not match');
                isValid = false;
            } else {
                clearError(confirmPass);
            }

            // Validate address
            if (address && !address.value.trim()) {
                showError(address, 'Restaurant address is required');
                isValid = false;
            } else if (address) {
                clearError(address);
            }

            // Validate terms agreement
            if (!agreeTerms.checked) {
                alert('Please agree to the Terms & Conditions');
                isValid = false;
            }

            if (isValid) {
                // Show loading state
                submitBtn.classList.add('loading');

                // Simulate API call
                setTimeout(() => {
                    submitBtn.classList.remove('loading');

                    // Success
                    console.log('Registration successful!');
                    alert('Registration successful! Please login to continue.');

                    // Redirect to admin login
                    window.location.href = 'admin-login.html';
                }, 1500);
            }
        });
    }

    // ===== LOGIN FORM =====
    const loginForm = document.getElementById('loginForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('loginEmail');
            const password = document.getElementById('loginPassword');
            const submitBtn = this.querySelector('.auth-submit-btn');
            let isValid = true;

            // Validate email
            if (!email.value.trim()) {
                showError(email, 'Email is required');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError(email, 'Please enter a valid email');
                isValid = false;
            } else {
                clearError(email);
            }

            // Validate password
            if (!password.value) {
                showError(password, 'Password is required');
                isValid = false;
            } else {
                clearError(password);
            }

            if (isValid) {
                // Show loading state
                submitBtn.classList.add('loading');

                // Simulate API call
                setTimeout(() => {
                    submitBtn.classList.remove('loading');

                    // Success - redirect to dashboard
                    console.log('Login successful!');
                    alert('Login successful! Redirecting to dashboard...');
                    // window.location.href = 'dashboard.html';
                }, 1500);
            }
        });
    }

    // ===== REAL-TIME VALIDATION =====
    const inputs = document.querySelectorAll('.auth-form input[type="text"], .auth-form input[type="email"], .auth-form input[type="tel"], .auth-form input[type="password"]');

    inputs.forEach(input => {
        // Validate on blur
        input.addEventListener('blur', function() {
            if (this.value.trim()) {
                if (this.type === 'email' && !isValidEmail(this.value)) {
                    showError(this, 'Please enter a valid email');
                } else if (this.id === 'registerPhone' && !isValidPhone(this.value)) {
                    showError(this, 'Please enter a valid phone number');
                } else if (this.type === 'password' && this.id === 'registerPassword' && !isValidPassword(this.value)) {
                    showError(this, 'Password must be at least 8 characters');
                } else if (this.id === 'confirmPassword') {
                    const password = document.getElementById('registerPassword');
                    if (password && this.value !== password.value) {
                        showError(this, 'Passwords do not match');
                    } else {
                        clearError(this);
                    }
                } else {
                    clearError(this);
                }
            }
        });

        // Clear error on input
        input.addEventListener('input', function() {
            clearError(this);
        });
    });

    // ===== SOCIAL AUTH BUTTONS =====
    const socialBtns = document.querySelectorAll('.social-btn');

    socialBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const provider = this.classList.contains('google') ? 'Google' : 'Facebook';
            console.log(`${provider} authentication clicked`);
            alert(`${provider} authentication will be implemented with backend integration`);
        });
    });

});
