@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>User Management</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-user-btn">
                        <i class="fas fa-plus"></i> Add User
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="users-table" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phone</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIP</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will fill this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="user-form">
                <div class="modal-body">
                    <input type="hidden" id="user-id">
                    <div class="alert alert-danger" id="form-errors" style="display: none;"></div>
                    
                    <div class="form-group">
                        <label for="nama" class="form-control-label">Name</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-control-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-control-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted" id="password-help">Leave blank to keep current password when editing.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="no_hp" class="form-control-label">Phone Number</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nip" class="form-control-label">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip">
                    </div>
                    
                    <div class="form-group">
                        <label for="role" class="form-control-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="save-user-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Add Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with debug mode
        var table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('users.data') }}",
                error: function(xhr, error, thrown) {
                    console.log('DataTables error: ', error);
                    console.log('Server response: ', xhr.responseText);
                    toastr.error('Error loading data. Please check the console for details.');
                }
            },
            columns: [
            { 
                data: null, // Kolom ini tidak terikat pada data spesifik dari server
                name: 'no', // Nama kolom untuk server-side processing (opsional)
                render: function(data, type, row, meta) {
                    // Menghasilkan nomor urut berdasarkan indeks baris dan pagination
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false // Nonaktifkan pengurutan untuk kolom nomor urut
            },
            { data: 'nama', name: 'nama' },
            { data: 'email', name: 'email' },
            { data: 'no_hp', name: 'no_hp' },
            { data: 'nip', name: 'nip' },
            { data: 'role', name: 'role' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
        });

        // Add User Button Click
        $('#add-user-btn').click(function() {
            resetForm();
            $('#userModalLabel').text('Add New User');
            $('#password').attr('required', true);
            $('#password-help').hide();
            var userModal = new bootstrap.Modal(document.getElementById('user-modal'));
            userModal.show();
        });

        // Edit User Button Click
        $(document).on('click', '.edit-user-btn', function() {
            var userId = $(this).data('id');
            resetForm();
            $('#userModalLabel').text('Edit User');
            $('#password').removeAttr('required');
            $('#password-help').show();
            
            // Fetch user data
            $.ajax({
                url: '/users/' + userId,
                type: 'GET',
                success: function(response) {
                    $('#user-id').val(response.id);
                    $('#nama').val(response.nama);
                    $('#email').val(response.email);
                    $('#no_hp').val(response.no_hp);
                    $('#nip').val(response.nip);
                    if(response.roles && response.roles.length > 0) {
                        $('#role').val(response.roles[0].name);
                    }
                    var userModal = new bootstrap.Modal(document.getElementById('user-modal'));
                    userModal.show();
                },
                error: function(xhr) {
                    console.log('Error response:', xhr.responseText);
                    toastr.error('Error fetching user data');
                }
            });
        });

        // Delete User Button Click
        $(document).on('click', '.delete-user-btn', function() {
            var userId = $(this).data('id');
            $('#confirm-delete-btn').data('id', userId);
            var deleteModal = new bootstrap.Modal(document.getElementById('delete-modal'));
            deleteModal.show();
        });

        // Confirm Delete Button Click
        $('#confirm-delete-btn').click(function() {
            var userId = $(this).data('id');
            
            $.ajax({
                url: '/users/' + userId,
                type: 'DELETE',
                success: function(response) {
                    var deleteModal = bootstrap.Modal.getInstance(document.getElementById('delete-modal'));
                    deleteModal.hide();
                    table.ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    console.log('Error response:', xhr.responseText);
                    toastr.error('Error deleting user');
                }
            });
        });

        // Form Submit
        $('#user-form').submit(function(e) {
            e.preventDefault();
            
            var userId = $('#user-id').val();
            var formData = $(this).serialize();
            var url = userId ? '/users/' + userId : '/users';
            var method = userId ? 'PUT' : 'POST';
            
            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function(response) {
                    var userModal = bootstrap.Modal.getInstance(document.getElementById('user-modal'));
                    userModal.hide();
                    table.ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    console.log('Error response:', xhr.responseText);
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorHtml = '<ul>';
                        $.each(errors, function(key, value) {
                            errorHtml += '<li>' + value + '</li>';
                        });
                        errorHtml += '</ul>';
                        $('#form-errors').html(errorHtml).show();
                    } else {
                        toastr.error('Error processing request');
                    }
                }
            });
        });

        // Reset Form
        function resetForm() {
            $('#user-form')[0].reset();
            $('#user-id').val('');
            $('#form-errors').hide();
        }
    });
</script>
@endsection

