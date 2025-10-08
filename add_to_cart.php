<?php
session_start();
include "db.php";

// check user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $pid = intval($_POST['pid']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // check if product already in cart
    $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $check->bind_param("ii", $user_id, $pid);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        // update quantity
        $row = $res->fetch_assoc();
        $new_qty = $row['quantity'] + $quantity;

        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update->bind_param("ii", $new_qty, $row['id']);
        $update->execute();
    } else {
        // insert new
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $user_id, $pid, $quantity);
        $insert->execute();
    }

    header("Location: cart.php");
    exit();
}
?>
