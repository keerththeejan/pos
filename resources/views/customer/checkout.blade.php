<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Complete Your Purchase</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        header {
            grid-column: 1 / -1;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            color: #4a6cf7;
            margin-bottom: 10px;
        }
        
        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
        }
        
        h2 {
            font-size: 1.5rem;
            color: #4a6cf7;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eaeaea;
        }
        
        .section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #4a6cf7;
            box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.1);
        }
        
        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .payment-option {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-option:hover, .payment-option.active {
            border-color: #4a6cf7;
            background: #f8faff;
        }
        
        .payment-option i {
            margin-right: 10px;
            color: #4a6cf7;
            font-size: 20px;
        }
        
        .payment-details {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .payment-option.active .payment-details {
            display: block;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .btn-primary {
            background: #4a6cf7;
            color: white;
            margin-bottom: 15px;
        }
        
        .btn-primary:hover {
            background: #3a5bd9;
        }
        
        .btn-secondary {
            background: transparent;
            color: #4a6cf7;
            border: 1px solid #4a6cf7;
        }
        
        .btn-secondary:hover {
            background: rgba(74, 108, 247, 0.1);
        }
        
        .order-summary {
            background: linear-gradient(135deg, #4a6cf7, #2f54eb);
            color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .order-summary h2 {
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
        }
        
        .total {
            font-size: 1.5rem;
            font-weight: bold;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
        
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        input:invalid, select:invalid {
            border-color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">SHOPPING</div>
            <h1>Complete Your Checkout</h1>
        </header>
        
        <main>
            <div class="section">
                <h2>Contact Information</h2>
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required>
                    <div class="error" id="email-error">Please enter a valid email address</div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required>
                    <div class="error" id="phone-error">Please enter a valid phone number</div>
                </div>
            </div>
            
            <div class="section">
                <h2>Shipping Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="first-name">First Name *</label>
                        <input type="text" id="first-name" name="first_name" required>
                        <div class="error" id="first-name-error">Please enter your first name</div>
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name *</label>
                        <input type="text" id="last-name" name="last_name" required>
                        <div class="error" id="last-name-error">Please enter your last name</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address *</label>
                    <input type="text" id="address" name="address" required>
                    <div class="error" id="address-error">Please enter your address</div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" required>
                        <div class="error" id="city-error">Please enter your city</div>
                    </div>
                    <div class="form-group">
                        <label for="postal-code">Postal Code *</label>
                        <input type="text" id="postal-code" name="postal_code" required>
                        <div class="error" id="postal-code-error">Please enter your postal code</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="country">Country/Region *</label>
                    <select id="country" name="country" required>
                        <option value="">Select a country</option>
                        <option value="switzerland">Switzerland</option>
                        <option value="germany">Germany</option>
                        <option value="france">France</option>
                        <option value="italy">Italy</option>
                        <option value="austria">Austria</option>
                    </select>
                    <div class="error" id="country-error">Please select your country</div>
                </div>
            </div>
            
            <div class="section">
                <h2>Payment Method</h2>
                <div class="payment-options">
                    <div class="payment-option" data-method="card">
                        <p><i class="far fa-credit-card"></i> Credit Card</p>
                        
                        <div class="payment-details" id="credit-card-details">
                            <div class="form-group">
                                <label for="card-number">Card Number *</label>
                                <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" required>
                                <div class="error" id="card-number-error">Please enter a valid card number</div>
                            </div>
                            <div class="form-group">
                                <label for="card-name">Name on Card *</label>
                                <input type="text" id="card-name" name="card_name" required>
                                <div class="error" id="card-name-error">Please enter the name on your card</div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="expiry">Expiry Date *</label>
                                    <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                                    <div class="error" id="expiry-error">Please enter a valid expiry date</div>
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV *</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123" required>
                                    <div class="error" id="cvv-error">Please enter a valid CVV</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="payment-option" data-method="paypal">
                        <p><i class="fab fa-paypal"></i> PayPal</p>
                        <div class="payment-details" id="paypal-details">
                            <p>You will be redirected to PayPal to complete your payment securely.</p>
                        </div>
                    </div>
                    
                    <div class="payment-option" data-method="cod">
                        <p><i class="fas fa-money-bill-wave"></i> Cash on Delivery</p>
                        <div class="payment-details" id="cod-details">
                            <p>Please have the exact amount ready for our delivery personnel.</p>
                            <p>An additional CHF 2.00 processing fee applies to cash payments.</p>
                        </div>
                    </div>
                </div>
                <div class="error" id="payment-method-error">Please select a payment method</div>
            </div>
            
            <div class="section">
                <h2>Order Notes (Optional)</h2>
                <textarea name="order_notes" rows="4" placeholder="Special instructions for delivery or order"></textarea>
            </div>
        </main>
        
        <aside>
            <div class="order-summary">
                <h2>Order Summary</h2>
                
                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span id="subtotal">CHF {{ number_format($summary['subtotal'] ?? 0, 2) }}</span>
                </div>
                
                <div class="summary-item">
                    <span>Shipping:</span>
                    <span id="shipping-cost">CHF {{ number_format($summary['shipping'] ?? 0, 2) }}</span>
                </div>
                
                <div class="summary-item">
                    <span>Tax:</span>
                    <span id="tax-amount">CHF {{ number_format($summary['tax'] ?? 0, 2) }}</span>
                </div>
                
                <div class="summary-item total">
                    <span>Total:</span>
                    <span id="total-amount">CHF {{ number_format($summary['total'] ?? 0, 2) }}</span>
                </div>
                
                <form id="checkout-form" action="{{ route('checkout.place') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" id="payment-method">
                    <button type="submit" class="btn btn-primary" id="place-order-btn">Place Order</button>
                </form>
                <button class="btn btn-secondary" id="return-to-cart">Return to Cart</button>
            </div>
            
            <div class="section">
                <h3>Order Items</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="order-items">
                        @forelse(($items ?? []) as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>CHF {{ number_format($item['unit_price'], 2) }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>CHF {{ number_format($item['line_total'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No items in cart</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </aside>
    </div>

    <script>
        // Sample cart data (replace with your actual cart data)
        const cartItems = [
            { id: 1, name: "Sample Product 1", price: 29.99, quantity: 2 },
            { id: 2, name: "Sample Product 2", price: 49.99, quantity: 1 }
        ];

        // Initialize the checkout page
        document.addEventListener('DOMContentLoaded', function() {
            // Set up payment method selection
            setupPaymentMethods();
            
            // Set up form validation
            setupFormValidation();
            
            // Set up return to cart button
            document.getElementById('return-to-cart').addEventListener('click', function() {
                window.history.back();
            });
        });

        // Populate order items in the table
        function populateOrderItems() {
            const orderItemsContainer = document.getElementById('order-items');
            orderItemsContainer.innerHTML = '';
            
            if (cartItems.length === 0) {
                orderItemsContainer.innerHTML = '<tr><td colspan="4" class="text-center">No items in cart</td></tr>';
                return;
            }
            
            cartItems.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>CHF ${item.price.toFixed(2)}</td>
                    <td>${item.quantity}</td>
                    <td>CHF ${(item.price * item.quantity).toFixed(2)}</td>
                `;
                orderItemsContainer.appendChild(row);
            });
        }

        // Server renders summary values; no JS summary calculation needed here

        // Set up payment method selection
        function setupPaymentMethods() {
            const paymentOptions = document.querySelectorAll('.payment-option');
            
            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove active class from all options
                    paymentOptions.forEach(opt => {
                        opt.classList.remove('active');
                        const details = opt.querySelector('.payment-details');
                        if (details) details.style.display = 'none';
                    });
                    
                    // Add active class to clicked option
                    this.classList.add('active');
                    const details = this.querySelector('.payment-details');
                    if (details) details.style.display = 'block';
                    
                    // Update hidden payment method input
                    document.getElementById('payment-method').value = this.dataset.method;
                    
                    // Clear payment method error
                    document.getElementById('payment-method-error').style.display = 'none';
                });
            });
        }

        // Set up form validation
        function setupFormValidation() {
            const form = document.getElementById('checkout-form');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate all required fields
                const isValid = validateForm();
                
                if (isValid) {
                    // Show loading state and submit form to server
                    const submitBtn = document.getElementById('place-order-btn');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Processing...';

                    // Collect fields and append as hidden inputs
                    const makeHidden = (name, value) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = name;
                        input.value = value ?? '';
                        return input;
                    };

                    // Build shipping/billing summary strings
                    const email = (document.getElementById('email')?.value || '').trim();
                    const phone = (document.getElementById('phone')?.value || '').trim();
                    const first = (document.getElementById('first-name')?.value || '').trim();
                    const last = (document.getElementById('last-name')?.value || '').trim();
                    const address = (document.getElementById('address')?.value || '').trim();
                    const city = (document.getElementById('city')?.value || '').trim();
                    const postal = (document.getElementById('postal-code')?.value || '').trim();
                    const country = (document.getElementById('country')?.value || '').trim();
                    const notes = (document.querySelector('textarea[name="order_notes"]')?.value || '').trim();

                    const shippingAddress = `${first} ${last}\n${address}\n${city} ${postal}\n${country}\nEmail: ${email}\nPhone: ${phone}`.trim();

                    form.appendChild(makeHidden('first_name', first));
                    form.appendChild(makeHidden('last_name', last));
                    form.appendChild(makeHidden('shipping_address', shippingAddress));
                    form.appendChild(makeHidden('billing_address', shippingAddress));
                    form.appendChild(makeHidden('order_notes', notes));

                    form.submit();
                }
            });
            
            // Add input event listeners to clear errors when user starts typing
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const errorId = this.id + '-error';
                    const errorElement = document.getElementById(errorId);
                    if (errorElement) {
                        errorElement.style.display = 'none';
                        this.style.borderColor = '#ddd';
                    }
                });
            });
        }

        // Validate the entire form
        function validateForm() {
            let isValid = true;
            
            // Validate contact information
            if (!validateField('email', value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value))) {
                isValid = false;
            }
            
            if (!validateField('phone', value => value.length >= 10)) {
                isValid = false;
            }
            
            // Validate shipping information
            if (!validateField('first-name', value => value.trim().length > 0)) {
                isValid = false;
            }
            
            if (!validateField('last-name', value => value.trim().length > 0)) {
                isValid = false;
            }
            
            if (!validateField('address', value => value.trim().length > 0)) {
                isValid = false;
            }
            
            if (!validateField('city', value => value.trim().length > 0)) {
                isValid = false;
            }
            
            if (!validateField('postal-code', value => value.trim().length > 0)) {
                isValid = false;
            }
            
            if (!validateField('country', value => value !== '')) {
                isValid = false;
            }
            
            // Validate payment method
            const paymentMethod = document.getElementById('payment-method').value;
            if (!paymentMethod) {
                document.getElementById('payment-method-error').style.display = 'block';
                isValid = false;
            }
            
            // Validate credit card details if credit card is selected
            if (paymentMethod === 'credit-card') {
                if (!validateField('card-number', value => value.replace(/\s/g, '').length === 16)) {
                    isValid = false;
                }
                
                if (!validateField('card-name', value => value.trim().length > 0)) {
                    isValid = false;
                }
                
                if (!validateField('expiry', value => /^\d{2}\/\d{2}$/.test(value))) {
                    isValid = false;
                }
                
                if (!validateField('cvv', value => value.length >= 3 && value.length <= 4)) {
                    isValid = false;
                }
            }
            
            return isValid;
        }

        // Validate a specific field
        function validateField(fieldId, validationFn) {
            const field = document.getElementById(fieldId);
            const errorElement = document.getElementById(fieldId + '-error');
            
            if (!field || !validationFn(field.value)) {
                if (errorElement) {
                    errorElement.style.display = 'block';
                }
                field.style.borderColor = 'red';
                return false;
            }
            
            if (errorElement) {
                errorElement.style.display = 'none';
            }
            field.style.borderColor = '#ddd';
            return true;
        }

        // Auto-format credit card number
        const cardNumber = document.getElementById('card-number');
        if (cardNumber) {
            cardNumber.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s+/g, '');
                if (value.length > 0) {
                    value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
                }
                e.target.value = value;
            });
        }
        
        // Auto-format expiry date
        const expiry = document.getElementById('expiry');
        if (expiry) {
            expiry.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                e.target.value = value;
            });
        }
        
        // Auto-format CVV
        const cvv = document.getElementById('cvv');
        if (cvv) {
            cvv.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '').substring(0, 4);
            });
        }
    </script>
</body>
</html>