
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Account | PSAU COMSOC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light" style="color:#173B50">

<nav class="navbar bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            PSAU <span style="color:#21849A">COMSOC</span>
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
            Back to Home
        </a>
    </div>
</nav>

<main class="container py-5" style="max-width:750px">
    <h2 class="fw-bold mb-1">My Account</h2>
    <p class="text-secondary mb-4">Manage your profile information.</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            Please check the information you entered.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-white"
                     style="width:80px;height:80px;background:#173B50;font-size:28px">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-secondary small">{{ $user->email }}</p>
                <span class="badge rounded-pill"
                      style="background:#e5f0f2;color:#173B50">
                    {{ $user->is_admin ? 'Administrator' : 'COMSOC Member' }}
                </span>
            </div>

            <hr class="mb-4">

            <form method="POST" action="{{ route('account.update') }}">
                @csrf
                @method('PUT')

                <h5 class="fw-bold mb-3">Profile Information</h5>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <h5 class="fw-bold mb-3">Change Password</h5>
                <p class="text-secondary small">
                    Leave these fields blank if you don't want to change your password.
                </p>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control"
                           autocomplete="new-password">
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" autocomplete="new-password">
                </div>

                <button type="submit" class="btn text-white px-4"
                        style="background:#173B50">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</main>

<footer class="text-center py-4 text-secondary small">
    PSAU Computer Society · © {{ date('Y') }}
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
