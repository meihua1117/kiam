<?php
$ftp_user_name = "obmms";
$ftp_user_pass = "onlyone123!";
// open some file for reading
$file = 'docs/2019_OnlyOneMMS_Manual_V01.pdf';
$fp = fopen($file, 'r');
$ftp_server = "goodhow.com";

// set up basic connection
$conn_id = ftp_connect($ftp_server, 8821);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// try to upload $file
if (ftp_fput($conn_id, $file, $fp, FTP_ASCII)) {
    echo "Successfully uploaded $file\n";
} else {
    echo "There was a problem while uploading $file\n";
}

// close the connection and the file handler
ftp_close($conn_id);
fclose($fp);

?>