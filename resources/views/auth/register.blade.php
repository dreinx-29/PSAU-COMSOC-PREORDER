
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | PSAU COMSOC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar bg-white border-bottom">
    <div class="container py-2">
        <a href="{{ route('home') }}" class="navbar-brand fw-bold" style="color:#173B50">
            PSAU <span style="color:#21849A">COMSOC</span>
        </a>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            ← Back to Login
        </a>
    </div>
</nav>

<main class="container flex-grow-1 d-flex align-items-center justify-content-center py-5">
    <div class="card border-0 shadow-sm rounded-4 w-100" style="max-width:460px">
        <div class="card-body p-4 p-md-5 text-center">

            <img src="{{ asset('images/comsoc-logo.png') }}" alt="COMSOC Logo"
                 style="width:70px;height:70px;object-fit:contain">

            <p class="small fw-bold mt-3 mb-2" style="color:#21849A;letter-spacing:2px">
                PSAU COMPUTER SOCIETY
            </p>
            <h1 class="h3 fw-bold" style="color:#173B50">Create an Account</h1>
            <p class="text-secondary small">Join COMSOC and pre-order merchandise.</p>

            <hr class="my-4">

            @if ($errors->any())
                <div class="alert alert-danger small text-start">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" id="registerForm" class="text-start">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold">Full Name</label>
                    <input id="name" name="name" class="form-control rounded-3 py-2"
                           placeholder="Enter your full name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold">Email Address</label>
                    <input id="email" type="email" name="email"
                           class="form-control rounded-3 py-2" placeholder="you@example.com"
                           value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold">Password</label>
                    <input id="password" type="password" name="password"
                           class="form-control rounded-3 py-2" placeholder="At least 8 characters"
                           minlength="8" required>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label small fw-semibold">
                        Confirm Password
                    </label>
                    <input id="password_confirmation" type="password"
                           name="password_confirmation" class="form-control rounded-3 py-2"
                           placeholder="Re-enter password" minlength="8" required>
                </div>

                <button id="registerButton" class="btn text-white w-100 rounded-3 py-2 fw-semibold"
                        style="background:#173B50">
                    Create Account →
                </button>
            </form>

            <p class="text-secondary small mt-4 mb-0">
                Already have an account?
                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold"
                   style="color:#21849A">Sign in</a>
            </p>
        </div>
    </div>
</main>

<footer class="bg-white border-top text-center py-3">
    <p class="fw-semibold small mb-1" style="color:#173B50">PSAU Computer Society</p>
    <p class="text-secondary small mb-0">Merchandise Pre-Order System © {{ date('Y') }}</p>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    $('.card').hide().fadeIn(300);

    $('#password, #password_confirmation').each(function () {
        const field = $(this);
        const button = $('<button type="button" class="btn btn-outline-secondary">Show</button>');
        const wrapper = $('<div class="input-group"></div>');

        field.wrap(wrapper);
        field.after(button);

        button.on('click', function () {
            const show = field.attr('type') === 'password';
            field.attr('type', show ? 'text' : 'password');
            button.text(show ? 'Hide' : 'Show');
        });
    });

    $('#registerForm').on('submit', function () {
        $('#registerButton').prop('disabled', true).text('Creating Account...');
    });
});
</script>

</body>
</html>
