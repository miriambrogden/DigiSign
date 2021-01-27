<?PHP
    include "inc/connection.php";
    include "session.php";
    include "inc/header.php";
    if ($userType == "ADVERTISER"){ include $dir."inc/advertiserMenu.php";} else {include $dir."inc/spaceOwnerMenu.php";}
    include "inc/footer.php";

    $user = $_SESSION['login_user']; // get the email of the logged-in user
    $userIDQuery = "SELECT id FROM user WHERE email='".$user."'";
    $userIDResult = mysqli_query( $link, $userIDQuery ); // get the ID of that user from the DB
    $userIDRows = mysqli_num_rows( $userIDResult ); // count rows
    if ($userIDRows != 1) { // should only be 1
        echo "Error - Invalid login profile, no associated user ID";
        echo "result: ";
        echo $userIDResult;
    }

    $userID = $userIDResult->fetch_assoc();
    $id = $userID["id"];

    // Query db for this user's ads with status == PENDING
    $adQuery = "SELECT * FROM advertisement WHERE status='PENDING' AND user_id='".$id."' ORDER BY date_requested DESC";
    $adResult = mysqli_query( $link, $adQuery );

    $dataRow = "";
    $i = 0;


    // Build the rows of the table from DB result
    while ($row = $adResult->fetch_assoc()) {
         $requestedSTR = date('D, M jS, Y', strtotime($row['date_requested']));
        $startSTR = date('D, M jS, Y', strtotime($row['start_date']));
        $endSTR = date('D, M jS, Y', strtotime($row['end_date']));
        $dataRow = $dataRow."<tr id='row$i'>
                            <th scope='row' id='id'>".$row['id']."</th>
                            <td id='title'>".$row['title']."</td>
                            <td id='requested'>".$requestedSTR."</td>
                            <td id='start'>".$startSTR."</td>
                            <td id='end'>".$endSTR."</td>
                            <td id='type'>".$row['content_type']."</td>
                            <td id='file'>".$row['content_url']."</td>
                            <td id='notes'>".$row['comment']."</td>
                            </tr>";
       $i = $i + 1;
    }
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type='text/javascript'>
    var globalID;

    $(document).ready(function() {
        // Get the modal
        var modal = document.getElementById("myModal");

        // Set the modal to hidden (default)
        modal.style.display = "none";

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("exit")[0];

        // Table row click event handler
        $('#pendingTable tr').click(function(event) {


            var table = document.getElementById('pendingTable'); // Get the table
            var array = $(this).toArray(); // Convert clicked row to array
            var row = array[0]; // Extract the <tr> element
            var id = row.id; // Extract the ID value from the row
            var cells = row.cells; // Extract each cell value from the row
            globalID = cells[0].innerHTML;
            var file = cells[6].innerHTML;
            var type = cells[5].innerHTML;

            var modalTitle = document.getElementById('modalTitle');
            modalTitle.innerHTML = "Pending Advertisement: " + cells[1].innerHTML;
            modalBody.innerHTML = "";
            $(".adPreview").empty();

            for (let c of cells) {
                console.log(c.innerHTML); // Log the inner HTML of each cell for testing
            }

            var preview = document.getElementById("image"); // Get the window for the ad preview

            if (type == "IMAGE") {
                preview.innerHTML = '<img id="image" width="600px" style="padding: 5%" src="userImg/' + file + '">';
            } else if (type == "VIDEO") {
                // Added for Req. 56
                // TODO: add support for .WMV and .MOV files
                preview.innerHTML = '<video width="600px" controls muted style="padding: 5%"> <source src="userImg/'+ file +'" type="video/mp4"></video>';
            }

            // When the user clicks on the row, open the modal
            modal.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            $('#cancel').click(function(event) {
                // Cancel the ad request
                // For testing, set status to CANCELLED
                var myData = {
                    "ad_id": globalID,
                    "img": preview.src
                }

                $.ajax({
                    type: 'POST',
                    url: 'pendingAdvertisementsScript.php',
                    data: myData,
                    error: function(error) {
                        console.log("Error: " + error);
                    }
                }).done(function(data) {
                    console.log("Response: " + data);
                    var temp = $.parseJSON(data);
                    console.log(temp);
                    modal.style.display = "none";
                    window.location.href = "pendingAdvertisements.php";
                });
            });
        });
    });

</script>

<div class="mainContent">

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-dialog modal-lg">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalTitle" class="modal-title"></h4>
                </div>
                <div id='modalBody' class="modal-body">
                </div>
                <div id="image">

                </div>
                <!-- -->
                <div id="modalFooter">
                    <span class="exit"><input type="button" style="margin-left: 5%" class="submit" value="Go Back"></input></span>
                    <form method="post" action="">
                        <input type="button" style="margin-left: 5%; background-color: red;" id="cancel" name="cancel" class="submit" value="Delete Ad"></input>
                    </form>
                </div>

                <p>
                    <?php echo $row ?>
                </p>
            </div>
        </div>
    </div>

    <h2>Pending Advertisements for
        <?php echo $user ?>
    </h2>
    <p>Below are your advertisement requests, which have not yet been approved by your Space Owner.<br>(Ordered by "Date Requested", newest to oldest)</p>

    <!-- ADVERTISEMENT VIEW -->
    <div class="table-responsive">
        <table id='pendingTable' class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Date Requested</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Type</th>
                    <th scope="col">File Name</th>
                    <th scope="col">Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $dataRow ?>
            </tbody>
        </table>
    </div>
</div>
