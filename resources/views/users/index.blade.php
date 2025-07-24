@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-users"></i> Users Management
            </h1>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Search users by name or email...">
            <button class="btn btn-outline-secondary" type="button">Search</button>
        </div>
    </div>
    <div class="col-md-4">
        <select class="form-select">
            <option selected>All Users</option>
            <option value="active">Active Users</option>
            <option value="inactive">Inactive Users</option>
        </select>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Users (1,234)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#1</td>
                        <td>
                            <img src="https://via.placeholder.com/40" class="rounded-circle" alt="User" width="40">
                        </td>
                        <td>
                            <div>
                                <h6 class="mb-0">John Smith</h6>
                                <small class="text-muted">Administrator</small>
                            </div>
                        </td>
                        <td>john.smith@example.com</td>
                        <td>Jan 15, 2025</td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('users.show', 1) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', 1) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#2</td>
                        <td>
                            <img src="https://via.placeholder.com/40" class="rounded-circle" alt="User" width="40">
                        </td>
                        <td>
                            <div>
                                <h6 class="mb-0">Jane Doe</h6>
                                <small class="text-muted">Editor</small>
                            </div>
                        </td>
                        <td>jane.doe@example.com</td>
                        <td>Jan 20, 2025</td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('users.show', 2) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', 2) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#3</td>
                        <td>
                            <img src="https://via.placeholder.com/40" class="rounded-circle" alt="User" width="40">
                        </td>
                        <td>
                            <div>
                                <h6 class="mb-0">Mike Johnson</h6>
                                <small class="text-muted">User</small>
                            </div>
                        </td>
                        <td>mike.johnson@example.com</td>
                        <td>Jan 22, 2025</td>
                        <td>
                            <span class="badge bg-warning">Pending</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('users.show', 3) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', 3) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0 justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
