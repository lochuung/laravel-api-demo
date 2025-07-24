@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-user"></i> User Details
            </h1>
            <div>
                <a href="{{ route('users.edit', 1) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
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
                <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="User Avatar" width="150">
                <h4 class="card-title">John Smith</h4>
                <p class="text-muted">Administrator</p>
                <div class="d-flex justify-content-center mb-3">
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-check-circle"></i> Active
                    </span>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary">
                        <i class="fas fa-envelope"></i> Send Message
                    </button>
                    <button class="btn btn-outline-danger">
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
                        <h5 class="text-primary">5</h5>
                        <small class="text-muted">Orders</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success">$1,250</h5>
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
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" 
                                type="button" role="tab" aria-controls="activity" aria-selected="false">
                            <i class="fas fa-history"></i> Activity Log
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
                            <div class="col-sm-9">John Smith</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-envelope"></i> Email:</strong>
                            </div>
                            <div class="col-sm-9">
                                john.smith@example.com
                                <span class="badge bg-success ms-2">
                                    <i class="fas fa-check"></i> Verified
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-phone"></i> Phone:</strong>
                            </div>
                            <div class="col-sm-9">+1 (555) 123-4567</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-calendar"></i> Joined:</strong>
                            </div>
                            <div class="col-sm-9">January 15, 2025</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-clock"></i> Last Login:</strong>
                            </div>
                            <div class="col-sm-9">July 24, 2025 at 2:30 PM</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-map-marker-alt"></i> Address:</strong>
                            </div>
                            <div class="col-sm-9">
                                123 Main Street<br>
                                New York, NY 10001<br>
                                United States
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
                                <tbody>
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

                    <!-- Activity Log Tab -->
                    <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                        <div class="timeline">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-sign-in-alt text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">User logged in</h6>
                                    <small class="text-muted">Today at 2:30 PM</small>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-edit text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Profile updated</h6>
                                    <small class="text-muted">Yesterday at 4:15 PM</small>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-shopping-cart text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">New order placed</h6>
                                    <small class="text-muted">July 20, 2025 at 10:30 AM</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
