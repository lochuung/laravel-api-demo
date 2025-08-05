@extends('layouts.app')

@section('title', 'Dashboard')

@push('scripts')
    <script type="module" src="{{ asset('js/views/dashboard.js')  }}"></script>
@endpush


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </h1>
                <small class="text-muted">Welcome back, <span class="user-name"></span>!</small>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <x-dashboard.stat-card
            title="Total Users"
            id="total-users"
            icon="fas fa-users"
            bg-color="bg-primary"
            footer-text="View all"
            :link="route('users.index')"
        />

        <x-dashboard.stat-card
            title="Products"
            id="total-products"
            icon="fas fa-box"
            bg-color="bg-success"
            footer-text="View all"
            :link="route('products.index')"
        />

        <x-dashboard.stat-card
            title="Orders"
            id="total-orders"
            icon="fas fa-shopping-cart"
            bg-color="bg-warning"
            footer-text="View all"
            :link="route('orders.index')"
        />

        <x-dashboard.stat-card
            title="Revenue"
            id="monthly-revenue"
            icon="fas fa-money-bill-wave"
            bg-color="bg-info"
            footer-text="This month"
        />
    </div>


    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('users.create') }}" class="btn btn-outline-primary w-100 p-3">
                                <i class="fas fa-user-plus fa-2x d-block mb-2"></i>
                                Add New User
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('products.create') }}" class="btn btn-outline-success w-100 p-3">
                                <i class="fas fa-plus-square fa-2x d-block mb-2"></i>
                                Add New Product
                            </a>
                        </div>
{{--                        <div class="col-md-4 mb-3">--}}
{{--                            <a href="{{ route('orders.create') }}" class="btn btn-outline-warning w-100 p-3">--}}
{{--                                <i class="fas fa-cart-plus fa-2x d-block mb-2"></i>--}}
{{--                                Create Order--}}
{{--                            </a>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock"></i> Recent Users
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush recent-users">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-bag"></i> Recent Orders
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush recent-orders">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
