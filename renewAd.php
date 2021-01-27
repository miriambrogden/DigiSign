<?PHP
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";
include $dir."inc/header.php";
if ($userType == "ADVERTISER"){ include $dir."inc/advertiserMenu.php";} else {include $dir."inc/spaceOwnerMenu.php";}
include "inc/footer.php";
$adID = $_GET['id'];

$query = "SELECT * FROM advertisement WHERE id='".$adID."' AND status='APPROVED'";
$result = $link->query($query);
$row = $result->fetch_assoc();
$title = $row['title'];
$contentURL = $row['content_url'];
$contentType = $row['content_type'];
$dateApproved = $row['date_approved'];
$user_id = $row['user_id'];

date_default_timezone_set('America/Toronto');
$currDate = date("Y-m-d");

?>

<!-- Date Picker Style sheet
Date Picker From: https://github.com/wakirin/Lightpick -->
<link rel="stylesheet" type="text/css" href="css/lightpick.css">

<div class="mainContent">
    <h3>Renew Advertisement</h3>
    <div class="statusMsg"> </div>

    <p><input type="text" value="<?php echo $title; ?>" disabled /></p>
    <p><input type=" email" id="email" name="email" value="<?php echo $login_session; ?>" disabled></p>


    <form id="advertUploadForm" enctype="multipart/form-data">

        <p><input required type="text" id="datepicker" placeholder="Date" /></p>
        <p><input type="text" id="comment" name="comment" placeholder="Comments" /></p>
        <p><input type="hidden" id="adID" name="adID" value="<?php echo $adID; ?>" /></p>
        <p><input type="submit" name="submit" class="submit" value="SUBMIT" /></p>
    </form>

</div>

<div class="imgPreview">
    <?php
    if ($contentType == "VIDEO"){
        echo '<video muted controls style="max-width: 600px;"> <source src="userImg/'.$contentURL.'" type="video/mp4"></video>';
    } else {
        echo '<img style="max-width:600px; padding:5%" src="userImg/'.$contentURL.'" />';
    }
    ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="js/lightpick.js"></script>
<script>
    var picker = new Lightpick({
        field: document.getElementById('datepicker'),
        singleDate: false,
    });

    $(document).ready(function(e) {
        // Submit form data via Ajax
        $("#advertUploadForm").on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            $(".submit").prop('disabled', true);
            $(".statusMsg").empty();

            var start_date = picker.getStartDate();
            var end_date = picker.getEndDate();

            if (start_date == null || end_date == null) {
                $('.statusMsg').html("Please enter a valid date.");
                $(".submit").prop('disabled', false);
            } else if (!start_date.isValid() || !end_date.isValid()) {
                $('.statusMsg').html("Please enter a valid date.");
                $(".submit").prop('disabled', false);
            } else {

                var formdata = new FormData(this);
                var start_date_str = start_date.format("YYYY-MM-DD");
                var end_date_str = end_date.format("YYYY-MM-DD");
                formdata.append('start', start_date_str);
                formdata.append('end', end_date_str);

                $.ajax({
                    type: 'POST',
                    url: 'renewAdScript.php',
                    data: formdata,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,

                    success: function(response) {
                        console.log(response);
                        $('.statusMsg').html(response.message);

                    },
                    fail: function(response) {
                        console.log(response);
                        $('.statusMsg').html(response.message);
                    }

                });
            }
        });
    });

</script>
