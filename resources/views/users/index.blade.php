<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">User Management</h3>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-plus"></i> Add User
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Users will be loaded here via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addUserForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback" id="password-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit_user_id" name="user_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <div class="invalid-feedback" id="edit_name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                            <div class="invalid-feedback" id="edit_email-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="edit_password" name="password" required>
                            <div class="invalid-feedback" id="edit_password-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                    <p><strong>User: </strong><span id="deleteUserName"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Set CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Load users on page load
            loadUsers();

            // Add User Form Submit
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                addUser();
            });

            // Edit User Form Submit
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();
                updateUser();
            });

            // Confirm Delete
            $('#confirmDelete').on('click', function() {
                deleteUser();
            });
        });

        function loadUsers() {
            $.ajax({
                url: "{{ route('users.index') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        displayUsers(response.data);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading users:', xhr);
                }
            });
        }

        function displayUsers(users) {
            let tbody = $('#usersTable tbody');
            tbody.empty();

            users.forEach(function(user) {
                let row = `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${new Date(user.created_at).toLocaleDateString()}</td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-user" data-user-id="${user.id}" data-user-name="${user.name}" data-user-email="${user.email}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-user" data-user-id="${user.id}" data-user-name="${user.name}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });

            // Bind edit and delete events
            $('.edit-user').on('click', function() {
                let user = {
                    id: $(this).data('user-id'),
                    name: $(this).data('user-name'),
                    email: $(this).data('user-email')
                };
                openEditModal(user);
            });

            $('.delete-user').on('click', function() {
                let user = {
                    id: $(this).data('user-id'),
                    name: $(this).data('user-name')
                };
                openDeleteModal(user);
            });
        }

        function addUser() {
            let formData = {
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val()
            };

            $.ajax({
                url: "{{ route('users.store') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#addUserModal').modal('hide');
                        $('#addUserForm')[0].reset();
                        loadUsers();
                        showAlert('User added successfully!', 'success');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        displayValidationErrors(xhr.responseJSON.errors, 'add');
                    } else {
                        showAlert('Error adding user!', 'danger');
                    }
                }
            });
        }

        function openEditModal(user) {
            $('#edit_user_id').val(user.id);
            $('#edit_name').val(user.name);
            $('#edit_email').val(user.email);
            $('#edit_password').val('');
            $('#editUserModal').modal('show');
        }

        function updateUser() {
            let userId = $('#edit_user_id').val();
            let formData = {
                name: $('#edit_name').val(),
                email: $('#edit_email').val(),
                password: $('#edit_password').val(),
                _method: 'PUT'
            };

            $.ajax({
                url: "{{ route('users.update', ['user' => ':id']) }}".replace(':id', userId),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#editUserModal').modal('hide');
                        loadUsers();
                        showAlert('User updated successfully!', 'success');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        displayValidationErrors(xhr.responseJSON.errors, 'edit');
                    } else {
                        showAlert('Error updating user!', 'danger');
                    }
                }
            });
        }

        function openDeleteModal(user) {
            $('#deleteUserName').text(user.name);
            $('#deleteUserModal').modal('show');
            $('#confirmDelete').data('user-id', user.id);
        }

        function deleteUser() {
            let userId = $('#confirmDelete').data('user-id');

            $.ajax({
                url: "{{ route('users.destroy', ['user' => ':id']) }}".replace(':id', userId),
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        $('#deleteUserModal').modal('hide');
                        loadUsers();
                        showAlert('User deleted successfully!', 'success');
                    }
                },
                error: function(xhr) {
                    showAlert('Error deleting user!', 'danger');
                }
            });
        }

        function displayValidationErrors(errors, formType) {
            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            // Display new errors
            Object.keys(errors).forEach(function(field) {
                let fieldId = formType === 'edit' ? `edit_${field}` : field;
                $(`#${fieldId}`).addClass('is-invalid');
                $(`#${fieldId}-error`).text(errors[field][0]);
            });
        }

        function showAlert(message, type) {
            let alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('.container').prepend(alertHtml);

            // Auto dismiss after 3 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 3000);
        }
    </script>
</body>
</html> 