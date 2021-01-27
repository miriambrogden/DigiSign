<?PHP
    include "inc/connection.php";
    include "session.php";
    include "inc/header.php";
    include "inc/spaceOwnerMenu.php";
include "inc/footer.php";
?>

<div class="mainContent">
    <h3>New Advertiser Account</h3>

    <?PHP

    if (isset( $_POST['create'] )){
        //get data submitted through form and assign to variables
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $secure_pswd = hash("md5", $password);
        // verify input
        if (test_email( $email ) == "") {
            echo "<strong>Email must be in format address@provider.ext. Please try again.</strong>";
        } else {

            if (!($stmt = $link->prepare("SELECT id FROM user WHERE email= ?"))) {
                echo "Prepare failed: (" . $link->errno . ") " . $link->error;
            }
            if (!$stmt->bind_param("s", $email)) {
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            $result = $stmt->get_result();
            $rowsemail = $result->num_rows;

            if ($rowsemail != 0) {
                echo "<strong>An account already exists with this email address. Please try again.</strong>";
            } else {

                if (!($stmt = $link->prepare("INSERT INTO user (name, email, password, permission) VALUES (?, ?, ?, 'ADVERTISER')"))) {
                    echo "Prepare failed: (" . $link->errno . ") " . $link->error;
                }
                if (!$stmt->bind_param("sss", $name, $email, $secure_pswd)) {
                    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }
                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                }

                if ($stmt){
                    //send email
                    $boundary = md5("sanwebe");
                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "From:noreply@digisign4250.ca\r\n";
                    $headers .= "Reply-To: ".$email."" . "\r\n";
                    $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";
                    $message = "<p>Hello!</p>You have been signed up for a DigiSign Advertiser Account!</p><p>Please contact your Space Owner for your password.</p><p><a href='http://miriamsnow.com/digisign/index.php'>Click Here</a> to log in to your account.</p><p>Sincerely,<br>The DigiSign Design Team</p>";
                    $body = "--$boundary\r\n";
                    $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
                    $body .= chunk_split(base64_encode($message));
                    mail($email, "DigiSign - Account Activation", $body, $headers);

                    echo "<strong>Thank you, an email has been sent to the email address you provided.</strong>";

                } else {
                    echo "<strong>Sorry, there was an error. Please refresh the page and try again.</strong>";
                }
            }
        }
    }

    function test_email( $data ) {
        $safe = $copy = $data;
        $strings1 = explode( "@", $copy );
        $strings2 = explode( ".", $data );

        if (count( $strings1 ) != 2 || count( $strings2 ) != 2) {
            return "";
        } elseif (empty( $strings2[1] )) {
            return "";
        }

        return $safe;
    }
?>
    <p>Create accounts for advertisers you wish to display ads in your space.</p>
    <form method="post" action="">
        <p><input placeholder="Name" type="text" required name="name" id="name"></p>
        <p><input placeholder="Email" type="text" required name="email" id="email"></p>
        <p><input placeholder="Password" type="password" required name="password" id="password"></p>
        <input type="submit" class="submit" name="create" value="Create Account" id="create">
    </form>
</div>
