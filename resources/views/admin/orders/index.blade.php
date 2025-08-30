<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header-primary { background: #0d6efd; color:#fff; font-weight:600; }
        .badge-status { font-size: .75rem; }
        .action-btns .btn { padding: .25rem .4rem; }
        .table > :not(caption) > * > * { vertical-align: middle; }
    </style>
</head>
<body class="bg-light">
<div class="container-fluid py-3">
    <div class="card shadow-sm">
        <div class="card-header card-header-primary">Orders</div>
        <div class="card-body">
            <form method="get" class="row g-2 align-items-end mb-3">
                <div class="col-12 col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Order ID, Name, Email">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-12 col-md-1">
                    <a href="{{ url('/order-details') }}" class="btn btn-outline-secondary w-100">↩</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-borderless">
                    <thead class="table-light">
                        <tr>
                            <th style="width:110px">Order ID</th>
                            <th>Customer</th>
                            <th style="width:210px">Date</th>
                            <th style="width:160px" class="text-end">Total</th>
                            <th style="width:120px">Status</th>
                            <th style="width:120px">Payment</th>
                            <th style="width:160px" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $o)
                            <tr>
                                <td>#{{ $o['order_no'] }}</td>
                                <td>{{ $o['customer_name'] ?: 'Guest User' }}</td>
                                <td>{{ $o['date'] }}</td>
                                <td class="text-end">CHF {{ number_format($o['total'], 2) }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark badge-status">{{ $o['status'] }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $o['payment_status']==='Paid' ? 'bg-success' : 'bg-warning text-dark' }} badge-status">{{ $o['payment_status'] }}</span>
                                </td>
                                <td class="text-end action-btns">
                                    <a class="btn btn-primary btn-sm" title="View" href="{{ route('order.details', ['order_no' => $o['order_no']]) }}"><i class="bi bi-eye"></i> View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Icons (for the eye icon) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</body>
</html>
