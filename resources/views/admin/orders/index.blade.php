
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Orders | PSAU COMSOC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid px-4 py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color:#18394B">Order Management</h3>
            <p class="text-secondary mb-0">Manage pre-orders, pickup schedules, and cancellation requests.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
            Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @php
        $pendingCancellations = $orders->where('cancellation_request_status', 'Pending');
    @endphp

    @if ($pendingCancellations->count() > 0)
        <div class="alert alert-warning rounded-3">
            <strong>{{ $pendingCancellations->count() }}</strong>
            cancellation request(s) need your review.
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Tracking No.</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pickup Date</th>
                        <th>Update Order</th>
                        <th>Cancellation Request</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="fw-semibold">
                                {{ $order->tracking_number }}
                            </td>

                            <td>
                                {{ $order->customer_name }}<br>
                                <small class="text-secondary">
                                    {{ $order->year_level }} · {{ $order->section }}
                                </small>
                            </td>

                            <!-- Product Details and Clothing Size -->
                            <td>
                                @foreach ($order->items as $item)
                                    <div class="mb-2">
                                        <div class="fw-semibold">
                                            {{ $item->product_name }} × {{ $item->quantity }}
                                        </div>

                                        @if ($item->size)
                                            <small class="text-secondary">
                                                Size:
                                                <span class="badge text-bg-secondary">
                                                    {{ $item->size }}
                                                </span>
                                            </small>
                                        @endif
                                    </div>
                                @endforeach
                            </td>

                            <td>
                                ₱{{ number_format($order->total_amount, 2) }}
                            </td>

                            <td>
                                <span class="badge text-bg-secondary">
                                    {{ $order->status }}
                                </span>
                            </td>

                            <td>
                                {{ $order->estimated_pickup_date ?? 'Not set' }}
                            </td>

                            <td style="min-width:190px">
                                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <select name="status" class="form-select form-select-sm mb-2" required>
                                        @foreach (['Pending', 'Approved', 'Ready for Pickup', 'Completed', 'Cancelled'] as $status)
                                            <option value="{{ $status }}" @selected($order->status === $status)>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="date"
                                        name="estimated_pickup_date"
                                        class="form-control form-control-sm mb-2"
                                        value="{{ $order->estimated_pickup_date }}"
                                        min="{{ now()->toDateString() }}">

                                    <button type="submit"
                                        class="btn btn-sm text-white rounded-pill w-100"
                                        style="background:#287C91">
                                        Save Update
                                    </button>
                                </form>
                            </td>

                            <td style="min-width:230px">

                                @if ($order->cancellation_request_status === 'Pending')

                                    <span class="badge text-bg-warning mb-2">
                                        Pending Review
                                    </span>

                                    <div class="small mb-2">
                                        <strong>Reason:</strong><br>
                                        {{ $order->cancellation_reason ?: 'No reason provided.' }}
                                    </div>

                                    <small class="text-secondary d-block mb-2">
                                        Requested:
                                        {{ $order->cancellation_requested_at?->format('M d, Y h:i A') ?? 'N/A' }}
                                    </small>

                                    <form action="{{ route('admin.orders.cancellation-review', $order) }}"
                                        method="POST" class="mb-2"
                                        onsubmit="return confirm('Are you sure you want to approve this cancellation?')">
                                        @csrf
                                        <input type="hidden" name="decision" value="Approved">

                                        <button type="submit" class="btn btn-success btn-sm rounded-pill w-100">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.orders.cancellation-review', $order) }}"
                                        method="POST" class="mb-2"
                                        onsubmit="return confirm('Are you sure you want to reject this cancellation?')">
                                        @csrf
                                        <input type="hidden" name="decision" value="Rejected">

                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100">
                                            Reject
                                        </button>
                                    </form>

                                @elseif ($order->cancellation_request_status === 'Approved')

                                    <span class="badge text-bg-success">
                                        Cancellation Approved
                                    </span>

                                @elseif ($order->cancellation_request_status === 'Rejected')

                                    <span class="badge text-bg-danger">
                                        Cancellation Rejected
                                    </span>

                                @else

                                    <span class="text-secondary small">
                                        No cancellation request
                                    </span>

                                @endif

                                <hr class="my-2">

                                <form action="{{ route('admin.orders.destroy', $order) }}"
                                    method="POST"
                                    onsubmit="return confirm('Permanently delete this order and its items?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="btn btn-danger btn-sm rounded-pill w-100">
                                        Delete Order
                                    </button>
                                </form>

                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-secondary py-5">
                                No pre-orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

</body>
</html>
