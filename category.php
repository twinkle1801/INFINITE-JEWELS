<?php
include "header.php";
include "db.php";

// Add Category
if(isset($_POST['add'])){
    $cnm = mysqli_real_escape_string($conn, $_POST['cnm']);
    mysqli_query($conn, "INSERT INTO category (cnm) VALUES ('$cnm')");
    echo "<script>alert('Category Added Successfully');window.location='category.php';</script>";
}

// Delete Category
if(isset($_GET['delete'])){
    $cid = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM category WHERE cid=$cid");
    echo "<script>alert('Category Deleted');window.location='category.php';</script>";
}
?>

<h2 class="mb-4">Category Management</h2>

<!-- Add Category Form -->
<div class="card p-4 mb-4">
    <form method="POST">
        <div class="mb-3">
            <label>Category Name</label>
            <input type="text" name="cnm" class="form-control" required>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Category</button>
    </form>
</div>

<!-- Category List -->
<div class="card p-4">
    <h4>Category List</h4>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM category ORDER BY cid DESC");
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                    <td>".$row['cid']."</td>
                    <td>".$row['cnm']."</td>
                    <td>
                        <a href='edit_category.php?cid=".$row['cid']."' class='btn btn-sm btn-warning'>Edit</a>
                        <a href='category.php?delete=".$row['cid']."' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this category?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"; ?>
