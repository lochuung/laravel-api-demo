@extends('layouts.app')

@section('title', 'Edit User')

@push('scripts')
    <script type="module" src="{{ asset('/js/views/users/edit.js')  }}"></script>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-user-edit"></i> Edit User
                </h1>
                <div>
                    <a href="#" class="btn btn-info me-2" id="view-btn">
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
                    <form id="editUserForm">
                        <!-- Current Avatar -->
                        <div class="text-center mb-4">
                            <img src="" class="rounded-circle mb-2" alt="Current Avatar"
                                 width="100" height="100" id="profile_picture">
                            <h6>Current Profile Picture</h6>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user"></i> Full Name *
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email Address *
                                </label>
                                <input type="email" class="form-control" id="email" name="email"
                                       required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone"></i> Phone Number
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag"></i> Role
                                </label>
                                <select class="form-select" id="role" name="role">
                                    <option value="User">User</option>
                                    <option value="Moderator">Moderator</option>
                                    <option value="Admin" selected>Administrator</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">
                                <i class="fas fa-image"></i> Update Profile Picture
                            </label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            <small class="form-text text-muted">Leave blank to keep current picture. Maximum file size:
                                2MB.</small>

                            <!-- Preview image -->
                            <div id="preview-container" class="mt-3">
                                <img id="avatar-preview" src="#" alt="Image Preview"
                                     style="display:none; max-width: 200px; max-height: 200px;"/>
                            </div>
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
                                        <label for="password_confirmation" class="form-label">Confirm New
                                            Password</label>
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
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                           checked>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check-circle"></i> Active User
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="email_verified"
                                           name="email_verified" checked>
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
                                        <span id="created_at">January 15, 2025</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div>
                                        <button type="button" class="btn btn-outline-danger me-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal">
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
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" data-bs-dismiss="modal">
                        <i class="fas fa-trash"></i> Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview image on file selection
        document.getElementById('avatar').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Update preview image
                    document.querySelector('.rounded-circle').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        $(document).ready(function () {
            $('#avatar').on('change', function () {
                const file = this.files[0];
                const preview = $('#avatar-preview');

                console.log(file);

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.attr('src', '#').hide();
                }
            });
        });
    </script>
@endpush
