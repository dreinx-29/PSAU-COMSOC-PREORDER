<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use App\Models\PreOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PreOrderController extends Controller
{
    // Display the logged-in customer's pre-orders
    public function index()
    {
        $preOrders = PreOrder::with('items')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('preorders.index', compact('preOrders'));
    }

    // Save a new pre-order
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
            'size' => ['nullable', 'string', 'max:20'],
            'customer_name' => ['required', 'string', 'max:255'],
            'year_level' => [
                'required',
                'in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
            ],
            'section' => ['required', 'string', 'max:100'],
        ]);

        $preOrder = DB::transaction(function () use ($data) {
            $product = Product::whereKey($data['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if (!$product->is_available || $product->stock < 1) {
                throw ValidationException::withMessages([
                    'product_id' => 'This product is currently unavailable.',
                ]);
            }

            if ($data['quantity'] > $product->stock) {
                throw ValidationException::withMessages([
                    'quantity' => 'The requested quantity exceeds available stock.',
                ]);
            }

            // Validate clothing size
            $selectedSize = null;

            if ($product->is_clothing) {
                $availableSizes = $product->available_sizes ?? [];

                if (
                    empty($data['size']) ||
                    !in_array($data['size'], $availableSizes, true)
                ) {
                    throw ValidationException::withMessages([
                        'size' => 'Please select a valid available clothing size.',
                    ]);
                }

                $selectedSize = $data['size'];
            }

            $trackingNumber = 'CS-' . Str::upper(Str::random(10));

            $order = PreOrder::create([
                'user_id' => auth()->id(),
                'tracking_number' => $trackingNumber,
                'customer_name' => $data['customer_name'],
                'year_level' => $data['year_level'],
                'section' => $data['section'],
                'status' => 'Pending',
                'is_ready_for_pickup' => false,
                'total_amount' => $product->price * $data['quantity'],
            ]);

            PreOrderItem::create([
                'pre_order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_image' => $product->image,
                'quantity' => $data['quantity'],
                'unit_price' => $product->price,
                'size' => $selectedSize,
            ]);

            $product->decrement('stock', $data['quantity']);

            return $order;
        });

        return redirect()
            ->route('preorders.index')
            ->with(
                'success',
                'Pre-order placed successfully! Tracking number: '
                . $preOrder->tracking_number
            );
    }

    // Customer requests order cancellation
    public function requestCancellation(Request $request, PreOrder $preorder)
    {
        // Ensure the order belongs to the logged-in customer
        abort_unless($preorder->user_id === auth()->id(), 403);

        $data = $request->validate([
            'cancellation_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        // Only Pending or Approved orders can be requested for cancellation
        if (!in_array($preorder->status, ['Pending', 'Approved'])) {
            return back()->with(
                'error',
                'This order can no longer be requested for cancellation.'
            );
        }

        // Prevent duplicate pending requests
        if ($preorder->cancellation_request_status === 'Pending') {
            return back()->with(
                'error',
                'You already have a pending cancellation request.'
            );
        }

        $preorder->update([
            'cancellation_request_status' => 'Pending',
            'cancellation_reason' => $data['cancellation_reason'] ?? null,
            'cancellation_requested_at' => now(),
        ]);

        return redirect()
            ->route('preorders.index')
            ->with('success', 'Your cancellation request has been submitted!');
    }
}