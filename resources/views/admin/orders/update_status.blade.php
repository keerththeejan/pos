<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Order Status - Order #{{ $order_no }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    .header { background:#0d6efd; color:#fff; font-weight:700; }
    .note-box { background:#e3f7ff; border-radius:.5rem; border:1px solid #bfe9fb; }
  </style>
</head>
<body class="bg-light">
<div class="container py-3">
  <a href="{{ route('order.details', ['order_no' => $order_no]) }}" class="btn btn-secondary btn-sm mb-3">
    <i class="bi bi-arrow-left"></i> Back to Order
  </a>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header header">Update Order Status - Order #{{ $order_no }}</div>
    <div class="card-body">
      <form method="POST" action="{{ route('order.status.update', ['order_no' => $order_no]) }}">
        @csrf
        <div class="mb-3">
          <label for="status" class="form-label">Order Status</label>
          @php($current = $current_status ?? 'Pending')
          <select id="status" name="status" class="form-select">
            <option value="Pending" {{ $current === 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Processing" {{ $current === 'Processing' ? 'selected' : '' }}>Processing</option>
            <option value="Shipped" {{ $current === 'Shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="Delivered" {{ $current === 'Delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="Cancelled" {{ $current === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
        </div>

        <div class="note-box p-3 mb-3">
          <h6 class="mb-2 text-primary">Status Information:</h6>
          <ul class="mb-0">
            <li><strong>Pending:</strong> Order has been placed but not yet processed.</li>
            <li><strong>Processing:</strong> Order is being prepared for shipping.</li>
            <li><strong>Shipped:</strong> Order has been shipped to the customer.</li>
            <li><strong>Delivered:</strong> Order has been delivered to the customer.</li>
            <li><strong>Cancelled:</strong> Order has been cancelled.</li>
          </ul>
        </div>

        <div class="d-flex justify-content-between">
          <a href="{{ route('order.details', ['order_no' => $order_no]) }}" class="btn btn-outline-secondary">Cancel</a>
          <button type="submit" class="btn btn-primary">Update Status</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
