<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname="login_register";
// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
<?php

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['Password'];

    // Prepare and execute
    $stmt = $conn->prepare("SELECT Password FROM login_register WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password);
        $stmt->fetch();

        if ($password === $db_password) {
            $message = "Login successful";
            $toastClass = "bg-success";
            // Start the session and redirect to the dashboard or home page
            session_start();
            $_SESSION['email'] = $email;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Incorrect password";
            $toastClass = "bg-danger";
        }
    } else {
        $message = "Email not found";
        $toastClass = "bg-warning";
    }

    $stmt->close();
    $conn->close();
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
    <link rel="icon" href="images/icon.png" type="image/png" sizes="48x48"> 
    
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">

	<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet">
<style>

.parkinsans {
  font-family: "Parkinsans", sans-serif;
  font-optical-sizing: auto;
  font-weight:500;
  font-style: normal;
  text-align: center;
  font-size: 40px !important;
  color: rgb(249, 48, 34);
  

}
.div#text1 {
    color: #763bb1;

    font-weight: 500;
}

.dynamic-text {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
			text-transform: capitalize;
		
        }
	
	
.body_back{

    background-size: cover;
    backdrop-filter: none;
    background-repeat: no-repeat;
    background-color: bisque !important;
}


.row {
            height: 50%; /* Each row takes up one-third of the viewport height */
        }
        .box {
            background-color: rgba(255, 255, 255, 0.991);
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.295);
            text-align: center;
			border-left: 1px solid #0056b3;
			font-weight: 500;
		
			
        }
		.box h2 {
    font-size: 40px;
    color: cornflowerblue;
    font-weight: 800;
    font-family: ui-monospace;
    letter-spacing: 12px;
    text-transform: uppercase;
}
.box p {
    font-size: 18px;
    color: #8a642d;
    letter-spacing: 1px;
    font-weight: 500;
    font-family: auto;
}
        .btn-custom {
            border: none;
            padding: 10px 20px;
			
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
		.btn-login {
    background-color: red;
    color: white;
    margin-right: 10px;
   
    padding: 10px 45px;
}
.btn-signup{
    padding: 10px 45px;
}
        .btn-login:hover {
            background-color: #0056b3;
        }
        .btn-signup {
            background-color: #28a745;
            color: white;
        }
        .btn-signup:hover {
            background-color: #218838;
        }
      

.footer-design{}

.footer_back{
	background-image: url("./images/logo1.png") !important;
	background-repeat:repeat;
	background-size: cover;
}
.vstr{
    background-color: #763bb1;
}

.vstr:hover {
            background-color: white;
            border: 3px solid blue;
            border-radius: 3px solid blue;
        color: #0056b3;

        }
@media (min-width: 576px) {
            .btn-custom {
                width: auto; /* Buttons have auto width on medium screens */
           
			margin-bottom: 10px;
			}
        }
</style>
</head>
<body class="">
<!--<div class="se-pre-con"></div>-->
<div class="theme-layout">
	<div class="container-fluid pdng0">
		<div class="row merged">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="land-featurearea">

					
					<div class=" body_back">
						<div class="log-reg-area  dynamic_box parkinsans ">
							
							<div id="text1" class="dynamic-text"></div>
							<br>
							<div id="text2" class="dynamic-text"></div>
							<script>
								// Text strings
								const text1 = "If you think you can,";
								const text2 = "you will can......";
						
								let index1 = 0;
								let index2 = 0;
						
								// Function to update the text one character at a time
								function animateText() {
									if (index1 < text1.length) {
										document.getElementById("text1").innerHTML += text1.charAt(index1);
										index1++;
									} else {
										document.getElementById("text2").innerHTML += text2.charAt(index2);
										index2++;
									}
						
									// Repeat the animation continuously
									if (index1 < text1.length || index2 < text2.length) {
										setTimeout(animateText, 100); // Adjust the speed of typing (100 ms)
									} else {
										index1 = 0;  // Reset for text1
										index2 = 0;  // Reset for text2
										document.getElementById("text1").innerHTML = "";
										document.getElementById("text2").innerHTML = "";
										setTimeout(animateText, 1000); // Delay before restarting
									}
								}
						
								// Start the animation
								animateText();
							</script>
		
						</div>
						
								</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 body_back ">
			
				<div class="container-fluid d-flex flex-column h-100">
					<!-- Top Empty Row -->
					<div class="row"></div>
			
					<!-- Middle Row with White Box -->
					<div class="row d-flex align-items-center justify-content-center">
						<div class="col-md-4  col-lg-10">
							<div class="box">
								<h2>Welcome</h2>
								<p>Don't Think To Much Just Start Your Day.</p>
							<a href="../uploads/login.php">	<button class="btn btn-custom btn-login">Login</button></a>
							<a href="../uploads/register.php">	<button class="btn btn-custom btn-signup">Signup</button></a>
                            <br>
                            <a href="index.php">	<button class="btn btn-custom btn-login vstr">visitor</button></a>

                        </div>
						</div>
					</div>
			
					<!-- Bottom Empty Row -->
					<div class="row">
						
						
						<div class="land-meta ">
							
							<a href="landing.html">	<div class="friend-logo ">
									<span></span>
									
								</div>
								</a>
			
							</div>	
							
						<div class="">
					
					

						
					</div></div>
				</div>
			
			
			
			
			
			
				
						
					</div>
				</div>
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
		
		
		
		</div>
	

	
	<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="js/main.min.js"></script>
	<script src="js/script.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>	

</html>