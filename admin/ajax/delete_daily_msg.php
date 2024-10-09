<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

$id_arr = array();
if(strpos($gd_id, ",") !== false){
    $id_arr = explode(",", $gd_id);
}
else{
    $id_arr[0] = $gd_id;
}

for($i = 0; $i < count($id_arr); $i++){
    $sql = "delete from  Gn_daily where  gd_id='$id_arr[$i]'";
    $result=mysql_query($sql);
    
    $query = "delete from Gn_daily_date where gd_id='$id_arr[$i]';";
    mysql_query($query);
        
    $query = "delete from Gn_MMS where gd_id='$id_arr[$i]' ";
    mysql_query($query);

    $query = "delete from gn_mail where gd_id='$gd_id' ";
    mysql_query($query);  
}

if($type == "service") $link = "daily_msg_list_service.php";
else $link = "daily_msg_list_mem.php";

echo "<script>alert('삭제 되었습니다.');location='../".$link."'</script>"; exit;
?>