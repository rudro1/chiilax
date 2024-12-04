<?php
$post_id = $_GET['post_id']; // The post being liked

$sql = "INSERT INTO post_likes (post_id, user_id) VALUES ($post_id, " . $_SESSION['user_id'] . ")";
$conn->query($sql);
?>
