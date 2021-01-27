<?php
$dir = "";
include $dir."inc/connection.php";
include $dir."session.php";

$uploadDir = 'userImg/';
$response = array(
    'status' => 0,
    'message' => '<p><strong>Sorry, your logo was not uploaded. Please refresh the page and try again.</strong></p>'
);

    $uploadStatus = 1;
    $uploadedFile = '';
    
    if ( !empty($_FILES["file"]["name"]) ){
        // File path config
        $fileName = basename($_FILES["file"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $response['message'] = $fileName;
        // Renaming file again with timestamp
        $fileName = "logo.".$fileType;
        $targetFilePath = $uploadDir . $fileName;
        $allowTypes = array('jpg');

        if ( in_array($fileType, $allowTypes) ){
            if ( move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath) ){
                $uploadedFile = $fileName;
            } else {
                $uploadStatus = 0;
                $response['message'] = '<p><strong>Sorry, there was an error uploading your file.</strong></p>';
            }
        } else {
            $uploadStatus = 0;
            $response['message'] = "<p><strong>Sorry, only jpg files are allowed to upload.</strong></p>";
        }
    }

    if ( $uploadStatus == 1 ){

        $response['status'] = 1;
        $response['message'] = '<p><strong>Thank you! Your logo has been updated.</strong></p>';
                        
                
    } else {
         $response['message'] = '<p><strong>Please refresh the page and try again.</strong></p>';
    }

// Return response
echo json_encode($response);
