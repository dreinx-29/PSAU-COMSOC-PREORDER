
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales History | PSAU COMSOC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #F3F6F7;
            color: #263640;
            font-size: 14px;
        }

        .history-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 3px 12px rgba(24, 57, 75, 0.06);
        }

        h4, h5 {
            color: #18394B;
            font-weight: 700;
        }

        .table th {
            color: #858D93;
            font-size: 12px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Sales History</h4>
            <p class="text-secondary mb-0">Previously saved sales records.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-outline-secondary rounded-pill">
            Back to Dashboard
        </a>
    </div>

    <div class="history-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Total Sales</th>
                        <th>Reset Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($salesHistories as $history)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">
                                ₱{{ number_format((float) $history->total_sales, 2) }}
                            </td>
                            <td>{{ $history->reset_at->format('M d, Y - h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-secondary py-4">
                                No previous sales records yet.
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
