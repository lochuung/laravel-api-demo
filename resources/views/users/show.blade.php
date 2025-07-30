@extends('layouts.app')

@section('title', 'User Details')

@push('scripts')
    <script type="module" src="{{ asset('js/views/users/show.js') }}"></script>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-user"></i> User Details
                </h1>
                <div>
                    <a href="#" class="btn btn-warning me-2" id="edit-user-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button class="btn btn-danger me-2" id="delete-user-btn">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="Loading..." height="150"
                         width="150">
                    <h4 class="card-title">Loading...</h4>
                    <p class="text-muted">Loading...</p>
                    <div class="d-flex justify-content-center mb-3">
                    <span class="badge bg-secondary fs-6">
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </span>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" id="send-message-btn">
                            <i class="fas fa-envelope"></i> Send Message
                        </button>
                        <button class="btn btn-outline-danger" id="suspend-user-btn">
                            <i class="fas fa-ban"></i> Suspend User
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-primary">-</h5>
                            <small class="text-muted">Orders</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-success">-</h5>
                            <small class="text-muted">Total Spent</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="userTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                                    type="button" role="tab" aria-controls="info" aria-selected="true">
                                <i class="fas fa-info-circle"></i> Personal Info
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders"
                                    type="button" role="tab" aria-controls="orders" aria-selected="false">
                                <i class="fas fa-shopping-cart"></i> Orders
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="userTabsContent">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-user"></i> Full Name:</strong>
                                </div>
                                <div class="col-sm-9">Loading...</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-envelope"></i> Email:</strong>
                                </div>
                                <div class="col-sm-9">
                                    Loading...
                                    <span class="badge bg-secondary ms-2">
                                    <i class="fas fa-spinner fa-spin"></i> Loading
                                </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-phone"></i> Phone:</strong>
                                </div>
                                <div class="col-sm-9">Loading...</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-calendar"></i> Joined:</strong>
                                </div>
                                <div class="col-sm-9">Loading...</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-map-marker-alt"></i> Address:</strong>
                                </div>
                                <div class="col-sm-9">
                                    Loading...
                                </div>
                            </div>
                        </div>

                        <!-- Orders Tab -->
                        <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody id="orders-body">
                                    <tr>
                                        <td><a href="{{ route('orders.show', 1001) }}">#1001</a></td>
                                        <td>July 20, 2025</td>
                                        <td>3 items</td>
                                        <td>$299.99</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('orders.show', 1002) }}">#1002</a></td>
                                        <td>July 18, 2025</td>
                                        <td>1 item</td>
                                        <td>$199.50</td>
                                        <td><span class="badge bg-info">Shipped</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('orders.show', 1003) }}">#1003</a></td>
                                        <td>July 15, 2025</td>
                                        <td>5 items</td>
                                        <td>$750.49</td>
                                        <td><span class="badge bg-warning">Processing</span></td>
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
@endsection
