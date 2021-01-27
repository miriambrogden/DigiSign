<?php
$dir = "";
include $dir."inc/connection.php";
session_start();

$user_check = $_SESSION['login_user'];
$sessionQuery = "select email from user where email='$user_check'";
$sessionResult = mysqli_query($link, $sessionQuery); 
$row = mysqli_fetch_assoc($sessionResult);
$login_session = $row['email'];

if (!isset($login_session)) {
    header('Location: login-form.php');
}
?>
