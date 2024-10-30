#!/opt/php/bin/php
<?php
set_time_limit(0);
ini_set('memory_limit', '-1');

$mysql_host = 'localhost';
$mysql_user = 'kiam';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';

$self_con = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if (!$self_con) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
mysqli_query($self_con, "set names utf8");


echo "Start working with daily update\n";

$query = "start transaction";
$result = mysqli_query($self_con, $query);

$sub_sql = "select end_idx from crawler_conf";
$result1 = mysqli_query($self_con, $sub_sql);
$row1 = mysqli_fetch_array($result1);
$startIdx = $row1['end_idx'];

echo "startIdx=" . $startIdx . "\n";

$length =   1000000;
$endIdx = 0;
$query = "select seq, user_id, keyword, incword, exword, data_type, cell, email, ceo, url, company_name, company_type, page_title, address, upload_date, tag, info, regdate from crawler_data_2022 where seq > $startIdx order by seq limit $length";
$result = mysqli_query($self_con, $query);
while ($row = mysqli_fetch_array($result)) {

    $title = str_replace("'", "", $row['page_title']);
    $info = str_replace("'", "", $row['info']);
    $upload_date = ($row['upload_date'] != '') ? $row['upload_date'] : "0000-00-00";
    $keyword = str_replace("\n", "", $row['keyword']);
    $keyword = trim($keyword);

    $query = "insert into crawler_data_supply set user_id='{$row['user_id']}',
                                                keyword='$keyword',
                                                incword='{$row['incword']}',
                                                exword='{$row['exword']}',
                                                data_type='{$row['data_type']}',
                                                cell='{$row['cell']}',
                                                email='{$row['email']}',
                                                ceo='{$row['ceo']}',
                                                url='{$row['url']}',
                                                company_name='{$row['company_name']}',
                                                company_type='{$row['company_type']}',
                                                page_title='$title',
                                                address='{$row['address']}',
                                                upload_date='$upload_date',
                                                tag='{$row['tag']}',
                                                info='$info',
                                                regdate='{$row['regdate']}'";
    mysqli_query($self_con, $query);

    //echo $query . "\n";
    $endIdx = $row['seq'];
}

echo "endIdx=" . $endIdx . "\n";

if ($endIdx > 0) {
    $query = "update crawler_conf set end_idx='$endIdx'";
    mysqli_query($self_con, $query);
}

$query = "commit";
$result = mysqli_query($self_con, $query);

echo "Well Done !!!\n";
mysqli_close($self_con);
?>