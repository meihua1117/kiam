<?
//header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

$userid= isset($_REQUEST['userid'])?$_REQUEST['userid']:"";

$items = array();
$index  = 0;
$totalCnt = 0;

$sql_idx = "select * from Gn_Member where mem_id='{$userid}'";
$res_idx = mysqli_query($self_con,$sql_idx);
$row_idx = mysqli_fetch_array($res_idx);
$callback_idx = $row_idx['mem_callback'];

$phone_callback = $row_idx['phone_callback'];
$phone_callback_state = $row_idx['phone_callback_state'];
$mun_callback = $row_idx['mun_callback'];
$mun_callback_state = $row_idx['mun_callback_state'];

$phone_sel_state = $row_idx['mem_callback_phone_state'];
$mun_sel_state = $row_idx['mem_callback_mun_state'];

if($callback_idx == 0){
    $index  = 0;
}
else{
    $sql = "select SQL_CALC_FOUND_ROWS * from gn_mms_callback where service_state=1 and allow_state=1 and idx={$callback_idx}";
    $res_call = mysqli_query($self_con,$sql);
    if(mysqli_num_rows($res_call)){
        $callback_state = 0;
        $row_call = mysqli_fetch_array($res_call);
        $item['idx'] = $row_call['idx'];
        $item['title'] = $row_call['title'];
        $item['content'] = $row_call['content'];
        $item['image'] = $row_call['img'];

        if($phone_callback == $callback_idx && $phone_sel_state) {$callback_state = 1;}
        if($mun_callback == $callback_idx && $mun_sel_state) {$callback_state = 2;}
        if($mun_callback == $callback_idx && $callback_idx == $phone_callback) {
            if(!$phone_sel_state && $mun_sel_state){
                $callback_state = 2;
            }
            if(!$mun_sel_state && $phone_sel_state){
                $callback_state = 1;
            }
            if($phone_sel_state && $mun_sel_state){
                $callback_state = 3;
            }
        }

        $item['type'] = $callback_state;
        $item['service_state'] = $row_call['service_state'];
        if($row_call['iam_link'] == "" )
        {
            if($userid != "")
            {
                $query = "select mem_code from Gn_Member where mem_id='$userid'";             
                $res	    = mysqli_query($self_con,$query);
                $row = mysqli_fetch_array($res);
                $mem_code = $row[0];			
                $query = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id='$userid' order by req_data limit 0,1";             
                $res	    = mysqli_query($self_con,$query);
                $row = mysqli_fetch_array($res);
                $cardlink = $row[0];        
        
                $item['content'] = $item['content'] . "\r\nhttps://www.kiam.kr/?" . $cardlink . $mem_code;
            }        
        }
        else
        {
            $item['content'] = $item['content'] . "\r\n" . $row_call['iam_link'];
        }
        $items[$index++] = $item;
        $totalCnt++;
    }
    else{
        $index = 0;
    }
}




$query = "select SQL_CALC_FOUND_ROWS * from gn_mms_callback where service_state=0";
             
$res1	    = mysqli_query($self_con,$query);
$totalCnt	+=  mysqli_num_rows($res1);

while($rows = mysqli_fetch_array($res1))
{
    $item['idx'] = $rows['idx'];
    $item['title'] = $rows['title'];
    $item['content'] = $rows['content'];
    $item['image'] = $rows['img'];

    $callback_state = 0;
    if($phone_callback == $rows['idx'] && $phone_sel_state) {$callback_state = 1;}
    if($mun_callback == $rows['idx'] && $mun_sel_state) {$callback_state = 2;}
    if($mun_callback == $rows['idx'] && $rows['idx'] == $phone_callback) {
        if(!$phone_sel_state && $mun_sel_state){
            $callback_state = 2;
        }
        if(!$mun_sel_state && $phone_sel_state){
            $callback_state = 1;
        }
        if($phone_sel_state && $mun_sel_state){
            $callback_state = 3;
        }
    }

    $item['type'] = $callback_state;
    $item['service_state'] = $rows['service_state'];
    if($rows['iam_link'] == "" )
    {
        if($userid != "")
        {
            $query = "select mem_code from Gn_Member where mem_id='$userid'";             
            $res	    = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($res);
            $mem_code = $row[0];			
	        $query = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id='$userid' order by req_data limit 0,1";             
            $res	    = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($res);
            $cardlink = $row[0];        
    
            $item['content'] = $item['content'] . "\r\nhttps://www.kiam.kr/?" . $cardlink . $mem_code;
        }        
    }
    else
    {
        $item['content'] = $item['content'] . "\r\n" . $rows['iam_link'];
    }
    $items[$index++] = $item;
}

