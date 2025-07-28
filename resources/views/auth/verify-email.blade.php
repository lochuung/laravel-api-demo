@extends('layouts.auth')

@section('title', 'Verify Email')

@push('scripts')
    <script>
        $(function () {
            const wrappedHandleResendVerification = withButtonControl(handleResendVerificationEmail, $('#resend'));

            // Get token from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            const email = urlParams.get('email');
            if (token && email) {
                // Auto-verify if token is present
                handleVerifyEmail({token, email});
            }

            $('#resendForm').on('submit', function (e) {
                e.preventDefault();
                wrappedHandleResendVerification({
                    email: $('#email').val().trim()
                });
            });
        });
    </script>
@endpush

@section('card-content')
    <div class="text-center mb-4 fade-in">
        <div class="icon-wrapper">
            <i class="fas fa-envelope-open fa-4x text-info mb-3"
               style="background: linear-gradient(135deg, #06b6d4, #0891b2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <h2 class="fw-bold mb-2" style="color: var(--text-dark);">Verify Your Email</h2>
        <p class="text-muted mb-0">We've sent you a verification link</p>

        <div id="errors" class="mt-3"></div>
    </div>

    <div class="fade-in" style="animation-delay: 0.2s;">
        <div class="alert alert-info border-0" style="background-color: rgba(6, 182, 212, 0.1);">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-3 text-info"></i>
                <div>
                    <strong>Check your email!</strong><br>
                    <small>Click the verification link in your email to activate your account.</small>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <p class="text-muted mb-3">Didn't receive the email? Check your spam folder or request a new one.</p>

            <form id="resendForm" class="d-inline-block">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="Enter your email" required>
                    <button type="submit" class="btn btn-info" id="resend">
                        <i class="fas fa-paper-plane me-2"></i>Resend
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center fade-in" style="animation-delay: 0.4s;">
        <div class="d-flex align-items-center mb-3">
            <hr class="flex-grow-1">
            <span class="px-3 text-muted small">or</span>
            <hr class="flex-grow-1">
        </div>

        <p class="text-muted mb-0">
            Already verified?
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold"
               style="color: var(--primary-color);">
                Go to Login
            </a>
        </p>
    </div>
@endsection
