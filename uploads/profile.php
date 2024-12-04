<?php
session_start();
include('db.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user profile
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile picture upload
if (isset($_POST['upload_pic'])) {
    if (isset($_FILES['profile_pic'])) {
        $image = $_FILES['profile_pic'];
        $target_directory = 'uploads/';
        $target_file = $target_directory . basename($image['name']);

        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            $updateQuery = "UPDATE users SET profile_pic = ? WHERE user_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $target_file, $user_id);
            $updateStmt->execute();
            $_SESSION['profile_pic'] = $target_file;
        }
    }
}

// Handle new post submission
if (isset($_POST['post'])) {
    $post_content = $_POST['post_content'];

    // Ensure unique post
    $checkQuery = "SELECT * FROM posts WHERE post_content = ? AND user_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("si", $post_content, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "This post already exists.";
    } else {
        $insertQuery = "INSERT INTO posts (user_id, post_content) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("is", $user_id, $post_content);
        $insertStmt->execute();
        echo "Post successfully created!";
    }
}

// Fetch user's posts
$postQuery = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$postStmt = $conn->prepare($postQuery);
$postStmt->bind_param("i", $user_id);
$postStmt->execute();
$postResult = $postStmt->get_result();

// Fetch posts from other users with their usernames
$otherPostsQuery = "SELECT posts.post_content, posts.created_at, users.username 
                    FROM posts 
                    INNER JOIN users ON posts.user_id = users.user_id 
                    WHERE posts.user_id != ? 
                    ORDER BY posts.created_at DESC";
$otherPostsStmt = $conn->prepare($otherPostsQuery);
$otherPostsStmt->bind_param("i", $user_id);
$otherPostsStmt->execute();
$otherPostsResult = $otherPostsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
	<title>chill and relax</title>
    <link rel="icon" href="images/chillax.png" type="image/png" sizes="16x16"> 
    
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">
	<link rel="stylesheet" href="css/test.css">
	<link rel="stylesheet" href="style1.css">
   
   <style>
        body {
            background-color: white;
            color: #333;
        }
        .body_back{
background-image: url("images/logo12.png");
background-size: cover;
backdrop-filter: none;
background-repeat: repeat-y;


}
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .profile-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-section img {
            border-radius: 50%;
            width: 120px;
        }
        .btn-red {
            background-color: #dc3545;
            color: white;
        }
        .btn-red:hover {
            background-color: #c82333;
        }
        .post {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .post h5 {
            color: #dc3545;
        }
        .post button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
        }

        .body_back{
background-image: url("images/logo12.png");
background-size: cover;
backdrop-filter: none;
background-repeat: repeat-y;


}

.footer_back{
	
	background-repeat:repeat;
	background-size: cover;
	
}
.footer-design {
	background-color: antiquewhite;
}

.div{
background-color: wheat ;
width: 100%;
display: flex;
justify-content:flex-end;
padding: 10px;


}
.nav_s {
    width: 45%;
    border-radius: 5px;
    background: #ff4658;
    margin-right: 8px;
	padding: 5px 20px;
   
}
.nav_s button{
	margin-left: 10px;
	padding: 5px 30px;
	background-color:#ff4658 !important;
	box-shadow: 4px 2px 2px  green;
	color: black;
	margin-right: 10px;
	font-family: monospace;
}

.nav_s button:hover {
	
	background-color: green !important;
	box-shadow: 4px 2px 2px  green;
        }

button.post-buttons {
	background: red none repeat scroll 0 0 !important;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.5px;
    padding: 5px 0;
    text-transform: uppercase;
   width: 50%;
    color: #fff;
}
.post-buttons:hover {
    color: #fff;
    background: green none repeat scroll 0 0 !important;
}

html,body{
	font-family: "Muli", "Segoe Ui";
	text-transform: capitalize;
	font-weight: 600;
}


.side_design{
padding: 10px 0px;
	background: #ff0000;
	border-radius: 10px;
}

.input-group input{
width: 100%;
}

#topcontrol{
background-color: red !important;
}


