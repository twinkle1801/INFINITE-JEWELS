<?php
session_start();
include "db.php";

if (isset($_GET['id'])) {
    $cart_id = intval($_GET['id']);

    $delete = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $delete->bind_param("i", $cart_id);
    $delete->execute();
}

header("Location: cart.php");
exit();
?>
