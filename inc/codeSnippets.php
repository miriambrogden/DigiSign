<?PHP
$dir = "../";
include $dir."inc/connection.php";
include $dir."inc/header.php";
if ($userType == "ADVERTISER"){ include $dir."inc/advertiserMenu.php";} else {include $dir."inc/spaceOwnerMenu.php";}
include $dir."inc/footer.php";
?>


<div class="mainContent">
    <h1>Welcome!</h1>
    <p>Main content of page goes here.</p>
    <p>User:
        <?php echo $login_session; ?>
    </p>

    <!-- php date function: https://www.php.net/manual/en/function.date.php -->
    <p>The current date is
        <?PHP echo date("l F jS Y"); ?>
    </p>

    <h2>Here we will query the info in the user table</h2>

    <?PHP 
        $query = "SELECT * FROM user ORDER BY id DESC";
        
        // $link variable is the connection to the db in the connection.php file
        $result = $link->query($query);
           
        //row index value is column in the db table
        while($row = $result->fetch_assoc()) {
            echo "<p>Name: ".$row['name']."</p>";
            echo "<p>Email: ".$row['email']."</p>";
            echo "<p>Password: ".$row['password']."</p>";
            echo "<p>ID: ".$row['id']."</p>";
            echo "<p>&nbsp;</p>";
        }

?>


    <h2>Here is a simple form that adds info to the DB</h2>

    <form method="post" action="">
        <p>Name:<input type="text" required name="name" id="name"></p>
        <p>Email:<input type="text" required name="email" id="email"></p>
        <p>Password:<input type="text" required name="password" id="password"></p>
        <input type="submit" name="submit" value="Create Account" id="submit">
    </form>

    <?PHP

if (isset($_POST['submit'])){
    //get data submitted through form and assign to variables
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $query = "INSERT INTO users (name, email, password, type) VALUES ('".$name."', '".$email."', '".$password."', 'advertiser')";
    $insert = mysqli_query($link , $query) or die ('Unable to execute query. '. mysqli_error($link));
            
    if ($insert){
        echo "<p>Successfully inserted!</p>";
        echo '<meta http-equiv="Refresh" content="0;' . $_SERVER['PHP_SELF'].'">';
    } else {
        echo "<p>Sorry, there was an error</p>";
    }
    
}

$query = "SELECT * FROM user";
$result = mysqli_query($link, $query); 
$rows = mysqli_num_rows($result);
if ($rows == 1) {
    echo "COOL";
} else {
    echo "NOT COOL";
}
    ?>

</div>

<?php 
$update = "UPDATE advertisement SET start_date='".$newdate1."', end_date='".$newdate2."' WHERE id='".$adID."'";

date_default_timezone_set('America/Toronto');
$currDate = date("Y-m-d");


$ad_email_query = "SELECT email from user WHERE id='".$user_id."'";
$emailQueryResult = $link->query($getEmailQuery);
$row = $emailQueryResult->fetch_assoc();
$ad_email = $row['email'];


?>
