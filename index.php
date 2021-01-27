<?PHP
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";
include $dir."inc/header.php";
if ($userType == "ADVERTISER"){ include $dir."inc/advertiserMenu.php";} else {include $dir."inc/spaceOwnerMenu.php";}
?>


<div class="mainContent">
    <?php
    $query = "SELECT name FROM user WHERE email = '".$login_session."'";
    $result = $link->query($query);
    $row = $result->fetch_assoc();
    ?>
    <h2>Welcome <?php echo $row['name']; ?>!</h2>
    <p>You are logged in as 
        <?php 
        if ($userType == "ADVERTISER"){
            echo "an <strong>Advertiser</strong>.</p>";
        } else {
            echo "a <strong>Space Owner</strong>.</p>";
        }
        ?>
    <p>Please choose one of the options above to manage advertisements for your digital signage display.</p>
    <p>Sincerely, <br>
    The DigiSign Design Team</p>
</div>


<?PHP include $dir."inc/footer.php"; ?>
