<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['profile_pic'] = $user['profile_pic'];
            header("Location: profile.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
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
<body class="bg-danger text-white">
<div class="container mb-5 ">

<div class="  div rounds sticky fixed-top ">

	
<span class=""></span>

<div class="d-flex nav_s justify-content-end top-area ">
<button id="nightModeToggle" class="btn btn-primary me-2 ">Night Mode</button>
       <a href="../chillax/landing.php"> <button class="btn btn-secondary">Home</button></a>

	
		</div>
		</div>
</div>

    <div class="container mt-5 headers">
        <h2>Login</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-light">Login</button>
        </form>
        <h4 style="margin-top:10px">If You Are New,Please <a href="register.php" class="underline btn-login" style="text-decoration:none; " > SignUp </a>  First</h4>
       
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
