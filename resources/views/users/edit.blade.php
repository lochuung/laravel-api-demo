@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-user-edit"></i> Edit User
            </h1>
            <div>
                <a href="{{ route('users.show', 1) }}" class="btn btn-info me-2">
                    <i class="fas fa-eye"></i> View
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user"></i> Edit User Information
                </h5>
            </div>
            <div class="card-body">
                <form>
                    <!-- Current Avatar -->
                    <div class="text-center mb-4">
                        <img src="https://via.placeholder.com/100" class="rounded-circle mb-2" alt="Current Avatar" width="100">
                        <h6>Current Profile Picture</h6>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i> Full Name *
                            </label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="John Smith" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email Address *
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="john.smith@example.com" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i> Phone Number
                            </label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="+1 (555) 123-4567">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag"></i> Role
                            </label>
                            <select class="form-select" id="role" name="role">
                                <option value="user">User</option>
                                <option value="editor">Editor</option>
                                <option value="admin" selected>Administrator</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Address
                        </label>
                        <textarea class="form-control" id="address" name="address" rows="3">123 Main Street
New York, NY 10001
United States</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="avatar" class="form-label">
                            <i class="fas fa-image"></i> Update Profile Picture
                        </label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                        <small class="form-text text-muted">Leave blank to keep current picture. Maximum file size: 2MB.</small>
                    </div>

                    <!-- Password Change Section -->
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-header bg-transparent">
                            <h6 class="mb-0">
                                <i class="fas fa-key"></i> Change Password (Optional)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Leave blank to keep current password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" 
                                           name="password_confirmation" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Options -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-check-circle"></i> Active User
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="email_verified" name="email_verified" checked>
                                <label class="form-check-label" for="email_verified">
                                    <i class="fas fa-envelope-check"></i> Email Verified
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-info-circle"></i> Account Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Created:</small><br>
                                    <span>January 15, 2025</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Last Login:</small><br>
                                    <span>July 24, 2025 at 2:30 PM</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                <div>
                                    <button type="button" class="btn btn-outline-danger me-2">
                                        <i class="fas fa-trash"></i> Delete User
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> All associated data (orders, activity logs) will also be deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete User
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show delete modal
    document.querySelector('.btn-outline-danger').addEventListener('click', function(e) {
        e.preventDefault();
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });

    // Preview image on file selection
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update preview image
                document.querySelector('.rounded-circle').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
