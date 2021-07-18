<?PHP 

$hostDB = "HOST";
$userDB = "USERNAME";
$passwordDB = "PASSWORD";
$databaseDB = "DATABASE";
    
$link = mysqli_connect($hostDB, $userDB, $passwordDB, $databaseDB);

if (!$link) {
    echo "Error: Unable to connect to MySQL ".mysqli_connect_error();
} else {
   // echo "Successfully connected to DB!";
}

?>
