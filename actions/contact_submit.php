<?php
require_once('../includes/paths.php');
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    $query = "INSERT INTO contact_messages (name, email, message, created_at) VALUES ('$name', '$email', '$message', NOW())";

    if ($conn->query($query)) {
        header('Location: ' . url('contact.php?success=1'));
    } else {
        header('Location: ' . url('contact.php?error=1'));
    }
    exit();
} else {
    header('Location: ../contact.php');
    exit();
}
?>
