
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Pre-orders | PSAU COMSOC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark" style="background:#173B50">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">PSAU COMSOC</a>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-light btn-sm">Shop</a>
    </div>
</nav>

<div class="container py-5">

    <h3 class="fw-bold mb-4" style="color:#173B50">My Pre-orders</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @forelse($preOrders as $order)

        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body">

                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <strong>{{ $order->tracking_number }}</strong>

                    <span class="badge
                        @if($order->status === 'Cancelled') bg-danger
                        @elseif($order->status === 'Completed') bg-success
                        @elseif($order->status === 'Ready for Pickup') bg-success
                        @elseif($order->status === 'Approved') bg-primary
                        @else bg-warning text-dark
                        @endif">
                        {{ $order->status }}
                    </span>
                </div>

                <div class="bg-light rounded p-3 mt-3">
                    <p class="mb-1">
                        <strong>Customer Name:</strong>
                        {{ $order->customer_name }}
                    </p>

                    <p class="mb-1">
                        <strong>Year Level:</strong>
                        {{ $order->year_level }}
                    </p>

                    <p class="mb-0">
                        <strong>Section:</strong>
                        {{ $order->section }}
                    </p>
                </div>

                <h6 class="fw-bold mt-3">Order Details</h6>

                @foreach($order->items as $item)
                    <div class="d-flex justify-content-between flex-wrap gap-2 mt-3">

                        <div>
                            <div class="fw-semibold">
                                {{ $item->product_name }} × {{ $item->quantity }}
                            </div>

                            @if($item->size)
                                <div class="small text-muted mt-1">
                                    <span class="fw-semibold">Size:</span>
                                    <span class="badge bg-secondary">
                                        {{ $item->size }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div>
                            ₱{{ number_format($item->unit_price * $item->quantity, 2) }}
                        </div>

                    </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between">
                    <strong>Total</strong>
                    <strong>₱{{ number_format($order->total_amount, 2) }}</strong>
                </div>

                <small class="text-muted d-block mt-2">
                    Pickup:
                    {{ $order->is_ready_for_pickup ? 'Ready for pickup' : 'Not ready yet' }}
                </small>

                {{-- Cancellation Request History --}}
                @if($order->cancellation_request_status)

                    <div class="mt-3 p-3 rounded
                        @if($order->cancellation_request_status === 'Pending') bg-warning-subtle
                        @elseif($order->cancellation_request_status === 'Approved') bg-success-subtle
                        @else bg-danger-subtle
                        @endif">

                        <div class="d-flex justify-content-between flex-wrap gap-2">
                            <strong>Cancellation Request</strong>

                            <span class="badge
                                @if($order->cancellation_request_status === 'Pending') bg-warning text-dark
                                @elseif($order->cancellation_request_status === 'Approved') bg-success
                                @else bg-danger
                                @endif">
                                {{ $order->cancellation_request_status }}
                            </span>
                        </div>

                        @if($order->cancellation_reason)
                            <p class="mb-1 mt-2 small">
                                <strong>Reason:</strong>
                                {{ $order->cancellation_reason }}
                            </p>
                        @endif

                        @if($order->cancellation_requested_at)
                            <small class="text-muted">
                                Requested:
                                {{ $order->cancellation_requested_at->format('M d, Y h:i A') }}
                            </small>
                        @endif

                        @if($order->cancellation_request_status === 'Approved')
                            <hr>
                            <p class="mb-0 small text-success fw-semibold">
                                Your cancellation request has been approved.
                                This order is now part of your cancelled order history.
                            </p>
                        @elseif($order->cancellation_request_status === 'Rejected')
                            <hr>
                            <p class="mb-0 small text-danger">
                                Your cancellation request was rejected by the admin.
                                Your original order status remains unchanged.
                            </p>
                        @else
                            <hr>
                            <p class="mb-0 small text-muted">
                                Your cancellation request is waiting for admin review.
                            </p>
                        @endif

                    </div>

                @endif

                {{-- Request Cancellation Button --}}
                @if(in_array($order->status, ['Pending', 'Approved'])
                    && !in_array($order->cancellation_request_status, ['Pending', 'Approved']))

                    <div class="mt-3">
                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#cancelModal{{ $order->id }}">
                            Request Cancellation
                        </button>
                    </div>

                    {{-- Cancellation Modal --}}
                    <div class="modal fade"
                         id="cancelModal{{ $order->id }}"
                         tabindex="-1"
                         aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 rounded-3">

                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">
                                        Request Order Cancellation
                                    </h5>

                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                </div>

                                <form action="{{ route('preorders.cancel-request', $order) }}"
                                      method="POST">

                                    @csrf

                                    <div class="modal-body">

                                        <p class="text-muted">
                                            Are you sure you want to request cancellation
                                            of order
                                            <strong>{{ $order->tracking_number }}</strong>?
                                        </p>

                                        <label class="form-label fw-semibold">
                                            Reason for cancellation (Optional)
                                        </label>

                                        <textarea
                                            name="cancellation_reason"
                                            class="form-control"
                                            rows="3"
                                            maxlength="1000"
                                            placeholder="Tell us why you want to cancel this order..."></textarea>

                                        <small class="text-muted">
                                            Your request will be reviewed by the admin.
                                            Your order will not be cancelled immediately.
                                        </small>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button"
                                                class="btn btn-light"
                                                data-bs-dismiss="modal">
                                            Go Back
                                        </button>

                                        <button type="submit"
                                                class="btn btn-danger">
                                            Submit Request
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                @endif

            </div>
        </div>

    @empty

        <div class="card border-0 shadow-sm text-center p-5">
            <h5>No pre-orders yet</h5>
            <p class="text-muted">Your orders will appear here.</p>

            <a href="{{ route('shop.index') }}"
               class="btn btn-primary mx-auto"
               style="background:#21849A;border:0">
                Browse Products
            </a>
        </div>

    @endforelse

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
