@extends('layouts.auth')
@section('title', 'Login')

@section('card-content')
    <div class="text-center mb-6 animate-fade-in">
        <div class="inline-block animate-float">
            <i class="fas fa-cube text-6xl mb-4 bg-gradient-to-br from-indigo-500 to-purple-600 bg-clip-text text-transparent"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back</h2>
        <p class="text-gray-600 mb-0">Sign in to continue to your account</p>

        <div id="errors" class="mt-4"></div>
    </div>

    <form id="login-form" class="animate-fade-in" style="animation-delay: 0.2s;">
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2"></i>Email Address
            </label>
            <input 
                type="email" 
                id="email" 
                name="email"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100 transition-all duration-300 text-sm"
                placeholder="Enter your email" 
                required
            >
        </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2"></i>Password
            </label>
            <div class="relative">
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100 transition-all duration-300 text-sm pr-12"
                    placeholder="Enter your password" 
                    required
                >
                <button 
                    type="button" 
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors"
                    onclick="togglePassword('password')"
                >
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="remember" 
                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2"
                >
                <label for="remember" class="ml-2 text-sm text-gray-600">
                    Remember me
                </label>
            </div>
            <a 
                href="{{ route('password.request') }}" 
                class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline transition-colors"
            >
                Forgot password?
            </a>
        </div>

        <div class="mb-6">
            <button 
                type="submit" 
                id="submit"
                class="w-full bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden"
            >
                <span class="relative z-10">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
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
            Don't have an account?
            <a 
                href="{{ route('register') }}" 
                class="text-indigo-600 hover:text-indigo-800 font-semibold hover:underline transition-colors"
            >
                Create one here
            </a>
        </p>
    </div>
@endsection

@vite('resources/js/pages/login.js')
