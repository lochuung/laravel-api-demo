@extends('layouts.auth')

@section('title', 'Reset Password')

@section('card-content')
    <div class="text-center mb-6 animate-fade-in">
        <div class="inline-block animate-float">
            <i class="fas fa-lock text-6xl mb-4 bg-gradient-to-br from-indigo-500 to-purple-600 bg-clip-text text-transparent"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Reset Password</h2>
        <p class="text-gray-600 mb-0">Choose a new password for your account</p>

        <div id="errors" class="mt-4"></div>
    </div>

    <form id="reset-password-form" class="animate-fade-in" style="animation-delay: 0.2s;">
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2"></i>Email Address
            </label>
            <input 
                type="email" 
                id="email" 
                name="email"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-100 text-sm cursor-not-allowed"
                placeholder="Enter your email" 
                required 
                readonly
            >
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2"></i>New Password
            </label>
            <div class="relative">
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100 transition-all duration-300 text-sm pr-12"
                    placeholder="Enter new password" 
                    required 
                    onkeyup="checkPasswordStrength()"
                >
                <button 
                    type="button" 
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors"
                    onclick="togglePassword('password')"
                >
                    <i class="fas fa-eye" id="toggleIconPassword"></i>
                </button>
            </div>
            <div id="passwordStrength" class="mt-2" style="display: none;">
                <div class="w-full bg-gray-200 rounded-full h-1">
                    <div class="progress-bar h-1 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <div class="mt-1" id="strengthText"></div>
            </div>
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2"></i>Confirm New Password
            </label>
            <div class="relative">
                <input 
                    type="password" 
                    id="password_confirmation"
                    name="password_confirmation" 
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100 transition-all duration-300 text-sm pr-12"
                    placeholder="Confirm new password" 
                    required
                    onkeyup="checkPasswordMatch()"
                >
                <button 
                    type="button" 
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors"
                    onclick="togglePassword('password_confirmation')"
                >
                    <i class="fas fa-eye" id="toggleIconConfirm"></i>
                </button>
            </div>
            <div id="passwordMatch" class="mt-2" style="display: none;">
                <div id="matchText"></div>
            </div>
        </div>

        <div class="mb-6">
            <button 
                type="submit" 
                id="submit"
                class="w-full bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden"
            >
                <span class="relative z-10">
                    <i class="fas fa-key mr-2"></i>Reset Password
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 hover:opacity-20 transform -skew-x-12 -translate-x-full hover:translate-x-full transition-transform duration-500"></div>
            </button>
        </div>
    </form>

    <div class="text-center animate-fade-in" style="animation-delay: 0.4s;">
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

@vite('resources/js/pages/reset-password.js')
