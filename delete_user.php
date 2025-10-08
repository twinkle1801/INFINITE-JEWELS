<?php
include "auth.php";
include "db.php";

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $sql = "DELETE FROM users WHERE id = $user_id";
    if (mysqli_query($conn, $sql)) {
        header("Location: users.php?msg=deleted");
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
} else {
    header("Location: users.php");
}
?>
