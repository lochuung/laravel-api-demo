@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('card-content')
    <div class="text-center mb-6 animate-fade-in">
        <div class="inline-block animate-float">
            <i class="fas fa-key text-6xl mb-4 bg-gradient-to-br from-amber-500 to-orange-600 bg-clip-text text-transparent"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Forgot Password?</h2>
        <p class="text-gray-600 mb-0">No worries, we'll send you reset instructions</p>

        <div id="errors" class="mt-4"></div>
    </div>

    <form id="forgot-password-form" class="animate-fade-in" style="animation-delay: 0.2s;">
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2"></i>Email Address
            </label>
            <input 
                type="email" 
                id="email" 
                name="email"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-amber-500 focus:ring-3 focus:ring-amber-100 transition-all duration-300 text-sm"
                placeholder="Enter your email address" 
                required
            >
            <p class="text-sm text-gray-500 mt-2">
                We'll send password reset instructions to this email
            </p>
        </div>

        <div class="mb-6">
            <button 
                type="submit" 
                id="submit"
                class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden"
            >
                <span class="relative z-10">
                    <i class="fas fa-paper-plane mr-2"></i>Send Reset Instructions
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 hover:opacity-20 transform -skew-x-12 -translate-x-full hover:translate-x-full transition-transform duration-500"></div>
            </button>
        </div>
    </form>

    <div class="text-center animate-fade-in" style="animation-delay: 0.4s;">
        <div class="flex items-center mb-4">
            <hr class="flex-grow border-gray-300">
            <span class="px-3 text-gray-500 text-sm">or</span>
            <hr class="flex-grow border-gray-300">
        </div>

        <p class="text-gray-600 mb-0">
            Remember your password?
            <a 
                href="{{ route('login') }}" 
                class="text-indigo-600 hover:text-indigo-800 font-semibold hover:underline transition-colors"
            >
                Back to Login
            </a>
        </p>
    </div>
@endsection

@vite('resources/js/pages/forgot-password.js')