.night-mode {
      background-color: #121212 !important;
      color: #ffffff !important;
    }
    .night-mode a {
      color: #bb86fc !important;
    }
    .night-mode .btn {
      background-color: #333333;
      color: #ffffff;
    }
    .night-mode .btn:hover {
      background-color: #444444;
    }
    .night-mode .div {
      background-color: #1e1e1e;
      border-color: #444444 !important;
    }


	.night-mode .footer-design {
      background-color: #1e1e1e;
      border-color: #444444 !important;
    }

	.night-mode .side-panel {
      background-color: #1e1e1e;
      border-color: #444444 !important;
	  
    }
	.rounds >  span {
    font-family: hobo std;
    font-size: 200px;
    position: relative;
	overflow: visible;
    
}

	.rounds > span::before,
	.rounds > span::after {
    background: #fff none repeat scroll 0 0;
    position: absolute;
    border: 1px solid rgb(206 17 17 / 96%);
    border-radius: 25%;
    content: "";
    height: 140px;
    left: -600px;
    top: -80%;
    width: 140px;
    z-index: 18;
    background-image: url(./images/chillax.png) !important;
    background-size: 200px;
    background-position-x: 60%;
    background-position-y: -15%;
    background-repeat: repeat;
    box-shadow: 4px 2px 3px #ff4658;
}
.ti-menu{
	color: white !important;
}
.headers {
    margin-bottom: 50px !important;
    top: 50px;
    position: relative;
    /* bottom: -10px; */
    left: 2;
    display: grid;
    justify-content: space-around;
}
    </style>
</head>
<body>

<div class="container">

<div class="  div rounds sticky fixed-top ">

	
<span class=""></span>

<div class="d-flex nav_s justify-content-end top-area ">
<button id="nightModeToggle" class="btn btn-primary me-2 ">Night Mode</button>
       <a href="logout.php"> <button class="btn btn-secondary">Logout</button></a>

	
		</div>
		</div>
</div>
    <div class="container headers ">
        <header>
            <h1 class="text-center " style="font-family: fantasy;">Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>

        </header>

        <!-- Profile Picture Section -->
        <div class="profile-section">
            <img src="<?php echo 'uploads/' . (isset($user['profile_pic']) ? $user['profile_pic'] : 'default.png'); ?>" alt="Profile Picture">
            <form method="POST" enctype="multipart/form-data" class="mt-3">
                <input type="file" name="profile_pic" required>
                <button type="submit" name="upload_pic" class="btn btn-red mt-2">Upload Picture</button>
            </form>
        </div>

        <!-- New Post Section -->
        <div class="new-post mb-4">
            <h2>Create New Post</h2>
            <form method="POST">
                <textarea name="post_content" class="form-control" placeholder="Write something..." required></textarea>
                <button type="submit" name="post" class="btn btn-red mt-2">Post</button>
            </form>
        </div>

        <!-- User's Posts -->
        <div class="user-posts">
            <h2>Your Posts</h2>
            <?php
            if ($postResult->num_rows > 0) {
                while ($post = $postResult->fetch_assoc()) {
                    echo '<div class="post">';
                    echo '<p>' . htmlspecialchars($post['post_content']) . '</p>';
                    echo '<small>Posted on: ' . $post['created_at'] . '</small>';
                    echo '</div>';
                }
            } else {
                echo '<p>No posts available.</p>';
            }
            ?>
        </div>

        <!-- Other Users' Posts -->
        <div class="other-posts">
            <h2>Other Users' Posts</h2>
            <?php
            if ($otherPostsResult->num_rows > 0) {
                while ($post = $otherPostsResult->fetch_assoc()) {
                    echo '<div class="post">';
                    echo '<h5>' . htmlspecialchars($post['username']) . '</h5>';
                    echo '<p>' . htmlspecialchars($post['post_content']) . '</p>';
                    echo '<small>Posted on: ' . $post['created_at'] . '</small>';
                    echo '<button class="btn btn-light mt-2">Like</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No posts from other users available.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const body = document.body;
    const nightModeToggle = document.getElementById('nightModeToggle');

    nightModeToggle.addEventListener('click', () => {
      const isNightMode = body.classList.toggle('night-mode');
      nightModeToggle.textContent = isNightMode ? 'Disable Night Mode' : 'Enable Night Mode';
    });
  </script>

</body>
</html>
