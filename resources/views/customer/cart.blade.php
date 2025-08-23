<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shopping Cart</title>
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
        
        body {
            background-color: #f9fafb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
            padding-bottom: 2rem;
        }
        
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .cart-title {
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }
        
        .cart-items-count {
            color: var(--text-muted);
            font-size: 1rem;
        }
        
        .clear-cart-btn {
            background-color: #dc3545;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .clear-cart-btn:hover {
            background-color: #bb2d3b;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        
        .cart-table th {
            background-color: var(--light-gray);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
        }
        
        .cart-table td {
            padding: 1rem;
            vertical-align: top;
            border-bottom: 1px solid var(--border-color);
        }
        
        .cart-table tr:last-child td {
            border-bottom: none;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }
        
        .product-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
        }
        
        .product-sku {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .stock-info {
            color: #28a745;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .price {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .quantity-control {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
        }
        
        .quantity-btn {
            background: var(--light-gray);
            border: none;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .quantity-btn:hover {
            background: #e9ecef;
        }
        
        .quantity-input {
            width: 50px;
            height: 36px;
            border: none;
            text-align: center;
            font-weight: 500;
            -moz-appearance: textfield;
        }
        
        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        .remove-btn {
            color: #dc3545;
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
        }
        
        .remove-btn:hover {
            background: rgba(220, 53, 69, 0.1);
        }
        
        .continue-shopping {
            margin-top: 1.5rem;
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        
        .continue-shopping:hover {
            background-color: #3a5ad9;
        }
        
        .summary-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        
        .summary-title {
            font-weight: 700;
            margin-bottom: 1.25rem;
            color: var(--text-dark);
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        
        .summary-label {
            color: var(--text-muted);
        }
        
        .summary-value {
            font-weight: 500;
        }
        
        .summary-divider {
            border-top: 1px solid var(--border-color);
            margin: 1rem 0;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 1.25rem 0;
        }
        
        .checkout-btn {
            background-color: var(--secondary-color);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            width: 100%;
        }
        
        .checkout-btn:hover {
            background-color: #5a32a3;
        }
        
        .security-notice {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 1rem;
            text-align: center;
        }
        
        .copyright {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-align: center;
            margin-top: 2rem;
        }
        
        @media (max-width: 992px) {
            .cart-table {
                display: block;
                overflow-x: auto;
            }
            
            .product-image {
                width: 60px;
                height: 60px;
            }
        }
        
        @media (max-width: 576px) {
            .cart-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .clear-cart-btn {
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="cart-container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-header">
                        <div>
                            <h1 class="cart-title">Shopping Cart</h1>
                            <div class="cart-items-count">Cart Items ({{ count($items ?? []) }})</div>
                        </div>
                        <button class="btn btn-danger clear-cart-btn">
                            <i class="bi bi-trash"></i> Clear Cart
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($items ?? []) as $it)
                                <tr data-pid="{{ $it['product_id'] }}">
                                    <td>
                                        <img src="{{ $it['image_url'] ?? asset('img/default.png') }}" alt="{{ $it['name'] }}" class="product-image">
                                    </td>
                                    <td>
                                        <div class="product-name">{{ $it['name'] }}</div>
                                        <div class="product-sku">{{ $it['sku'] }} @if(!empty($it['variation_name'])) • {{ $it['variation_name'] }} @endif</div>
                                        @if(!empty($it['manages_stock']))
                                            @if(($it['stock_left'] ?? null) !== null)
                                            <div class="stock-info">Only {{ (int)$it['stock_left'] }} left in stock</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <div class="price">CHF {{ number_format((float)($it['unit_price'] ?? 0), 2) }}</div>
                                    </td>
                                    <td>
                                        <div class="quantity-control" data-pid="{{ $it['product_id'] }}">
                                            <button class="quantity-btn qty-minus">-</button>
                                            <input type="number" class="quantity-input qty-input" value="{{ (int)($it['quantity'] ?? 1) }}" min="1">
                                            <button class="quantity-btn qty-plus">+</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="price">CHF {{ number_format((float)($it['line_total'] ?? 0), 2) }}</div>
                                    </td>
                                    <td>
                                        <button class="remove-btn btn-remove" data-pid="{{ $it['product_id'] }}">×</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Your cart is empty.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <a href="{{ url('/?only=featured') }}" class="btn btn-primary continue-shopping">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>
                </div>
                
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="summary-card">
                        <h3 class="summary-title">Order Summary</h3>
                        
                        <div class="summary-item">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value">CHF {{ number_format((float)($summary['subtotal'] ?? 0), 2) }}</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">Shipping</span>
                            <span class="summary-value">Free</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">Tax</span>
                            <span class="summary-value">Calculated at checkout</span>
                        </div>
                        
                        <div class="summary-divider"></div>
                        
                        <div class="summary-total">
                            <span>Estimated Total:</span>
                            <span>CHF {{ number_format((float)($summary['total'] ?? 0), 2) }}</span>
                        </div>
                        
                        <a href="{{ url('/checkout') }}" class="btn checkout-btn w-100 text-center">Proceed to Checkout</a>
                        
                        <div class="security-notice">
                            <div>Secure checkout • 256-bit SSL</div>
                            <div>Free returns within 30 days</div>
                        </div>
                    </div>
                    
                    <div class="copyright">
                        pos - V6.7 | Copyright © 2025 All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const postJson = (url, data) => fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            // Quantity controls
            document.querySelectorAll('.quantity-control').forEach(control => {
                const minusBtn = control.querySelector('.qty-minus');
                const plusBtn = control.querySelector('.qty-plus');
                const input = control.querySelector('.qty-input');
                const pid = control.getAttribute('data-pid');

                const pushUpdate = (qty) => {
                    if (!pid) return;
                    postJson('{{ route('cart.update') }}', { product_id: pid, quantity: qty })
                        .then(r => r.ok ? r.json() : Promise.reject(r))
                        .then(() => window.location.reload())
                        .catch(() => alert('Failed to update quantity.'));
                };

                minusBtn?.addEventListener('click', () => {
                    let value = parseInt(input.value || '1');
                    value = isNaN(value) ? 1 : value;
                    value = Math.max(1, value - 1);
                    input.value = value;
                    pushUpdate(value);
                });

                plusBtn?.addEventListener('click', () => {
                    let value = parseInt(input.value || '1');
                    value = isNaN(value) ? 1 : value;
                    value = value + 1;
                    input.value = value;
                    pushUpdate(value);
                });

                input?.addEventListener('change', () => {
                    let value = parseInt(input.value || '1');
                    value = isNaN(value) ? 1 : value;
                    value = Math.max(1, value);
                    input.value = value;
                    pushUpdate(value);
                });
            });

            // Remove item
            document.querySelectorAll('.btn-remove').forEach(btn => {
                btn.addEventListener('click', () => {
                    const pid = btn.getAttribute('data-pid');
                    if (!pid) return;
                    const row = btn.closest('tr');
                    row.style.opacity = '0.4';
                    postJson('{{ route('cart.remove') }}', { product_id: pid })
                        .then(r => r.ok ? r.json() : Promise.reject(r))
                        .then(() => window.location.reload())
                        .catch(() => { row.style.opacity = '1'; alert('Failed to remove item.'); });
                });
            });

            // Clear cart
            const clearCartBtn = document.querySelector('.clear-cart-btn');
            clearCartBtn?.addEventListener('click', function() {
                if (!confirm('Clear all items from cart?')) return;
                postJson('{{ route('cart.clear') }}', {})
                    .then(r => r.ok ? r.json() : Promise.reject(r))
                    .then(() => window.location.reload())
                    .catch(() => alert('Failed to clear cart.'));
            });
        });
    </script>
</body>
</html>