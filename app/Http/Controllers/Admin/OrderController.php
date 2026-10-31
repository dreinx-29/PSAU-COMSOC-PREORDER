<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
    }

    public function index()
    {
        $this->authorizeAdmin();

        $orders = PreOrder::with(['user', 'items'])
            ->where('status', '!=', 'Completed')
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, PreOrder $order)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'status' => ['required', 'in:Pending,Approved,Ready for Pickup,Completed,Cancelled'],
            'estimated_pickup_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
                'required_if:status,Ready for Pickup',
            ],
        ]);

        $previousStatus = $order->status;
        $newStatus = $data['status'];

        $order->status = $newStatus;

        $order->estimated_pickup_date = $newStatus === 'Cancelled'
            ? null
            : ($data['estimated_pickup_date'] ?? null);

        $order->is_ready_for_pickup = $newStatus === 'Ready for Pickup';

        if ($newStatus === 'Completed' && $previousStatus !== 'Completed') {
            $order->completed_at = now();
        }

        if ($newStatus !== 'Completed') {
            $order->completed_at = null;
        }

        $order->save();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order updated successfully!');
    }

    public function reviewCancellation(Request $request, PreOrder $order)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'decision' => ['required', 'in:Approved,Rejected'],
        ]);

        DB::transaction(function () use ($order, $data) {
            $order = PreOrder::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->cancellation_request_status !== 'Pending') {
                abort(422, 'This cancellation request has already been reviewed.');
            }

            if ($data['decision'] === 'Approved') {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $product = Product::whereKey($item->product_id)
                            ->lockForUpdate()
                            ->first();

                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    }
                }

                $order->status = 'Cancelled';
                $order->is_ready_for_pickup = false;
                $order->estimated_pickup_date = null;
                $order->completed_at = null;
            }

            $order->cancellation_request_status = $data['decision'];
            $order->save();
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Cancellation request ' . strtolower($data['decision']) . ' successfully!');
    }

    // Delete any order
    public function destroy(PreOrder $order)
    {
        $this->authorizeAdmin();

        DB::transaction(function () use ($order) {
            $order->items()->delete();
            $order->delete();
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully!');
    }
}
