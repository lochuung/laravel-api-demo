@extends('layouts.app')

@section('title', 'User Details')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h1 class="text-3xl font-bold text-gray-900 mb-4 md:mb-0 flex items-center">
                <i class="fas fa-user mr-3 text-blue-600"></i>
                User Details
            </h1>
            <div class="flex flex-col md:flex-row gap-2">
                <a href="#"
                   class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center"
                   id="edit-user-btn">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <button
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center"
                    id="delete-user-btn">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
                <a href="{{ route('users.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Users
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 text-center">
                    <img src="https://via.placeholder.com/150"
                         class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-gray-200" alt="Loading..."
                         id="user-avatar">
                    <h4 class="text-xl font-semibold text-gray-900 mb-2" id="user-name">Loading...</h4>
                    <p class="text-gray-500 mb-4" id="user-email">Loading...</p>
                    <div class="flex justify-center mb-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600"
                            id="user-status">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Loading...
                        </span>
                    </div>
                    <div class="space-y-2">
                        <button
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center"
                            id="send-message-btn">
                            <i class="fas fa-envelope mr-2"></i>Send Message
                        </button>
                        <button
                            class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center"
                            id="suspend-user-btn">
                            <i class="fas fa-ban mr-2"></i>Suspend User
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar mr-3 text-blue-600"></i>Quick Stats
                    </h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <h5 class="text-2xl font-bold text-blue-600" id="total-orders">-</h5>
                            <p class="text-sm text-gray-500">Orders</p>
                        </div>
                        <div>
                            <h5 class="text-2xl font-bold text-green-600" id="total-spent">-</h5>
                            <p class="text-sm text-gray-500">Total Spent</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="lg:col-span-2" x-data="{ activeTab: 'info' }">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button @click="activeTab = 'info'"
                                :class="activeTab === 'info' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-all duration-200">
                            <i class="fas fa-info-circle mr-2"></i>Personal Info
                        </button>
                        <button @click="activeTab = 'orders'"
                                :class="activeTab === 'orders' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-all duration-200">
                            <i class="fas fa-shopping-cart mr-2"></i>Orders
                        </button>
                    </nav>
                </div>
                <div class="p-6">
                    <div class="tab-content">
                        <!-- Personal Information Tab -->
                        <div x-show="activeTab === 'info'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             id="info" class="tab-pane">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-1">
                                        <span class="text-sm font-medium text-gray-700 flex items-center">
                                            <i class="fas fa-user mr-2"></i>Full Name:
                                        </span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-sm text-gray-900">Loading...</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-1">
                                        <span class="text-sm font-medium text-gray-700 flex items-center">
                                            <i class="fas fa-envelope mr-2"></i>Email:
                                        </span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-sm text-gray-900">Loading...</span>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 ml-2">
                                            <i class="fas fa-spinner fa-spin mr-1"></i>Loading
                                        </span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-1">
                                        <span class="text-sm font-medium text-gray-700 flex items-center">
                                            <i class="fas fa-phone mr-2"></i>Phone:
                                        </span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-sm text-gray-900">Loading...</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-1">
                                        <span class="text-sm font-medium text-gray-700 flex items-center">
                                            <i class="fas fa-calendar mr-2"></i>Joined:
                                        </span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-sm text-gray-900">Loading...</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-1">
                                        <span class="text-sm font-medium text-gray-700 flex items-center">
                                            <i class="fas fa-map-marker-alt mr-2"></i>Address:
                                        </span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-sm text-gray-900">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Orders Tab -->
                        <div x-show="activeTab === 'orders'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             id="orders" class="tab-pane">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order #
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Items
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="orders-body" class="bg-white divide-y divide-gray-200">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@vite('resources/js/pages/users/show.js')
