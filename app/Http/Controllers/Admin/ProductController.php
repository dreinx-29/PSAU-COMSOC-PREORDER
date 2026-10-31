<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
    }

    public function index()
    {
        $this->authorizeAdmin();

        $products = Product::latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],

            'is_clothing' => ['nullable', 'boolean'],
            'available_sizes' => ['nullable', 'array'],
            'available_sizes.*' => ['required', 'string', 'max:20'],
            'size_chart' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available');
        $validated['is_clothing'] = $request->boolean('is_clothing');

        if ($validated['is_clothing']) {
            if ($request->hasFile('size_chart')) {
                $validated['size_chart'] = $request->file('size_chart')
                    ->store('size-charts', 'public');
            }
        } else {
            $validated['available_sizes'] = null;
            $validated['size_chart'] = null;
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        $this->authorizeAdmin();

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],

            'is_clothing' => ['nullable', 'boolean'],
            'available_sizes' => ['nullable', 'array'],
            'available_sizes.*' => ['required', 'string', 'max:20'],
            'size_chart' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('products', 'public');

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
        }

        $validated['is_available'] = $request->boolean('is_available');
        $validated['is_clothing'] = $request->boolean('is_clothing');

        if ($validated['is_clothing']) {
            if ($request->hasFile('size_chart')) {
                $validated['size_chart'] = $request->file('size_chart')
                    ->store('size-charts', 'public');

                if ($product->size_chart) {
                    Storage::disk('public')->delete($product->size_chart);
                }
            }
        } else {
            if ($product->size_chart) {
                Storage::disk('public')->delete($product->size_chart);
            }

            $validated['available_sizes'] = null;
            $validated['size_chart'] = null;
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorizeAdmin();

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        if ($product->size_chart) {
            Storage::disk('public')->delete($product->size_chart);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
