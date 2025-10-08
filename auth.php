<?php
// Session सिर्फ तभी start होगा जब पहले start न हुआ हो
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
?>
