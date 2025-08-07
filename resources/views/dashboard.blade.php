@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Dashboard Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center">
                    <i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>
                    Dashboard
                </h1>
                <p class="text-gray-600">Welcome back, <span class="user-name font-semibold">Loading...</span>!</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="flex flex-wrap -mx-3 mb-8">
        <x-dashboard.stat-card
            title="Total Users"
            id="total-users"
            icon="fas fa-users"
            bg-gradient="from-blue-500 to-blue-600"
            footer-text="View all"
            :link="route('users.index')"
        />

        <x-dashboard.stat-card
            title="Products"
            id="total-products"
            icon="fas fa-box"
            bg-gradient="from-green-500 to-green-600"
            footer-text="View all"
            :link="route('products.index')"
        />

        <x-dashboard.stat-card
            title="Orders"
            id="total-orders"
            icon="fas fa-shopping-cart"
            bg-gradient="from-purple-500 to-purple-600"
            footer-text="View all"
            :link="route('orders.index')"
        />

        <x-dashboard.stat-card
            title="Revenue"
            id="monthly-revenue"
            icon="fas fa-money-bill-wave"
            bg-gradient="from-amber-500 to-amber-600"
            footer-text="This month"
        />
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-bolt mr-3 text-yellow-500"></i>
                Quick Actions
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('users.create') }}" class="flex flex-col items-center p-6 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-300 group border-2 border-transparent hover:border-blue-200">
                    <div class="mb-4">
                        <i class="fas fa-user-plus text-4xl text-blue-600 group-hover:text-blue-700 group-hover:scale-110 transition-all duration-300"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-blue-900 mb-2">Add New User</h4>
                    <p class="text-sm text-blue-600 text-center">Create a new user account for the system</p>
                </a>

                <a href="{{ route('products.create') }}" class="flex flex-col items-center p-6 bg-green-50 hover:bg-green-100 rounded-lg transition-all duration-300 group border-2 border-transparent hover:border-green-200">
                    <div class="mb-4">
                        <i class="fas fa-plus-square text-4xl text-green-600 group-hover:text-green-700 group-hover:scale-110 transition-all duration-300"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-green-900 mb-2">Add New Product</h4>
                    <p class="text-sm text-green-600 text-center">Create a new product in the inventory</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-clock mr-3 text-blue-500"></i>
                    Recent Users
                </h3>
            </div>
            <div class="recent-users divide-y divide-gray-200">
                <!-- Users will be populated dynamically -->
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p>Loading recent users...</p>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-shopping-bag mr-3 text-purple-500"></i>
                    Recent Orders
                </h3>
            </div>
            <div class="recent-orders divide-y divide-gray-200">
                <!-- Orders will be populated dynamically -->
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p>Loading recent orders...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@vite('resources/js/pages/dashboard.js')
