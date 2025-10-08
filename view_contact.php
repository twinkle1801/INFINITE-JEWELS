<?php

include("header.php"); // Admin Panel header



include("db.php"); // DB connection

// Delete query if requested
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM contact WHERE id=$id";
    mysqli_query($conn, $delete_query);
    echo "<script>alert('Contact deleted successfully'); window.location='view_contact.php';</script>";
}

// Fetch contacts
$result = mysqli_query($conn, "SELECT * FROM contact_us ORDER BY created_at DESC");
?>

<div class="container mt-4">
    <h2 class="mb-4">📩 Contact Messages</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['subject']); ?></td>
                <td><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                <td><?= $row['created_at']; ?></td>
                <td>
                    <a href="?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php include("footer.php"); ?>
