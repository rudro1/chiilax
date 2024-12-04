<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'blog');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'], $_POST['title'], $_POST['author'])) {
    $author = $conn->real_escape_string($_POST['author']);
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // Check if the post content already exists
    $checkQuery = "SELECT id FROM posts WHERE content = '$content'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "<script>alert('This post content already exists. Please write something new.');</script>";
    } else {
        // Insert the post if it doesn't exist
        $sql = "INSERT INTO posts (author, title, content, likes) VALUES ('$author', '$title', '$content', 0)";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle like increment
if (isset($_POST['like_post_id'])) {
    $postId = intval($_POST['like_post_id']);
    $conn->query("UPDATE posts SET likes = likes + 1 WHERE id = $postId");
    header("Location: index.php");
    exit;
}

// Fetch all posts
$posts = $conn->query("SELECT * FROM posts ORDER BY publish_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Post Form -->
        <form class="post-form" method="POST" action="">
            <div class="input-group">
                <input type="text" name="title" placeholder="Enter title" required>
                <input type="text" name="author" placeholder="Enter author name" required>
            </div>
            <textarea name="content" placeholder="Write something..." required></textarea>
            <button class="post-button" type="submit">Post</button>
        </form>

        <!-- Blog Posts -->
        <?php if ($posts->num_rows > 0): ?>
            <?php while ($row = $posts->fetch_assoc()): ?>
                <div class="post-container">
                    <div class="post-header">
                        <h2><?= htmlspecialchars($row['author']) ?></h2>
                        <p class="publish-date">Published: <?= date('F d, Y h:i A', strtotime($row['publish_date'])) ?></p>
                    </div>
                    <div class="post-title">
                        <h1><?= htmlspecialchars($row['title']) ?></h1>
                    </div>
                    <div class="post-content">
                        <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                    </div>
                    <div class="post-footer">
                        <div class="likes-comments">
                            <form method="POST" action="" class="like-form">
                                <input type="hidden" name="like_post_id" value="<?= $row['id'] ?>">
                                <button style="border-radius: 10px;" type="submit" class="like-button" >❤️ Like (<?= $row['likes'] ?>)</button>
                            </form>
                            <span>52 💬</span>
                            <span>2.2k 🔄</span>
                            <span class="share-icon">🔗</span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts yet. Be the first to post!</p>
        <?php endif; ?>
    </div>
</body>
</html>
