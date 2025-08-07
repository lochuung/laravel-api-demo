@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h1 class="text-3xl font-bold text-gray-900 mb-4 md:mb-0 flex items-center">
                <i class="fas fa-user-edit mr-3 text-blue-600"></i>
                Edit User
            </h1>
            <div class="flex flex-col md:flex-row gap-2">
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center" id="view-btn">
                    <i class="fas fa-eye mr-2"></i>View
                </a>
                <a href="{{ route('users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Users
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-user mr-3 text-blue-600"></i>
                    Edit User Information
                </h3>
            </div>
            <div class="p-6">
                <form id="editUserForm">
                    <!-- Current Avatar -->
                    <div class="text-center mb-6">
                        <img src="" class="w-24 h-24 rounded-full mx-auto mb-2 border-4 border-gray-200" alt="Current Avatar" id="profile_picture">
                        <h6 class="text-sm font-medium text-gray-700">Current Profile Picture</h6>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2"></i>Full Name *
                            </label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="name" name="name" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2"></i>Email Address *
                            </label>
                            <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-2"></i>Phone Number
                            </label>
                            <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="phone" name="phone">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-tag mr-2"></i>Role
                            </label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="role" name="role">
                                <option value="User">User</option>
                                <option value="Moderator">Moderator</option>
                                <option value="Admin" selected>Administrator</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Address
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="address" name="address" rows="3"></textarea>
                    </div>

                    <div class="mt-6">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2"></i>Update Profile Picture
                        </label>
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="avatar" name="avatar" accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">Leave blank to keep current picture. Maximum file size: 2MB.</p>

                        <!-- Preview image -->
                        <div id="preview-container" class="mt-3">
                            <img id="avatar-preview" src="#" alt="Image Preview" class="hidden max-w-48 max-h-48 rounded-lg border border-gray-200"/>
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-key mr-2"></i>Change Password (Optional)
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="password" name="password" placeholder="Leave blank to keep current password">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>

                    <!-- Status Options -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" id="is_active" name="is_active" checked>
                            <label class="ml-2 text-sm font-medium text-gray-700" for="is_active">
                                <i class="fas fa-check-circle mr-2"></i>Active User
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" id="email_verified" name="email_verified" checked>
                            <label class="ml-2 text-sm font-medium text-gray-700" for="email_verified">
                                <i class="fas fa-envelope-check mr-2"></i>Email Verified
                            </label>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h6 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>Account Information
                        </h6>
                        <div>
                            <span class="text-sm text-gray-500">Created:</span>
                            <span id="created_at" class="text-sm font-medium text-gray-900 ml-2">January 15, 2025</span>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:justify-end items-center gap-4 mt-8 pt-6 border-t border-gray-200">
                        <button type="button" class="w-full md:w-auto px-6 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition-colors flex items-center justify-center" id="delete-modal-trigger">
                            <i class="fas fa-trash mr-2"></i>Delete User
                        </button>
                        <button type="submit" class="w-full md:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center mb-4">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Confirm Delete</h3>
                    <p class="text-sm text-gray-500 mb-4">Are you sure you want to delete this user? This action cannot be undone.</p>
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg mb-4">
                        <p class="text-sm text-amber-800"><strong>Warning:</strong> All associated data (orders, activity logs) will also be deleted.</p>
                    </div>
                    <div class="flex gap-3 justify-center">
                        <button id="cancelDelete" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">Cancel</button>
                        <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center">
                            <i class="fas fa-trash mr-2"></i>Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@vite('resources/js/pages/users/edit.js')
