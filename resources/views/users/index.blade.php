@extends('layouts.app')

@section('title', 'Users Management')

@push('scripts')
    <script type="module" src="{{ asset('js/users/index.js')  }}"></script>
@endpush

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
        <div class="col-md-6">
            <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
                <input type="text" id="search-input" class="form-control" placeholder="Search users by name or email...">
                <button id="search-button" class="btn btn-outline-secondary" type="button">Search</button>
            </div>
        </div>
        <div class="col-md-3">
            <select id="role-filter" class="form-select">
                <option value="all" selected>All Users</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
                <option value="moderator">Moderator</option>
            </select>
        </div>
        <div class="col-md-3">
            <button id="clear-filters" class="btn btn-outline-warning w-100" type="button">
                <i class="fas fa-times"></i> Clear Filters
            </button>
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
                    <tbody id="user-table-body">
                    <tr>
                        <td colspan="7" class="text-center text-muted">No users found.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <nav>
                <ul class="pagination pagination-sm justify-content-center mb-0" id="pagination"></ul>
            </nav>
        </div>
    </div>
@endsection
