<?PHP
$dir = "";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/logoBar.php";
?>
<div class="blueBar"></div>
<nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav auto">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Go Back</a>
        </li>
    </ul>
</nav>
<div class="greenBar"></div>

<?PHP include $dir."inc/footer.php"; ?>

<div class="mainContent">
    <h3>Change Your Password</h3>
    <?PHP
    if (isset($_POST['submit'])){
        //verify user
        $email = stripslashes($_POST['email']);
        $query = "SELECT * FROM user WHERE email= '".$email."'";
        $result = mysqli_query($link, $query);
        $rows = mysqli_num_rows($result);

        $result2 = $link->query($query);
        while($rows2 = $result2->fetch_assoc()) {
            $temp = $rows2['email'].$rows2['name'].$rows2['id'];
        }

        if ($rows == 1) {
            // get unique url for user
            echo "<p><strong>Thank you! You will recieve an email shortly with password reset instructions.</strong></p>";
            $hashEmail = hash("md5", $temp);
            $uniqueURL = "http://miriamsnow.com/digisign/passwordResetScript.php?id=".$hashEmail;

            //send email
            $boundary = md5("sanwebe");
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "From:noreply@digisign4250.ca\r\n";
            $headers .= "Reply-To: ".$email."" . "\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";
            $message = "<p>Hello!</p>We received a request to change your DigiSign login information.</p><p>Please <a href='".$uniqueURL."'>Click Here</a> to change your password.</p><p>Sincerely,<br>The DigiSign Design Team</p>";
            $body = "--$boundary\r\n";
            $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= chunk_split(base64_encode($message));
            mail($email, "DigiSign - Change Your Password", $body, $headers);
        } else {
            echo "<p><strong>We're sorry, there is no account associated with that email address. Please try again.</strong></p>";
        }
    }
    ?>
    <p>Please type your email address in the box provided.</p>
    <form method="post" action="">
        <p><input type="text" name="email" required id="email" placeholder="Email"></p>
        <p>
            <!-- google recaptcha -->
            <script>function recaptchaCallback() {$('#submit').removeAttr('disabled');}</script>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <div class="g-recaptcha" data-callback="recaptchaCallback" data-expired-callback="capcha_expired" data-sitekey="6LePhGoUAAAAAKpDtKq63RD2rV90Luv6UP_PyymM"></div>
        </p>
        <p><input type="submit" name="submit" disabled value="Submit" class="submit" id="submit"></p>
    </form>
</div>
