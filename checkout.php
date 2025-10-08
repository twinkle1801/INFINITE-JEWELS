<?php
session_start();
ob_start();
include "db.php";
include "header.php";

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch logged-in user info for pre-fill
$sql_user = "SELECT username, email FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$res_user = $stmt_user->get_result()->fetch_assoc();

$username = $res_user['username'];
$email = $res_user['email'];

// Fetch cart items
$sql_cart = "SELECT c.product_id, c.quantity, p.pname, p.price 
             FROM cart c 
             JOIN product p ON c.product_id = p.pid 
             WHERE c.user_id = ?";
$stmt_cart = $conn->prepare($sql_cart);
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$res_cart = $stmt_cart->get_result();

$cart_items = [];
$total_amount = 0;
while ($row = $res_cart->fetch_assoc()) {
    $cart_items[] = $row;
    $total_amount += $row['price'] * $row['quantity'];
}

if (empty($cart_items)) {
    echo "<div class='alert alert-warning'>Your cart is empty.</div>";
    exit();
}

// Handle order placement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email_post = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment_method']);

    // Determine payment status
    $payment_status = ($payment == 'COD') ? 'pending' : 'completed';

    // Insert into orders
    $sql_order = "INSERT INTO orders (user_id, name, email, phone, address, total_amount, payment_method, status, created_at)
                  VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("issssds", $user_id, $name, $email_post, $phone, $address, $total_amount, $payment);

    if(!$stmt_order->execute()){
        echo "Order Insert Error: " . $stmt_order->error;
        exit();
    }

    $order_id = $stmt_order->insert_id;

    // Insert order items
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $product_name = $item['pname'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $subtotal = $price * $quantity;

        $sql_item = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal)
                     VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);
        $stmt_item->bind_param("iisidd", $order_id, $product_id, $product_name, $quantity, $price, $subtotal);
        $stmt_item->execute();
    }

    // Insert into bill_payment
    $sql_bill = "INSERT INTO bill_payment (user_id, total_amount, payment_method, payment_status, billing_date, order_id)
                 VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt_bill = $conn->prepare($sql_bill);
    $stmt_bill->bind_param("idssi", $user_id, $total_amount, $payment, $payment_status, $order_id);

    if(!$stmt_bill->execute()){
        echo "Bill Payment Insert Error: " . $stmt_bill->error;
        exit();
    }

    // Clear cart
    $sql_clear_cart = "DELETE FROM cart WHERE user_id = ?";
    $stmt_clear = $conn->prepare($sql_clear_cart);
    $stmt_clear->bind_param("i", $user_id);
    $stmt_clear->execute();

    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit();
}
?>

<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>
    <form method="post">
        <div class="row">
            <!-- User Details -->
            <div class="col-md-6">
                <h4>Shipping Details</h4>
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="Enter your phone" required>
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control" placeholder="Enter your shipping address" required></textarea>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-6">
                <h4>Order Summary</h4>
                <ul class="list-group mb-3">
                    <?php foreach ($cart_items as $item): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <?php echo htmlspecialchars($item['pname']); ?> (x<?php echo $item['quantity']; ?>)
                            <span>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between font-weight-bold">
                        Total
                        <span>₹<?php echo number_format($total_amount, 2); ?></span>
                    </li>
                </ul>

                <h4>Payment Method</h4>
                <select name="payment_method" class="form-control mb-3" required>
                    <option value="COD">Cash on Delivery</option>
                    <option value="Card">Credit/Debit Card</option>
                    <option value="UPI">UPI</option>
                </select>

                <button type="submit" name="place_order" class="btn btn-primary w-100">Place Order</button>
            </div>
        </div>
    </form>
</div>

<?php include "footer.php"; ?>
