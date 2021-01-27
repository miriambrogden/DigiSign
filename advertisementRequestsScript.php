<?php
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";

$response = array (
    'status' => 0,
    'message' => 'Something went wrong.',
    'id' => 0,
);

// If form is submitted
if(isset($_POST['id']) && isset($_POST['action']) ) {
    // Get the submitted form data
    $ad_id = $_POST['id'];
    $ad_action = $_POST['action'];
    $ad_action_sql = "";
    $date_approved_query = "";

    if ($ad_action == "APPROVE") {
        $ad_action_sql = "APPROVED";
        $date_approved_query=', date_approved="'.date("Y-m-d").'"';
    }
    if ($ad_action == "DENY") {
        $ad_action_sql = "DENIED";
    }


    //$email = $login_session;
    $getEmailQuery =    "SELECT user.email from advertisement
                        INNER JOIN user on advertisement.user_id=user.id
                        WHERE advertisement.id=".$ad_id;

    $emailQueryResult = $link->query($getEmailQuery);
    $row = $emailQueryResult->fetch_assoc();
    $email = $row['email'];

    $emailName = explode('@',$email)[0];

    $response['id'] = $ad_id;

    $query = 'UPDATE advertisement SET status = "'.$ad_action_sql.'"'.$date_approved_query.' WHERE id = '.$_POST["id"].';';
    $result = $link->query($query);



    if ($result) {
        $response['message'] = 'Success! Email sent to: '.$email;
        $response['status'] = 1;

        $hashEmail = hash("md5", $temp);
        $uniqueURL = "http://miriamsnow.com/digisign/passwordResetScript.php?id=".$hashEmail;

        //send email
        $boundary = md5("sanwebe");
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From:noreply@digisign4250.ca\r\n";
        $headers .= "Reply-To: ".$email."" . "\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";
        $message = "<p>Hello!</p><p>Your space owner has ".$ad_action_sql." your advertisement request.</p>";
        if ($_POST['comment']) {
            $message = $message."<p>Here are their comments: </p>";
            $message = $message."<p>".$_POST['comment']."</p>";
        }
        $message = $message."<p></p><p>Sincerely,<br>The DigiSign Design Team</p>";

        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($message));
        mail($email, "DigiSign - Your Advertisement Status Has Changed", $body, $headers);

    }

}

// Return response
echo json_encode($response);
