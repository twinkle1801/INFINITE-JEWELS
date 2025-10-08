<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = ""; // Use your DB password if any
$dbname = "jewels"; // Replace with your actual DB name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $stmt = $conn->prepare("INSERT INTO contact_us (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Message sent successfully.'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('Something went wrong.');</script>";
    }

    $stmt->close();
}
?>

<?php include "header.php"; ?>

<!-- Breadcrumb Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="#">Home</a>
                <span class="breadcrumb-item active">Contact</span>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Contact Start -->
<div class="container-fluid">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Contact Us</span>
    </h2>
    <div class="row px-xl-5">
        <div class="col-lg-7 mb-5">
            <div class="contact-form bg-light p-30">
                <form method="post" action="contact.php">
                    <div class="control-group mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Your Name" required />
                    </div>
                    <div class="control-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Your Email" required />
                    </div>
                    <div class="control-group mb-3">
                        <input type="text" class="form-control" name="subject" placeholder="Subject" required />
                    </div>
                    <div class="control-group mb-3">
                        <textarea class="form-control" name="message" rows="8" placeholder="Message" required></textarea>
                    </div>
                    <div>
                        <button class="btn btn-primary py-2 px-4" type="submit">Send Message</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5 mb-5">
            <div class="bg-light p-30 mb-30">
                <iframe style="width: 100%; height: 250px;" src="https://www.google.com/maps/embed?pb=..."
                    frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
            <div class="bg-light p-30 mb-3">
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 Street, New York, USA</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                <p class="mb-2"><i class="fa fa-phone-alt text-primary mr-3"></i>+012 345 67890</p>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->

<?php include "footer.php"; ?>
