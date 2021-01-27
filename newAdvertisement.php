<?PHP
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";
include $dir."inc/header.php";
if ($userType == "ADVERTISER"){ include $dir."inc/advertiserMenu.php";} else {include $dir."inc/spaceOwnerMenu.php";}
include "inc/footer.php";
?>

<!-- Date Picker Style sheet
Date Picker From: https://github.com/wakirin/Lightpick -->
<link rel="stylesheet" type="text/css" href="css/lightpick.css">

<div class="mainContent">
    <h3>Upload New Advertisement</h3>
    <div class="statusMsg"> </div>
    <p>Upload images as jpg, jpeg, or png format, 1900px x 800px.</p>
    <p>Upload videos as mp4, mov, or wmv format, maximum 20MB.</p>
    <form id="advertUploadForm" enctype="multipart/form-data">
        <p><input type="file" required id="file" onchange="loadFile(event)" name="file" /> <!-- removed 'required' tag for YouTube video, added it back for R23/47 -->
        <p><input type="text" id="name" name="name" placeholder="Advertisment Title" required /></p>
        <p><input type="email" id="email" name="email" value="<?php echo $login_session; ?>" disabled></p>
        <p><input required type="text" id="datepicker" placeholder="Date"/></p>
        <p><input type="text" id="comment" name="comment" placeholder="Comments" /></p>
        <p><input type="submit" name="submit" class="submit" value="SUBMIT" /></p>
    </form>
</div>

<div class="imgPreview">
    <img id="imgPreviewTarget" style="max-width:600px; padding:5%" />
</div>

<div class="vidPreview">
    <video id="vidPreviewTarget" width="320" height="240" controls muted style="display: none;">
        <source src="" type="video/mp4">
    </video>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="js/lightpick.js"></script>

<script>
    var picker = new Lightpick({
        field: document.getElementById('datepicker'),
        singleDate: false,
    });
</script>

<script>
    var loadFile = function(event) {
        var output; // view to show
        var hide;   // view to be hidden
        var fileType = event.target.files[0].type;

        // TODO: test support for .WMV files
        if (fileType.includes( 'video/')) {
            output = document.getElementById( 'vidPreviewTarget' ); 
            hide = document.getElementById( 'imgPreviewTarget' );     
        } else {
            output = document.getElementById( 'imgPreviewTarget' );
            hide = document.getElementById( 'vidPreviewTarget' ); 
        }
        console.log( output );
        console.log( event.target.files[0] );
        console.log( event.target.files[0].type );
        output.src = URL.createObjectURL(event.target.files[0]);
        output.style.display = "block";
        hide.style.display = "none";
    };

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
                    url: 'newAdvertisementScript.php',
                    data: formdata,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,

                }).done(function (response) {
                    console.log(response);
                    $('.statusMsg').html(response.message);
                    if (response.status == 0) {
                        $(".submit").prop('disabled', false);
                    }
                });
            }
        });
    });
</script>
