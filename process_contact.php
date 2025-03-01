<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $subject = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["message"]);

    $to = "poudelpawan500@gmail.com";  // Replace with your email
    $headers = "From: $email\r\nReply-To: $email";
    $emailBody = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    if (mail($to, $subject, $emailBody, $headers)) {
        echo "<script>alert('Message sent successfully!'); window.location='contact.php';</script>";
    } else {
        echo "<script>alert('Failed to send message. Please try again.'); window.location='contact.php';</script>";
    }
} else {
    header("Location: contact.php");
}
?>
