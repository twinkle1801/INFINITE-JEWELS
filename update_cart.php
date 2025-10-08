<?php
session_start();
include "db.php";

if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $update->bind_param("ii", $quantity, $cart_id);
    $update->execute();
}

header("Location: cart.php");
exit();
?>
