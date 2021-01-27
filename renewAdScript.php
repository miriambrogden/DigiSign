<?php
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";
$uploadDir = 'userImg/';
$response = array(
    'status' => 0,
    'message' => '<p><strong>Sorry, your advertisement was not uploaded. Please refresh the page and try again.</strong></p>'
);

    $start = $_POST['start'];
    $end = $_POST['end'];
    date_default_timezone_set('America/Toronto');
    $currDate = date("Y-m-d");
    $comment = $_POST['comment'];
    $adStatus = "PENDING";
    $adID = $_POST['adID'];



    // Check whether submitted data is not empty

    if (!($stmt = $link->prepare("UPDATE advertisement SET start_date= ?, end_date= ?, date_requested= ?, comment= ?, date_approved= ' ', status= 'PENDING' WHERE id= ?"))) {
        echo "Prepare failed: (" . $link->errno . ") " . $link->error;
    }
    if (!$stmt->bind_param("sssss", $start, $end, $currDate, $comment,  $adID)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }


    if ($stmt){
        $getSpaceOwn = "SELECT email from user WHERE permission='SPACE_OWNER'";
        $getSpaceOwnResult = $link->query($getSpaceOwn);
        $rowspace = $getSpaceOwnResult->fetch_assoc();
        $spaceEmail = $rowspace["email"]; // get space owner email address

        $query = "SELECT * FROM advertisement WHERE id='".$adID."'";
        $result = $link->query($query);
        $row = $result->fetch_assoc();
        $adTitle = $row['title']; // get advertisement title

        //send email to space owner
        $boundary = md5("sanwebe");
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From:noreply@digisign4250.ca\r\n";
        $headers .= "Reply-To: ".$spaceEmail."" . "\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";
        $message = "<p>Dear Space Owner,</p><p>You have received a request for a new advertisement for your space. For a full list of details and to approve or deny the request, please <a href='http://miriamsnow.com/digisign/index.php'>log in</a>.</p><p><strong>Advertiser: </strong>".$login_session."</p><p><strong>Ad Title: </strong>".$adTitle."</p><p><strong>Start Date: </strong>".$start."</p><p><strong>End Date: </strong>".$end."</p><p><strong>Date Submitted: </strong>".$currDate."</p><p><strong>Comments: </strong>".$comment."</p>";
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($message));
        $mailSent = mail($spaceEmail, "DigiSign - New Advertisement Request", $body, $headers);

        if ($mailSent){
            $response['status'] = 1;
            $response['message'] = '<p><strong>Thank you! Your advertisement has been submitted for approval.</p><p>Click here to <a href="pendingAdvertisements.php" >view pending ads</a> or <a href="newAdvertisement.php">create another advertisement</a>.</strong></p>';
        } else {
             $response['message'] = "<p><strong>We're sorry, there has been an error. Please refresh the page and try again.</strong></p>";
        }
    } else {
         $response['message'] = "<p><strong>We're sorry, there has been an error with updating your information. Please refresh the page and try again.</strong></p>";
    }

// Return response
echo json_encode($response);
