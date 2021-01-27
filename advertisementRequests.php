<?PHP
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";
include $dir."inc/header.php";
if ($userType == "ADVERTISER"){ include $dir."inc/advertiserMenu.php";} else {include $dir."inc/spaceOwnerMenu.php";}
include "inc/footer.php";

?>


<div class="mainContent">
    <h3>Advertisement Requests</h3>
    <p>Approve and deny requests for new advertisements from all advertisers.<br>(Ordered by "Date Requested", newest to oldest)</p>
    <?PHP
    $query = "SELECT * FROM advertisement WHERE advertisement.status = 'PENDING' ORDER BY advertisement.date_requested DESC";

    if ($result = $link->query($query)) {
        /* fetch associative array */
        while ($row = $result->fetch_assoc()) {
            $ad_id = $row["id"];
            $ad_title = $row["title"];
            $ad_content_type = $row["content_type"];
            $ad_src = $row["content_url"];
            $ad_user_id = $row["user_id"];
            $ad_date_requested = $row["date_requested"];
            $ad_start_date = $row["start_date"];
            $ad_end_date = $row["end_date"];
            $ad_status = $row["status"];
            $ad_date_approved = $row["date_approved"];
            $ad_comment = $row["comment"];
            $ad_email = $row["email"];
            $ad_start_date_formatted = date("D, M jS, Y", strtotime($ad_start_date));
            $ad_end_date_formatted = date("D, M jS, Y", strtotime($ad_end_date));
            $ad_date_requested_formatted = date("D, M jS, Y", strtotime($ad_date_requested));
?>
    <div class="container">
        <div class="row">

            <?php
            echo '<div class="col-md-4" style="width: 600px;">';

            if ($ad_content_type == "VIDEO"){
                echo '<video width="320" autoplay controls muted> <source src="userImg/'.$ad_src.'" type="video/mp4"></video>';
            } else {
                echo '<img src="userImg/'.$ad_src.'" class="card-img-top" alt="...">';
            }
               echo ' </div>
                <div class="col-md-4" >
                    <h5 class="card-title">'.$ad_title.'</h5>
                    <h6 class="card-text"><strong>Advertiser:</strong> '.$ad_user_id.'</h6>
                    <h6 class="card-text"><strong>Start Date:</strong> '.$ad_start_date_formatted.'</h6>
                    <h6 class="card-text"><strong>End Date:</strong> '.$ad_end_date_formatted.'</h6>
                    <h6 class="card-text"><strong>Comments:</strong> '.$ad_comment.'</h6>
                    <h6 class="card-text"><strong>Date Requested:</strong> '.$ad_date_requested_formatted.'</h6>
                    <h6 hidden>'.$ad_id.'</h6>
                    <p>&nbsp;</p>
                </div>
                <div class="col-md-2">
                    <p><button id="'.$ad_id.'" class="btn btn-success ">Approve</button></p>
                    <p><button id="'.$ad_id.'" class="btn btn-danger">Deny</button></p>
                </div></div></div>';

        }

        /* free result set */
        $result->free();
    }
    ?>



</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
    $(".btn-success").on('click', function(event) {
        $("[id=" + this.id + "]").attr("disabled", true);
        event.stopPropagation();
        event.stopImmediatePropagation();
        data = new FormData();
        data.append('id', this.id);
        data.append('action', "APPROVE");

        $.ajax({
            type: 'POST',
            url: 'advertisementRequestsScript.php',
            data: data,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,

            success: function(response) {
                console.log(response);
                if (response.status == 1) {
                    $("[id=" + response.id + "]").last().after('<button id="approvedbtn" class="btn btn-success ">Approved!</button>');
                    $("[id=" + response.id + "]").remove();
                    setTimeout(function() {
                        $("[id=approvedbtn]").parent().parent().parent().remove();
                    }, 1500);
                }
            },
            fail: function(response) {
                console.log(response);
                alert("Something broke");
            }
        });
    });

    $(".btn-danger").on('click', function(event) {
        $("[id=" + this.id + "]").attr("disabled", true);
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("[id=" + this.id + "]").last().after('<div><br><p><input type="text" id="' + this.id + '" class="deny-comments rounded-0" placeholder="Please Provide Comments" id="exampleFormControlTextarea1" rows="10"></input></p><p><button id="' + this.id +'" class="btn btn-outline-secondary denySubmit">Send</button></p></div>');
    });

    $(document).on('click', '.denySubmit', function(event) {
        data = new FormData();
        data.append('id', this.id);
        data.append('action', "DENY");
        data.append('comment', $("#"+this.id+".deny-comments").first().val());

        $.ajax({
            type: 'POST',
            url: 'advertisementRequestsScript.php',
            data: data,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,

            success: function(response) {
                console.log(response);
                if (response.status == 1) {
                    $("[id=" + response.id + "]").last().after('<button id="deniedbtn" class="btn btn-danger ">Denied</button>');
                    $("[id=" + response.id + "]").remove();
                    setTimeout(function() {
                        $("[id=deniedbtn]").parent().parent().parent().parent().parent().parent().remove();
                    }, 1500);

                }
            },
            fail: function(response) {
                console.log(response);
                alert("Something broke");
            }
        });
    });

    $(document).keyup(function(event) {
        if ($(".deny-comments").is(":focus") && event.key == "Enter") {
            var thisID = $(":focus").attr('id');
            $("#"+thisID+".denySubmit").first().click();
        }

    });




    $("#explode").on('click', function(event) {
        event.stopPropagation();
        event.stopImmediatePropagation();

    });

</script>
