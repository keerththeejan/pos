<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4a6cf7;
            --secondary-color: #6f42c1;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
            --text-dark: #212529;
            --text-muted: #6c757d;
        }
        body { background-color: #f9fafb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: var(--text-dark); }
        .page-title { font-weight: 700; margin: 1rem 0 1.25rem; }
        .card-clean { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); }
        .card-clean .card-header { background: var(--light-gray); border-bottom: 1px solid var(--border-color); font-weight: 600; }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background-color: #3a5ad9; border-color: #3a5ad9; }
        .btn-outline-secondary:hover { background-color: #eef2ff; }
        .summary-title { font-weight: 700; }
        .summary-item { display:flex; justify-content: space-between; margin-bottom: .5rem; }
        .summary-divider { border-top: 1px solid var(--border-color); margin: .75rem 0; }
        .summary-total { display:flex; justify-content: space-between; font-weight: 700; font-size: 1.05rem; }
        .lock-icon { margin-right: .5rem; }
        .order-items img { width: 48px; height: 48px; object-fit: cover; border: 1px solid var(--border-color); border-radius: 6px; }
        .coupon .form-control { border-right: 0; }
        .coupon .btn { border-left: 0; }
    </style>
</head>
<body>
<div class="container py-4">
    <h1 class="page-title">Checkout</h1>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card card-clean mb-3">
                <div class="card-header">Shipping & Billing Information</div>
                <div class="card-body">
                    <form id="checkout-form" method="POST" action="{{ route('checkout.place') }}">
                        @csrf
                        <div class="row g-3 align-items-center mb-2">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Full Name<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" name="customer_name" placeholder="Enter your full name" value="{{ old('customer_name') }}" required>
                                @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Mobile Number<span class="text-danger"> *</span></label>
                                <input type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile" placeholder="e.g. +41 79 123 45 67" value="{{ old('mobile') }}" required pattern="^[0-9+\-\s]{7,20}$" maxlength="20" title="Enter a valid phone number (digits, +, -, spaces).">
                                @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 align-items-center mb-2">
                            <div class="col-12 col-md-8">
                                <label class="form-label">Shipping Address</label>
                                <textarea class="form-control @error('shipping_address') is-invalid @enderror" name="shipping_address" rows="3" placeholder="Street, City, Postal Code, Country" required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary w-100"><i class="bi bi-geo-alt me-1"></i>Manage</button>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center mb-2">
                            <div class="col-12 col-md-8">
                                <label class="form-label">Billing Address</label>
                                <textarea class="form-control @error('billing_address') is-invalid @enderror" name="billing_address" rows="3" placeholder="Street, City, Postal Code, Country" required>{{ old('billing_address') }}</textarea>
                                @error('billing_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary w-100"><i class="bi bi-geo-alt me-1"></i>Manage</button>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="sameAs" checked>
                            <label class="form-check-label" for="sameAs">Shipping address same as billing address</label>
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold mb-1">Payment Method</div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="pm_cod" value="cod" checked>
                                <label class="form-check-label" for="pm_cod">Cash on Delivery</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="pm_card" value="card">
                                <label class="form-check-label" for="pm_card">Credit/Debit Card</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="pm_paypal" value="paypal">
                                <label class="form-check-label" for="pm_paypal">PayPal</label>
                            </div>

                            <div id="card-fields" class="mt-3" style="display:none;">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Name on Card<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control @error('card_name') is-invalid @enderror" name="card_name" value="{{ old('card_name') }}" placeholder="As printed on card">
                                        @error('card_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Card Number<span class="text-danger"> *</span></label>
                                        <input type="text" inputmode="numeric" autocomplete="cc-number" class="form-control @error('card_number') is-invalid @enderror" name="card_number" value="{{ old('card_number') }}" placeholder="1234 5678 9012 3456" maxlength="19">
                                        @error('card_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <label class="form-label">Exp. Month<span class="text-danger"> *</span></label>
                                        <input type="number" min="1" max="12" class="form-control @error('card_exp_month') is-invalid @enderror" name="card_exp_month" value="{{ old('card_exp_month') }}" placeholder="MM">
                                        @error('card_exp_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <label class="form-label">Exp. Year<span class="text-danger"> *</span></label>
                                        <input type="number" min="{{ date('Y') }}" max="{{ date('Y') + 15 }}" class="form-control @error('card_exp_year') is-invalid @enderror" name="card_exp_year" value="{{ old('card_exp_year') }}" placeholder="YYYY">
                                        @error('card_exp_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">CVV<span class="text-danger"> *</span></label>
                                        <input type="password" inputmode="numeric" class="form-control @error('card_cvv') is-invalid @enderror" name="card_cvv" value="" placeholder="3-4 digits" maxlength="4">
                                        @error('card_cvv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-muted small mt-2">We never store full card numbers. Payments are processed securely.</div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control" name="order_notes" rows="3" placeholder="Special instructions for delivery or order"></textarea>
                        </div>

                        <div class="card card-clean mt-4">
                            <div class="card-header">Order Items</div>
                            <div class="card-body p-0">
                                <div class="table-responsive order-items">
                                    <table class="table mb-0 align-middle">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse(($items ?? []) as $it)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <img src="{{ $it['image_url'] ?? asset('img/default.png') }}" alt="{{ $it['name'] }}">
                                                        <div>
                                                            <div class="fw-semibold">{{ $it['name'] }}</div>
                                                            <div class="text-muted small">{{ $it['sku'] }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>CHF {{ number_format((float)($it['unit_price'] ?? 0), 2) }}</td>
                                                <td>{{ (int)($it['quantity'] ?? 1) }}</td>
                                                <td>CHF {{ number_format((float)($it['line_total'] ?? 0), 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No items in cart</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-lg-none my-3">
                            <button type="submit" class="btn btn-primary w-100 py-2"><i class="bi bi-lock-fill lock-icon"></i>Place Order</button>
                            <div class="text-center mt-2"><a href="{{ route('cart.show') }}" class="text-decoration-none">← Return to Cart</a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-clean mb-3">
                <div class="card-body">
                    <div class="summary-title mb-2">Order Summary</div>
                    <div class="summary-item"><span class="text-muted">Subtotal:</span><span>CHF {{ number_format((float)($summary['subtotal'] ?? 0), 2) }}</span></div>
                    <div class="summary-item"><span class="text-muted">Shipping:</span><span>Free</span></div>
                    <div class="summary-item"><span class="text-muted">Tax ({{ (int)($summary['tax_rate'] ?? 0) }}%):</span><span>CHF {{ number_format((float)($summary['tax'] ?? 0), 2) }}</span></div>
                    <div class="summary-divider"></div>
                    <div class="summary-total"><span>Total:</span><span>CHF {{ number_format((float)($summary['total'] ?? 0), 2) }}</span></div>
                    <button form="checkout-form" type="submit" class="btn btn-primary w-100 py-2 mt-2"><i class="bi bi-lock-fill lock-icon"></i>Place Order</button>
                    <div class="text-center mt-2"><a href="{{ route('cart.show') }}" class="text-decoration-none">← Return to Cart</a></div>
                </div>
            </div>

            <div class="card card-clean coupon">
                <div class="card-body">
                    <div class="summary-title mb-2">Have a coupon?</div>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Coupon code" aria-label="Coupon code">
                        <button class="btn btn-outline-secondary" type="button">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Copy shipping to billing when "same as" is checked
    const sameAs = document.getElementById('sameAs');
    if (sameAs) {
        const ship = document.querySelector('textarea[name="shipping_address"]');
        const bill = document.querySelector('textarea[name="billing_address"]');
        const sync = () => { if (sameAs.checked) bill.value = ship.value; };
        ship?.addEventListener('input', sync);
        sameAs.addEventListener('change', sync);
        sync();
    }

    const checkoutForm = document.getElementById('checkout-form');
    checkoutForm?.addEventListener('submit', function (e) {
        const ok = window.confirm('Order confirm?');
        if (!ok) {
            e.preventDefault();
            e.stopPropagation();
            return; // stay on checkout
        }
        // Allow normal form submission; backend will save and redirect to /order-details?order_no=...
    });


    // Payment method toggle: show/hide card fields and toggle required attributes
    (function(){
        const pmRadios = document.querySelectorAll('input[name="payment_method"]');
        const cardBox = document.getElementById('card-fields');
        const cardName = document.querySelector('input[name="card_name"]');
        const cardNumber = document.querySelector('input[name="card_number"]');
        const cardExpMonth = document.querySelector('input[name="card_exp_month"]');
        const cardExpYear = document.querySelector('input[name="card_exp_year"]');
        const cardCvv = document.querySelector('input[name="card_cvv"]');

        function setCardRequired(on){
            [cardName, cardNumber, cardExpMonth, cardExpYear, cardCvv].forEach(el => { if (el) el.required = !!on; });
        }

        function onPMChange(){
            const val = document.querySelector('input[name="payment_method"]:checked')?.value;
            const show = (val === 'card');
            if (cardBox) cardBox.style.display = show ? '' : 'none';
            setCardRequired(show);
        }

        pmRadios.forEach(r => r.addEventListener('change', onPMChange));
        onPMChange(); // init on load

        // Light formatting: keep digits only, space every 4 for display
        cardNumber?.addEventListener('input', function(){
            const digits = this.value.replace(/\D+/g,'').slice(0,19);
            this.value = digits.replace(/(.{4})/g,'$1 ').trim();
        });
        cardCvv?.addEventListener('input', function(){
            this.value = this.value.replace(/\D+/g,'').slice(0,4);
        });
        cardExpMonth?.addEventListener('input', function(){
            let v = parseInt(this.value||'');
            if (isNaN(v)) return; if (v<1) v=1; if (v>12) v=12; this.value = String(v);
        });
    })();
</script>




</body>
</html>
