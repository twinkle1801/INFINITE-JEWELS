<?php
session_start();
include "header.php";
include "db.php";

// Pagination setup
$limit = 9;  // products per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filters initialization
$conditions = [];
$params = [];
$types = "";

// Category filter (single numeric)
if (!empty($_GET['category']) && is_numeric($_GET['category'])) {
    $conditions[] = "cid = ?";
    $params[] = (int)$_GET['category'];
    $types .= "i";
}

// Price ranges for checkbox filter
$priceRanges = [
    '0-10000' => '₹0 - ₹10,000',
    '10001-20000' => '₹10,001 - ₹20,000',
    '20001-30000' => '₹20,001 - ₹30,000',
    '30001-40000' => '₹30,001 - ₹40,000',
    '40001-50000' => '₹40,001 - ₹50,000',
    '50001-1000000' => '₹50,001+'
];

// Process price checkbox filter
if (!empty($_GET['price_range']) && is_array($_GET['price_range'])) {
    $priceConditions = [];
    foreach ($_GET['price_range'] as $range) {
        if (preg_match('/^(\d+)-(\d+)$/', $range, $matches)) {
            $priceConditions[] = "(price BETWEEN ? AND ?)";
            $params[] = (float)$matches[1];
            $params[] = (float)$matches[2];
            $types .= "dd";
        }
    }
    if (!empty($priceConditions)) {
        $conditions[] = "(" . implode(" OR ", $priceConditions) . ")";
    }
}

// Subcategory filter (multiple checkboxes)
if (!empty($_GET['subcategory']) && is_array($_GET['subcategory'])) {
    $subcat_ids = array_filter($_GET['subcategory'], function($id) {
        return is_numeric($id);
    });
    if (!empty($subcat_ids)) {
        $placeholders = implode(',', array_fill(0, count($subcat_ids), '?'));
        $conditions[] = "sid IN ($placeholders)";
        foreach ($subcat_ids as $id) {
            $params[] = (int)$id;
            $types .= "i";
        }
    }
}

// Sorting
$sort_sql = " ORDER BY pid DESC";  // default latest
if (!empty($_GET['sort'])) {
    switch ($_GET['sort']) {
        case "popularity":
            $sort_sql = " ORDER BY qty DESC";
            break;
        case "rating":
            $sort_sql = " ORDER BY rating DESC";
            break;
        case "latest":
            $sort_sql = " ORDER BY pid DESC";
            break;
    }
}

// Build SQL query
$sql = "SELECT * FROM product";
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= $sort_sql;
$sql .= " LIMIT ? OFFSET ?";

// Add pagination params
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

// Prepare statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("SQL Prepare error: " . htmlspecialchars($conn->error));
}
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Count total rows for pagination
$count_sql = "SELECT COUNT(*) FROM product";
if (!empty($conditions)) {
    $count_sql .= " WHERE " . implode(" AND ", $conditions);
}
$count_stmt = $conn->prepare($count_sql);
if ($count_stmt === false) {
    die("Count Prepare error: " . htmlspecialchars($conn->error));
}
if (!empty($conditions)) {
    $count_types = substr($types, 0, -2);
    $count_params = array_slice($params, 0, -2);
    if (!empty($count_types)) {
        $count_stmt->bind_param($count_types, ...$count_params);
    }
}
$count_stmt->execute();
$count_stmt->bind_result($total_products);
$count_stmt->fetch();
$count_stmt->close();

$total_pages = ceil($total_products / $limit);

// Helper function to keep URL params
function buildUrl($paramsToAdd = []) {
    $params = $_GET;
    foreach ($paramsToAdd as $key => $value) {
        $params[$key] = $value;
    }
    $queryParts = [];
    foreach ($params as $k => $v) {
        if (is_array($v)) {
            foreach ($v as $val) {
                $queryParts[] = urlencode($k) . '[]=' . urlencode($val);
            }
        } else {
            $queryParts[] = urlencode($k) . '=' . urlencode($v);
        }
    }
    return "?" . implode("&", $queryParts);
}
?>

