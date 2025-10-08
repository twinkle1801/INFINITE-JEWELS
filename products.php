<?php
include "header.php";
include "db.php";

// Add Product
if(isset($_POST['add'])){
    $pname = mysqli_real_escape_string($conn, $_POST['pname']);
    $cid = $_POST['cid'];
    $sid = $_POST['sid'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Image Upload
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    // Create unique name for image to prevent overwrite
    $image = time() . "_" . basename($image);

    // Path where image will be stored (inside admin/image folder)
    $path = __DIR__ . "/image/" . $image;

    if(move_uploaded_file($tmp_name, $path)){
        mysqli_query($conn, "INSERT INTO product (pname, cid, sid, price, qty, description, image) 
                             VALUES ('$pname', '$cid', '$sid', '$price', '$qty', '$description', '$image')");
        echo "<script>alert('Product Added Successfully');window.location='products.php';</script>";
    } else {
        echo "<script>alert('Image Upload Failed');</script>";
    }
}

// Delete Product
if(isset($_GET['delete'])){
    $pid = $_GET['delete'];

    // Delete image file also
    $img_query = mysqli_query($conn, "SELECT image FROM product WHERE pid=$pid");
    if($img_data = mysqli_fetch_assoc($img_query)){
        $img_path = __DIR__ . "/image/" . $img_data['image'];
        if(file_exists($img_path)){
            unlink($img_path);
        }
    }

    mysqli_query($conn, "DELETE FROM product WHERE pid=$pid");
    echo "<script>alert('Product Deleted');window.location='products.php';</script>";
}
?>

<h2 class="mb-4">Product Management</h2>

<!-- Add Product Form -->
<div class="card p-4 mb-4">
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Category</label>
            <select name="cid" class="form-control" required>
                <option value="">Select Category</option>
                <?php
                $cat = mysqli_query($conn, "SELECT * FROM category ORDER BY cnm ASC");
                while($c = mysqli_fetch_assoc($cat)){
                    echo "<option value='".$c['cid']."'>".$c['cnm']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Subcategory</label>
            <select name="sid" class="form-control" required>
                <option value="">Select Subcategory</option>
                <?php
                $subcat = mysqli_query($conn, "SELECT * FROM subcategory ORDER BY snm ASC");
                while($s = mysqli_fetch_assoc($subcat)){
                    echo "<option value='".$s['sid']."'>".$s['snm']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="pname" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" name="qty" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <button type="submit" name="add" class="btn btn-primary">Add Product</button>
    </form>
</div>

<!-- Product List -->
<div class="card p-4">
    <h4>Product List</h4>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT product.*, category.cnm, subcategory.snm 
                                           FROM product
                                           JOIN category ON product.cid = category.cid
                                           JOIN subcategory ON product.sid = subcategory.sid
                                           ORDER BY pid DESC");
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                    <td>".$row['pid']."</td>
                    <td><img src='image/".$row['image']."' width='50' height='50' style='object-fit:cover;'></td>
                    <td>".$row['pname']."</td>
                    <td>".$row['cnm']."</td>
                    <td>".$row['snm']."</td>
                    <td>".$row['price']."</td>
                    <td>".$row['qty']."</td>
                    <td>
                        <a href='edit_product.php?pid=".$row['pid']."' class='btn btn-sm btn-warning'>Edit</a>
                        <a href='products.php?delete=".$row['pid']."' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this product?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"; ?>
