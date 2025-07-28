@extends('layouts.auth')
@section('title', 'Login')

@push('scripts')
    <script>
        $(function () {

            const wrappedHandleLogin = withButtonControl(handleLogin, $('#submit'));

            $('form').on('submit', function (e) {
                e.preventDefault();
                wrappedHandleLogin({
                    email: $('#email').val(),
                    password: $('#password').val()
                });
            });
        });
    </script>
@endpush

@section('card-content')
    <div class="text-center mb-4 fade-in">
        <div class="icon-wrapper">
            <i class="fas fa-cube fa-4x text-primary mb-3"
               style="background: linear-gradient(135deg, #6366f1, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <h2 class="fw-bold mb-2" style="color: var(--text-dark);">Welcome Back</h2>
        <p class="text-muted mb-0">Sign in to continue to your account</p>

        <div id="errors"></div>
    </div>

    <form class="fade-in" style="animation-delay: 0.2s;">
        <div class="mb-4">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Email Address
            </label>
            <input type="email" class="form-control form-control-lg" id="email" name="email"
                   placeholder="Enter your email" required>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>Password
            </label>
            <div class="position-relative">
                <input type="password" class="form-control form-control-lg" id="password" name="password"
                       placeholder="Enter your password" required>
                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                        style="border: none; background: none; color: var(--text-muted);"
                        onclick="togglePassword('password')">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label text-muted" for="remember">
                    Remember me
                </label>
            </div>
            <a href="{{ route('password.request') }}" class="text-decoration-none"
               style="color: var(--primary-color); font-size: 14px;">
                Forgot password?
            </a>
        </div>

        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg" id="submit">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
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
            Don't have an account?
            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold"
               style="color: var(--primary-color);">
                Create one here
            </a>
        </p>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById('toggleIcon');

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
    </script>
@endsection
