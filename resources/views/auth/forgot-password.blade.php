@extends('layouts.auth')

@section('title', 'Forgot Password')

@push('scripts')
    <script>
        $(function () {
            const wrappedHandleForgotPassword = withButtonControl(handleForgotPassword, $('#submit'));

            $('form').on('submit', function (e) {
                e.preventDefault();
                wrappedHandleForgotPassword({
                    email: $('#email').val().trim()
                });
            });

            $('#email').on('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    wrappedHandleForgotPassword({
                        email: $('#email').val().trim()
                    });
                }
            });
        });
    </script>
@endpush

@section('card-content')
    <div class="text-center mb-4 fade-in">
        <div class="icon-wrapper">
            <i class="fas fa-key fa-4x text-warning mb-3"
               style="background: linear-gradient(135deg, #f59e0b, #d97706); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <h2 class="fw-bold mb-2" style="color: var(--text-dark);">Forgot Password?</h2>
        <p class="text-muted mb-0">No worries, we'll send you reset instructions</p>

        <div id="errors" class="mt-3"></div>
    </div>

    <form class="fade-in" style="animation-delay: 0.2s;">
        <div class="mb-4">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Email Address
            </label>
            <input type="email" class="form-control form-control-lg" id="email" name="email"
                   placeholder="Enter your email address" required>
            <div class="form-text text-muted">
                We'll send password reset instructions to this email
            </div>
        </div>

        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-warning btn-lg" id="submit">
                <i class="fas fa-paper-plane me-2"></i>Send Reset Instructions
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
            Remember your password?
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold"
               style="color: var(--primary-color);">
                Back to Login
            </a>
        </p>
    </div>
@endsection
