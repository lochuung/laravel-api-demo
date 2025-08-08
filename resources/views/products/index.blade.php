@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="grid grid-cols-1 gap-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Products Management
        </h1>
        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add New Product
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="md:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="search-input" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="Search products...">
                    <button type="button" id="search-btn" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <span class="text-sm font-medium text-blue-600 hover:text-blue-700">Search</span>
                    </button>
                </div>
            </div>
            <div>
                <select id="category-filter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" selected>All Categories</option>
                    <!-- Categories will be populated dynamically -->
                </select>
            </div>
            <div>
                <select id="status-filter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" selected>All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
            <div>
                <select id="code-prefix-filter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" selected>All Prefixes</option>
                    <!-- Code prefixes will be populated dynamically -->
                </select>
            </div>
            <div>
                <button id="price-filter-btn" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Price Range
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Active Filters Row -->
<div id="active-filters" class="bg-gray-50 rounded-lg p-4 hidden">
    <div class="flex items-center gap-3 flex-wrap">
        <span class="text-sm text-gray-600">Active filters:</span>
        <div id="filter-tags" class="flex gap-2"></div>
        <button id="clear-all-filters" class="text-sm text-red-600 hover:text-red-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Clear all
        </button>
    </div>
</div>

<!-- Price Filter Modal -->
<div id="price-filter-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Price Range Filter</h3>
            <button id="close-price-modal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="min-price" class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                    <input type="number" id="min-price" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                </div>
                <div>
                    <label for="max-price" class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                    <input type="number" id="max-price" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="1000">
                </div>
            </div>
            <div class="mt-4">
                <p id="price-range-info" class="text-sm text-gray-600">Price range: $0 - $1000</p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-3 p-6 border-t bg-gray-50">
            <button id="cancel-price-filter" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500">Cancel</button>
            <button id="clear-price-filter" class="px-4 py-2 text-yellow-700 border border-yellow-300 rounded-lg hover:bg-yellow-50 focus:outline-none focus:ring-1 focus:ring-yellow-500">Clear</button>
            <button id="apply-price-filter" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500">Apply Filter</button>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="bg-white rounded-lg shadow-sm border">
    <div class="flex justify-between items-center p-6 border-b">
        <h2 id="product-count" class="text-lg font-semibold text-gray-900">All Products (0)</h2>
        <div class="flex items-center gap-4">
            <!-- Sort Controls -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Sort by:</span>
                <select id="sort-by" class="px-3 py-1 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="created_at">Date Created</option>
                    <option value="name">Name</option>
                    <option value="price">Price</option>
                    <option value="stock">Stock</option>
                    <option value="updated_at">Last Updated</option>
                </select>
                <button id="sort-order" data-order="desc" class="p-1 text-gray-600 hover:text-gray-900 focus:outline-none" title="Sort Direction">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                    </svg>
                </button>
            </div>
            <!-- View Toggle -->
            <div class="flex bg-gray-100 rounded-lg p-1">
                <button id="grid-view" class="px-3 py-1 text-sm font-medium rounded-md transition-colors bg-white text-blue-600 shadow-sm">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Grid
                </button>
                <button id="list-view" class="px-3 py-1 text-sm font-medium rounded-md text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    List
                </button>
            </div>
        </div>
    </div>

    <!-- Grid View -->
    <div id="grid-container" class="p-6">
        <!-- Products will be populated dynamically -->
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Loading products...</h3>
        </div>
    </div>

    <!-- List View (Hidden by default) -->
    <div id="list-container" class="hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-sort="name">
                            Product
                            <svg class="w-4 h-4 inline-block ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable w-32" data-sort="code">
                            Code
                            <svg class="w-4 h-4 inline-block ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable w-24" data-sort="price">
                            Price
                            <svg class="w-4 h-4 inline-block ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable w-20" data-sort="stock">
                            Stock
                            <svg class="w-4 h-4 inline-block ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable w-32" data-sort="created_at">
                            Created
                            <svg class="w-4 h-4 inline-block ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Products will be populated dynamically -->
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-blue-600 animate-spin mb-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <div class="text-lg font-medium text-gray-900">Loading products...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<nav class="flex items-center justify-center mt-6" aria-label="Page navigation">
    <ul id="pagination" class="flex items-center space-x-1">
        <!-- Pagination will be populated dynamically -->
    </ul>
</nav>
@endsection

@vite('resources/js/pages/products-index.js')
