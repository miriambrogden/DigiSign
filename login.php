<?php
session_start();

$dir = "";
include $dir."inc/connection.php";

$error='';
if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Username or Password is invalid";
    } else {
        $username = stripslashes($_POST['username']);
        $password = stripslashes($_POST['password']);
        $hashPassword = hash("md5", $password);

        if (!($stmt = $link->prepare("select * from user where password=? AND email=?"))) {
            echo "Prepare failed: (" . $link->errno . ") " . $link->error;
        }
        if (!$stmt->bind_param("ss", $hashPassword, $username)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $result = $stmt->get_result();
        $rows = $result->num_rows;

        if ($rows == 1) {
            $_SESSION['login_user']=$username;
            header("location: index.php");
        } else {
            $error = "Username or Password is invalid";
        }
    }
}
?>
