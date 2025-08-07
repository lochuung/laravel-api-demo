@extends('layouts.auth')

@section('title', 'Verify Email')

@section('card-content')
    <div class="text-center mb-6 animate-fade-in">
        <div class="inline-block animate-float">
            <i class="fas fa-envelope-open text-6xl mb-4 bg-gradient-to-br from-cyan-500 to-blue-600 bg-clip-text text-transparent"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Verify Your Email</h2>
        <p class="text-gray-600 mb-0">We've sent you a verification link</p>

        <div id="errors" class="mt-4"></div>
    </div>

    <div class="animate-fade-in" style="animation-delay: 0.2s;">
        <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-4 mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-cyan-600 mr-3 mt-1 flex-shrink-0"></i>
                <div>
                    <h3 class="font-semibold text-cyan-800 mb-1">Check your email!</h3>
                    <p class="text-sm text-cyan-700">
                        Click the verification link in your email to activate your account.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mb-6">
            <p class="text-gray-600 mb-4">Didn't receive the email? Check your spam folder or request a new one.</p>

            <form id="resendForm" class="space-y-4">
                <div class="flex gap-2">
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-3 focus:ring-cyan-100 transition-all duration-300 text-sm"
                        placeholder="Enter your email" 
                        required
                    >
                    <button 
                        type="submit" 
                        id="resend"
                        class="bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 whitespace-nowrap"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>Resend
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center animate-fade-in" style="animation-delay: 0.4s;">
        <div class="flex items-center mb-4">
            <hr class="flex-grow border-gray-300">
            <span class="px-3 text-gray-500 text-sm">or</span>
            <hr class="flex-grow border-gray-300">
        </div>

        <p class="text-gray-600 mb-0">
            Already verified?
            <a 
                href="{{ route('login') }}" 
                class="text-indigo-600 hover:text-indigo-800 font-semibold hover:underline transition-colors"
            >
                Go to Login
            </a>
        </p>
    </div>
@endsection

@vite('resources/js/pages/verify-email.js')
