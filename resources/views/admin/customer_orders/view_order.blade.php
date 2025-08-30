
<?php
$conn = new PDO("mysql:host=localhost;dbname=multi_pos_mph", "root", "");

// Get order id
$order_id = $_GET['id'];

// Fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch order items
$stmtItems = $conn->prepare("SELECT oi.*, p.name as product_name, p.sku 
                             FROM order_items oi 
                             JOIN products p ON p.id = oi.product_id 
                             WHERE oi.order_id = ?");
$stmtItems->execute([$order_id]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order #<?= $order['id'] ?> Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4 bg-light">
    <a href="my_orders.php" class="btn btn-secondary btn-sm">← Back to Orders</a>
    <a href="cancel_order.php?id=<?= $order['id'] ?>" class="btn btn-danger btn-sm">Cancel Order</a>

    <h4 class="mt-3">Order #<?= $order['id'] ?> Details</h4>
    <div class="row">
        <div class="col-md-6">
            <h5>Order Information</h5>
            <p><b>Order Number:</b> <?= $order['order_number'] ?></p>
            <p><b>Date:</b> <?= date("F d, Y, h:i a", strtotime($order['created_at'])) ?></p>
            <p><b>Status:</b> <span class="badge badge-warning">Pending</span></p>
            <p><b>Payment Method:</b> <?= $order['payment_method'] ?></p>
            <p><b>Payment Status:</b> <span class="badge badge-warning">Pending</span></p>
        </div>
        <div class="col-md-6">
            <h5>Customer Information</h5>
            <p><b>Name:</b> <?= $order['customer_name'] ?></p>
            <p><b>Email:</b> <?= $order['customer_email'] ?></p>
            <p><b>Shipping Address:</b> <?= $order['shipping_address'] ?></p>
            <p><b>Billing Address:</b> <?= $order['billing_address'] ?></p>
        </div>
    </div>

    <h5 class="mt-4">Order Items</h5>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $subtotal = 0;
        foreach ($items as $it): 
            $line_total = $it['price'] * $it['quantity'];
            $subtotal += $line_total;
        ?>
            <tr>
                <td><?= $it['product_name'] ?></td>
                <td><?= $it['sku'] ?></td>
                <td>CHF <?= number_format($it['price'], 2) ?></td>
                <td><?= $it['quantity'] ?></td>
                <td>CHF <?= number_format($line_total, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-right">
        <p><b>Subtotal:</b> CHF <?= number_format($subtotal, 2) ?></p>
        <p><b>Tax:</b> CHF <?= number_format($order['tax'], 2) ?></p>
        <p><b>Shipping:</b> CHF <?= number_format($order['shipping'], 2) ?></p>
        <h5><b>Total:</b> CHF <?= number_format($order['total'], 2) ?></h5>
    </div>
</body>
</html>
