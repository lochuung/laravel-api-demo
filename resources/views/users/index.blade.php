@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h1 class="text-3xl font-bold text-gray-900 mb-4 md:mb-0 flex items-center">
                <i class="fas fa-users mr-3 text-blue-600"></i>
                Users Management
            </h1>
            <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add New User
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2">
            <div class="relative flex items-center">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="search-input" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Search users by name or email...">
                <button id="search-button" class="px-4 py-2 bg-gray-50 hover:bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg text-gray-700 hover:text-gray-900 transition-colors" type="button">Search</button>
            </div>
        </div>
        <div class="flex gap-2">
            <select id="role-filter" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                <option value="all" selected>All Users</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
                <option value="moderator">Moderator</option>
            </select>
            <button id="clear-filters" class="px-4 py-2 bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 hover:text-amber-800 rounded-lg transition-colors flex items-center" type="button">
                <i class="fas fa-times mr-1"></i>
                Clear
            </button>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">All Users (1,234)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avatar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="user-table-body" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">No users found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <nav class="flex justify-center">
                <ul id="pagination" class="flex space-x-1"></ul>
            </nav>
        </div>
    </div>
@endsection

@vite('resources/js/pages/users/index.js')
