<?php
session_start();
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action']; // Accept or Decline

    if ($action == 'accept') {
        $query = "UPDATE friend_requests SET status = 'accepted' WHERE id = ?";
    } else if ($action == 'decline') {
        $query = "UPDATE friend_requests SET status = 'declined' WHERE id = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $request_id);
    if ($stmt->execute()) {
        echo ucfirst($action) . "ed friend request.";
    } else {
        echo "Error managing request.";
    }
}
?>
