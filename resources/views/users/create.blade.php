@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h1 class="text-3xl font-bold text-gray-900 mb-4 md:mb-0 flex items-center">
                <i class="fas fa-user-plus mr-3 text-blue-600"></i>
                Create New User
            </h1>
            <a href="{{ route('users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Users
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-user mr-3 text-blue-600"></i>
                    User Information
                </h3>
            </div>
            <div class="p-6">
                <form id="createUserForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2"></i>Full Name *
                            </label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="name" name="name" placeholder="Enter full name" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2"></i>Email Address *
                            </label>
                            <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="email" name="email" placeholder="Enter email address" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2"></i>Password *
                            </label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2"></i>Confirm Password *
                            </label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-2"></i>Phone Number
                            </label>
                            <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="phone" name="phone_number" placeholder="Enter phone number">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-tag mr-2"></i>Role
                            </label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="role" name="role">
                                <option value="User">User</option>
                                <option value="Moderator">Moderator</option>
                                <option value="Admin">Administrator</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Address
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="address" name="address" rows="3" placeholder="Enter address"></textarea>
                    </div>

                    <div class="mt-6">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2"></i>Profile Picture
                        </label>
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="avatar" name="avatar" accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF</p>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" id="is_active" name="is_active" checked>
                            <label class="ml-2 text-sm font-medium text-gray-700" for="is_active">
                                <i class="fas fa-check-circle mr-2"></i>Active User
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" id="email_verified" name="email_verified">
                            <label class="ml-2 text-sm font-medium text-gray-700" for="email_verified">
                                <i class="fas fa-envelope-check mr-2"></i>Email Verified
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:justify-between items-center gap-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('users.index') }}" class="w-full md:w-auto px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="w-full md:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2"></i>Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@vite('resources/js/pages/users/create.js')
