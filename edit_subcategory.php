<?php
include "header.php";
include "db.php";

$sid = $_GET['sid'];
$sub = mysqli_query($conn, "SELECT * FROM subcategory WHERE sid=$sid");
$data = mysqli_fetch_assoc($sub);

if(isset($_POST['update'])){
    $snm = mysqli_real_escape_string($conn, $_POST['snm']);
    $cid = $_POST['cid'];
    mysqli_query($conn, "UPDATE subcategory SET snm='$snm', cid='$cid' WHERE sid=$sid");
    echo "<script>alert('Subcategory Updated Successfully');window.location='subcategory.php';</script>";
}
?>

<h2>Edit Subcategory</h2>
<div class="card p-4">
    <form method="POST">
        <div class="mb-3">
            <label>Category</label>
            <select name="cid" class="form-control" required>
                <?php
                $cat = mysqli_query($conn, "SELECT * FROM category");
                while($c = mysqli_fetch_assoc($cat)){
                    $selected = ($c['cid'] == $data['cid']) ? "selected" : "";
                    echo "<option value='".$c['cid']."' $selected>".$c['cnm']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Subcategory Name</label>
            <input type="text" name="snm" value="<?php echo $data['snm']; ?>" class="form-control" required>
        </div>
        <button type="submit" name="update" class="btn btn-success">Update Subcategory</button>
    </form>
</div>

<?php include "footer.php"; ?>
