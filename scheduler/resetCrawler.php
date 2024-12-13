#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";
$today = date("d", time());
$query = "select * from crawler_member_real";
$result = mysqli_query($self_con, $query);
while($row=mysqli_fetch_array($result)) {
    $term = $row['term'];
    $date = date("d", strtotime($term));
    if($today == $date)
    {
        $query = "update crawler_member_real set total_cnt = total_cnt+monthly_cnt, search_email_total_cnt = search_email_total_cnt+search_email_use_cnt where cmid='{$row['cmid']}'";
        //echo $query."\n";
        mysqli_query($self_con, $query);
    
        $query = "update crawler_member_real set monthly_cnt=0,search_email_use_cnt=0 where cmid='{$row['cmid']}'";
        //echo $query."\n";
        mysqli_query($self_con, $query);
    }

}

mysqli_close($self_con);
?>
