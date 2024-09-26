<?php
    $url = $_GET['src'];
    header("Content-Type:text/html;charset=utf-8");
    readfile($url);
?>
