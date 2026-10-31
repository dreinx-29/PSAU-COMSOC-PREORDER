
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMSOC | Shop Merchandise</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #18394B;
            --bs-primary-rgb: 24, 57, 75;
            --comsoc-teal: #287C91;
        }

        .product-image, .product-placeholder {
            height: 220px;
            object-fit: cover;
        }

        .navbar-comsoc {
            background: var(--bs-primary);
        }

        .btn-comsoc {
            background: var(--comsoc-teal);
            color: white;
        }

        .btn-comsoc:hover {
            background: var(--bs-primary);
            color: white;
        }

        .size-chart-image {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
        }
    </style>
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark navbar-comsoc">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">PSAU COMSOC</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#shopNavbar" aria-controls="shopNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="shopNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('shop.index') }}">Products</a>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-light text-dark ms-lg-2"
                           href="{{ route('register') }}">Create Account</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('preorders.index') }}">
                            My Pre-orders
                        </a>
                    </li>

                    <li class="nav-item">
                        <span class="nav-link">
                            Hi, {{ auth()->user()->name }}
                        </span>
                    </li>

                    <li class="nav-item ms-lg-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-light btn-sm" type="submit">
                                Logout
                            </button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="container py-5">

    <div class="bg-white rounded-3 shadow-sm p-4 p-md-5 mb-4">
        <span class="badge text-bg-primary mb-2">Official Merchandise</span>
        <h1 class="fw-bold">COMSOC Merchandise</h1>
        <p class="text-secondary mb-0">
            Discover merchandise from the PSAU Computer Society and choose your items for pre-order.
        </p>
    </div>

    <!-- Search and Category Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label for="productSearch" class="form-label fw-semibold">
                        Search Products
                    </label>
                    <input type="search" id="productSearch" class="form-control"
                           placeholder="Search by product name...">
                </div>

                <div class="col-md-4">
                    <label for="categoryFilter" class="form-label fw-semibold">
                        Category
                    </label>
                    <select id="categoryFilter" class="form-select">
                        <option value="">All Categories</option>

                        @foreach ($products->pluck('category')->filter()->unique()->sort() as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Products -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Available Products</h4>

        <span class="text-secondary small">
            <span id="visibleProductCount">{{ $products->count() }}</span> items
        </span>
    </div>

    <div class="row g-4" id="productGrid">

        @forelse ($products as $product)

            <div class="col-sm-6 col-lg-4 col-xl-3 product-item"
                 data-name="{{ strtolower($product->name) }}"
                 data-category="{{ $product->category ?? '' }}">

                <div class="card h-100 border-0 shadow-sm">

                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="card-img-top product-image"
                             alt="{{ $product->name }}">
                    @else
                        <div class="product-placeholder bg-body-secondary d-flex align-items-center justify-content-center">
                            <span class="text-secondary">No image available</span>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">

                        <span class="badge text-bg-light border align-self-start mb-2">
                            {{ $product->category ?? 'Merchandise' }}
                        </span>

                        <h5 class="card-title fw-bold">{{ $product->name }}</h5>

                        <p class="card-text text-secondary small">
                            {{ $product->description ?: 'Official COMSOC merchandise.' }}
                        </p>

                        <h5 class="fw-bold text-primary">
                            ₱{{ number_format((float) $product->price, 2) }}
                        </h5>

                        <p class="small mb-3">
                            @if ($product->stock > 0)
                                <span class="badge text-bg-success">In Stock</span>
                                {{ $product->stock }} available
                            @else
                                <span class="badge text-bg-secondary">Out of Stock</span>
                            @endif
                        </p>

                        <button type="button"
                                class="btn btn-comsoc w-100 mt-auto order-btn"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-product-price="{{ $product->price }}"
                                data-product-stock="{{ $product->stock }}"
                                data-is-clothing="{{ $product->is_clothing ? '1' : '0' }}"
                                data-available-sizes="{{ json_encode($product->available_sizes ?? []) }}"
                                data-size-chart="{{ $product->size_chart ? asset('storage/' . $product->size_chart) : '' }}"
                                {{ $product->stock < 1 ? 'disabled' : '' }}>
                            Select Product
                        </button>

                    </div>
                </div>
            </div>

        @empty

            <div class="col-12" id="emptyProducts">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <h5 class="fw-bold">No products available yet</h5>
                        <p class="text-secondary mb-0">Please check back later.</p>
                    </div>
                </div>
            </div>

        @endforelse

        <div class="col-12 d-none" id="noSearchResults">
            <div class="alert alert-light border text-center py-4">
                <h5 class="fw-bold">No matching products</h5>
                <p class="text-secondary mb-0">
                    Try another product name or category.
                </p>
            </div>
        </div>

    </div>
</main>

<!-- Product Selection Modal -->
<div class="modal fade" id="orderModal" tabindex="-1"
     aria-labelledby="orderModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="orderModalLabel">
                    Product Details
                </h5>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <h5 id="modalProductName" class="fw-bold"></h5>

                <p class="text-primary fw-bold mb-3" id="modalProductPrice"></p>

                <!-- Clothing Size Selection -->
                <div id="clothingSizeSection" class="mb-3 d-none">

                    <label for="selectedSize" class="form-label fw-semibold">
                        Select Clothing Size <span class="text-danger">*</span>
                    </label>

                    <select id="selectedSize" class="form-select">
                        <option value="">Choose your size</option>
                    </select>

                    <div class="form-text">
                        Please select your preferred clothing size.
                    </div>

                    <!-- Size Chart -->
                    <div id="sizeChartContainer" class="mt-3 d-none">

                        <p class="fw-semibold small mb-2">Size Chart</p>

                        <a href="#" id="sizeChartLink"
                           target="_blank" rel="noopener"
                           class="btn btn-outline-secondary btn-sm mb-2">
                            Open Full Size Chart
                        </a>

                        <img src="" id="sizeChartImage"
                             class="img-fluid rounded border size-chart-image"
                             alt="Clothing size chart">

                    </div>

                    <div id="noSizeChart" class="small text-muted mt-2 d-none">
                        No size chart available for this product.
                    </div>

                </div>

                <!-- Quantity -->
                <div class="mb-3">
                    <label for="orderQuantity" class="form-label">
                        Quantity
                    </label>

                    <input type="number" id="orderQuantity"
                           class="form-control" min="1" value="1">

                    <div class="form-text" id="stockHelp"></div>
                </div>

                <div class="d-flex justify-content-between border-top pt-3">
                    <span class="fw-semibold">Estimated Total</span>
                    <span class="fw-bold" id="orderTotal">₱0.00</span>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-comsoc">
                        Login to Continue
                    </a>
                @else
                    <button type="button" id="continueOrder" class="btn btn-comsoc">
                        Continue
                    </button>
                @endguest

            </div>
        </div>
    </div>
</div>

<!-- Customer Details Modal -->
<div class="modal fade" id="customerModal" tabindex="-1"
     aria-labelledby="customerModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('preorders.store') }}">
                @csrf

                <input type="hidden" name="product_id" id="orderProductId">
                <input type="hidden" name="quantity" id="orderProductQuantity">
                <input type="hidden" name="size" id="orderProductSize">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="customerModalLabel">
                        Complete Your Pre-order
                    </h5>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <label for="customerName" class="form-label">Full Name</label>
                    <input type="text" id="customerName" name="customer_name"
                           class="form-control mb-3" maxlength="255" required>

                    <label for="yearLevel" class="form-label">Year Level</label>
                    <select id="yearLevel" name="year_level"
                            class="form-select mb-3" required>
                        <option value="">Select year level</option>
                        <option>1st Year</option>
                        <option>2nd Year</option>
                        <option>3rd Year</option>
                        <option>4th Year</option>
                        <option>5th Year</option>
                    </select>

                    <label for="section" class="form-label">Section</label>
                    <input type="text" id="section" name="section"
                           class="form-control" maxlength="100" required>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-comsoc">
                        Place Order
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(function () {

    let selectedProduct = null;

    const orderModalElement = document.getElementById('orderModal');
    const customerModalElement = document.getElementById('customerModal');

    const orderModal = bootstrap.Modal.getOrCreateInstance(orderModalElement);
    const customerModal = bootstrap.Modal.getOrCreateInstance(customerModalElement);

    // Search and category filter
    function filterProducts() {

        const search = $('#productSearch').val().toLowerCase().trim();
        const category = $('#categoryFilter').val();

        let visibleCount = 0;

        $('.product-item').each(function () {

            const name = String($(this).data('name') || '');
            const itemCategory = String($(this).data('category') || '');

            const show = name.includes(search) &&
                (!category || itemCategory === category);

            $(this).toggle(show);

            if (show) visibleCount++;
        });

        $('#visibleProductCount').text(visibleCount);

        $('#noSearchResults').toggleClass('d-none', visibleCount > 0);

        $('#emptyProducts').toggleClass(
            'd-none',
            search.length > 0 || category.length > 0
        );
    }

    $('#productSearch, #categoryFilter').on('input change', filterProducts);

    // Open Product Details Modal
    $('.order-btn').on('click', function () {

        selectedProduct = {
            id: $(this).attr('data-product-id'),
            name: $(this).attr('data-product-name'),
            price: Number($(this).attr('data-product-price')),
            stock: Number($(this).attr('data-product-stock')),
            isClothing: $(this).attr('data-is-clothing') === '1',
            sizes: JSON.parse($(this).attr('data-available-sizes') || '[]'),
            sizeChart: $(this).attr('data-size-chart') || ''
        };

        $('#modalProductName').text(selectedProduct.name);

        $('#modalProductPrice').text(
            '₱' + selectedProduct.price.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
        );

        $('#orderQuantity').val(1).attr('max', selectedProduct.stock);

        $('#stockHelp').text(
            'Maximum available quantity: ' + selectedProduct.stock
        );

        // Reset previous product's size information
        $('#selectedSize').empty().append(
            $('<option>', {
                value: '',
                text: 'Choose your size'
            })
        );

        $('#sizeChartImage').attr('src', '');
        $('#sizeChartLink').attr('href', '#');

        $('#sizeChartContainer').addClass('d-none');
        $('#noSizeChart').addClass('d-none');

        // Show size options only for clothing products
        if (selectedProduct.isClothing) {

            $('#clothingSizeSection').removeClass('d-none');

            selectedProduct.sizes.forEach(function (size) {

                $('#selectedSize').append(
                    $('<option>', {
                        value: size,
                        text: size
                    })
                );

            });

            // Display uploaded size chart
            if (selectedProduct.sizeChart) {

                $('#sizeChartContainer').removeClass('d-none');

                $('#sizeChartLink').attr(
                    'href',
                    selectedProduct.sizeChart
                );

                $('#sizeChartImage').attr(
                    'src',
                    selectedProduct.sizeChart
                );

            } else {

                $('#noSizeChart').removeClass('d-none');

            }

        } else {

            $('#clothingSizeSection').addClass('d-none');

        }

        updateOrderTotal();
        orderModal.show();
    });

    // Calculate estimated total
    function updateOrderTotal() {

        if (!selectedProduct) return;

        let quantity = parseInt($('#orderQuantity').val(), 10) || 1;

        quantity = Math.max(
            1,
            Math.min(quantity, selectedProduct.stock)
        );

        $('#orderQuantity').val(quantity);

        $('#orderTotal').text(
            '₱' + (selectedProduct.price * quantity).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
        );
    }

    $('#orderQuantity').on('input change', updateOrderTotal);

    // Continue to Customer Details Modal
    $('#continueOrder').on('click', function () {

        if (!selectedProduct) return;

        const quantity = parseInt($('#orderQuantity').val(), 10) || 1;

        if (quantity < 1 || quantity > selectedProduct.stock) {
            alert('Please enter a valid quantity.');
            return;
        }

        let chosenSize = '';

        // Require size for clothing products
        if (selectedProduct.isClothing) {

            chosenSize = $('#selectedSize').val();

            if (!chosenSize) {
                alert('Please select a clothing size before continuing.');
                $('#selectedSize').trigger('focus');
                return;
            }

            if (!selectedProduct.sizes.includes(chosenSize)) {
                alert('Please select a valid available size.');
                return;
            }
        }

        // Pass order details to the customer form
        $('#orderProductId').val(selectedProduct.id);
        $('#orderProductQuantity').val(quantity);
        $('#orderProductSize').val(chosenSize);

        orderModalElement.addEventListener(
            'hidden.bs.modal',
            function openCustomerModal() {

                customerModal.show();

                orderModalElement.removeEventListener(
                    'hidden.bs.modal',
                    openCustomerModal
                );
            }
        );

        orderModal.hide();
    });

});
</script>

</body>
</html>
