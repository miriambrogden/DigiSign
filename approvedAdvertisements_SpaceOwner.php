<?PHP
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";
include $dir."inc/header.php";
if ($userType == "ADVERTISER"){ include $dir."inc/advertiserMenu.php";} else {include $dir."inc/spaceOwnerMenu.php";}
include "inc/footer.php";
?>


<div class="mainContent">
    <h3>Approved Advertisements</h3>
    <p>View approved advertisements from all advertisers.<br>(Ordered by "Date Approved", newest to oldest)</p>
<?php

    $query = "SELECT * from advertisement WHERE status='APPROVED' ORDER BY date_approved ASC";
    $result = $link->query($query);

        //row index value is column in the db table
        while($row = $result->fetch_assoc()) {
            $ad_title = $row['title'];
            $content_url = $row['content_url'];
            $content_type = $row['content_type'];
            $ad_comment = $row['comment'];
            $user_id = $row['user_id'];
            $ad_id = $row['id'];
            $ad_start_date_formatted = date("D, M jS, Y", strtotime($row['start_date']));
            $ad_end_date_formatted = date("D, M jS, Y", strtotime($row['end_date']));
            $ad_date_requested_formatted = date("D, M jS, Y", strtotime($row['date_requested']));
            $ad_date_approved_formatted = date("D, M jS, Y", strtotime($row['date_approved']));

            $ad_email_query = "SELECT email from user WHERE id='".$user_id."'";
            $emailQueryResult = $link->query($ad_email_query);
            $row_email = $emailQueryResult->fetch_assoc();
            $ad_email = $row_email['email'];


            echo    '<div class="container">
                        <div class="row">
                            <div class="col-md-4">';

            if ($row['content_type'] == "IMAGE") {
                echo '<img src="userImg/'.$content_url.'"class="card-img-top" width="100%" alt="'.$ad_title.'">';
            } else {
                echo '<video width="100%" autoplay controls muted loop style="align-self: center;"> <source src="userImg/'.$content_url.'" type="video/mp4"></video>';
            }
            echo '</div><div class="col-md-8"><h5 class="card-title">'.$ad_title.'</h5>
                    <h6 class="card-text"><strong>Advertiser:</strong> '.$ad_email.'</h6>
                    <h6 class="card-text"><strong>Start Date:</strong> '.$ad_start_date_formatted.'</h6>
                    <h6 class="card-text"><strong>End Date:</strong> '.$ad_end_date_formatted.'</h6>
                    <h6 class="card-text"><strong>Comments:</strong> '.$ad_comment.'</h6>
                    <h6 class="card-text"><strong>Date Requested:</strong> '.$ad_date_requested_formatted.'</h6>
                    <h6 class="card-text"><strong>Date Approved:</strong> '.$ad_date_approved_formatted.'</h6>
                    <h6 hidden>'.$ad_id.'</h6>
                    </div><p>&nbsp;</p></div></div>';

        }

    ?>

</div>
