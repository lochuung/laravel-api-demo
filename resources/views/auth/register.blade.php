@extends('layouts.auth')

@section('title', 'Register')

@push('scripts')
    <script>
        $(function () {

            const wrappedHandleRegister = withButtonControl(handleRegister, $('#submit'));

            $('form').on('submit', function (e) {
                e.preventDefault();
                wrappedHandleRegister({
                    name: $('#name').val().trim(),
                    email: $('#email').val().trim(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val()
                })
            });
        });
    </script>
@endpush

@section('card-content')
    <div class="text-center mb-4 fade-in">
        <div class="icon-wrapper">
            <i class="fas fa-user-plus fa-4x text-success mb-3"
               style="background: linear-gradient(135deg, #10b981, #059669); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <h2 class="fw-bold mb-2" style="color: var(--text-dark);">Create Account</h2>
        <p class="text-muted mb-0">Join our community today</p>
        <div id="errors" class="mt-3"></div>
    </div>

    <form class="fade-in" style="animation-delay: 0.2s;">
        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="fas fa-user me-2"></i>Full Name
            </label>
            <input type="text" class="form-control" id="name" name="name"
                   placeholder="Enter your full name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Email Address
            </label>
            <input type="email" class="form-control" id="email" name="email"
                   placeholder="Enter your email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>Password
            </label>
            <div class="position-relative">
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Create a password" required onkeyup="checkPasswordStrength()">
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

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock me-2"></i>Confirm Password
            </label>
            <div class="position-relative">
                <input type="password" class="form-control" id="password_confirmation"
                       name="password_confirmation" placeholder="Confirm your password" required
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

        <div class="mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="terms" required>
                <label class="form-check-label text-muted" for="terms">
                    I agree to the <a href="#" class="text-decoration-none fw-semibold"
                                      style="color: var(--primary-color);">Terms & Conditions</a> and
                    <a href="#" class="text-decoration-none fw-semibold"
                       style="color: var(--primary-color);">Privacy Policy</a>
                </label>
            </div>
        </div>

        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-success btn-lg" id="submit">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>
        </div>
    </form>

    <div class="text-center fade-in" style="animation-delay: 0.4s;">
        <div class="d-flex align-items-center mb-3">
            <hr class="flex-grow-1">
            <span class="px-3 text-muted small">or</span>
            <hr class="flex-grow-1">
        </div>

        <p class="text-muted mb-0">
            Already have an account?
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold"
               style="color: var(--primary-color);">
                Sign in here
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