<!-- Breadcrumb Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="#">Home</a>
                <a class="breadcrumb-item text-dark" href="#">Shop</a>
                <span class="breadcrumb-item active">Shop List</span>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Shop Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <!-- Shop Sidebar Start -->
        <div class="col-lg-3 col-md-4">
            <div class="bg-light p-4 mb-30">
                <form method="GET" id="filterForm">
                    <!-- Preserve category & sort -->
                    <input type="hidden" name="category" value="<?php echo isset($_GET['category']) ? htmlspecialchars($_GET['category']) : ''; ?>">
                    <input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : ''; ?>">

                    <!-- Subcategory Filter -->
                    <?php if (!empty($_GET['category']) && is_numeric($_GET['category'])): ?>
                        <h6>Filter by Subcategory</h6>
                        <?php
                        $cid = (int)$_GET['category'];
                        $subcat_sql = "SELECT * FROM subcategory WHERE cid = $cid";
                        $subcat_res = $conn->query($subcat_sql);
                        if ($subcat_res && $subcat_res->num_rows > 0) {
                            while ($sc = $subcat_res->fetch_assoc()) {
                                $checked = (isset($_GET['subcategory']) && in_array($sc['sid'], $_GET['subcategory'])) ? "checked" : "";
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input filter-checkbox" type="checkbox" name="subcategory[]" value="' . $sc['sid'] . '" id="subcat_' . $sc['sid'] . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="subcat_' . $sc['sid'] . '">' . htmlspecialchars($sc['snm']) . '</label>';
                                echo '</div>';
                            }
                        }
                        ?>
                        <hr>
                    <?php endif; ?>

                    <!-- Price Filter -->
                    <h6>Filter by Price</h6>
                    <?php
                    foreach ($priceRanges as $range => $label) {
                        $checked = (isset($_GET['price_range']) && in_array($range, $_GET['price_range'])) ? "checked" : "";
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input filter-checkbox" type="checkbox" name="price_range[]" value="' . $range . '" id="price_' . $range . '" ' . $checked . '>';
                        echo '<label class="form-check-label" for="price_' . $range . '">' . $label . '</label>';
                        echo '</div>';
                    }
                    ?>
                </form>
            </div>
        </div>
        <!-- Shop Sidebar End -->

        <!-- Shop Product Start -->
        <div class="col-lg-9 col-md-8">
            <div class="row pb-3">
                <div class="col-12 pb-1">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <button class="btn btn-sm btn-light"><i class="fa fa-th-large"></i></button>
                            <button class="btn btn-sm btn-light ml-2"><i class="fa fa-bars"></i></button>
                        </div>
                        <div class="ml-2 d-flex align-items-center">
                            <div class="btn-group">
                                <form method="GET" id="sortForm">
                                    <input type="hidden" name="category" value="<?php echo isset($_GET['category']) ? htmlspecialchars($_GET['category']) : ''; ?>">
                                    <?php
                                    if (isset($_GET['subcategory']) && is_array($_GET['subcategory'])) {
                                        foreach ($_GET['subcategory'] as $sc) {
                                            echo '<input type="hidden" name="subcategory[]" value="' . htmlspecialchars($sc) . '">';
                                        }
                                    }
                                    if (isset($_GET['price_range']) && is_array($_GET['price_range'])) {
                                        foreach ($_GET['price_range'] as $pr) {
                                            echo '<input type="hidden" name="price_range[]" value="' . htmlspecialchars($pr) . '">';
                                        }
                                    }
                                    ?>
                                    <select class="form-control form-control-sm" name="sort" onchange="this.form.submit()">
                                        <option value="">Sort By</option>
                                        <option value="latest" <?php if(isset($_GET['sort']) && $_GET['sort'] === 'latest') echo "selected"; ?>>Latest</option>
                                        <option value="popularity" <?php if(isset($_GET['sort']) && $_GET['sort'] === 'popularity') echo "selected"; ?>>Popularity</option>
                                        <option value="rating" <?php if(isset($_GET['sort']) && $_GET['sort'] === 'rating') echo "selected"; ?>>Best Rating</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): 
                        $imagePath = "admin/image/" . $row['image']; 
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 pb-1">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                <!-- Image Clickable -->
                                <a href="detail.php?pid=<?php echo $row['pid']; ?>">
    <div style="width:100%; height:300px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
        <img src="<?php echo htmlspecialchars($imagePath); ?>" 
             alt="<?php echo htmlspecialchars($row['pname']); ?>" 
             style="max-height:100%; max-width:100%; object-fit:cover;">
    </div>
</a>

                                <div class="product-action">
                                    <a class="btn btn-outline-dark btn-square" href="detail.php?pid=<?php echo $row['pid']; ?>"><i class="fa fa-shopping-cart"></i></a>
                                    <a class="btn btn-outline-dark btn-square" href="#"><i class="far fa-heart"></i></a>
                                    <a class="btn btn-outline-dark btn-square" href="#"><i class="fa fa-sync-alt"></i></a>
                                    <!-- Search Icon Clickable -->
                                    <a class="btn btn-outline-dark btn-square" href="detail.php?pid=<?php echo $row['pid']; ?>"><i class="fa fa-search"></i></a>
                                </div>
                            </div>
                            <div class="text-center py-4">
                                <!-- Title Clickable -->
                                <a class="h6 text-decoration-none text-truncate" href="detail.php?pid=<?php echo $row['pid']; ?>">
                                    <?php echo htmlspecialchars($row['pname']); ?>
                                </a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    <h5>₹<?php echo htmlspecialchars($row['price']); ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="px-3 py-2">No products found.</p>
                <?php endif; ?>
                </div>
            </div>

            <div class="col-12">
                <nav>
                  <ul class="pagination justify-content-center">
                    <li class="page-item <?php if($page <= 1) echo "disabled"; ?>">
                        <a class="page-link" href="<?php echo buildUrl(['page' => max($page-1, 1)]); ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if($i == $page) echo "active"; ?>">
                            <a class="page-link" href="<?php echo buildUrl(['page' => $i]); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if($page >= $total_pages) echo "disabled"; ?>">
                        <a class="page-link" href="<?php echo buildUrl(['page' => min($page+1, $total_pages)]); ?>">Next</a>
                    </li>
                  </ul>
                </nav>
            </div>
        </div>
        <!-- Shop Product End -->
    </div>
</div>
<!-- Shop End -->

<script>
// auto-submit filters when checkbox changes
document.querySelectorAll(".filter-checkbox").forEach(function(cb){
    cb.addEventListener("change", function(){
        document.getElementById("filterForm").submit();
    });
});
</script>

<?php include "footer.php"; ?>
