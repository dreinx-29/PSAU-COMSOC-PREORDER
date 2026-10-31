
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMSOC | Add Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#F4F6F7; color:#18394B;">

<div class="container py-5" style="max-width:850px;">

    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}"
           class="text-decoration-none" style="color:#287C91;">
            &larr; Back to Products
        </a>

        <h2 class="fw-bold mt-3 mb-1">Add New Product</h2>
        <p class="text-secondary">Add COMSOC merchandise details and inventory.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">

            @if ($errors->any())
                <div class="alert alert-danger">
                    Please check the form for errors.
                </div>
            @endif

            <form method="POST"
                  action="{{ route('admin.products.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Product Name *</label>
                    <input type="text" id="name" name="name"
                           class="form-control" value="{{ old('name') }}"
                           placeholder="e.g. COMSOC Organization Shirt" required>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea id="description" name="description"
                              class="form-control" rows="4"
                              placeholder="Describe the product...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label fw-semibold">Price (₱) *</label>
                        <input type="number" id="price" name="price"
                               class="form-control" min="0.01" step="0.01"
                               value="{{ old('price') }}" required>
                        @error('price')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="stock" class="form-label fw-semibold">Stock Quantity *</label>
                        <input type="number" id="stock" name="stock"
                               class="form-control" min="0"
                               value="{{ old('stock', 0) }}" required>
                        @error('stock')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3 mb-3">
                    <label for="category" class="form-label fw-semibold">Category</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">Select a category</option>
                        <option value="Apparel" @selected(old('category') === 'Apparel')>Apparel</option>
                        <option value="Accessories" @selected(old('category') === 'Accessories')>Accessories</option>
                        <option value="School Supplies" @selected(old('category') === 'School Supplies')>School Supplies</option>
                        <option value="Other" @selected(old('category') === 'Other')>Other</option>
                    </select>
                    @error('category')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">Product Photo</label>
                    <input type="file" id="image" name="image"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">JPG, PNG, or WEBP. Maximum 2 MB.</div>
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <!-- Clothing Settings -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox"
                           role="switch" id="is_clothing"
                           name="is_clothing" value="1"
                           @checked(old('is_clothing'))>

                    <label class="form-check-label fw-semibold" for="is_clothing">
                        Clothing Product
                    </label>
                    <div class="form-text">
                        Enable this for shirts, hoodies, jackets, and other clothing.
                    </div>
                </div>

                <div id="clothingOptions" class="border rounded-3 p-3 mb-4"
                     style="display: {{ old('is_clothing') ? 'block' : 'none' }};">

                    <label class="form-label fw-semibold">Available Sizes</label>

                    <div class="d-flex flex-wrap gap-3 mb-3">
                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="available_sizes[]"
                                       value="{{ $size }}"
                                       id="size_{{ $size }}"
                                       @checked(in_array($size, old('available_sizes', [])))>
                                <label class="form-check-label" for="size_{{ $size }}">
                                    {{ $size }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    @error('available_sizes')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror
                    @error('available_sizes.*')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror

                    <div class="mb-2">
                        <label for="size_chart" class="form-label fw-semibold">
                            Upload Size Chart
                        </label>
                        <input type="file" id="size_chart" name="size_chart"
                               class="form-control"
                               accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">
                            Upload a size chart image. JPG, PNG, or WEBP. Maximum 2 MB.
                        </div>
                        @error('size_chart')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox"
                           role="switch" id="is_available"
                           name="is_available" value="1"
                           @checked(old('is_available', '1') === '1')>
                    <label class="form-check-label" for="is_available">
                        Product is available for pre-order
                    </label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.index') }}"
                       class="btn btn-outline-secondary">Cancel</a>

                    <button type="submit" class="btn text-white"
                            style="background:#287C91;">
                        Save Product
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    const clothingCheckbox = document.getElementById('is_clothing');
    const clothingOptions = document.getElementById('clothingOptions');

    clothingCheckbox.addEventListener('change', function () {
        clothingOptions.style.display = this.checked ? 'block' : 'none';
    });
</script>

</body>
</html>
