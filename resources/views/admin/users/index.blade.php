
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management | PSAU COMSOC</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #F3F6F7;
            color: #263640;
        }

        .page-header {
            background: #18394B;
            color: white;
            border-radius: 14px;
        }

        .content-card {
            background: white;
            border-radius: 14px;
            border: 1px solid #e5eaed;
        }

        .section-title {
            color: #18394B;
            font-weight: 700;
        }

        .btn-primary {
            background: #287C91;
            border-color: #287C91;
        }

        .btn-primary:hover {
            background: #216779;
            border-color: #216779;
        }

        .table th {
            color: #858D93;
            font-size: 13px;
            text-transform: uppercase;
        }

        .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>

<div class="container py-4">

    <div class="page-header p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">User Management</h3>
            <p class="mb-0 opacity-75">Manage administrator and customer accounts.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-light rounded-pill px-4">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ADMIN ACCOUNTS --}}
    <div class="content-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="section-title mb-1">Admin Accounts</h5>
                <small class="text-secondary">Accounts with administrator access.</small>
            </div>

            <span class="badge rounded-pill text-bg-primary">
                {{ $admins->count() }} Admins
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Account ID</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($admins as $admin)
                        <tr>
                            <td class="fw-semibold">{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>#{{ $admin->id }}</td>
                            <td class="text-end">
                                @if($admin->id !== auth()->id())
                                    <form action="{{ route('admin.users.demote', $admin->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Remove admin privileges from this account?')">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                                            Remove as Admin
                                        </button>
                                    </form>
                                @else
                                    <span class="badge text-bg-success">Currently Logged In</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary py-4">
                                No admin accounts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- REGULAR USER ACCOUNTS --}}
    <div class="content-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="section-title mb-1">Regular User Accounts</h5>
                <small class="text-secondary">Manage registered customer accounts.</small>
            </div>

            <span class="badge rounded-pill text-bg-secondary">
                {{ $users->count() }} Users
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Account ID</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2 flex-wrap">

                                    <form action="{{ route('admin.users.promote', $user->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Promote this user to admin?')">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                                            Add as Admin
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Permanently delete this account and its pre-orders? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                            Delete Account
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary py-4">
                                No regular user accounts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
