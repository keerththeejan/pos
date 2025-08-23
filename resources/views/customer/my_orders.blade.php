<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f5f7fb;
            --border: #e5e7eb;
            --muted: #6b7280;
            --text: #111827;
            --header: #f3f6fb;
        }
        body { background: var(--bg); color: var(--text); }
        .card-clean { background:#fff; border:1px solid var(--border); border-radius:10px; box-shadow: 0 8px 24px rgba(0,0,0,.06); }
        .card-clean .card-header { background: var(--header); border-bottom:1px solid var(--border); font-weight:600; }
        .empty { color: var(--muted); }
        h2 { font-weight: 700; letter-spacing:.2px; }
        .order-row:hover { background: #f9fbff; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex align-items-center mb-3">
        <h2 class="mb-0">My Orders</h2>
        <div class="ms-auto"><a class="btn btn-outline-secondary btn-sm" href="{{ route('cart.show') }}">← Back to Cart</a></div>
    </div>

    <div class="card card-clean">
        <div class="card-header">Order History</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Order No</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">Tax</th>
                        <th class="text-end">Shipping</th>
                        <th class="text-end">Total</th>
                        <th>Payment</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $o)
                        <tr class="order-row">
                            <td><strong>{{ $o['order_no'] }}</strong></td>
                            <td class="text-center">{{ (float) $o['items_count'] }}</td>
                            <td class="text-end">CHF {{ number_format($o['subtotal'], 2) }}</td>
                            <td class="text-end">CHF {{ number_format($o['tax'], 2) }} <span class="text-muted small">({{ $o['tax_rate'] }}%)</span></td>
                            <td class="text-end">CHF {{ number_format($o['shipping'], 2) }}</td>
                            <td class="text-end"><strong>CHF {{ number_format($o['total'], 2) }}</strong></td>
                            <td>{{ $o['payment_method'] ? strtoupper($o['payment_method']) : '—' }}</td>
                            <td class="text-end">
                                <a class="btn btn-primary btn-sm" href="{{ route('order.details', ['order_no' => $o['order_no']]) }}">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center empty py-4">No orders yet</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
