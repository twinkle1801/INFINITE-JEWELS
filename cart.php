<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT c.id, c.quantity, p.pname, p.price, p.image 
        FROM cart c 
        JOIN product p ON c.product_id = p.pid 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include "header.php"; ?>

<style>
/* Minimal animation cart page */
.cart-card {
    border-radius: 10px;
    overflow: hidden;
    transition: box-shadow 0.2s ease; /* subtle shadow only */
}
.cart-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.cart-img {
    max-width: 80px;
    border-radius: 8px;
    transition: none; /* no scaling */
}
.cart-total {
    font-weight: bold;
    font-size: 18px;
    color: #28a745;
}
.btn-update {
    background: #ffc107;
    border: none;
    color: #000;
    font-weight: bold;
    border-radius: 6px;
    padding: 5px 12px;
}
.btn-remove {
    background: #dc3545;
    border: none;
    color: #fff;
    border-radius: 6px;
    padding: 5px 12px;
}
.btn-checkout {
    background: #ff9900; /* solid color */
    border: none;
    font-size: 18px;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: bold;
    transition: none; /* no hover scaling */
}
</style>

<div class="container mt-5">
    <h2 class="mb-4 text-center"><i class="fa fa-shopping-cart" aria-hidden="true"></i> My Cart</h2>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grand_total = 0;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $total = $row['price'] * $row['quantity'];
                        $grand_total += $total;
                ?>
                <tr class="cart-card">
                    <td><img src="admin/image/<?php echo $row['image']; ?>" class="cart-img"></td>
                    <td><?php echo $row['pname']; ?></td>
                    <td>₹<?php echo number_format($row['price']); ?></td>
                    <td>
                        <form action="update_cart.php" method="POST" class="d-flex justify-content-center gap-2">
                            <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                            <input type="number" name="quantity" 
                                   value="<?php echo $row['quantity']; ?>" 
                                   min="1" class="form-control w-50 text-center">
                            <button type="submit" class="btn btn-update">Update</button>
                        </form>
                    </td>
                    <td class="cart-total">₹<?php echo number_format($total); ?></td>
                    <td>
                        <a href="remove_cart.php?id=<?php echo $row['id']; ?>" class="btn btn-remove">Remove</a>
                    </td>
                </tr>
                <?php 
                    } 
                } else { 
                    echo "<tr><td colspan='6'><h5 class='text-danger'>?? Your cart is empty!</h5></td></tr>";
                } 
                ?>
            </tbody>
        </table>
    </div>

    <!-- Grand Total & Checkout -->
    <div class="text-end mt-4">
        <h3 class="mb-3">Grand Total: <span class="text-success">₹<?php echo number_format($grand_total); ?></span></h3>
        <?php if ($grand_total > 0) { ?>
            <a href="checkout.php" class="btn btn-checkout">Proceed to Checkout <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
        <?php } ?>
    </div>
</div>

<?php include "footer.php"; ?>
