<?php
    $url = $_GET['src'];
    $pos = strrpos($url,".");
    $file_type = substr($url,$pos + 1);
    header("Content-Type:image/".$file_type);
    $url = iconv("euc-kr","utf-8",$url);
    readfile($url);
?>
