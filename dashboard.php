<?php
include "header.php";
include "db.php"; // your DB connection file

// Initialize counts
$category_count = 0;
$subcategory_count = 0;
$product_count = 0;
$order_count = 0;
$query_count = 0;
$user_count = 0;

// Category count
$sql = "SELECT COUNT(*) AS total FROM category";
$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $category_count = $row['total'];
} else {
    echo "Category query failed: " . mysqli_error($conn);
}

// Subcategory count
$sql = "SELECT COUNT(*) AS total FROM subcategory";
$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $subcategory_count = $row['total'];
}

// Product count
$sql = "SELECT COUNT(*) AS total FROM product";
$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $product_count = $row['total'];
}

// Orders count
$sql = "SELECT COUNT(*) AS total FROM orders";
$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $order_count = $row['total'];
}

// Customer queries count
$sql = "SELECT COUNT(*) AS total FROM contact_us";
$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $query_count = $row['total'];
}

// Registered users count
$sql = "SELECT COUNT(*) AS total FROM users";
$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $user_count = $row['total'];
}
?>

<style>
    /* Fade-in animation */
    @keyframes fadeInUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .dashboard-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: fadeInUp 0.8s ease forwards;
        border-radius: 12px;
    }

    .dashboard-card:hover {
        transform: translateY(-5px) scale(1.03);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }

    h4 {
        font-weight: 600;
    }

    p {
        font-size: 18px;
        font-weight: 500;
    }
</style>

<h2 class="mb-4">Admin Dashboard</h2>
<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary p-3 dashboard-card">
            <h4>Categories</h4>
            <p><?php echo $category_count; ?> total</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info p-3 dashboard-card">
            <h4>Subcategories</h4>
            <p><?php echo $subcategory_count; ?> total</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success p-3 dashboard-card">
            <h4>Products</h4>
            <p><?php echo $product_count; ?> total</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning p-3 dashboard-card">
            <h4>Orders</h4>
            <p><?php echo $order_count; ?> total</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger p-3 dashboard-card">
            <h4>Messages</h4>
            <p><?php echo $query_count; ?> total</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary p-3 dashboard-card">
            <h4>Registered Users</h4>
            <p><?php echo $user_count; ?> total</p>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