$query2 = "select SQL_CALC_FOUND_ROWS * from gn_mms_callback where service_state=2 and mb_id='{$userid}'";
$res2	    = mysqli_query($self_con,$query2);
$totalCnt	+=  mysqli_num_rows($res2);

while($rows_mem = mysqli_fetch_array($res2))
{
    $item['idx'] = $rows_mem['idx'];
    $item['title'] = $rows_mem['title'];
    $item['content'] = $rows_mem['content'];
    $item['image'] = $rows_mem['img'];
    // $item['type'] = $rows_mem['type'];
    $callback_state = 0;
    if($phone_callback == $rows_mem['idx'] && $phone_sel_state) {$callback_state = 1;}
    if($mun_callback == $rows_mem['idx'] && $mun_sel_state) {$callback_state = 2;}
    if($mun_callback == $rows_mem['idx'] && $rows_mem['idx'] == $phone_callback) {
        if(!$phone_sel_state && $mun_sel_state){
            $callback_state = 2;
        }
        if(!$mun_sel_state && $phone_sel_state){
            $callback_state = 1;
        }
        if($phone_sel_state && $mun_sel_state){
            $callback_state = 3;
        }
    }

    $item['type'] = $callback_state;
    $item['service_state'] = $rows_mem['service_state'];
    /*if($rows_mem['iam_link'] == "" )
    {
        if($userid != "")
        {
            $query = "select mem_code from Gn_Member where mem_id='$userid'";             
            $res	    = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($res);
            $mem_code = $row[0];			
	        $query = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id='$userid' order by req_data limit 0,1";             
            $res	    = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($res);
            $cardlink = $row[0];        
    
            $item['content'] = $item['content'] . "\r\nhttp://www.kiam.kr/?" . $cardlink . $mem_code;
        }        
    }
    else
    {
        $item['content'] = $item['content'] . "\r\n" . $rows_mem['iam_link'];
    }*/
    $items[$index++] = $item;
}

$udate  = NULL;
if($totalCnt != 0)
{
    $query = "select regdate from gn_mms_callback order by regdate desc";
    $res	    = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    $udate = date("YmdHis", strtotime($row['regdate']));

    // if($totalCnt >= 2)
    // {
    //     $query = "select count(*) from gn_mms_callback where service_state=0 and type=1";
    //     $res	    = mysqli_query($self_con,$query);
    //     $row = mysqli_fetch_array($res);
    //     $pcount = $row[0];
    
    //     $query = "select count(*) from gn_mms_callback where service_state=0 and type=2";
    //     $res	    = mysqli_query($self_con,$query);
    //     $row = mysqli_fetch_array($res);
    //     $mcount = $row[0];
    
    //     if($mcount == 0)
    //     {
    //         if($items[$totalCnt-1]['type'] != 0)
    //             $items[$totalCnt-2]['type'] = 2;
    //         else
    //             $items[$totalCnt-1]['type'] = 2;
    //     }    
        
    //     if($pcount == 0)
    //     {
    //         if($items[$totalCnt-1]['type'] != 0)
    //             $items[$totalCnt-2]['type'] = 1;
    //         else
    //             $items[$totalCnt-1]['type'] = 1;
    //     }   

    // }

}


$result = array();
$result['count'] = $totalCnt;
$result['update'] = $udate;
$result['items'] = $items;

echo json_encode($result);
?>