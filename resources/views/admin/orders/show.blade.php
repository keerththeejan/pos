<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order #{{ $order['id'] ?? '' }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    .header { background:#0d6efd; color:#fff; font-weight:700; }
    .badge-status { font-size: .75rem; }
    .table > :not(caption) > * > * { vertical-align: middle; }
    
    @media print {
      @page { 
        size: A4;
        margin: 10mm 10mm 10mm 10mm;
      }
      body { 
        background: #fff !important; 
        color: #000 !important; 
        font-size: 12pt;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      /* Print styles for products table */
      @media print {
        /* Hide product name and price columns */
        th:nth-child(1), td:nth-child(1),  /* Product name */
        th:nth-child(3), td:nth-child(3) { /* Price column */
          display: none !important;
        }
        /* Show only SKU, Quantity, and Subtotal columns */
        th:nth-child(2), td:nth-child(2),  /* SKU */
        th:nth-child(4), td:nth-child(4),  /* Quantity */
        th:nth-child(5), td:nth-child(5) { /* Subtotal */
          display: table-cell !important;
        }
        /* Adjust column widths for better print layout */
        th:nth-child(2), td:nth-child(2) { width: 40%; }  /* SKU */
        th:nth-child(4), td:nth-child(4) { width: 20%; text-align: center; }  /* Quantity */
        th:nth-child(5), td:nth-child(5) { width: 40%; text-align: right; }  /* Subtotal */
        
        /* Ensure subtotal row is properly aligned */
        .subtotal-row td {
          text-align: right !important;
          padding-right: 15px;
        }
      }
      /* Show only subtotal row */
      .subtotal-row {
        display: table-row !important;
      }
      .subtotal-row td {
        border-top: 2px solid #000 !important;
        font-weight: bold;
      }
      .card, .table { 
        border: 1px solid #000 !important; 
        page-break-inside: avoid;
      }
      .table { 
        width: 100% !important; 
        border-collapse: collapse !important;
        margin-bottom: 15px;
      }
      .table th, .table td { 
        border: 1px solid #000 !important; 
        padding: 6px !important;
        font-size: 11pt;
      }
      .text-end { text-align: right !important; }
      .text-center { text-align: center !important; }
      .table-light th { 
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .btn-group, .d-print-none { 
        display: none !important; 
      }
      a[href]:after { 
        content: none !important; 
      }
      .card {
        border: none !important;
        box-shadow: none !important;
      }
      .card-header {
        background-color: #0d6efd !important;
        color: #fff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
  </style>
</head>
<body class="bg-light">
<div class="container-fluid py-3">
  <a href="{{ url('/order-details') }}" class="btn btn-outline-secondary btn-sm mb-2">&larr; Back to Orders</a>

  <div class="card shadow-sm">
    <div class="card-header header">
      <div class="d-flex align-items-center">
        <div>Order <span class="ms-1">#{{ $order['id'] ?? '' }}</span></div>
        <div class="ms-auto">
          <div class="btn-group btn-group-sm">
            <a class="btn btn-light" onclick="window.print()"><i class="bi bi-printer"></i> Print</a>
            <a class="btn btn-warning text-dark" href="{{ route('order.status.edit', ['order_no' => $order['id'] ?? '']) }}">
              <i class="bi bi-check2-circle"></i> Update Status
            </a>
            <a class="btn btn-warning text-dark" href="{{ route('update_payment', ['transaction_id' => $order['id'] ?? '']) }}">
              <i class="bi bi-cash-coin"></i> Update Payment
            </a>
            <a class="btn btn-warning text-dark" href="#"><i class="bi bi-receipt"></i> Make Bill & Invoice</a>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="table-responsive">
            <table class="table table-sm">
              <thead class="table-light"><tr><th colspan="2">Order Information</th></tr></thead>
              <tbody>
                <tr><td style="width:220px">Order ID:</td><td>#{{ $order['id'] ?? '' }}</td></tr>
                <tr><td>Date:</td><td>{{ $order['date'] ?? '' }}</td></tr>
                <tr><td>Status:</td><td><span class="badge bg-warning text-dark badge-status">{{ $order['status'] ?? 'Pending' }}</span></td></tr>
                <tr><td>Payment Method:</td><td>{{ ucfirst(strtolower($order['payment_method'] ?? 'cod')) }}</td></tr>
                <tr><td>Payment Status:</td><td><span class="badge bg-warning text-dark badge-status">{{ $order['payment_status'] ?? 'Pending' }}</span></td></tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-md-6">
          <div class="table-responsive">
            <table class="table table-sm">
              <thead class="table-light"><tr><th colspan="2">Customer Information</th></tr></thead>
              <tbody>
                <tr><td style="width:220px">Name:</td><td>{{ $order['name'] ?? '' }}</td></tr>
                <tr><td>Email:</td><td>{{ $order['email'] ?? '' }}</td></tr>
                <tr><td>Shipping Address:</td><td>{{ $order['shipping'] ?? '' }}</td></tr>
                <tr><td>Billing Address:</td><td>{{ $order['billing'] ?? '' }}</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="table-responsive mt-3">
        <table class="table table-sm">
          <thead class="table-light">
            <tr>
              <th>Product</th>
              <th>SKU</th>
              <th class="text-end price-col">Price</th>
              <th class="text-center">Quantity</th>
              <th class="text-end subtotal-col">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach(($items ?? []) as $it)
              <tr>
                <td>{{ $it['product'] ?? '' }}</td>
                <td>{{ $it['sku'] ?? '' }}</td>
                <td class="text-end price-col">CHF {{ number_format($it['price'] ?? 0, 2) }}</td>
                <td class="text-center">{{ $it['qty'] ?? 0 }}</td>
                <td class="text-end subtotal-col print-hide">CHF {{ number_format($it['total'] ?? 0, 2) }}</td>
              </tr>
            @endforeach
            <tr class="subtotal-row"><td colspan="4" class="text-end">Subtotal:</td><td class="text-end">CHF {{ number_format($summary['subtotal'] ?? 0, 2) }}</td></tr>
            <tr class="print-hide"><td colspan="4" class="text-end">Tax:</td><td class="text-end">CHF {{ number_format($summary['tax'] ?? 0, 2) }}</td></tr>
            <tr class="print-hide"><td colspan="4" class="text-end">Shipping:</td><td class="text-end">CHF {{ number_format($summary['shipping'] ?? 0, 2) }}</td></tr>
            <tr class="print-hide"><td colspan="4" class="text-end fw-bold">Total:</td><td class="text-end fw-bold">CHF {{ number_format($summary['total'] ?? 0, 2) }}</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


</body>
</html>
