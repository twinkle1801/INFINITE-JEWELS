<?php
session_start();
include "db.php";
include "header.php";

// Get order_id from URL
if (!isset($_GET['order_id'])) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order info with user details
$sql_order = "SELECT o.*, u.username, u.email 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              WHERE o.order_id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$order = $stmt_order->get_result()->fetch_assoc();

if (!$order) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    exit();
}

// Fetch order items
$sql_items = "SELECT * FROM order_items WHERE order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$res_items = $stmt_items->get_result();

$order_items = [];
while ($row = $res_items->fetch_assoc()) {
    $order_items[] = $row;
}

// Fetch bill/payment info
$sql_bill = "SELECT * FROM bill_payment WHERE order_id = ?";
$stmt_bill = $conn->prepare($sql_bill);
$stmt_bill->bind_param("i", $order_id);
$stmt_bill->execute();
$bill = $stmt_bill->get_result()->fetch_assoc();
?>

<!-- Font Awesome for buttons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container my-5">
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel" align="center">Order Successful!</h5>
                </div>
                <div class="modal-body text-center">
                    <!-- Green Checkmark with Advanced Animation -->
                    <i class="fa fa-check-circle fa-5x text-success mb-3" id="checkmark" style="animation: bounce 1s ease-in-out; opacity: 0;"></i>
                    <p>Your order has been successfully placed! Please review your order details below.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="showOrderDetailsBtn">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details (Initially Hidden) -->
    <div id="orderDetails" style="display: none;">
        <!-- Order Details Content -->
        <div class="card mb-4">
            <div class="card-header text-dark" style="background-color: #FFF9C4;">Order Details:-</div>
            <div class="card-body">
                <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                <?php if($bill): ?>
                    <p><strong>Payment Status:</strong> <?php echo ucfirst($bill['payment_status']); ?></p>
                <?php endif; ?>
                <p><strong>Order Date:</strong> <?php echo $order['created_at']; ?></p>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header text-dark" style="background-color: #FFF9C4;">Customer Information:-</div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
            </div>
        </div>

        <!-- Ordered Items -->
        <div class="card mb-4">
            <div class="card-header text-dark" style="background-color: #FFF9C4;">Ordered Items:-</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>₹<?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                            <td>₹<?php 
                                $total = array_sum(array_column($order_items, 'subtotal'));
                                echo number_format($total, 2); 
                            ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Info -->
        <?php if($bill): ?>
        <div class="card mb-4">
            <div class="card-header text-dark" style="background-color: #FFF9C4;">Payment Information:-</div>
            <div class="card-body">
                <p><strong>Total Amount:</strong> ₹<?php echo number_format($bill['total_amount'], 2); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($bill['payment_method']); ?></p>
                <p><strong>Payment Status:</strong> <?php echo ucfirst($bill['payment_status']); ?></p>
                <p><strong>Billing Date:</strong> <?php echo $bill['billing_date']; ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Buttons -->
        <div class="text-center mt-4">
            <a href="download_invoice.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary">
                <i class="fa fa-file-invoice"></i> Download Invoice
            </a>
            <a href="index.php" class="btn btn-success">
                <i class="fa fa-home"></i> Continue Shopping
            </a>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Show success modal on page load
    window.onload = function() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'), {
            keyboard: false
        });
        successModal.show();

        // Trigger checkmark animation
        var checkmark = document.getElementById('checkmark');
        checkmark.style.animation = 'bounce 1s ease-in-out, fadeIn 0.5s ease-out forwards';
        checkmark.style.opacity = '1';

        // Show order details after OK is clicked
        document.getElementById('showOrderDetailsBtn').addEventListener('click', function() {
            document.getElementById('orderDetails').style.display = 'block';
            successModal.hide();
        });
    };
</script>

<!-- CSS for Green Checkmark Animation -->
<style>
    @keyframes bounce {
        0% { transform: scale(0); opacity: 0; }
        60% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(1); }
    }

    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }
</style>

<?php include "footer.php"; ?>
