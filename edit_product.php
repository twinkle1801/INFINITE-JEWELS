<?php
include "header.php";
include "db.php";

$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
$product = mysqli_query($conn, "SELECT * FROM product WHERE pid=$pid");
$data = mysqli_fetch_assoc($product);

if (isset($_POST['update'])) {
    $pname = mysqli_real_escape_string($conn, $_POST['pname']);
    $cid = intval($_POST['cid']);
    $sid = intval($_POST['sid']);
    $price = floatval($_POST['price']);
    $qty = intval($_POST['qty']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Image Upload
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . basename($_FILES['image']['name']); // unique filename
        $tmp_name = $_FILES['image']['tmp_name'];

        // Upload folder path (full server path)
        $upload_dir = __DIR__ . "/image/";

        if (move_uploaded_file($tmp_name, $upload_dir . $image)) {
            // Store only filename in DB
            $image_update = ", image='" . $image . "'";
        } else {
            $image_update = "";
        }
    } else {
        $image_update = "";
    }

    // Update Query
    $sql = "UPDATE product SET 
            pname='$pname', cid=$cid, sid=$sid, 
            price=$price, qty=$qty, description='$description' 
            $image_update WHERE pid=$pid";

    mysqli_query($conn, $sql);

    echo "<script>alert('Product Updated Successfully');window.location='products.php';</script>";
}
?>

<h2>Edit Product</h2>
<div class="card p-4">
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Category</label>
            <select name="cid" class="form-control" required>
                <?php
                $cat = mysqli_query($conn, "SELECT * FROM category");
                while ($c = mysqli_fetch_assoc($cat)) {
                    $selected = ($c['cid'] == $data['cid']) ? "selected" : "";
                    echo "<option value='{$c['cid']}' $selected>{$c['cnm']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Subcategory</label>
            <select name="sid" class="form-control" required>
                <?php
                $subcat = mysqli_query($conn, "SELECT * FROM subcategory");
                while ($s = mysqli_fetch_assoc($subcat)) {
                    $selected = ($s['sid'] == $data['sid']) ? "selected" : "";
                    echo "<option value='{$s['sid']}' $selected>{$s['snm']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="pname" value="<?php echo htmlspecialchars($data['pname']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($data['price']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" name="qty" value="<?php echo htmlspecialchars($data['qty']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"><?php echo htmlspecialchars($data['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
            <br>
            <?php if (!empty($data['image'])) { ?>
                <!-- Display image from admin/image folder -->
                <img src="image/<?php echo htmlspecialchars($data['image']); ?>" width="100" alt="Product Image">
            <?php } ?>
        </div>

        <button type="submit" name="update" class="btn btn-success">Update Product</button>
    </form>
</div>

<?php include "footer.php"; ?>
