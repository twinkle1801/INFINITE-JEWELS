<?php
include "header.php";
include "db.php";

$cid = $_GET['cid'];
$cat = mysqli_query($conn, "SELECT * FROM category WHERE cid=$cid");
$data = mysqli_fetch_assoc($cat);

if(isset($_POST['update'])){
    $cnm = mysqli_real_escape_string($conn, $_POST['cnm']);
    mysqli_query($conn, "UPDATE category SET cnm='$cnm' WHERE cid=$cid");
    echo "<script>alert('Category Updated Successfully');window.location='category.php';</script>";
}
?>

<h2>Edit Category</h2>
<div class="card p-4">
    <form method="POST">
        <div class="mb-3">
            <label>Category Name</label>
            <input type="text" name="cnm" value="<?php echo $data['cnm']; ?>" class="form-control" required>
        </div>
        <button type="submit" name="update" class="btn btn-success">Update Category</button>
    </form>
</div>

<?php include "footer.php"; ?>
