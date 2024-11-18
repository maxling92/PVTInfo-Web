<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Management</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage User Roles</h2>
        <a href="/" class="btn btn-secondary mb-3">Return to Home</a>

        @if ($users->isEmpty())
            <p>No users to display.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Perusahaan</th>
                        <th>Current Role</th>
                        <th>Change Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->nama_perusahaan }}</td>
                        <td>{{ ucfirst($user->role ?? 'No Role Assigned') }}</td>
                        <td>
                            <!-- Change Role Button -->
                            <button class="btn btn-primary change-role-btn" data-user-id="{{ $user->id }}" data-user-role="{{ $user->role }}" data-bs-toggle="modal" data-bs-target="#roleModal">
                                Change Role
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Change User Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changeRoleForm" method="POST" action="">
                        @csrf
                        <input type="hidden" name="userId" id="userId">
                        <div class="form-group">
                            <label for="role">Select Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="normal_user">Normal User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the external JavaScript file -->
    <script src="{{ asset('js/roleManager.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
