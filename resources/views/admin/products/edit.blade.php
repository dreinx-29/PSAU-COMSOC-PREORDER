
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | COMSOC</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5" style="max-width:750px">

    <div class="bg-white rounded-4 shadow-sm p-4 p-md-5">
        <h3 class="fw-bold mb-1" style="color:#18394B">Edit Product</h3>
        <p class="text-secondary mb-4">Update merchandise information.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Product Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Price (₱)</label>
                    <input type="number" name="price" class="form-control"
                           value="{{ old('price', $product->price) }}"
                           min="0.01" step="0.01" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Stock</label>
                    <input type="number" name="stock" class="form-control"
                           value="{{ old('stock', $product->stock) }}"
                           min="0" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Category</label>
                <input type="text" name="category" class="form-control"
                       value="{{ old('category', $product->category) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Product Image</label>

                @if ($product->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="Product image" width="75" height="75"
                             class="rounded-3" style="object-fit:cover">
                    </div>
                @endif

                <input type="file" name="image" class="form-control"
                       accept=".jpg,.jpeg,.png,.webp">
                <small class="text-secondary">Optional. Maximum 2 MB.</small>
                @error('image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <hr class="my-4">

            <!-- Clothing Settings -->
            <div class="form-check form-switch mb-3">
                <input type="hidden" name="is_clothing" value="0">

                <input class="form-check-input" type="checkbox"
                       role="switch" id="is_clothing"
                       name="is_clothing" value="1"
                       @checked(old('is_clothing', $product->is_clothing))>

                <label class="form-check-label fw-semibold" for="is_clothing">
                    Clothing Product
                </label>

                <div class="form-text">
                    Enable for shirts, hoodies, jackets, and other clothing.
                </div>
            </div>

            <div id="clothingOptions" class="border rounded-3 p-3 mb-4"
                 style="display: {{ old('is_clothing', $product->is_clothing) ? 'block' : 'none' }};">

                <label class="form-label fw-semibold">Available Sizes</label>

                @php
                    $selectedSizes = old('available_sizes', $product->available_sizes ?? []);
                @endphp

                <div class="d-flex flex-wrap gap-3 mb-3">
                    @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="available_sizes[]"
                                   value="{{ $size }}"
                                   id="size_{{ $size }}"
                                   @checked(in_array($size, $selectedSizes))>

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
                    <label class="form-label fw-semibold">Size Chart</label>

                    @if ($product->size_chart)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $product->size_chart) }}"
                                 alt="Current size chart"
                                 class="img-fluid rounded-3"
                                 style="max-width:300px; max-height:250px; object-fit:contain;">
                            <div class="form-text">Current size chart</div>
                        </div>
                    @endif

                    <input type="file" name="size_chart"
                           class="form-control"
                           accept=".jpg,.jpeg,.png,.webp">

                    <small class="text-secondary">
                        Upload a new image only if you want to replace the current chart.
                        Maximum 2 MB.
                    </small>

                    @error('size_chart')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-check form-switch mb-4">
                <input type="hidden" name="is_available" value="0">

                <input type="checkbox" name="is_available" value="1"
                       id="available" class="form-check-input"
                       @checked(old('is_available', $product->is_available))>

                <label for="available" class="form-check-label">
                    Available for pre-order
                </label>
            </div>

            <button type="submit"
                    class="btn text-white rounded-pill px-4"
                    style="background:#287C91">
                Save Changes
            </button>

            <a href="{{ route('admin.products.index') }}"
               class="btn btn-outline-secondary rounded-pill px-4">
                Cancel
            </a>
        </form>
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
