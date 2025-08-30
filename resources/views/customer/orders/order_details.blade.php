<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order #{{ $order['id'] ?? '' }} Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f5f7fb; }
    .card { border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,.06); margin-bottom:1rem; }
    .badge-status { padding:.4rem .7rem; font-size:.75rem; border-radius:.4rem; font-weight:600; }
    .badge-shipped { background:#06b6d4; color:#fff; }
    .badge-pending { background:#fbbf24; color:#fff; }
  </style>
</head>
<body>
<div class="container py-4">
  <h3 class="mb-3">Order #{{ $order['id'] ?? '' }} Details</h3>

  <!-- Order Information -->
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <p><strong>Order Number:</strong> #{{ $order['id'] ?? '' }}</p>
          <p><strong>Date:</strong> {{ $order['date'] ?? '' }}</p>
          <p><strong>Status:</strong> <span class="badge-status badge-shipped">{{ $order['status'] ?? '' }}</span></p>
          <p><strong>Payment Method:</strong> {{ $order['payment_method'] ?? '' }}</p>
          <p><strong>Payment Status:</strong> <span class="badge-status badge-pending">{{ $order['payment_status'] ?? '' }}</span></p>
        </div>
        <div class="col-md-6">
          <p><strong>Name:</strong> {{ $order['name'] ?? '' }}</p>
          <p><strong>Email:</strong> {{ $order['email'] ?? '' }}</p>
          <p><strong>Shipping Address:</strong> {{ $order['shipping'] ?? '' }}</p>
          <p><strong>Billing Address:</strong> {{ $order['billing'] ?? '' }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Order Items -->
  <div class="card">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead class="table-light">
          <tr>
            <th>Product</th><th>SKU</th><th>Price</th><th>Quantity</th><th>Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach(($items ?? []) as $it)
          <tr>
            <td>{{ $it['product'] ?? '' }}</td>
            <td>{{ $it['sku'] ?? '' }}</td>
            <td>CHF {{ number_format($it['price'] ?? 0, 2) }}</td>
            <td>{{ $it['qty'] ?? 0 }}</td>
            <td>CHF {{ number_format($it['total'] ?? 0, 2) }}</td>
          </tr>
          @endforeach
          <tr><td colspan="4" class="text-end">Subtotal:</td><td>CHF {{ number_format($summary['subtotal'] ?? 0, 2) }}</td></tr>
          <tr><td colspan="4" class="text-end">Tax ({{ $summary['tax_rate'] ?? 0 }}%):</td><td>CHF {{ number_format($summary['tax'] ?? 0, 2) }}</td></tr>
          <tr><td colspan="4" class="text-end">Shipping:</td><td>CHF {{ number_format($summary['shipping'] ?? 0, 2) }}</td></tr>
          <tr><td colspan="4" class="text-end fw-bold">Total:</td><td>CHF {{ number_format($summary['total'] ?? 0, 2) }}</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
