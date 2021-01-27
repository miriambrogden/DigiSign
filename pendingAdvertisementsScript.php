<?PHP
    include "inc/connection.php";
    include "session.php";

    $adID = $_POST['ad_id'];
    $adImage = $_POST['img'];

    $queryString = "UPDATE advertisement SET status = 'CANCELLED' WHERE id='".$adID."'";

    $query = $link->query( $queryString ) or die ( "Unable to execute UPDATE query".mysqli_error( $link ) );

    // notify the Space Owner by email
    // get SO email address from DB
    $spaceOwnerEmailQuery = "SELECT email FROM user WHERE permission = 'SPACE_OWNER'";
    $spaceOwnerEmail = $link->query( $spaceOwnerEmailQuery ) or die ( "Unable to get Space Owner email".mysqli_error( $link ) );
    $row = $spaceOwnerEmail->fetch_assoc();

    // send email
    $email = $row['email'];
    // $temp = $row['email']; // testing
    // $email = "jacksonfirth1@gmail.com"; // testing
    $boundary = md5( "sanwebe" );
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From:noreply@digisign4250.ca\r\n";
    $headers .= "Reply-To: ".$email."" . "\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";
    $message = "<p>Hello!</p>An Advertiser has cancelled a pending ad for your space.</p><p><a href='http://miriamsnow.com/digisign/index.php'>Click Here</a> to log in to your account and view your ad space.</p><p>Sincerely,<br>The DigiSign Design Team</p>";
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split( base64_encode( $message ) );
    mail( $email, "DigiSign - Advertisement Cancellation", $body, $headers );
    //echo "Thank you, an email has been sent to the email address you provided.";

    $myObj->id = $adID;
    $myObj->img = $adImage;

	$myJSON = json_encode( $myObj );

	echo $myJSON;

?>
