#!/usr/bin/php
<?php
set_time_limit(0);
ini_set('memory_limit','-1');

include_once "/home/kiam/lib/db_config.php";

$query = "start transaction";
$result = mysqli_query($self_con, $query) ;


//업종이 없는 주소를 찾아 업종을 검색해서 저장한다.
$query = "select url,seq,company_name from crawler_data_map where company_type = ''";
$result = mysqli_query($self_con, $query);
while($row=mysqli_fetch_array($result)) {
    $company_name = $row['company_name'];
    if( $company_name != ""){
        $query = "select company_type from crawler_data_temp where company_name='{$company_name}' and company_type != ''";
        $result1 = mysqli_query($self_con, $query);
        while($row1=mysqli_fetch_array($result1)){
            $company_type = $row1[0];
            echo "seq=$row[seq], company_name=$company_name, url={$row['url']}, company_type=$company_type \n"; 
            flush();                 
            $query = "update crawler_data_map set company_type='{$company_type}' where seq={$row['seq']}";
            mysqli_query($self_con, $query);
            break;
        }
    }
}

$query = "commit";
$result = mysqli_query($self_con, $query);

echo 'Done';
?>