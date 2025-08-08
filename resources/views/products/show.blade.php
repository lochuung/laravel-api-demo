@extends('layouts.app')

@section('title', 'Product Details')

@vite('resources/js/components/product-show.js')

<script>
    window.productId = {{ $id ?? 'null' }};
</script>

@section('content')
    <div x-data="productShow()" x-init="init()">
        <!-- Loading Overlay -->
        <div x-show="loading" class="fixed inset-0 bg-white bg-opacity-80 flex items-center justify-center z-50">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
                <div class="mt-4">
                    <h5 class="text-blue-600 font-medium">Loading Product Details...</h5>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-4">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-box text-blue-600"></i> Product Details
                </h1>
                <div class="flex flex-wrap gap-2">
                    <a :href="editProductLink"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button @click="deleteProduct"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Product Images -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-lg shadow-md border">
                    <div class="p-6">
                        <!-- Main Image -->
                        <div class="text-center mb-6">
                            <img :src="product.image || 'https://via.placeholder.com/400x300'"
                                 class="w-full h-64 object-cover rounded-lg" alt="Product Image">
                        </div>
                    </div>
                </div>

                <!-- Product Stats -->
                <div class="mt-6 bg-white rounded-lg shadow-md border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h6 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-blue-600"></i> Quick Stats
                        </h6>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <h5 class="text-2xl font-bold text-blue-600" x-text="product.stock || 0"></h5>
                                <small class="text-gray-500">In Stock</small>
                            </div>
                            <div>
                                <h5 class="text-2xl font-bold text-green-600" x-text="marginPercentage + '%'"></h5>
                                <small class="text-gray-500">Margin</small>
                            </div>
                        </div>
                        <hr class="my-4 border-gray-200">
                        <div class="text-center">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium"
                              :class="statusClass" x-text="statusText"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-lg shadow-md border">
                    <div class="border-b border-gray-200">
                        <nav class="flex space-x-8 px-6" aria-label="Tabs">
                            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                                    :class="activeTab === 'details' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    @click="activeTab = 'details'">
                                <i class="fas fa-info-circle mr-2"></i> Details
                            </button>
                            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                                    :class="activeTab === 'units' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    @click="activeTab = 'units'">
                                <i class="fas fa-layer-group mr-2"></i> Units
                            </button>
                            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                                    :class="activeTab === 'inventory' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    @click="activeTab = 'inventory'">
                                <i class="fas fa-boxes mr-2"></i> Inventory
                            </button>
                            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                                    :class="activeTab === 'sales' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    @click="activeTab = 'sales'">
                                <i class="fas fa-chart-line mr-2"></i> Sales
                            </button>
                        </nav>
                    </div>
                    <div class="p-6">
                        <div class="tab-content">
                            <!-- Product Details Tab -->
                            <div class="tab-pane" x-show="activeTab === 'details'">
                                <div class="space-y-4">

                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-tag text-blue-600"></i> Name:
                                            </div>
                                            <div class="sm:col-span-2">
                                                <h5 class="text-lg font-medium text-gray-900"
                                                    x-text="product.name || 'Loading...'"></h5>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-code text-blue-600"></i> SKU:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-900"
                                                 x-text="product.base_sku || 'Loading...'"></div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-barcode text-blue-600"></i> Barcode:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-900"
                                                 x-text="product.base_barcode || 'Loading...'"></div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-ruler text-blue-600"></i> Base Unit:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-900"
                                                 x-text="product.base_unit || 'Loading...'"></div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-layer-group text-blue-600"></i> Category:
                                            </div>
                                            <div class="sm:col-span-2">
                                        <span
                                            class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium"
                                            x-text="product.category?.name || 'Loading...'"></span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-dollar-sign text-blue-600"></i> Price:
                                            </div>
                                            <div class="sm:col-span-2">
                                                <h4 class="text-2xl font-bold text-blue-600"
                                                    x-text="formatCurrency(product.price) || 'Loading...'"></h4>
                                                <small class="text-gray-500"
                                                       x-text="product.base_unit ? `per ${product.base_unit}` : 'Loading...'"></small>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-money-bill text-blue-600"></i> Cost:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-900"
                                                 x-text="formatCurrency(product.cost) || 'Loading...'"></div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-percentage text-blue-600"></i> Margin:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-900"
                                                 x-text="marginPercentage + '%' || 'Loading...'"></div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-toggle-on text-blue-600"></i> Status:
                                            </div>
                                            <div class="sm:col-span-2">
                                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium"
                                              :class="statusClass" x-text="statusText"></span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-align-left text-blue-600"></i> Description:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-700"
                                                 x-text="product.description || 'No description available'"></div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-boxes text-blue-600"></i> Stock:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-900"
                                                 x-text="product.stock || 'Loading...'"></div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-exclamation-triangle text-blue-600"></i> Min Stock:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-900"
                                                 x-text="product.min_stock || 'Loading...'"></div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-chart-bar text-blue-600"></i> Stock Status:
                                            </div>
                                            <div class="sm:col-span-2">
                                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium"
                                              :class="stockStatusClass" x-text="stockStatusText"></span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-calendar-times text-blue-600"></i> Expiry:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-900"
                                                 x-text="formatDate(product.expiry_date) || 'No expiry date'"></div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-calendar text-blue-600"></i> Created:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-900"
                                                 x-text="formatDateTime(product.created_at) || 'Loading...'"></div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="font-semibold text-gray-700 flex items-center gap-2">
                                                <i class="fas fa-calendar-edit text-blue-600"></i> Updated:
                                            </div>
                                            <div class="sm:col-span-2 text-gray-900"
                                                 x-text="formatDateTime(product.updated_at) || 'Loading...'"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Units Tab -->
                                <div class="tab-pane" x-show="activeTab === 'units'">
                                    <div
                                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                                        <h6 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                            <i class="fas fa-layer-group text-blue-600"></i> Product Units
                                        </h6>
                                        <button
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
                                            @click="openUnitModal()">
                                            <i class="fas fa-plus"></i> New Unit
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div class="bg-blue-500 text-white rounded-lg p-4 text-center">
                                            <h4 class="text-2xl font-bold" x-text="units.length"></h4>
                                            <small>Total Units</small>
                                        </div>
                                        <div class="bg-green-500 text-white rounded-lg p-4 text-center">
                                            <h4 class="text-2xl font-bold" x-text="product.base_unit || '-'"></h4>
                                            <small>Base Unit</small>
                                        </div>
                                        <div class="bg-yellow-500 text-white rounded-lg p-4 text-center">
                                            <h4 class="text-2xl font-bold"
                                                x-text="units.filter(u => !u.is_base_unit).length"></h4>
                                            <small>Variations</small>
                                        </div>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-hashtag mr-1"></i> ID
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-tag mr-1"></i> Unit Name
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-code mr-1"></i> SKU
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-barcode mr-1"></i> Barcode
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-exchange-alt mr-1"></i> Conversion
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-dollar-sign mr-1"></i> Price
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-boxes mr-1"></i> Stock
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-star mr-1"></i> Base
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-cogs mr-1"></i> Actions
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                            <template x-for="unit in units" :key="unit.id">
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                        x-text="unit.id"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                        x-text="unit.unit_name"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                                        x-text="unit.sku || '-'"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                                        x-text="unit.barcode || '-'"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                                        x-text="unit.conversion_rate"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                        x-text="formatCurrency(unit.selling_price)"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                        x-text="Math.floor(product.stock / unit.conversion_rate)"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                    <span x-show="unit.is_base_unit"
                                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-star mr-1"></i> Base
                                                    </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <button @click="editUnit(unit)"
                                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button @click="confirmDeleteUnit(unit)"
                                                                class="text-red-600 hover:text-red-900"
                                                                x-show="!unit.is_base_unit">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                            <tr x-show="units.length === 0">
                                                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                                    <i class="fas fa-layer-group text-4xl mb-2"></i>
                                                    <p>No units found for this product</p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Inventory Tab -->
                                <div class="tab-pane" x-show="activeTab === 'inventory'">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <div class="bg-blue-500 text-white rounded-lg p-4 text-center">
                                            <h3 class="text-2xl font-bold" x-text="product.stock || 0"></h3>
                                            <small>Current Stock</small>
                                        </div>
                                        <div class="bg-yellow-500 text-white rounded-lg p-4 text-center">
                                            <h3 class="text-2xl font-bold" x-text="formatCurrency(stockValue)"></h3>
                                            <small>Stock Value</small>
                                        </div>
                                    </div>

                                    <h6 class="text-lg font-semibold text-gray-900 mb-4">Inventory Details</h6>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Field
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Value
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Base
                                                    SKU
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"
                                                    x-text="product.base_sku || '-'"></td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Base
                                                    Barcode
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"
                                                    x-text="product.base_barcode || '-'"></td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Base
                                                    Unit
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"
                                                    x-text="product.base_unit || '-'"></td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Cost
                                                    per Unit
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"
                                                    x-text="formatCurrency(product.cost) || '-'"></td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Stock
                                                    Status
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium"
                                                      :class="stockStatusClass" x-text="stockStatusText"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Margin
                                                    Percentage
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"
                                                    x-text="marginPercentage + '%'"></td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Is
                                                    Expired
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium"
                                                      :class="isExpired ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'"
                                                      x-text="isExpired ? 'Expired' : 'Fresh'"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Days
                                                    Until Expiry
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"
                                                    x-text="daysUntilExpiry !== null ? daysUntilExpiry + ' days' : 'No expiry date'"></td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Last
                                                    Updated
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"
                                                    x-text="formatDateTime(product.updated_at) || '-'"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Sales Tab -->
                                <div class="tab-pane" x-show="activeTab === 'sales'">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div class="bg-green-500 text-white rounded-lg p-4 text-center">
                                            <h4 class="text-2xl font-bold" x-text="salesStats.totalSold || 0"></h4>
                                            <small>Total Sold</small>
                                        </div>
                                        <div class="bg-blue-500 text-white rounded-lg p-4 text-center">
                                            <h4 class="text-2xl font-bold"
                                                x-text="formatCurrency(salesStats.totalRevenue) || '$0.00'"></h4>
                                            <small>Total Revenue</small>
                                        </div>
                                        <div class="bg-yellow-500 text-white rounded-lg p-4 text-center">
                                            <h4 class="text-2xl font-bold"
                                                x-text="formatCurrency(salesStats.avgSalePrice) || '$0.00'"></h4>
                                            <small>Avg. Sale Price</small>
                                        </div>
                                    </div>

                                    <h6 class="text-lg font-semibold text-gray-900 mb-4">Recent Sales</h6>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Order
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Customer
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Date
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Qty
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Price
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Total
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                            <template x-for="sale in recentSales" :key="sale.id">
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                        x-text="sale.order_code"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                        x-text="sale.customer_name"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                                        x-text="formatDate(sale.date)"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                        x-text="sale.quantity"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                        x-text="formatCurrency(sale.price)"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                        x-text="formatCurrency(sale.total)"></td>
                                                </tr>
                                            </template>
                                            <tr x-show="recentSales.length === 0">
                                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                                    No recent sales found
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Unit Modal -->
            <div x-show="showUnitModal"
                 class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-screen overflow-y-auto"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <h5 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-layer-group text-blue-600"></i>
                            <span x-text="editingUnit ? 'Edit Unit' : 'Add New Unit'"></span>
                        </h5>
                        <button type="button" class="text-gray-400 hover:text-gray-600" @click="closeUnitModal()">
                            <span class="sr-only">Close</span>
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="saveUnit()" novalidate>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="unit-name" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-tag text-blue-600"></i> Unit Name *
                                    </label>
                                    <input type="text"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           id="unit-name"
                                           x-model="unitForm.unit_name"
                                           placeholder="e.g., piece, box, carton"
                                           required>
                                    <div class="mt-1 text-red-600 text-sm" x-show="unitErrors.unit_name"
                                         x-text="unitErrors.unit_name"></div>
                                </div>
                                <div>
                                    <label for="unit-sku" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-code text-blue-600"></i> SKU
                                    </label>
                                    <input type="text"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           id="unit-sku"
                                           x-model="unitForm.sku"
                                           placeholder="e.g., PROD-001-BOX">
                                    <div class="mt-1 text-red-600 text-sm" x-show="unitErrors.sku"
                                         x-text="unitErrors.sku"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div>
                                    <label for="unit-barcode" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-barcode text-blue-600"></i> Barcode
                                    </label>
                                    <input type="text"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           id="unit-barcode"
                                           x-model="unitForm.barcode"
                                           placeholder="e.g., 1234567890123">
                                    <div class="mt-1 text-red-600 text-sm" x-show="unitErrors.barcode"
                                         x-text="unitErrors.barcode"></div>
                                </div>
                                <div>
                                    <label for="unit-conversion-rate"
                                           class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-exchange-alt text-blue-600"></i> Conversion Rate *
                                    </label>
                                    <input type="number"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           id="unit-conversion-rate"
                                           x-model="unitForm.conversion_rate"
                                           step="0.01"
                                           min="0.01"
                                           placeholder="1.0"
                                           required>
                                    <div class="mt-1 text-gray-500 text-xs">How many base units equal 1 of this unit
                                    </div>
                                    <div class="mt-1 text-red-600 text-sm" x-show="unitErrors.conversion_rate"
                                         x-text="unitErrors.conversion_rate"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div>
                                    <label for="unit-selling-price"
                                           class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-dollar-sign text-blue-600"></i> Selling Price *
                                    </label>
                                    <input type="number"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           id="unit-selling-price"
                                           x-model="unitForm.selling_price"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
                                           required>
                                    <div class="mt-1 text-red-600 text-sm" x-show="unitErrors.selling_price"
                                         x-text="unitErrors.selling_price"></div>
                                </div>
                                <div>
                                    <div class="mt-8">
                                        <label class="flex items-center">
                                            <input type="checkbox"
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                                   x-model="unitForm.is_base_unit">
                                            <span class="ml-2 text-sm font-medium text-gray-900">
                                        <i class="fas fa-star text-yellow-500"></i> Set as Base Unit
                                    </span>
                                        </label>
                                        <div class="mt-1 text-gray-500 text-xs">Base unit is the primary unit for this
                                            product
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <button type="button"
                                    class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    @click="closeUnitModal()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    :disabled="savingUnit">
                                <i class="fas fa-save" x-show="!savingUnit"></i>
                                <svg x-show="savingUnit" class="animate-spin h-5 w-5 text-white"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span x-text="editingUnit ? 'Update Unit' : 'Save Unit'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Unit Confirmation Modal -->
            <div x-show="showDeleteUnitModal"
                 class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50"
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
                    <div
                        class="flex items-center justify-between p-6 border-b border-gray-200 bg-red-500 text-white rounded-t-lg">
                        <h5 class="text-xl font-semibold flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i> Confirm Delete Unit
                        </h5>
                        <button type="button" class="text-red-100 hover:text-white" @click="closeDeleteUnitModal()">
                            <span class="sr-only">Close</span>
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-900 mb-4">Are you sure you want to delete the unit "<strong
                                x-text="unitToDelete?.unit_name"></strong>"?</p>
                        <p class="text-red-600 mb-0 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            This action cannot be undone.
                        </p>
                    </div>
                    <div
                        class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                        <button type="button"
                                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                @click="closeDeleteUnitModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="button"
                                class="inline-flex items-center gap-2 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                @click="executeDeleteUnit()"
                                :disabled="deletingUnit">
                            <i class="fas fa-trash" x-show="!deletingUnit"></i>
                            <svg x-show="deletingUnit" class="animate-spin h-5 w-5 text-white"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            Delete Unit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
