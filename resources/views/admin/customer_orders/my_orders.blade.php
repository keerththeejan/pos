<?php
// Use Laravel's DB connection so we read the same database used by the app
$orders = \DB::table('orders')
    ->orderByDesc('created_at')
    ->get();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4 bg-light">
    <h4>My Orders</h4>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row->order_number) ?></td>
                <td><?= date("M d, Y", strtotime($row->created_at)) ?></td>
                <td><span class="badge badge-secondary"><?= htmlspecialchars($row->status) ?></span></td>
                <td>CHF <?= number_format((float)$row->total, 2) ?></td>
                <td>
                    <a href="<?= url('/admin/customer-orders/my_orders') ?>?order_id=<?= (int)$row->id ?>">View Details</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
