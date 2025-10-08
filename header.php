<?php
// Output buffering start (important for header redirects)
if (session_status() === PHP_SESSION_NONE) {
    ob_start();
    session_start();
}

include "db.php";  // Database connection should be at the very beginning

// Get current selected category and subcategory from URL
$current_cat = isset($_GET['category']) ? intval($_GET['category']) : 0;
$current_sub = isset($_GET['subcategory']) ? intval($_GET['subcategory']) : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MultiShop - Online Shop Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* Dropdown vertical menu hover show */
        #navbar-vertical.show {
            display: block !important;
            animation: fadeInDown 0.3s ease forwards;
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Active item highlight */
        .dropdown-item.active,
        .nav-link.active {
            background-color: #007bff !important;
            color: white !important;
        }

        /* Style for the Sign In button */
        .btn-sign-in {
            background-color:#000000; /* Blue background */
            color: white; /* White text */
            border-radius: 30px; /* Rounded corners */
            padding: 10px 20px; /* Padding for better click area */
            font-size: 14px; /* Slightly larger font size */
            text-transform: uppercase; /* Capital letters */
            font-weight: bold; /* Bold text */
            transition: all 0.3s ease-in-out; /* Smooth transition on hover */
        }

        .btn-sign-in:hover {
            background-color:#CCCC33; /* Darker blue on hover */
            color: #fff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5); /* Glow effect */
        }

        /* Positioning the "Sign In" button to the right */
        .topbar-btn-group {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-secondary py-1 px-xl-5">
            <!-- Removed the unnecessary col-lg-6 d-none d-lg-block section -->
            <div class="d-inline-flex align-items-center d-block d-lg-none">
                <a href="" class="btn px-0 ml-2">
                    <i class="fas fa-shopping-cart text-dark"></i>
                    <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">0</span>
                </a>
            </div>
        </div>
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <a href="index.php" class="text-decoration-none">
                    <h3 class="mb-0">
                        <img src="img/1-removebg-preview.png" width="90" height="50" alt="Infinite Jewels Logo"> INFINITE JEWELS
                    </h3>
                </a>
            </div>

            <div class="col-lg-8 text-center text-lg-right">
                <div class="d-inline-flex align-items-center topbar-btn-group">
                    <div class="btn-group">
                        <?php if (isset($_SESSION['username'])): ?>
                            <span class="text-dark me-2 align-self-center">
                                <i class="fa fa-user"></i>&nbsp;&nbsp;Hello,
                                <?php echo htmlspecialchars($_SESSION['username']); ?>&nbsp;&nbsp;
                            </span>
                            <form method="post" action="logout.php" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-outline-dark">Logout</button>
                            </form>
                        <?php else: ?>
                            <a href="auth.php" class="btn btn-sm btn-sign-in">
                                <i class="fa fa-sign-in"></i>&nbsp;Sign In
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" data-toggle="collapse"
                    href="#navbar-vertical" style="height: 65px; padding: 0 30px;" aria-haspopup="true" aria-expanded="false">
                    <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Categories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light"
                    id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
                    <div class="navbar-nav w-100">
                       <?php
                        // Fetch all categories ordered by name ascending
                        $sql_cat = "SELECT * FROM category ORDER BY cnm ASC";
                        $res_cat = $conn->query($sql_cat);

                        if (!$res_cat) {
                            echo "<p class='px-3 py-2 text-danger'>Error: " . $conn->error . "</p>";
                        } elseif ($res_cat->num_rows > 0) {
                            while ($cat = $res_cat->fetch_assoc()) {
                                $category_id = (int)$cat['cid'];  // Cast to int for safety
                                $category_name = htmlspecialchars($cat['cnm']);  // Sanitize output
                                ?>
                                <a href="shop.php?category=<?php echo urlencode($category_id); ?>"
                                class="nav-item nav-link <?php echo ($current_cat === $category_id) ? 'active' : ''; ?>">
                                    <?php echo ucfirst($category_name); ?>
                                </a>
                                <?php
                            }
                        } else {
                            echo "<p class='px-3 py-2'>No categories found.</p>";
                        }
                        ?>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2">Multi</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Shop</span>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse"
                        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="index.php" class="nav-item nav-link <?php echo ($_SERVER['PHP_SELF'] == '/index.php') ? 'active' : ''; ?>">Home</a>
                            <a href="shop.php" class="nav-item nav-link <?php echo ($_SERVER['PHP_SELF'] == '/shop.php') ? 'active' : ''; ?>">Shop</a>
                            <a href="detail.php" class="nav-item nav-link <?php echo ($_SERVER['PHP_SELF'] == '/detail.php') ? 'active' : ''; ?>">Shop Detail</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages
                                    <i class="fa fa-angle-down mt-1"></i></a>
                                <div class="dropdown-menu bg-primary rounded-0 border-0 m-0">
								<a href="about.php" class="dropdown-item">About Us</a>
                                    <a href="cart.php" class="dropdown-item">Shopping Cart</a>
                                    <a href="checkout.php" class="dropdown-item">Checkout</a>
                                </div>
                            </div>
                            <a href="contact.php" class="nav-item nav-link <?php echo ($_SERVER['PHP_SELF'] == '/contact.php') ? 'active' : ''; ?>">Contact</a>
                        </div>
                        <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                            <?php
                            $cart_count = 0;
                            if (isset($_SESSION['user_id'])) {
                                $uid = $_SESSION['user_id'];
                                $sql_cart_count = "SELECT SUM(quantity) as total FROM cart WHERE user_id = ?";
                                $stmt_cart_count = $conn->prepare($sql_cart_count);
                                $stmt_cart_count->bind_param("i", $uid);
                                $stmt_cart_count->execute();
                                $res_count = $stmt_cart_count->get_result()->fetch_assoc();
                                $cart_count = $res_count['total'] ?? 0;
                            }
                            ?>
                            <a href="cart.php" class="btn px-0 ml-3">
                                <i class="fas fa-shopping-cart text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle"
                                    style="padding-bottom: 2px;">
                                    <?php echo $cart_count; ?>
                                </span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- JQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const navbarVerticalToggle = document.querySelector('a[href="#navbar-vertical"]');
            const navbarVertical = document.getElementById('navbar-vertical');

            navbarVerticalToggle.addEventListener('mouseenter', function () {
                $(navbarVertical).collapse('show');
            });

            navbarVertical.addEventListener('mouseleave', function () {
                $(navbarVertical).collapse('hide');
            });

            navbarVerticalToggle.addEventListener('mouseleave', function () {
                setTimeout(() => {
                    if (!navbarVertical.matches(':hover')) {
                        $(navbarVertical).collapse('hide');
                    }
                }, 300);
            });
        });
    </script>
</body>

</html>
