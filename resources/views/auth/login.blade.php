
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | PSAU COMSOC</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Navigation -->
    <nav class="navbar bg-white border-bottom">
        <div class="container py-2">
            <a href="{{ route('home') }}"
               class="navbar-brand fw-bold mb-0"
               style="color: #173B50;">
                PSAU <span style="color: #21849A;">COMSOC</span>
            </a>

            <a href="{{ route('home') }}"
               class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                ← Back to Home
            </a>
        </div>
    </nav>

    <!-- Login Section -->
    <main class="container flex-grow-1 d-flex align-items-center justify-content-center py-5">

        <div class="card border-0 shadow-sm rounded-4 w-100"
             style="max-width: 420px;">

            <div class="card-body p-4 p-md-5">

                <!-- Logo and Heading -->
                <div class="text-center mb-4">

                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <img
                            src="{{ asset('images/comsoc-logo.png') }}"
                            alt="PSAU COMSOC Logo"
                            style="width: 70px; height: 70px; object-fit: contain;"
                        >
                    </div>

                    <p class="small fw-bold mb-2"
                       style="color: #21849A; letter-spacing: 2px;">
                        PSAU COMPUTER SOCIETY
                    </p>

                    <h1 class="h3 fw-bold mb-2" style="color: #173B50;">
                        Welcome Back
                    </h1>

                    <p class="text-secondary small mb-0">
                        Sign in to access your COMSOC merchandise pre-orders.
                    </p>

                </div>

                <hr class="mb-4">

                <!-- Login Form -->
                <form
                    id="loginForm"
                    method="POST"
                    action="{{ route('login.submit') }}"
                    autocomplete="off"
                >
                    @csrf

                    <h2 class="h6 fw-bold mb-1" style="color: #173B50;">
                        Sign in to your account
                    </h2>

                    <p class="text-secondary small mb-4">
                        Enter your account details below.
                    </p>

                    <!-- Registration Success Message -->
                    @if (session('status'))
                        <div class="alert alert-success small rounded-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Login Error -->
                    @if ($errors->any())
                        <div class="alert alert-danger small rounded-3" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control rounded-3 py-2 @error('email') is-invalid @enderror"
                            placeholder="you@example.com"
                            value=""
                            autocomplete="off"
                            required
                            autofocus
                        >

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-semibold">
                            Password
                        </label>

                        <div class="input-group">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control rounded-start-3 py-2"
                                placeholder="Enter your password"
                                autocomplete="off"
                                required
                            >

                            <button
                                type="button"
                                id="togglePassword"
                                class="btn btn-outline-secondary rounded-end-3"
                                aria-controls="password"
                                aria-pressed="false"
                            >
                                Show
                            </button>
                        </div>

                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me and Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 small mb-4">

                        <div class="form-check">
                            <input
                                type="checkbox"
                                id="rememberMe"
                                name="remember"
                                value="1"
                                class="form-check-input"
                            >

                            <label for="rememberMe" class="form-check-label">
                                Remember me
                            </label>
                        </div>

                        <a
                            href="#"
                            id="forgotPassword"
                            class="text-decoration-none fw-semibold"
                            style="color: #21849A;"
                        >
                            Forgot password?
                        </a>

                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        id="loginButton"
                        class="btn text-white w-100 rounded-3 py-2 fw-semibold"
                        style="background: #173B50;"
                    >
                        Sign In →
                    </button>

                    <p
                        id="message"
                        class="text-center text-secondary small mt-3 mb-0"
                        aria-live="polite"
                    ></p>

                    <!-- Register Link -->
                    <div class="text-center mt-4">
                        <span class="text-secondary small">
                            Don't have an account?
                        </span>

                        <a
                            href="{{ route('register') }}"
                            class="text-decoration-none fw-semibold small"
                            style="color: #21849A;"
                        >
                            Create an account
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-top text-center py-3">
        <p class="fw-semibold small mb-1" style="color: #173B50;">
            PSAU Computer Society
        </p>

        <p class="text-secondary small mb-0">
            Merchandise Pre-Order System © {{ date('Y') }}
        </p>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(function () {

            // Fade in the login card
            $('.card').hide().fadeIn(300);

            // Clear login fields when the page loads
            function clearLoginFields() {
                $('#email').val('');
                $('#password').val('');
                $('#rememberMe').prop('checked', false);
            }

            clearLoginFields();

            // Clear fields when the browser restores the page
            $(window).on('pageshow', function () {
                clearLoginFields();

                $('#loginButton')
                    .prop('disabled', false)
                    .text('Sign In →');

                $('#togglePassword')
                    .text('Show')
                    .attr('aria-pressed', 'false');

                $('#password').attr('type', 'password');
            });

            // Show or hide password
            $('#togglePassword').on('click', function () {
                const field = $('#password');
                const show = field.attr('type') === 'password';

                field.attr('type', show ? 'text' : 'password');

                $(this)
                    .text(show ? 'Hide' : 'Show')
                    .attr('aria-pressed', show ? 'true' : 'false');
            });

            // Submit the real Laravel login form
            $('#loginForm').on('submit', function () {
                $('#loginButton')
                    .prop('disabled', true)
                    .text('Signing in...');

                $('#message').text('');
            });

            // Forgot password placeholder
            $('#forgotPassword').on('click', function (e) {
                e.preventDefault();

                $('#message').text(
                    'Password recovery is not available yet. Please contact the COMSOC administrator.'
                );
            });

        });
    </script>

</body>
</html>
