<?php
include 'header.php';
include 'db.php';

// Status update logic
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $update = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
    $update->bind_param("si", $status, $order_id);
    if($update->execute()){
        $msg = "Order status updated!";
    } else {
        $msg = "Failed to update status!";
    }
}

// Fetch orders with user info and bill_payment info
$ordersResult = $conn->query("SELECT o.*, u.username, 
    b.total_amount AS bill_total, b.payment_method AS bill_payment_method, b.payment_status
    FROM orders o 
    JOIN users u ON o.user_id = u.id
    LEFT JOIN bill_payment b ON o.order_id = b.order_id
    ORDER BY o.order_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Orders Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Orders Management</h2>
    <?php if (isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Order Payment</th>
                <th>Bill Total</th>
                <th>Bill Payment Method</th>
                <th>Payment Status</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Items</th>
                <th>Order Total</th>
            </tr>
        </thead>
        <tbody>
        <?php if($ordersResult->num_rows > 0) {
            while($order = $ordersResult->fetch_assoc()) { 
                // Fetch order items for this order
                $order_id = $order['order_id'];
                $itemsResult = $conn->query("SELECT product_name, quantity, price, subtotal 
                    FROM order_items WHERE order_id = $order_id");
        ?>
            <tr>
                <td><?= $order['order_id'] ?></td>
                <td><?= $order['username'] ?></td>
                <td><?= htmlspecialchars($order['payment_method']) ?></td>
                <td>₹<?= $order['bill_total'] ?? '0.00' ?></td>
                <td><?= htmlspecialchars($order['bill_payment_method'] ?? '-') ?></td>
                <td><?= ucfirst($order['payment_status'] ?? '-') ?></td>
                <td>
                    <form method="post" class="d-flex">
                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                        <select name="status" class="form-select me-2">
                            <option value="pending" <?= ($order['status']=="pending"?"selected":"") ?>>Pending</option>
                            <option value="processing" <?= ($order['status']=="processing"?"selected":"") ?>>Processing</option>
                            <option value="completed" <?= ($order['status']=="completed"?"selected":"") ?>>Completed</option>
                            <option value="cancelled" <?= ($order['status']=="cancelled"?"selected":"") ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </td>
                <td><?= $order['created_at'] ?></td>
                <td>
                    <ul>
                    <?php while($item = $itemsResult->fetch_assoc()) { ?>
                        <li><?= $item['product_name'] ?> × <?= $item['quantity'] ?> = ₹<?= $item['subtotal'] ?></li>
                    <?php } ?>
                    </ul>
                </td>
                <td>₹<?= $order['total_amount'] ?></td>
            </tr>
        <?php } 
        } else { ?>
            <tr>
                <td colspan="10" class="text-center">No orders found</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
