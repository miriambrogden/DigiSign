<?php
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";

$uploadDir = 'userImg/';
$response = array(
    'status' => 0,
    'message' => '<p><strong>Sorry, your advertisement was not uploaded. Please refresh the page and try again.</strong></p>'
);

// If form is submitted
if (isset( $_POST['name'] ) || isset( $_POST['email'] ) || isset( $_POST['file'] )) {
    // Get the submitted form data
    $name = $_POST['name'];
    $email = $login_session;
    $emailName = explode( '@', $email )[0];

    if (isset( $_POST['comment'] )) {
        $comment = $_POST['comment'];
    } else {
        $comment = "";
    }

    $start = $_POST['start'];
    $end = $_POST['end'];
    date_default_timezone_set( 'America/Toronto' );
    $currDate = date( "Y-m-d" );
    // $videoLink = $_POST['link']; // added for YouTube linking, removed for R23/47

    // Check whether submitted data is not empty
    if ($name) {
        $uploadStatus = 1;
        $uploadedFile = '';
        $isVideo = false;

        if ($_FILES["file"]["name"]) {
            // File path config
            $fileName = basename( $_FILES["file"]["name"] );
            $fileType = pathinfo( $fileName, PATHINFO_EXTENSION );

            // Renaming file again with timestamp
            $fileName = "$emailName"."_".strval(time()).".".$fileType;

            // Combine upload directory path with upload file name
            $targetFilePath = $uploadDir . $fileName;

            // Allowed file formats (adding .mp4, .mov, .wmv for requirement(s) 23/47)
            $allowTypes = array('jpg', 'png', 'jpeg', 'JPG', 'PNG', 'JPEG', 'mp4', 'MP4', 'mov', 'MOV', 'wmv', 'WMV' );
            $videoTypes = array( 'mp4', 'MP4', 'mov', 'MOV', 'wmv', 'WMV' );

            if (in_array( $fileType, $allowTypes )) {
                if (in_array( $fileType, $videoTypes )) {
                    $isVideo = true;
                }
                // Upload file to the server
                if (move_uploaded_file( $_FILES["file"]["tmp_name"], $targetFilePath )) {
                    $uploadedFile = $fileName;
                } else {
                    $uploadStatus = 0;
                    $response['message'] = 'Sorry, there was an error uploading your file.';
                }
            } else {
                $uploadStatus = 0;
                // modified response message to include file types for videos
                $response['message'] = "<p><strong>Sorry, only JPG, JPEG, & PNG files are accepted for images, use MP4, MOV, or WMV for video files.</strong></p>";
            }
        }

        if ($uploadStatus == 1) {
            $getEmailQuery = 'SELECT id from user WHERE email="'.$email.'";';
            $emailQueryResult = $link->query( $getEmailQuery );
            $row = $emailQueryResult->fetch_assoc();
            $user_id = $row["id"];
            $content_type = "IMAGE";
            if ($isVideo == true) {
                $content_type = "VIDEO";
            }

            if ($user_id) {
                // Insert form data in the database

                if (!($stmt = $link->prepare("INSERT INTO advertisement (title,content_type,content_url,user_id,date_requested,start_date,end_date,status,date_approved,comment) VALUES (?, ?, ?, ?, ?, ?, ?, 'PENDING', ' ', ?)"))) {
                    echo "Prepare failed: (" . $link->errno . ") " . $link->error;
                }
                if (!$stmt->bind_param("ssssssss", $name, $content_type, $uploadedFile, $user_id, $currDate, $start, $end, $comment)) {
                    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }
                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                }

                $getSpaceOwn = "SELECT email from user WHERE permission='SPACE_OWNER'";
                $getSpaceOwnResult = $link->query( $getSpaceOwn );
                $row = $getSpaceOwnResult->fetch_assoc();
                $spaceEmail = $row["email"];

                $getAdName = "SELECT email from user WHERE id='".$user_id."'";
                $getAdNameResult = $link->query( $getAdName );
                $row = $getAdNameResult->fetch_assoc();
                $adName = $row["email"];

                //send email to space owner
                $boundary = md5( "sanwebe" );
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "From:noreply@digisign4250.ca\r\n";
                $headers .= "Reply-To: ".$spaceEmail."" . "\r\n";
                $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";
                $message = "<p>Dear Space Owner,</p><p>You have received a request for a new advertisement for your space. For a full list of details and to approve or deny the request, please <a href='http://miriamsnow.com/digisign/index.php'>log in</a>.</p><p><strong>Advertiser: </strong>".$adName."</p><p><strong>Ad Title: </strong>".$name."</p><p><strong>Start Date: </strong>".$start."</p><p><strong>End Date: </strong>".$end."</p><p><strong>Date Submitted: </strong>".$currDate."</p><p><strong>Comments: </strong>".$comment."</p>";
                $body = "--$boundary\r\n";
                $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
                $body .= chunk_split( base64_encode( $message ) );
                mail( $spaceEmail, "DigiSign - New Advertisement Request", $body, $headers );

                if ($stmt) {
                    $response['status'] = 1;
                    $response['message'] = '<p><strong>Thank you! Your advertisement has been submitted for approval.</p><p>Click here to <a href="pendingAdvertisements.php" >view pending ads</a> or <a href="newAdvertisement.php">create another advertisement</a>.</strong></p>';
                }
            }
        }
    } else {
         $response['message'] = '<p><strong>Please fill all the mandatory fields (name and email).</strong></p>';
    }
}

// Return response
echo json_encode( $response );
