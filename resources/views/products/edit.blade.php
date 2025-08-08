@extends('layouts.app')

@section('title', 'Edit Product')

@vite('resources/js/components/product-edit.js')

<script>
    window.productId = {{ $id ?? 'null' }};
</script>

@section('content')
<div x-data="productEdit()" x-init="init()" class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Loading overlay -->
        <div x-show="loading" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 shadow-xl">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span class="text-gray-900 font-medium">Loading...</span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Product
            </h1>
            <div class="flex items-center gap-3">
                <a :href="viewProductLink" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View
                </a>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Products
                </a>
            </div>
        </div>

        <form @submit.prevent="updateProduct()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Product Information
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Product Name *
                            </label>
                            <input type="text" x-model="form.name"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                   :class="{ 'border-red-500': errors.name }"
                                   placeholder="Enter product name">
                            <div x-show="errors.name" class="text-red-600 text-sm mt-1" x-text="errors.name"></div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                                Description
                            </label>
                            <textarea x-model="form.description" rows="5"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                      :class="{ 'border-red-500': errors.description }"
                                      placeholder="Enter product description"></textarea>
                            <div x-show="errors.description" class="text-red-600 text-sm mt-1" x-text="errors.description"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Category *
                                </label>
                                <select x-model="form.category_id"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                        :class="{ 'border-red-500': errors.category_id }">
                                    <option value="">Select Category</option>
                                    <template x-for="[id, name] in Object.entries(categories)" :key="id">
                                        <option :value="id" x-text="name"></option>
                                    </template>
                                </select>
                                <div x-show="errors.category_id" class="text-red-600 text-sm mt-1" x-text="errors.category_id"></div>
                            </div>
                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Expiry Date
                                </label>
                                <input type="date" x-model="form.expiry_date"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                       :class="{ 'border-red-500': errors.expiry_date }">
                                <div x-show="errors.expiry_date" class="text-red-600 text-sm mt-1" x-text="errors.expiry_date"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="cost" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Cost Price *
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" x-model="form.cost" step="0.01" min="0"
                                           class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                           :class="{ 'border-red-500': errors.cost }"
                                           placeholder="0.00">
                                </div>
                                <div x-show="errors.cost" class="text-red-600 text-sm mt-1" x-text="errors.cost"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    Unit Price *
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" x-model="form.price" step="0.01" min="0"
                                           class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                           :class="{ 'border-red-500': errors.price }"
                                           placeholder="0.00">
                                </div>
                                <div x-show="errors.price" class="text-red-600 text-sm mt-1" x-text="errors.price"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    Stock Quantity *
                                </label>
                                <input type="number" x-model="form.stock" min="0"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                       :class="{ 'border-red-500': errors.stock }"
                                       placeholder="0">
                                <div x-show="errors.stock" class="text-red-600 text-sm mt-1" x-text="errors.stock"></div>
                            </div>
                            <div>
                                <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Min Stock Warning *
                                </label>
                                <input type="number" x-model="form.min_stock" min="0"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                       :class="{ 'border-red-500': errors.min_stock }"
                                       placeholder="0">
                                <div x-show="errors.min_stock" class="text-red-600 text-sm mt-1" x-text="errors.min_stock"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Unit Information (Read-only) -->
                <div class="bg-white rounded-lg shadow-sm border mt-6">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Unit Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-ruler text-blue-600"></i> Base Unit
                                </label>
                                <div class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                                    <span x-text="product.base_unit || 'Loading...'"></span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-code text-blue-600"></i> Base Unit SKU
                                </label>
                                <div class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-mono">
                                    <code x-text="product.base_sku || 'Loading...'"></code>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-percentage text-blue-600"></i> Margin Percentage
                                </label>
                                <div class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                                    <span :class="marginClass" x-text="marginPercentage + '%'"></span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-chart-bar text-blue-600"></i> Stock Status
                                </label>
                                <div class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="stockStatusClass" x-text="stockStatusText"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a :href="manageUnitsLink" class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="fas fa-layer-group mr-2"></i> Manage Units
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Current Images -->
                <div class="bg-white rounded-lg shadow-sm border mt-6">
                    <div class="px-6 py-4 border-b">
                        <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Product Images
                        </h5>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <div x-show="product.image" class="relative inline-block">
                                <img :src="product.image" class="w-full h-48 object-cover rounded-lg" alt="Product Image">
                                <button type="button" @click="removeCurrentImage()"
                                        class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors"
                                        title="Remove Image">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div x-show="!product.image" class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-500">No image available</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="new_image" class="block text-sm font-medium text-gray-700 mb-2">Upload New Image</label>
                            <input type="file" @change="handleImageUpload($event)"
                                   class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                   accept="image/*">
                            <small class="text-gray-500 text-sm mt-1">
                                Supported formats: JPG, PNG, GIF. Max size: 2MB
                            </small>
                        </div>

                        <div x-show="newImagePreview" class="mt-4">
                            <img :src="newImagePreview" class="w-full h-48 object-cover rounded-lg" alt="New Image Preview">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Product Settings -->
                <div class="bg-white rounded-lg shadow-sm border mb-6">
                    <div class="px-6 py-4 border-b">
                        <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Product Settings
                        </h5>
                    </div>
                    <div class="p-6">
                        <div class="mb-4 flex items-center">
                            <input type="checkbox" x-model="form.is_active"
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label class="ml-3 text-sm text-gray-700">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Active (Visible to customers)
                            </label>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <small class="text-blue-700 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Changes to product status will be effective immediately.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Product Statistics -->
                <div class="bg-white rounded-lg shadow-sm border mb-6">
                    <div class="px-6 py-4 border-b">
                        <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Statistics
                        </h5>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 text-center mb-4">
                            <div>
                                <h6 class="text-blue-600 text-lg font-semibold" x-text="stats.totalSales || '-'"></h6>
                                <small class="text-gray-500">Total Sales</small>
                            </div>
                            <div>
                                <h6 class="text-green-600 text-lg font-semibold" x-text="stats.pageViews || '-'"></h6>
                                <small class="text-gray-500">Page Views</small>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500">
                                <div>Created: <span x-text="formatDateTime(product.created_at) || 'Loading...'"></span></div>
                                <div>Last Updated: <span x-text="formatDateTime(product.updated_at) || 'Loading...'"></span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b">
                        <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Actions
                        </h5>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <button type="submit" :disabled="saving"
                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors">
                                <svg x-show="!saving" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <svg x-show="saving" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span x-text="saving ? 'Updating...' : 'Update Product'"></span>
                            </button>

                            <button type="button" @click="duplicateProduct()"
                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Duplicate Product
                            </button>

                            <button type="button" @click="showDeleteModal = true"
                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Product
                            </button>

                            <a :href="cancelLink"
                               class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-red-500 text-white rounded-t-lg">
                <h5 class="text-xl font-semibold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Confirm Delete
                </h5>
                <button type="button" class="text-red-100 hover:text-white" @click="showDeleteModal = false">
                    <span class="sr-only">Close</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <p class="text-gray-900 mb-4">Are you sure you want to delete this product? This action cannot be undone.</p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800 font-medium">Warning:</p>
                    <p class="text-yellow-700">All associated data (sales history, reviews) will also be deleted.</p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                <button type="button" @click="showDeleteModal = false"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </button>
                <button type="button" @click="deleteProduct()" :disabled="deleting"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 disabled:opacity-50">
                    <svg x-show="!deleting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <svg x-show="deleting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span x-text="deleting ? 'Deleting...' : 'Delete Product'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
