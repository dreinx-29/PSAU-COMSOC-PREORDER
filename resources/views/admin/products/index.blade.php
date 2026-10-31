
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMSOC | Products</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        :root {
            --navy: #18394B;
            --teal: #287C91;
            --green: #397C58;
            --gray: #858D93;
            --white: #FFFFFF;
        }

        body {
            background: #F4F6F7;
            color: var(--navy);
            font-family: Arial, sans-serif;
        }

        .page-header,
        .product-card {
            background: var(--white);
            border: none;
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(24, 57, 75, 0.06);
        }

        .product-photo {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 8px;
        }

        .btn-teal {
            background: var(--teal);
            color: var(--white);
            border: none;
        }

        .btn-teal:hover {
            background: var(--navy);
            color: var(--white);
        }

        .table thead th {
            color: var(--gray);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .table tbody td {
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .status-available {
            background: #E5F2E9;
            color: var(--green);
        }

        .status-unavailable {
            background: #F1F1F1;
            color: #666666;
        }

        .btn-action {
            white-space: nowrap;
        }

        @media (max-width: 576px) {
            .page-header {
                align-items: flex-start !important;
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
</head>

<body>

    <div class="container py-5">

        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center p-4 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Product Management</h2>
                <p class="text-secondary mb-0">
                    Manage COMSOC merchandise and inventory.
                </p>
            </div>

            <a href="{{ route('admin.products.create') }}"
               class="btn btn-teal px-4 py-2 rounded-pill">
                + Add Product
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show"
                 role="alert">
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>
        @endif

        <!-- Validation / Error Message -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">
                <strong>Please check the following:</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>
        @endif

        <!-- Products Table -->
        <div class="product-card">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">All Products</h5>

                    <span class="text-secondary small">
                        Total: {{ $products->count() }}
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Availability</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <!-- Product Photo -->
                                    <td>
                                        @if ($product->image)
                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="product-photo">
                                        @else
                                            <div class="product-photo d-flex align-items-center justify-content-center"
                                                 style="background:#E9EEF0; color:#858D93;">
                                                <span class="small">N/A</span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Product Name -->
                                    <td class="fw-semibold">
                                        {{ $product->name }}
                                    </td>

                                    <!-- Category -->
                                    <td>
                                        {{ $product->category ?? '—' }}
                                    </td>

                                    <!-- Price -->
                                    <td class="fw-semibold">
                                        ₱{{ number_format((float) $product->price, 2) }}
                                    </td>

                                    <!-- Stock -->
                                    <td>
                                        {{ $product->stock }}
                                    </td>

                                    <!-- Availability -->
                                    <td>
                                        @if ($product->is_available)
                                            <span class="badge rounded-pill status-available px-3 py-2">
                                                Available
                                            </span>
                                        @else
                                            <span class="badge rounded-pill status-unavailable px-3 py-2">
                                                Unavailable
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Edit and Delete Actions -->
                                    <td>
                                        <div class="d-flex gap-2">

                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="btn btn-sm btn-outline-secondary rounded-pill px-3 btn-action">
                                                Edit
                                            </a>

                                            <!-- Delete Button -->
                                            <form
                                                action="{{ route('admin.products.destroy', $product) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this product?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger rounded-pill px-3 btn-action">
                                                    Delete
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="fw-semibold mb-1">
                                            No products yet
                                        </div>

                                        <p class="text-secondary small mb-3">
                                            Start by adding your first COMSOC merchandise item.
                                        </p>

                                        <a href="{{ route('admin.products.create') }}"
                                           class="btn btn-teal rounded-pill px-4">
                                            + Add First Product
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Back to Dashboard -->
                <div class="border-top mt-4 pt-3">
                    <a href="{{ route('admin.dashboard') }}"
                       class="btn btn-outline-secondary rounded-pill px-3">
                        &larr; Back to Dashboard
                    </a>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
