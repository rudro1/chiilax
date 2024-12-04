<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "root";
$dbName = "test1";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}
else{
    echo"yeah baby";
}

?>