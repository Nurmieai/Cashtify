@extends('layouts.prf')
@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-3 mb-4">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo" height="34" class="me-2">
                <strong>Cashtify</strong>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">

                <form class="d-flex ms-3 w-50">
                    <input class="form-control me-2" type="search" placeholder="Search admin, orders, users..." aria-label="Search">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>

                <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="#" data-bs-toggle="offcanvas"
                            data-bs-target="#quickActions" aria-controls="quickActions">
                            <i class="bi bi-lightning-charge-fill"></i> Quick
                        </a>
                    </li>

                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <span class="badge bg-danger rounded-pill small">3</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown">
                            <li class="dropdown-item">New order received — <small class="text-muted">2m ago</small></li>
                            <li class="dropdown-item">Payment failed — <small class="text-muted">1h ago</small></li>
                            <li class="dropdown-item">New user registered — <small class="text-muted">3h ago</small></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('assets/images/default_user.png') }}" alt="avatar"
                                class="rounded-circle me-2" height="34">
                            <span>Hi, {{ Auth::user()->name ?? 'Admin' }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- KPI ROW -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted">Total Revenue</small>
                        <h5 class="mb-0">Rp 125.430.000</h5>
                        <small class="text-success">+12% vs last month</small>
                    </div>
                    <div class="icon-box bg-light rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-currency-dollar fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted">Active Users</small>
                        <h5 class="mb-0">2.431</h5>
                        <small class="text-warning">+3.2% today</small>
                    </div>
                    <div class="icon-box bg-light rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted">New Orders</small>
                        <h5 class="mb-0">128</h5>
                        <small class="text-muted">Avg processing time: 3h</small>
                    </div>
                    <div class="icon-box bg-light rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-bag fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted">Conversion Rate</small>
                        <h5 class="mb-0">4.87%</h5>
                        <small class="text-muted">Stable</small>
                    </div>
                    <div class="icon-box bg-light rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-graph-up fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT & CHARTS -->
    <div class="row g-3">
        <div class="col-xl-8 col-lg-12">

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Revenue Overview</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-2">1W</button>
                            <button class="btn btn-sm btn-outline-primary me-2">1M</button>
                            <button class="btn btn-sm btn-primary">1Y</button>
                        </div>
                    </div>
                    <canvas id="revenueChart" height="120"></canvas>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6>Sales by Category</h6>
                            <canvas id="categoryDoughnut" height="140"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6>Top Products</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex justify-content-between py-2 border-bottom">Product A <span class="text-muted">1.2k</span></li>
                                <li class="d-flex justify-content-between py-2 border-bottom">Product B <span class="text-muted">980</span></li>
                                <li class="d-flex justify-content-between py-2 border-bottom">Product C <span class="text-muted">760</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xl-4 col-lg-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6>Traffic Sources</h6>
                    <canvas id="trafficBar" height="180"></canvas>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Recent Users</h6>
                    <ul class="list-unstyled mb-0">

                        <li class="d-flex align-items-center py-2 border-bottom">
                            <img src="{{ asset('assets/images/default_user.png') }}" class="rounded-circle me-2" height="36">
                            <div class="flex-grow-1">
                                <strong>Siti A.</strong>
                                <div class="small text-muted">Joined 2 days ago</div>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">View</button>
                        </li>

                        <li class="d-flex align-items-center py-2 border-bottom">
                            <img src="{{ asset('assets/images/default_user.png') }}" class="rounded-circle me-2" height="36">
                            <div class="flex-grow-1">
                                <strong>Joko P.</strong>
                                <div class="small text-muted">Joined 1 week ago</div>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">View</button>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT ORDERS TABLE -->
    <div class="row mt-4">
        <div class="col-12">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recent Orders</h5>
                        <button class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#1023</td>
                                    <td>Siti A.</td>
                                    <td>Rp 420.000</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>2025-11-10</td>
                                    <td><button class="btn btn-sm btn-outline-secondary">Details</button></td>
                                </tr>
                                <tr>
                                    <td>#1022</td>
                                    <td>Joko P.</td>
                                    <td>Rp 59.000</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>2025-11-09</td>
                                    <td><button class="btn btn-sm btn-outline-secondary">Details</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- OFFCANVAS QUICK ACTIONS -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="quickActions"
        aria-labelledby="quickActionsLabel">
        <div class="offcanvas-header">
            <h5 id="quickActionsLabel">Quick Actions</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <button class="btn btn-primary w-100 mb-2">Create new user</button>
            <button class="btn btn-outline-secondary w-100 mb-2">Create new product</button>
            <button class="btn btn-outline-danger w-100">Clear cache</button>
        </div>
    </div>

</div>
@endsection
