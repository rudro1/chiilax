<?php
session_start();
include 'db.php'; // Ensure your db.php file has the correct database connection

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle profile picture upload
if (isset($_POST['upload_pic'])) {
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $image_name = $_FILES['profile_pic']['name'];
        $image_tmp_name = $_FILES['profile_pic']['tmp_name'];
        $image_path = 'uploads/' . basename($image_name);

        // Check if the directory exists and create it if not
        if (!is_dir('uploads/')) {
            mkdir('uploads/', 0777, true);
        }

        // Move uploaded file
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            $query = "UPDATE users SET profile_pic = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $image_path, $user_id);
            $stmt->execute();
        }
    }
}

// Handle posting new content
if (isset($_POST['create_post'])) {
    $post_content = $_POST['post_content'];
    if (!empty($post_content)) {
        // Check if the same post already exists
        $check_query = "SELECT * FROM posts WHERE user_id = ? AND post_content = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("is", $user_id, $post_content);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Proceed to insert the post
            $query = "INSERT INTO posts (user_id, post_content, post_time) VALUES (?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $user_id, $post_content);
            $stmt->execute();
        } else {
            echo "You cannot post the same content.";
        }
    }
}

// Fetch user profile information
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// If user is not found, redirect to login page
if (!$user) {
    header('Location: login.php');
    exit();
}

// Fetch user's posts
$query = "SELECT * FROM posts WHERE user_id = ? ORDER BY post_time DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        .btn-warning {
            background-color: #ffc107;
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Welcome, <?php echo htmlspecialchars($user['username'] ?? 'User'); ?>!</h1>

    <!-- Profile Picture Upload -->
    <div class="mt-3">
        <h4>Upload Profile Picture</h4>
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_pic" class="form-control mb-2">
            <button type="submit" name="upload_pic" class="btn btn-primary">Upload</button>
        </form>
        <img src="<?php echo htmlspecialchars($user['profile_pic'] ?? 'uploads/default.png'); ?>" alt="Profile Picture" class="profile-pic mt-2">
    </div>

    <!-- Create New Post -->
    <div class="mt-5">
        <h4>Create a New Post</h4>
        <form action="profile.php" method="POST">
            <textarea name="post_content" class="form-control" rows="3" placeholder="What's on your mind?" required></textarea>
            <button type="submit" name="create_post" class="btn btn-success mt-2">Post</button>
        </form>
    </div>

    <!-- Display User's Posts -->
    <div class="mt-5">
        <h4>Your Posts</h4>
        <?php while ($post = $posts_result->fetch_assoc()) { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <p><?php echo htmlspecialchars($post['post_content']); ?></p>
                    <small>Posted on: <?php echo $post['post_time']; ?></small>
                    <form action="profile.php" method="POST" class="mt-2">
                        <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                        <button type="submit" name="like_post" class="btn btn-info">Like</button>
                    </form>
                </div>
            </div>
        <?php 



session_start();
include 'db.php'; // Ensure this includes your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect if user is not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle profile picture upload
if (isset($_POST['upload_pic'])) {
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $image_name = $_FILES['profile_pic']['name'];
        $image_tmp_name = $_FILES['profile_pic']['tmp_name'];
        $image_path = 'uploads/' . basename($image_name);

        // Ensure the uploads directory exists
        if (!is_dir('uploads/')) {
            mkdir('uploads/', 0777, true);
        }

        // Move the uploaded file to the uploads folder
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            // Update the database with the new profile picture
            $query = "UPDATE users SET profile_pic = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $image_path, $user_id);
            $stmt->execute();
        }
    }
}

// Handle creating a new post
if (isset($_POST['create_post'])) {
    $post_content = $_POST['post_content'];
    if (!empty($post_content)) {
        // Check if the same post already exists
        $check_query = "SELECT * FROM posts WHERE user_id = ? AND post_content = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("is", $user_id, $post_content);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Proceed to insert the new post
            $query = "INSERT INTO posts (user_id, post_content, post_time) VALUES (?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $user_id, $post_content);
            $stmt->execute();
        } else {
            echo "You cannot post the same content.";
        }
    }
}

// Fetch user profile information
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch user's posts
$query = "SELECT * FROM posts WHERE user_id = ? ORDER BY post_time DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        .btn-warning {
            background-color: #ffc107;
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Welcome, <?php echo htmlspecialchars($user['username'] ?? 'User'); ?>!</h1>

    <!-- Profile Picture Upload -->
    <div class="mt-3">
        <h4>Upload Profile Picture</h4>
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_pic" class="form-control mb-2">
            <button type="submit" name="upload_pic" class="btn btn-primary">Upload</button>
        </form>
        <img src="<?php echo htmlspecialchars($user['profile_pic'] ?? 'uploads/default.png'); ?>" alt="Profile Picture" class="profile-pic mt-2">
    </div>

    <!-- Create New Post -->
    <div class="mt-5">
        <h4>Create a New Post</h4>
        <form action="profile.php" method="POST">
            <textarea name="post_content" class="form-control" rows="3" placeholder="What's on your mind?" required></textarea>
            <button type="submit" name="create_post" class="btn btn-success mt-2">Post</button>
        </form>
    </div>

    <!-- Display User's Posts -->
    <div class="mt-5">
        <h4>Your Posts</h4>
        <?php while ($post = $posts_result->fetch_assoc()) { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <p><?php echo htmlspecialchars($post['post_content']); ?></p>
                    <small>Posted on: <?php echo $post['post_time']; ?></small>
                    <form action="profile.php" method="POST" class="mt-2">
                        <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                        <button type="submit" name="like_post" class="btn btn-info">Like</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
</body>
</html>
