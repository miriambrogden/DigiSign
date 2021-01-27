<?PHP
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";
include $dir."inc/header.php";
if ($userType == "ADVERTISER"){ include $dir."inc/advertiserMenu.php";} else {include $dir."inc/spaceOwnerMenu.php";}

?>


<div class="mainContent">

    <h3>Hey What's Up</h3>
    <?php


        if (!($stmt = $link->prepare("INSERT INTO `user`(`name`, `email`, `password`, `permission`) VALUES (?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $i = "pedro";
        if (!$stmt->bind_param("ssss", $i, $i, $i, $i)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
     ?>

</div>
