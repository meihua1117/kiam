<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'cities';
$location = isset($_REQUEST['location']) ? $_REQUEST['location'] : '';

$locations = array();
if($location != '') {
    if($type == 'cities') {
        $query = "SELECT city as location FROM gn_cities WHERE province = '{$location}' group by city";
    }else {
        $query = "SELECT town as location FROM gn_cities WHERE city = '{$location}' group by town";
    }
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) {
        $locations[] = $row['location'];
    }
}
echo json_encode(array('status'=>'1', 'locations'=>$locations));
?>