<?PHP
$dir ="";
include $dir."login.php";
include $dir."inc/header.php";
include $dir."inc/logoBar.php";
?>
<div class="blueBar"></div>
<nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav auto">
        <li class="nav-item">
            <a class="nav-link" href="passwordReset.php">Forgot Password?</a>
        </li>
    </ul>
</nav>
<div class="greenBar"></div>

<?PHP

if (isset($_SESSION['login_user'])){
header("location: index.php");
}
?>


<div class="mainContent">
    <h1>Login</h1>
    <div id="login">
        <form action="" method="post">
            <p>&nbsp;</p>
            <p><input id="name" placeholder="Username" name="username" type="text"></p>
            <p><input id="password" placeholder="Password" name="password" type="password"></p>
            <p>
                <!-- google recaptcha -->
                <script>function recaptchaCallback() {$('#submit').removeAttr('disabled');}</script>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                <div class="g-recaptcha" data-callback="recaptchaCallback" data-expired-callback="capcha_expired" data-sitekey="6LePhGoUAAAAAKpDtKq63RD2rV90Luv6UP_PyymM"></div>
            </p>
            <!-- submit button -->
            <p><input name="submit" class="submit" type="submit" id="submit" disabled value="Submit"></p>
            <p>
                <?php echo $error; ?>
            </p>
        </form>
    </div>
</div>


<?PHP include $dir."inc/footer.php"; ?>
