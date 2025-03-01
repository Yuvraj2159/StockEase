<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "<script>alert('All fields are required!'); window.location.href='contact.php';</script>";
        exit();
    }

    // Database connection
    $conn = new mysqli("localhost", "root", "", "stock_management");

    if ($conn->connect_error) {
        die("Database Connection Failed: " . $conn->connect_error);
    }

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO contact_responses (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        // Send email to admin
        $to = "yubrajp54@gmail.com"; // Replace with your admin email
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8";

        $email_body = "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong><br>$message</p>
        ";

        mail($to, "New Contact Form Message", $email_body, $headers);

        echo "<script>alert('Message Sent Successfully!'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('Error! Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - STOCKEASE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">STOCKEASE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link btn btn-primary text-black px-3" href="login.php">Log In</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contact Form Section -->
<div class="contact-section">
    <div class="contact-box">
        <h2>Contact Us</h2>
        <p>We’d love to hear from you! Send us a message and we’ll get back to you soon.</p>
        <form action="contact.php" method="POST">
            <label>Your Name</label>
            <input type="text" class="form-control" name="name" required placeholder="Enter your name">
            <label>Your Email</label>
            <input type="email" class="form-control" name="email" required placeholder="Enter your email">
            <label>Subject</label>
            <input type="text" class="form-control" name="subject" required placeholder="Subject">
            <label>Message</label>
            <textarea class="form-control" name="message" required placeholder="Write your message"></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</div>

<!-- Footer -->
<footer class="text-center py-3 bg-light">
    <p>&copy; 2025 STOCKEASE. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
