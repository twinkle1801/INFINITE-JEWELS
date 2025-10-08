<?php
include "header.php"; 
include "db.php";  

// ✅ Check if product ID is passed
if (isset($_GET['pid']) && !empty($_GET['pid'])) {
    $pid = intval($_GET['pid']); 

    $stmt = $conn->prepare("SELECT * FROM product WHERE pid = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<h4 class='text-center text-danger mt-5'>⚠️ Product not found!</h4>";
        include "footer.php";
        exit;
    }
} else {
    echo "<h4 class='text-center text-danger mt-5'>⚠️ Invalid Product!</h4>";
    include "footer.php";
    exit;
}
?>

<style>
.product-img {
    max-width: 350px;   
    max-height: 350px;  
    object-fit: contain; 
    border-radius: 8px;
    
    background: #fff;
    box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
}
.product-details h2 {
    font-weight: 600;
    margin-bottom: 10px;
}
.product-details h4 {
    font-weight: 500;
    margin-bottom: 15px;
    color: #28a745;
}
.product-details p {
    font-size: 15px;
    margin-bottom: 10px;
}

/* 🟡 Add to Cart Button */
.btn-add {
    background: #ffc107;
    border: none;
    color: #000;
    font-weight: 500;
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 20px; /* pill shape */
    display: inline-block;
    transition: 0.3s;
}
.btn-add:hover {
    background: #e0a800;
    transform: scale(1.05);
}

.out-of-stock-btn {
    background: #dc3545;
    color: #fff;
    padding: 12px 20px;
    font-size: 16px;
    border-radius: 6px;
    width: 100%;
}

.qty-box {
    display: flex;
    align-items: center;
    margin-top: 5px;
}
.qty-btn {
    width: 32px;
    height: 32px;
    border: 1px solid #ccc;
    background: #f8f9fa;
    text-align: center;
    cursor: pointer;
    font-weight: bold;
    border-radius: 4px;
}
.qty-btn:hover {
    background: #e2e6ea;
}
.qty-input {
    width: 60px;
    text-align: center;
    margin: 0 5px;
}

/* ⭐ Reviews Section */
.reviews {
    background: #fff;
    border-radius: 10px;
    padding: 25px;
    margin-top: 40px;
}
.reviews h5 {
    font-weight: 600;
    margin-bottom: 20px;
    text-align: center;
}
.review-box {
    border: 1px solid #eee;
    border-radius: 10px;
    background: #fafafa;
    padding: 15px;
    text-align: center;
    transition: 0.3s;
}
.review-box:hover {
    transform: translateY(-5px);
    box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
}
.review-stars {
    color: #ffc107;
    font-size: 18px;
}
.review-author {
    font-weight: 600;
    font-size: 15px;
    color: #333;
    margin-top: 8px;
}
.review-text {
    font-size: 14px;
    color: #555;
    margin-top: 6px;
}
</style>

<div class="container mt-5">
    <div class="row align-items-start">
        <!-- Product Image -->
        <div class="col-md-5 text-center mb-4 mb-md-0">
            <img src="<?php 
                $imgPath = 'admin/image/' . $row['image'];
                echo file_exists($imgPath) ? $imgPath : 'admin/image/default.png'; 
            ?>" 
            class="img-fluid product-img" 
            alt="<?php echo htmlspecialchars($row['pname']); ?>">
        </div>

        <!-- Product Details -->
        <div class="col-md-7 product-details">
            <h2><?php echo $row['pname']; ?></h2>
            <h4>₹ <?php echo number_format($row['price'], 2, '.', ','); ?></h4>
            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>

            <!-- ⭐ Static Rating -->
            <div class="rating">
                ★★★★☆ (4.5/5 based on 120 reviews)
            </div>

            <!-- Add to Cart -->
            <?php if ($row['qty'] > 0) { ?>
                <form action="add_to_cart.php" method="POST" class="mt-3">
                    <input type="hidden" name="pid" value="<?php echo $row['pid']; ?>">
                    <input type="hidden" name="price" value="<?php echo $row['price']; ?>">

                    <!-- Quantity Box -->
                    <div class="mb-3">
                        <label><strong>Quantity:</strong></label>
                        <div class="qty-box">
                            <button type="button" class="qty-btn" onclick="updateQty(-1)">-</button>
                            <input type="number" id="qtyInput" name="quantity" 
                                   value="1" min="1" max="<?php echo $row['qty']; ?>" 
                                   class="form-control qty-input">
                            <button type="button" class="qty-btn" onclick="updateQty(1)">+</button>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <div>
                        <button type="submit" name="add_to_cart" class="btn btn-add">
                            🛒 Add to Cart
                        </button>
                    </div>
                </form>
            <?php } else { ?>
                <button class="out-of-stock-btn mt-3" disabled>Out of Stock</button>
            <?php } ?>
        </div>
    </div>

    <!-- ✅ Reviews Section -->
    <div class="reviews">
        <h5>Customer Reviews</h5>
        <div class="row">
            
            <!-- Review 1 -->
            <div class="col-md-4 mb-3">
                <div class="review-box">
                    <div class="review-stars">★★★★★</div>
                    <div class="review-author">Amila M.</div>
                    <div class="review-text">
                        They are sooo pretty 😍 I always wished to have such earrings in real gold, 
                        but gold is sooo expensive now. Glad I found Infinite Jewels. Thank you!
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="col-md-4 mb-3">
                <div class="review-box">
                    <div class="review-stars">★★★★★</div>
                    <div class="review-author">Yash K.</div>
                    <div class="review-text">
                        My experience was amazing after purchasing this product. 
                        Price and quality are unbelievable. Truly a tough competition to gold jewellery.
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="col-md-4 mb-3">
                <div class="review-box">
                    <div class="review-stars">★★★★★</div>
                    <div class="review-author">Deepali B.</div>
                    <div class="review-text">
                        It's exactly the same as shown in the image ✨ Great for styling in different 
                        occasions and everyday wear too. Totally satisfied!
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function updateQty(change) {
    let qtyInput = document.getElementById("qtyInput");
    let current = parseInt(qtyInput.value);
    let min = parseInt(qtyInput.min);
    let max = parseInt(qtyInput.max);

    let newValue = current + change;
    if (newValue >= min && newValue <= max) {
        qtyInput.value = newValue;
    }
}
</script>

<?php include "footer.php"; ?>
