<?php
$conn = mysqli_connect('localhost:8889', 'root', 'root', 'social_apps');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
