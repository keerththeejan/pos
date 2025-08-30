<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Payment - Order #{{ $order_no }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body { background-color: #f8f9fa; }
    .payment-card {
      border: 1px solid #dee2e6;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      cursor: pointer;
      transition: all 0.3s;
    }
    .payment-card:hover {
      border-color: #0d6efd;
      box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
    }
    .payment-card.active {
      border-color: #0d6efd;
      background-color: #e7f1ff;
    }
    .payment-card i {
      font-size: 24px;
      margin-bottom: 10px;
    }
    .amount-input {
      font-size: 24px;
      font-weight: 600;
      text-align: right;
      border: none;
      border-bottom: 2px solid #dee2e6;
      border-radius: 0;
      padding: 10px 5px;
    }
    .amount-input:focus {
      box-shadow: none;
      border-color: #0d6efd;
    }
    .summary-card {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 20px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('order.details', ['order_no' => $order_no]) }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Back to Order
    </a>
    <h4 class="mb-0">Update Payment</h4>
    <div style="width: 120px;"></div>
  </div>

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

  <div class="row">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="mb-4">Select Payment Method</h5>
          
          <div class="row">
            <div class="col-4">
              <div class="payment-card text-center active" data-method="cash">
                <i class="bi bi-cash-coin text-success"></i>
                <div class="fw-medium">Cash</div>
                <input type="radio" name="method" value="cash" checked hidden>
              </div>
            </div>
            <div class="col-4">
              <div class="payment-card text-center" data-method="card">
                <i class="bi bi-credit-card text-primary"></i>
                <div class="fw-medium">Card</div>
                <input type="radio" name="method" value="card" hidden>
              </div>
            </div>
            <div class="col-4">
              <div class="payment-card text-center" data-method="upi">
                <i class="bi bi-phone text-info"></i>
                <div class="fw-medium">UPI</div>
                <input type="radio" name="method" value="upi" hidden>
              </div>
            </div>
          </div>

          <div class="mt-4">
            <label class="form-label">Enter Amount</label>
            <div class="input-group mb-3">
              <span class="input-group-text">₹</span>
              <input type="number" 
                     class="form-control amount-input" 
                     id="amount-input" 
                     value="{{ $balance_due }}" 
                     step="0.01" 
                     min="0" 
                     max="{{ $balance_due }}"
                     required>
            </div>
            <input type="hidden" name="amount" id="amount" value="{{ $balance_due }}">
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
              <i class="bi bi-check-circle"></i> Record Payment
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Payment Details</h5>
          
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Order Total:</span>
            <span class="fw-medium">₹{{ number_format($order_total, 2) }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Amount Paid:</span>
            <span class="fw-medium">₹{{ number_format($amount_paid, 2) }}</span>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <span class="text-muted">Balance Due:</span>
            <span class="fw-bold text-danger">₹<span id="display-amount">{{ number_format($balance_due, 2) }}</span></span>
          </div>
          
          <div class="form-floating mb-3">
            <textarea class="form-control" placeholder="Add a note" id="note" name="note" style="height: 100px"></textarea>
            <label for="note">Payment Note (Optional)</label>
          </div>
          
          <a href="{{ route('order.details', ['order_no' => $order_no]) }}" class="btn btn-outline-secondary w-100">
            Cancel
          </a>
        </div>
      </div>
    </div>
  </div>

  <form method="POST" action="{{ route('update_payment.submit', ['transaction_id' => $order_no]) }}" class="mt-4">
    @csrf
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Handle payment method selection
  const paymentCards = document.querySelectorAll('.payment-card');
  paymentCards.forEach(card => {
    card.addEventListener('click', function() {
      // Remove active class from all cards
      paymentCards.forEach(c => c.classList.remove('active'));
      // Add active class to clicked card
      this.classList.add('active');
      // Update the corresponding radio button
      this.querySelector('input[type="radio"]').checked = true;
    });
  });

  // Handle amount input
  const amountInput = document.getElementById('amount-input');
  const amountDisplay = document.getElementById('display-amount');
  const amountField = document.getElementById('amount');
  
  // Format amount on input
  amountInput.addEventListener('input', function() {
    let value = parseFloat(this.value) || 0;
    const maxAmount = parseFloat('{{ $balance_due }}');
    
    if (value > maxAmount) {
      value = maxAmount;
      this.value = value.toFixed(2);
    }
    
    amountDisplay.textContent = value.toFixed(2);
    amountField.value = value;
  });

  // Format amount on blur
  amountInput.addEventListener('blur', function() {
    const value = parseFloat(this.value) || 0;
    this.value = value.toFixed(2);
  });
});
</script>
</body>
</html>
