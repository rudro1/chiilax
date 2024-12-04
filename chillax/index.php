<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blog');

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

.body_back{
background-image: url("images/logo12.png");
background-size: cover;
backdrop-filter: none;
background-repeat: repeat-y;


}

.footer_back{
	background-image: url("./images/logo1.png") !important;
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
.btn{
	background-color: red !important;
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
  
</style>






</head>
<body class="news body_back ">

<!--<div class="se-pre-con"></div>-->
<div class="theme-layout ">
	
	<div class="responsive-header">
		<div class="mh-head first Sticky">
			<span class="mh-btns-left">
				<a class="" href="#menu"><i class="fa fa-align-justify"></i></a>
			</span>
			
			<span class="mh-btns-right">
				<a class="fa fa-sliders" href="#shoppingbag"></a>
			</span>
		</div>
		
		<nav id="menu" class="res-menu">
			<ul>
				<li><span>Home</span>
					<ul>
						
						<li><a href="landing.html" title="">Login page</a></li>
						<li><a href="logout.html" title="">Logout Page</a></li>
					
					</ul>
				</li>
			
			
			</ul>
		</nav>
		<nav id="shoppingbag">
			<div>
				<div class="">
					<form method="post">
						<div class="setting-row">
							<span>use night mode</span>
							<input type="checkbox" id="nightmode"/> 
							<label for="nightmode" data-on-label="ON" data-off-label="OFF"></label>
						</div>
						<div class="setting-row">
							<span>Notifications</span>
							<input type="checkbox" id="switch2"/> 
							<label for="switch2" data-on-label="ON" data-off-label="OFF"></label>
						</div>
						<div class="setting-row">
							<span>Notification sound</span>
							<input type="checkbox" id="switch3"/> 
							<label for="switch3" data-on-label="ON" data-off-label="OFF"></label>
						</div>
						<div class="setting-row">
							<span>My profile</span>
							<input type="checkbox" id="switch4"/> 
							<label for="switch4" data-on-label="ON" data-off-label="OFF"></label>
						</div>
						<div class="setting-row">
							<span>Show profile</span>
							<input type="checkbox" id="switch5"/> 
							<label for="switch5" data-on-label="ON" data-off-label="OFF"></label>
						</div>
					</form>
					
				</div>
			</div>
		</nav>
	</div><!-- responsive header -->
	
	<div class="  div rounds sticky fixed-top ">

	
<span class=""></span>

	<div class="d-flex nav_s justify-content-end top-area ">
        <button id="nightModeToggle" class="btn btn-primary me-2 ">Night Mode</button>
       <a href="../uploads/login.php"> <button class="btn btn-secondary">LogIn</button></a>

		<span class="ti-menu main-menu" data-ripple=""></span> 
		</div>
		</div>
	
		
	<section>
		<div class="gap gray-bgs">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
						<div class="row" id="page-contents">
						<!-- centerl meta -->
						<div class="col-lg-3 side_design">
								<aside class="sidebar static">
									<div class="widget">
										<h4 class="widget-title page-meta">
										<a href="#" title="" class="underline " style="color:red">Author name</a>
										</h4>	
										<div class="your-page">
										<form class="post-forms" method="POST" action="">
										<div class="input-group">
											<input type="text" name="title" placeholder="Enter title" required class="mb-2">
											<br>
											<input type="text" name="author" placeholder="Enter author name" required>
										</div>
				
										<textarea name="content" placeholder="Write something..." required class="mb-2 mt-2"></textarea>
										<button class="post-buttons" type="submit" >Post</button>
									</form>
											
										</div>







										
									</div><!-- page like widget -->
									<div class="widget">
										<div class="banner medium-opacity bluesh">
											<div class="bg-image" style="background-image: url(images/resources/baner-widgetbg.jpg)"></div>
											<div class="baner-top">
												<span><img alt="" src="images/book-icon.png"></span>
												<i class="fa fa-ellipsis-h"></i>
											</div>
											<div class="banermeta">
												<p>
													Find your own jonra .
												</p>
												<span>Allawys be creative</span>
												<a data-ripple="" title="" href="#">start now!</a>
											</div>
										</div>											
									</div>
									<!-- friends list sidebar -->
								</aside>
							</div><!-- sidebar -->
							<div class="col-lg-8">
				
									<!-- Post Form -->
									
									
							
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
													
													</div>
												</div>
											</div>
										<?php endwhile; ?>
									<?php else: ?>
										<p>No posts yet. Be the first to post!</p>
									<?php endif; ?>
								
						</div>
							
						</div>	
					</div>
				</div>
			</div>
		</div>	
	</section>

	
	
</div>
<div class="footer_back">
				<div class="card-footer text-center  ">
					<div class="row ">
						<div class="col footer-design ">
							<img src="./images/chillax.png" alt="User 1" class="rounded-circle" width="250px">
							<p class="text-muted">Terms of Use | Privacy Policy | Developers | Contact | About</p>
							<p class="text-muted">Copyright © Company - All rights reserved</p>
						</div>
						
						</div>
	
				</div>
			</div>
	
<div class="side-panel">
			<h4 class="panel-title">General Setting</h4>
			<form method="post">
				<div class="setting-row">
					<span>use night mode</span>
					<input type="checkbox" id="nightModeToggle"/> 
					<label for="nightModeToggle" data-on-label="ON" data-off-label="OFF"></label>
				</div>
				<div class="setting-row">
					<a href="../uploads/login.php"><span>LOG IN</span></a>
					<input type="checkbox" id="switch22" /> 
					<label for="switch22" data-on-label="ON" data-off-label="OFF"></label>
				</div>
			
				<div class="setting-row">
				<a href="../uploads/register.php"></a>	<span>My profile</span></a>
					<input type="checkbox" id="switch44" /> 
					<label for="switch44" data-on-label="ON" data-off-label="OFF">
						
					</label>
				</div>
			
			</form>
			
		</div><!-- side panel -->		
	
	<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="js/main.min.js"></script>
	<script src="js/script.js"></script>
	<script src="js/map-init.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8c55_YHLvDHGACkQscgbGLtLRdxBDCfI"></script>


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