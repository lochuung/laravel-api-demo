@extends('layouts.auth')

@section('title', 'Reset Password')

@push('scripts')
    <script>
        $(function () {
            const wrappedHandleResetPassword = withButtonControl(handleResetPassword, $('#submit'));

            // Get token and email from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            const email = urlParams.get('email');

            if (email) {
                $('#email').val(email);
            }

            if (!token) {
                showError({message: 'Invalid reset token. Please request a new password reset.'});
                $('#submit').prop('disabled', true);
            }

            $('form').on('submit', function (e) {
                e.preventDefault();
                if (!token) {
                    showError({message: 'Invalid reset token. Please request a new password reset.'});
                    return;
                }

                wrappedHandleResetPassword({
                    token: token,
                    email: $('#email').val().trim(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val()
                });
            });
        });
    </script>
@endpush

@section('card-content')
    <div class="text-center mb-4 fade-in">
        <div class="icon-wrapper">
            <i class="fas fa-lock fa-4x text-primary mb-3"
               style="background: linear-gradient(135deg, #6366f1, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <h2 class="fw-bold mb-2" style="color: var(--text-dark);">Reset Password</h2>
        <p class="text-muted mb-0">Choose a new password for your account</p>

        <div id="errors" class="mt-3"></div>
    </div>

    <form class="fade-in" style="animation-delay: 0.2s;">
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Email Address
            </label>
            <input type="email" class="form-control" id="email" name="email"
                   placeholder="Enter your email" required readonly>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>New Password
            </label>
            <div class="position-relative">
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Enter new password" required onkeyup="checkPasswordStrength()">
                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                        style="border: none; background: none; color: var(--text-muted);"
                        onclick="togglePassword('password')">
                    <i class="fas fa-eye" id="toggleIconPassword"></i>
                </button>
            </div>
            <div class="password-strength mt-2" id="passwordStrength" style="display: none;">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <small class="text-muted mt-1 d-block" id="strengthText"></small>
            </div>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock me-2"></i>Confirm New Password
            </label>
            <div class="position-relative">
                <input type="password" class="form-control" id="password_confirmation"
                       name="password_confirmation" placeholder="Confirm new password" required
                       onkeyup="checkPasswordMatch()">
                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                        style="border: none; background: none; color: var(--text-muted);"
                        onclick="togglePassword('password_confirmation')">
                    <i class="fas fa-eye" id="toggleIconConfirm"></i>
                </button>
            </div>
            <div id="passwordMatch" class="mt-2" style="display: none;">
                <small class="text-muted" id="matchText"></small>
            </div>
        </div>

        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg" id="submit">
                <i class="fas fa-key me-2"></i>Reset Password
            </button>
        </div>
    </form>

    <div class="text-center fade-in" style="animation-delay: 0.4s;">
        <p class="text-muted mb-0">
            Remember your password?
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold"
               style="color: var(--primary-color);">
                Back to Login
            </a>
        </p>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const iconId = fieldId === 'password' ? 'toggleIconPassword' : 'toggleIconConfirm';
            const icon = document.getElementById(iconId);

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthDiv = document.getElementById('passwordStrength');
            const progressBar = strengthDiv.querySelector('.progress-bar');
            const strengthText = document.getElementById('strengthText');

            if (password.length === 0) {
                strengthDiv.style.display = 'none';
                return;
            }

            strengthDiv.style.display = 'block';

            let strength = 0;
            let text = '';
            let color = '';

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            switch (strength) {
                case 0:
                case 1:
                    text = 'Very Weak';
                    color = '#ef4444';
                    break;
                case 2:
                    text = 'Weak';
                    color = '#f59e0b';
                    break;
                case 3:
                    text = 'Fair';
                    color = '#3b82f6';
                    break;
                case 4:
                    text = 'Good';
                    color = '#10b981';
                    break;
                case 5:
                    text = 'Strong';
                    color = '#059669';
                    break;
            }

            progressBar.style.width = (strength * 20) + '%';
            progressBar.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('passwordMatch');
            const matchText = document.getElementById('matchText');

            if (confirmPassword.length === 0) {
                matchDiv.style.display = 'none';
                return;
            }

            matchDiv.style.display = 'block';

            if (password === confirmPassword) {
                matchText.textContent = '✓ Passwords match';
                matchText.style.color = '#10b981';
            } else {
                matchText.textContent = '✗ Passwords do not match';
                matchText.style.color = '#ef4444';
            }
        }
    </script>
@endsection
