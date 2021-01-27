<?PHP
$dir = "";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/logoBar.php";
?>
<div class="blueBar"></div>
<nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav auto">
        <li class="nav-item">
            <a class="nav-link" href="logout.php">Log In</a>
        </li>
    </ul>
</nav>
<div class="greenBar"></div>

<?PHP include $dir."inc/footer.php"; ?>

<?PHP
$id = $_GET['id'];
$userEmail = "";
//only display info on page if there is an id that is real
if ($id){
    $query = "SELECT * from user";
    $result = $link->query($query);
    while($row = $result->fetch_assoc()) {
        $temp = $row['email'].$row['name'].$row['id'];
        if ($id == hash("md5",$temp)){
            $userEmail = $row['email'];
        }
    }
    if ($userEmail){
?>

<div class="mainContent">
    <h3>Change Password for
        <?php echo $userEmail; ?>
    </h3>

    <?PHP
    //new password form
    if (isset($_POST['submit'])){
        $newPassword1 = stripslashes($_POST['password']);
        $newPassword2 = stripslashes($_POST['password2']);
        if ($newPassword1 == $newPassword2){
            $hashPassword1 = hash("md5", $newPassword1);

            if (!($stmt = $link->prepare("UPDATE user SET password= ? WHERE email= ?"))) {
                echo "Prepare failed: (" . $link->errno . ") " . $link->error;
            }
            if (!$stmt->bind_param("ss", $hashPassword1, $userEmail)) {
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                echo "<p><strong>We're sorry, a problem occured. Please refresh the page and try again.</strong></p>";
            }
            else {
                echo "<p><strong>Thank you, your password has been changed, click to <a href='logout.php'>Log In</a>.</strong></p>";
            }

        } else {
            echo "<p><strong>Passwords do not match. Please try again.</strong></p>";
        }
    }
    ?>
    <p>Please fill out the form below to reset your password.</p>
    <form action="" method="post">
        <p><input type="password" required name="password" id="password" placeholder="New Password"></p>
        <p><input type="password" required name="password2" id="password2" placeholder="Confirm New Password"></p>
        <p><input type="submit" class="submit" name="submit" id="submit" value="Submit"></p>
    </form>
</div>

<?php
//if user email is not true, send them back to index page
    } else {
        echo '<meta http-equiv="refresh" content="0;url=index.php">';
    }
//if id is not true, send them back to index page
} else {
    echo '<meta http-equiv="refresh" content="0;url=index.php">';
}
?>
