
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COMSOC | Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --navy: #18394B;
            --teal: #287C91;
            --green: #397C58;
            --gray: #858D93;
            --white: #FFFFFF;
            --bg: #F3F6F7;
        }

        body {
            background: var(--bg);
            color: #263640;
            font-size: 14px;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: var(--navy);
            position: fixed;
            top: 0;
            left: 0;
            padding: 25px 18px;
        }

        .brand {
            color: var(--white);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 35px;
        }

        .sidebar .nav-link {
            color: #E0E8EC;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 7px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: var(--teal);
            color: var(--white);
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .stat-card,
        .content-card {
            background: var(--white);
            border: 0;
            border-radius: 16px;
            box-shadow: 0 3px 12px rgba(24, 57, 75, 0.06);
        }

        .stat-card {
            padding: 22px;
            height: 100%;
        }

        .stat-number {
            font-size: 27px;
            font-weight: 700;
            color: var(--navy);
        }

        .stat-label {
            color: var(--gray);
        }

        .section-title {
            color: var(--navy);
            font-weight: 700;
        }

        .table th {
            color: var(--gray);
            font-size: 12px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        #analytics, #orders {
            scroll-margin-top: 20px;
        }

        html {
            scroll-behavior: smooth;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 20px 15px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            PSAU <span style="color:#70C1D0">COMSOC</span>
        </div>

        <nav class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}" class="nav-link">
                User Accounts
            </a>

            <a href="{{ route('admin.products.index') }}" class="nav-link">
                Products
            </a>

            <a href="{{ route('admin.orders.index') }}" class="nav-link">
                Pre-Orders
            </a>

            <a href="{{ route('admin.dashboard') }}#analytics" class="nav-link">
                Sales Analytics
            </a>
        </nav>

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-outline-light rounded-pill w-100">
                Logout
            </button>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <!-- Dashboard Heading -->
        <div class="mb-4">
            <h3 class="fw-bold mb-1" style="color:var(--navy)">
                Welcome, Admin!
            </h3>

            <p class="text-secondary mb-0">
                Manage your COMSOC merchandise pre-orders here.
            </p>
        </div>

        <!-- Dashboard Statistics -->
        <div class="row g-3 mb-4">

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <p class="stat-label mb-2">Total Products</p>
                    <div class="stat-number">{{ $totalProducts }}</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <p class="stat-label mb-2">Total Pre-Orders</p>
                    <div class="stat-number">{{ $totalPreOrders }}</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <p class="stat-label mb-2">Pending Orders</p>
                    <div class="stat-number">{{ $pendingOrders }}</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <p class="stat-label mb-2">Total Sales</p>

                    <div class="stat-number mb-3">
                        ₱{{ number_format((float) $totalSales, 2) }}
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.sales-history.index') }}"
                           class="btn btn-sm rounded-pill text-white"
                           style="background:var(--teal)">
                            Sales History
                        </a>

                        <form action="{{ route('admin.sales.reset') }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to reset current sales? The amount will be saved in Sales History.')">
                            @csrf

                            <button type="submit"
                                    class="btn btn-sm rounded-pill btn-outline-danger">
                                Reset Sales
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sales Analytics -->
        <div class="content-card p-4 mb-4" id="analytics">
            <div class="mb-4">
                <h5 class="section-title mb-1">Sales Analytics</h5>

                <p class="text-secondary mb-0">
                    Overview of COMSOC merchandise sales and orders.
                </p>
            </div>

            <div class="content-card p-3 mb-4">
                <h6 class="fw-bold mb-3" style="color:var(--navy)">
                    Monthly Sales Overview
                </h6>

                <div class="chart-container">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-12 col-lg-6">
                    <div class="content-card p-3 h-100">
                        <h6 class="fw-bold mb-3" style="color:var(--navy)">
                            Order Status Distribution
                        </h6>

                        <div class="chart-container">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="content-card p-3 h-100">
                        <h6 class="fw-bold mb-3" style="color:var(--navy)">
                            Top 5 Selling Products
                        </h6>

                        <div class="chart-container">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-card p-4 mb-4">
            <h5 class="section-title mb-3">Quick Actions</h5>

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('admin.products.index') }}"
                   class="btn text-white rounded-pill px-4"
                   style="background:var(--teal)">
                    Manage Products
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="btn text-white rounded-pill px-4"
                   style="background:var(--green)">
                    View Pre-Orders
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="btn text-white rounded-pill px-4"
                   style="background:var(--gray)">
                    User Accounts
                </a>

            </div>
        </div>

        <!-- Recent Pre-Orders -->
        <div class="content-card p-4 mb-4" id="orders">
            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="section-title mb-0">Recent Pre-Orders</h5>

                <a href="{{ route('admin.orders.index') }}"
                   class="btn btn-sm btn-outline-primary rounded-pill">
                    View All
                </a>

            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Tracking No.</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recentOrders as $order)

                            @php
                                $statusColors = [
                                    'Pending' => 'warning',
                                    'Approved' => 'primary',
                                    'Ready for Pickup' => 'info',
                                    'Completed' => 'success',
                                    'Cancelled' => 'danger',
                                ];
                            @endphp

                            <tr>
                                <td class="fw-semibold">
                                    {{ $order->tracking_number }}
                                </td>

                                <td>{{ $order->customer_name }}</td>

                                <td>
                                    ₱{{ number_format((float) $order->total_amount, 2) }}
                                </td>

                                <td>
                                    <span class="badge text-bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">
                                    No recent pre-orders to display.
                                </td>
                            </tr>

                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function () {
            $('.stat-card, .content-card').hide().fadeIn(500);

            // Monthly Sales Chart
            new Chart(document.getElementById('monthlySalesChart'), {
                type: 'line',
                data: {
                    labels: @json($salesLabels),
                    datasets: [{
                        label: 'Monthly Sales (₱)',
                        data: @json($salesData),
                        borderColor: '#287C91',
                        backgroundColor: 'rgba(40, 124, 145, 0.12)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#18394B'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '₱' + value.toLocaleString()
                            }
                        }
                    }
                }
            });

            // Order Status Chart
            new Chart(document.getElementById('orderStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($statusLabels),
                    datasets: [{
                        data: @json($statusData),
                        backgroundColor: [
                            '#E9B949',
                            '#287C91',
                            '#70B7C5',
                            '#397C58',
                            '#C94C4C'
                        ],
                        borderWidth: 2,
                        borderColor: '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // Top Selling Products Chart
            new Chart(document.getElementById('topProductsChart'), {
                type: 'bar',
                data: {
                    labels: @json($productLabels),
                    datasets: [{
                        label: 'Quantity Sold',
                        data: @json($productData),
                        backgroundColor: '#397C58',
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        });
    </script>

</body>
</html>
