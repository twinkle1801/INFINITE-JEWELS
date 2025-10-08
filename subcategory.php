<?php
include "header.php";
include "db.php";

// Add Subcategory
if(isset($_POST['add'])){
    $snm = mysqli_real_escape_string($conn, $_POST['snm']);
    $cid = $_POST['cid'];
    mysqli_query($conn, "INSERT INTO subcategory (snm, cid) VALUES ('$snm', '$cid')");
    echo "<script>alert('Subcategory Added Successfully');window.location='subcategory.php';</script>";
}

// Delete Subcategory
if(isset($_GET['delete'])){
    $sid = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM subcategory WHERE sid=$sid");
    echo "<script>alert('Subcategory Deleted');window.location='subcategory.php';</script>";
}
?>

<h2 class="mb-4">Subcategory Management</h2>

<!-- Add Subcategory Form -->
<div class="card p-4 mb-4">
    <form method="POST">
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
            <label>Subcategory Name</label>
            <input type="text" name="snm" class="form-control" required>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Subcategory</button>
    </form>
</div>

<!-- Subcategory List -->
<div class="card p-4">
    <h4>Subcategory List</h4>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subcategory Name</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT subcategory.*, category.cnm 
                                           FROM subcategory 
                                           JOIN category ON subcategory.cid = category.cid 
                                           ORDER BY sid DESC");
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                    <td>".$row['sid']."</td>
                    <td>".$row['snm']."</td>
                    <td>".$row['cnm']."</td>
                    <td>
                        <a href='edit_subcategory.php?sid=".$row['sid']."' class='btn btn-sm btn-warning'>Edit</a>
                        <a href='subcategory.php?delete=".$row['sid']."' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this subcategory?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"; ?>
