<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$content = $_POST['content'];

if (!empty($content)) {
    // Prevent duplicate posts
    $sql = "SELECT * FROM posts WHERE user_id = ? AND content = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $content);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $sql = "INSERT INTO posts (user_id, content) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $content);
        $stmt->execute();
    } else {
        $_SESSION['message'] = 'You cannot post the same content again.';
    }
}

header('Location: profile.php');
?>
