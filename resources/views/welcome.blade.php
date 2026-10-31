
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home | PSAU COMSOC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light" style="color:#173B50;">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <span style="color:#173B50;">PSAU</span>
            <span style="color:#21849A;">COMSOC</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                <a class="nav-link active" href="{{ route('home') }}">Home</a>
                <a class="nav-link" href="{{ route('shop.index') }}">Products</a>
                <a class="nav-link" href="{{ url('/preorders') }}">My Pre-orders</a>
                <a class="nav-link" href="{{ url('/account') }}">Account</a>

                @guest
                    <a class="btn btn-sm text-white px-3"
                       style="background:#173B50;"
                       href="{{ route('login') }}">Login</a>
                @else
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="py-5" style="background:linear-gradient(110deg,#fff,#e7f1f3);">
    <div class="container py-lg-4">
        <div class="row align-items-center justify-content-center g-5">

            <div class="col-lg-6">
                <span class="badge rounded-pill border bg-white mb-3 p-2"
                      style="color:#173B50; user-select:none;">
                    PSAU COMPUTER SOCIETY
                </span>

                <h1 class="display-5 fw-bold">
                    Wear Your Community.
                    <span style="color:#21849A;">Represent COMSOC.</span>
                </h1>

                <p class="text-secondary my-3">
                    Discover official COMSOC merchandise and place your pre-orders
                    in one convenient place. Made for students who love technology and community.
                </p>

                <a href="{{ route('shop.index') }}"
                   class="btn text-white me-2"
                   style="background:#173B50;">
                    Explore Merchandise
                </a>

                <a href="{{ url('/preorders') }}" class="btn btn-outline-secondary">
                    My Pre-orders
                </a>

                <p class="small text-secondary mt-3">
                    <span class="text-success">●</span>
                    Official merchandise for the COMSOC community
                </p>
            </div>

            <!-- COLLECTION CARD -->
            <div class="col-lg-4 col-md-7">
                <div class="card border rounded-4 shadow-sm p-3">
                    <p class="small text-secondary mb-3">
                        COMSOC COLLECTION
                        <span class="badge float-end"
                              style="background:#e5f0f2;color:#173B50;">
                            2026
                        </span>
                    </p>

                    <div class="rounded-4 text-center text-white p-4"
                         style="background:linear-gradient(135deg,#173B50,#21849A);">

                        <div class="rounded-circle border border-light d-flex
                                    align-items-center justify-content-center
                                    mx-auto mb-3 overflow-hidden"
                             style="width:90px;height:90px;background:#fff;">
                            <img src="{{ asset('images/comsoc-logo.png') }}"
                                 alt="PSAU COMSOC Logo"
                                 class="img-fluid"
                                 style="width:100%;height:100%;object-fit:contain;padding:.5px;">
                        </div>

                        <h2 class="h4 fw-bold mb-1" style="letter-spacing:3px;">
                            COMSOC
                        </h2>

                        <p class="small fw-semibold mb-4" style="letter-spacing:2px;">
                            COMPUTER SOCIETY
                        </p>

                        <span class="badge rounded-pill border border-light p-2">
                            OFFICIAL COLLECTION
                        </span>
                    </div>

                    <p class="small text-secondary mt-3 mb-1">YOUR COMMUNITY</p>
                    <strong>YOUR IDENTITY.</strong>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <p class="small fw-bold mb-1" style="color:#21849A;">
                SHOP WITH EASE
            </p>

            <h2 class="h3 fw-bold">How It Works</h2>

            <p class="small text-secondary mb-4">
                Your COMSOC merchandise journey in three simple steps.
            </p>

            <div class="row g-3">
                @foreach ([
                    ['01', 'Browse Products', 'Explore available merchandise and choose the items you want.'],
                    ['02', 'Place a Pre-order', 'Select your preferred size and quantity, then submit your order.'],
                    ['03', 'Track Your Order', 'Check your order information and status on your pre-orders page.']
                ] as $step)
                    <div class="col-md-4">
                        <div class="card border rounded-4 p-4 h-100">
                            <span class="badge align-self-start mb-3 p-2"
                                  style="background:#e5f0f2;color:#173B50;">
                                {{ $step[0] }}
                            </span>

                            <h3 class="h5 fw-bold">{{ $step[1] }}</h3>

                            <p class="small text-secondary mb-0">
                                {{ $step[2] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- BOTTOM BANNER -->
            <div class="rounded-4 p-4 p-md-5 mt-4 text-white"
                 style="background:linear-gradient(110deg,#173B50,#21849A);">
                <div class="row align-items-center g-3">

                    <div class="col-md-8">
                        <p class="small fw-bold mb-2">
                            BE PART OF THE COMMUNITY
                        </p>

                        <h2 class="h3 fw-bold">
                            Ready to represent COMSOC?
                        </h2>

                        <p class="small mb-0">
                            Find your next favorite piece in our merchandise collection.
                        </p>
                    </div>

                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('shop.index') }}" class="btn btn-light">
                            Browse Collection →
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-white border-top text-center py-3">
    <p class="small text-secondary mb-0">
        PSAU Computer Society · Merchandise Pre-Order System © {{ date('Y') }}
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
