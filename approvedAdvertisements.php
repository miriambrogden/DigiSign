<?PHP
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";
include $dir."inc/header.php";
if ($userType == "ADVERTISER"){ include $dir."inc/advertiserMenu.php";} else {include $dir."inc/spaceOwnerMenu.php";}
?>

<div class="mainContent">

    <h2>Approved Advertisements for
        <?php echo $login_session; ?>
    </h2>

    <p>Below are your advertisement requests, which have been approved by your Space Owner.<br>(Ordered by "Date Approved", newest to oldest)</p>

    <div class="table-responsive">
        <table id='pendingTable' class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Date Approved</th>
                    <th scope="col">Date Requested</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">File Name</th>
                    <th scope="col">Comments</th>
                </tr>
            </thead>
            <tbody>

                <?php

            date_default_timezone_set('America/Toronto');
            $currDate = date("Y-m-d");

            $getEmailQuery = 'SELECT id from user WHERE email="'.$login_session.'";';
            $emailQueryResult = $link->query($getEmailQuery);
            $row = $emailQueryResult->fetch_assoc();
            $user_id = $row["id"];

            $query = "SELECT * FROM advertisement WHERE status='APPROVED' AND user_id='".$user_id."' ORDER BY start_date ASC";
            $result = $link->query($query);

            while($row = $result->fetch_assoc()) {
                $requestedDate = date("D, M jS, Y", strtotime($row['date_requested']));
                $approvedDate = date("D, M jS, Y", strtotime($row['date_approved']));
                $startDate = date("D, M jS, Y", strtotime($row['start_date']));
                $endDate = date("D, M jS, Y", strtotime($row['end_date']));
                echo "<tr data-toggle='modal' data-target='#".$row['id']."'><th scope='row'>".$row['id']."</th>
                    <td id='title'>".$row['title']."</td>
                    <td id='approved'>".$approvedDate."</td>
                    <td id='requested'>".$requestedDate."</td>
                    <td id='start'>".$startDate."</td>
                    <td id='end'>".$endDate."</td>
                    <td id='file'>".$row['content_url']."</td>
                    <td id='notes'>".$row['comment']."</td></tr>";

                echo '<div id="'.$row['id'].'" class="modal"><div class="modal-dialog modal-lg"><div class="modal-content">
                <div class="modal-header"><h4 id="modalTitle" class="modal-title">Approved Ad: '.$row['title'].'</h4></div>
                <div id="modalBody" class="modal-body">';
                if ($row['content_type'] == "VIDEO"){
                    // Added for Req. 56
                    echo '<video muted controls width="100%;"> <source src="userImg/'.$row['content_url'].'" type="video/mp4"></video>';
                } else {
                    echo '<img id="image" width="100%;" src="userImg/'.$row['content_url'].'" style="padding: 5%">';
                }

                echo '</div>
                <div id="modalFooter"><button type="button" style="margin-left: 5%" class="submit" data-dismiss="modal" aria-label="Close">Go Back</button>';

                if ($currDate > $row['end_date']){
                    ?>
                <p><button onclick="window.location.href='renewAd.php?id=<?php echo $row['id']; ?>';" style="margin-left: 5%; background-color: #6fff00;" class="submit">Renew Ad</button></p>
                <?php
                } else {
                    echo "<p></p>";
                }

                echo '</div></div></div></div>';

            }

            ?>
            </tbody>
        </table>
    </div>
</div>


<?PHP include $dir."inc/footer.php"; ?>
