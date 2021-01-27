<?PHP
    $dir = "";
    include $dir."inc/connection.php";
    include $dir."session.php";
    include $dir."inc/header.php";
?>

<style>
.shadow {
    -webkit-filter: drop-shadow(5px 5px 5px #222);
    filter: drop-shadow(5px 5px 5px #222);
}
</style>

<!-- <body style="background-image: url('inc/digisign preview.jpg');  background-size: 100%; background-repeat: no-repeat;"> -->
<body>
    <div class="row" style="padding: 1% 1% 1% 1.7%;">
        <div class="col-md-2">

            <?php
            $myfile = fopen("inc/twitter.txt", "r") or die("Unable to open file!");
            $twitterHandle = fread($myfile,filesize("inc/twitter.txt"));
            fclose($myfile);

            ?>
            <a class="twitter-timeline" data-width="500" data-height="350" data-chrome="nofooter noborders noscrollbar" href="https://twitter.com/<?php echo $twitterHandle; ?>?ref_src=twsrc%5Etfw"></a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

            <div style="margin-left: 20%;">
                <iframe scrolling="no" frameborder="no" clocktype="html5" style="overflow:hidden;border:0;margin:0;padding:0;width:125px;height:125px;" src="https://www.clocklink.com/html5embed.php?clock=041&timezone=Canada_Toronto&color=blue&size=200&Title=&Message=&Target=&From=2019,1,1,0,0,0&Color=blue"></iframe>
            </div>

        </div>
        <div class="col-md-10">
            <div class="w3-content shadow w3-display-container">
                <?PHP
            $query = "SELECT * FROM advertisement WHERE status ='APPROVED' ORDER BY start_date ASC";
            $result = $link->query($query);
            $count = 0;
            date_default_timezone_set('America/Toronto');
            $currDate = date("Y-m-d");

            while($row = $result->fetch_assoc()) {
                if ($row['start_date'] <= $currDate AND $row['end_date'] >= $currDate){
                    if ($row['content_type'] == 'VIDEO') {
                        // TODO: add support for .WMV files
                        echo '<video class="scroll mySlides w3-animate-opacity" muted autoplay loop> <source src="userImg/'.$row['content_url'].'" type="video/mp4" width="100%" height="100%"> Your browser does not support the video tag.</video>';
                    } else {
                        echo "<img class='scroll mySlides w3-animate-opacity' src='userImg/".$row['content_url']."'>";
                    }

                    $count++;
                }
            }
            if ($count == 0) {
                $time = 10;
            } else {
                $time = $count * 10;
            }
        ?>

                <div class="w3-center w3-container w3-section w3-large w3-text-white w3-display-bottommiddle" style="width:100%">
                    <?PHP
                for ($i = 0; $i<$count; $i++){
                    echo '<span class="demo w3-transparent" style="margin:5px;" onclick="currentDiv('.$i.')"></span>';
                }
            ?>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <img style="border-radius:0.5em;" src="userImg/logo.jpg">
        </div>
        <div class="col-md-10" style="color: blue;">
            <?php
            $myfile2 = fopen("inc/scrolling.txt", "r") or die("Unable to open file!");
            $scrollText = fread($myfile2,filesize("inc/scrolling.txt"));
            fclose($myfile2);
            ?>
            <p>&nbsp;</p>
            <marquee scrollamount="10" behavior="scroll" style="font-size: 30px; font-weight: 600;" direction="left">
                <?php echo $scrollText; ?>
            </marquee>

        </div>
    </div>

</body>


<meta http-equiv="refresh" content="<?php echo $time;?>"> <!-- content == refresh timeout (in sec) -->

<script>
    var slideIndex = 0;
    carousel();

    function carousel() {
        var i;
        var dots = document.getElementsByClassName("demo");
        var x = document.getElementsByClassName("mySlides");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > x.length) {
            slideIndex = 1
        }
        x[slideIndex - 1].style.display = "block";
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" w3-white", "");
        }
        dots[slideIndex - 1].className += " w3-white";
        setTimeout(carousel, 10000); // changed from 5000 to 20,000 for 20 sec videos
    }

    document.getElementsByTagName("html")[0].style = "overflow:hidden;";

</script>
