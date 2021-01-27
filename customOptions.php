<?PHP
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";
include $dir."inc/header.php";
if ($userType == "ADVERTISER"){ include $dir."inc/advertiserMenu.php";} else {include $dir."inc/spaceOwnerMenu.php";}
include "inc/footer.php";
?>

<div class="mainContent">
    <h3>Custom Options</h3>
    <p>Adjust custom options for the preview screen.</p>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Upload Logo
                        </h5>
                        <div class="statusMsg"> </div>
                        <p>Acceptable files must be in jpg format, 100px tall X 200px wide.</p>
                        <form id="logoUploadForm" enctype="multipart/form-data">
                            <p><input type="file" id="file" onchange="loadFile(event)" name="file" required /></p>
                            <p><input type="submit" name="submit" class="submit" value="Upload Logo" /></p>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <p><img id="imgPreviewTarget" src="http://firehallartscentre.ca/wp/wp-content/uploads/2017/07/white-square-for-shit.jpg" style="max-height:200px;" /></p>
            </div>
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Twitter Timeline
                        </h5>
                        <?php
                        if (isset($_POST['submitTwitter'])){
                            $myfile = fopen("inc/twitter.txt", "w") or die("Unable to open file!");
        
                            if ($myfile){
                                $txt = $_POST['twitter'];
                                fwrite($myfile, $txt);
                                echo '<p><strong>Thank you! Your Twitter handle has been updated to '.$_POST['twitter'].'</strong></p>';
                            } else {
                                echo '<p><strong>Sorry, there was an error. Please refresh the page and try again.</strong></p>';
                            }
                            fclose($myfile);
                        }
                        ?>
                        <p>Please type your twitter handle in the space below.<br>Omit the @ symbol.</p>
                        <form action="" method="post">
                            <p><input type="text" id="twitter" name="twitter" placeholder="myhandle" required /></p>
                            <p><input type="submit" name="submitTwitter" class="submit" id="submitTwitter" value="Insert Timeline" /></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p>&nbsp;</p>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Scrolling Text
                        </h5>
                        <?php
                        if (isset($_POST['submitScrolling'])){
                            $textFile = fopen("inc/scrolling.txt", "w") or die("Unable to open file!");
        
                            if ($textFile){
                                $txt2 = $_POST['scrolling'];
                                fwrite($textFile, $txt2);
                                echo '<p><strong>Thank you! Your scrolling text has been updated to <br>'.$_POST['scrolling'].'</strong></p>';
                            } else {
                                echo '<p><strong>Sorry, there was an error. Please refresh the page and try again.</strong></p>';
                            }
                            fclose($textFile);
                        }
                        ?>
                        <p>Please type your scrolling text in space below.</p>
                        <form action="" method="post">
                            <textarea id="scrolling" cols="40" rows="4" name="scrolling" maxlength="1000" required></textarea>
                            <p><input type="submit" name="submitScrolling" class="submit" id="submitScrolling" value="Update Scroll" /></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    var loadFile = function(event) {
        var output = document.getElementById('imgPreviewTarget');
        output.src = URL.createObjectURL(event.target.files[0]);
    };

    $(document).ready(function(e) {
        // Submit form data via Ajax
        $("#logoUploadForm").on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            $(".submit").prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: 'uploadLogoScript.php',
                data: new FormData(this),
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
        });
    });

</script>
