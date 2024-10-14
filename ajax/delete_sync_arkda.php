<?
include_once "../lib/rlatjd_fun.php";

define("GOOGLE_SERVER_KEY", "AAAAmvl-uQA:APA91bHP4S4L8-nMvfOJ9vcjYlTmiRjEfOcLbAm6ITDFo9Ky-ziKAowlZi0rWhO3c7jsZ50unqWabQCBAmtr9bOxUIbwyAMgRsxO1jeLKlJ9l_Gir_wc1sZ66VBtHVBSjeAZcRfffVwo7M2fBvrrt1d5vz5clf7PVQ");
$push_url = 'https://fcm.googleapis.com/fcm/send';
$push_headers = array (
    'Authorization: key=' . GOOGLE_SERVER_KEY,
    'Content-Type: application/json'
);

$currentDateTime = date('Ymd');
$s = 0;

$sql_b = "select count(*) from Gn_Iam_Contents_Gwc where sync_date='{$currentDateTime}' and contents_url like '%https://arkda.kr/shop/view.php?index_no=%'";
$res_b = mysqli_query($self_con,$sql_b);
$row_b = mysqli_fetch_array($res_b);
if(!$row_b[0]){
    exit;
}

$sql_sel = "select idx, card_idx from Gn_Iam_Contents_Gwc where mem_id='iamstore' and contents_url like '%https://arkda.kr/shop/view.php?index_no=%' and sync_date!='$currentDateTime'";
$res_sel = mysqli_query($self_con,$sql_sel);
while($row_sel = mysqli_fetch_array($res_sel)){
    $sql_del = "delete from Gn_Iam_Con_Card where cont_idx='{$row_sel['idx']}' and card_idx='{$row_sel['card_idx']}'";
    mysqli_query($self_con,$sql_del);

    $sql_save = "select idx, card_idx, mem_id, contents_title from Gn_Iam_Contents_Gwc where ori_store_prod_idx='{$row_sel['idx']}'";
    $res_save = mysqli_query($self_con,$sql_save);
    while($row_save = mysqli_fetch_array($res_save)){
        $sql_del = "delete from Gn_Iam_Con_Card where cont_idx='{$row_save['idx']}' and card_idx='{$row_save['card_idx']}'";
        mysqli_query($self_con,$sql_del);
        $s++;
        $uni_id=time().sprintf("%02d",$s);
        $sql_mem = "select mem_phone, mem_name, mem_point, mem_cash from Gn_Member where mem_id='{$row_save['mem_id']}'";
        $res_mem = mysqli_query($self_con,$sql_mem);
        $row_mem = mysqli_fetch_array($res_mem);

        send_mms($row_save['mem_id'], $row_mem['mem_phone'], $uni_id, $row_save['contents_title'], $push_url, $push_headers, $row_mem['mem_name'], $row_mem['mem_point'], $row_mem['mem_cash']);
    }
    $sql_del_get = "delete from Gn_Iam_Contents_Gwc where ori_store_prod_idx='{$row_sel['idx']}'";
    mysqli_query($self_con,$sql_del_get);
}

function send_mms($mem_id, $mem_phone, $uni_id, $contents_title, $url, $headers, $mem_name, $mem_point, $mem_cash){
	$title = "굿마켓 상품 안내(아크팜스)";
	$txt = "[".$contents_title."]상품은 도매몰에서 더이상 공급하지 않아 굿마켓에서도 삭제가 되었습니다. 다른 상품을 가져오기 해주시길 바랍니다.";

	$query = "insert into Gn_MMS set mem_id='$mem_id',
		send_num='$mem_phone',
		recv_num='$mem_phone',
		uni_id='$uni_id',
		content='$txt',
		title='$title',
		delay='5',
		delay2='15',
		close='24',
		type='1',
		reg_date=NOW(),
		agreement_yn='Y',
		recv_num_cnt=1
	";

	mysqli_query($self_con,$query) or die(mysqli_error($self_con));
	$sidx = mysqli_insert_id($self_con);

    $sql_notice = "insert into Gn_Item_Pay_Result
                set buyer_id='$mem_id',
                    buyer_tel='$mem_phone',
                    site='',
                    pay_method='obmms02',
                    item_name = '공지사항전송',
                    pay_date=NOW(),
                    pay_status='Y',
                    pay_percent='90',
                    order_number = '$uni_id',
                    VACT_InputName='$mem_name',
                    seller_id='{$title}',
                    point_val=3,
                    type='noticerecv',
                    current_point={$mem_point},
                    current_cash={$mem_cash},
                    receive_state=1,
                    message='$txt',
                    alarm_state=0";
    mysqli_query($self_con,$sql_notice);

	$query = "select * from Gn_MMS_Number where mem_Id='$mem_id' and sendnum='$mem_phone'";
	$result = mysqli_query($self_con,$query);
	$info = mysqli_fetch_array($result);       
	$pkey = $info['pkey'];	
	if($pkey != "") {
		$send_type = $info['type'];
		sendPush($url , $headers, $pkey, $sidx, $send_type, $mem_phone);
	}
    echo "delete_get:".$mem_id."\n";
}

function sendPush($url, $headers, $pkey, $sidx, $send_type, $send_num)
{

    $title='{"MMS Push"}';
    $message='{"Send":"Start","idx":"'.$sidx.'","send_type":"'.$send_type.'"}';
    $fields = array ( 
        'data' => array (
                    "body" => $message,
                    "title" => $title)
    );
    

    if(is_array($pkey)) {
        $fields['registration_ids'] = $pkey;
    } else {
        $fields['to'] = $pkey;
    }
    $fields['token'] = $pkey;
    $fields['priority'] = "high";

    //$fields = json_encode ($fields);
    $fields = http_build_query ($fields);
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, "https://nm.kiam.kr/fcm/send_fcm.php" );
    curl_setopt ( $ch, CURLOPT_POST, true );
    //curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0); 
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0);

    $result = curl_exec ( $ch );
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    } 
    //print_R($result);
    $json = json_decode($result);
    $msg = "";
    $msg = $json->results[0]->error;
    curl_close ( $ch );
    
    $query = "insert into Gn_MMS_PUSH set send_num='".$send_num."',
                                          idx='".$sidx."',
                                          token='".$pkey."',
                error='$msg'";

    mysqli_query($self_con,$query) or die(mysqli_error($self_con));        

}

$sql_del = "update Gn_Iam_Contents_Gwc set public_display='N' where mem_id='iamstore' and contents_url like '%https://arkda.kr/shop/view.php?index_no=%' and sync_date!='$currentDateTime'";
$res_sel = mysqli_query($self_con,$sql_del);
$sql_del1 = "delete from Gn_Iam_Contents_Gwc where mem_id='iamstore' and contents_url like '%https://arkda.kr/shop/view.php?index_no=%' and sync_date!='$currentDateTime' and card_idx in(2477701, 1274691, 1268514, 934328)";
$res_sel1 = mysqli_query($self_con,$sql_del1);
$sql_del_issue = "delete from Gn_Iam_Contents_Gwc where contents_img='' and contents_title='' and contents_price=0 and contents_sell_price=0 and product_model_name=''";
$res_sel = mysqli_query($self_con,$sql_del_issue);
$sql_del_issue = "delete from Gn_Iam_Contents_Gwc where send_provide_price = '0' or contents_price = '0'";
$res_sel = mysqli_query($self_con,$sql_del_issue);
echo "delete!>>".date("Y-m-d H:i:s")."\n";

$query_update = "select count(*) from Gn_Iam_Contents_Gwc where sync_date='{$currentDateTime}' and req_data!=up_data and contents_url like '%https://arkda.kr/shop/view.php?index_no=%' and public_display='Y'";
$res_update = mysqli_query($self_con,$query_update);
$row_update = mysqli_fetch_array($res_update);

$query_insert = "select count(*) from Gn_Iam_Contents_Gwc where sync_date='{$currentDateTime}' and req_data=up_data and contents_url like '%https://arkda.kr/shop/view.php?index_no=%' and public_display='Y'";
$res_insert = mysqli_query($self_con,$query_insert);
$row_insert = mysqli_fetch_array($res_insert);

$sql_insert_history = "insert into Gn_Gwc_Sync_State set type=2, add_cnt={$row_insert[0]}, update_cnt={$row_update[0]}, reg_date=now()";
mysqli_query($self_con,$sql_insert_history);

$sql_update = "update crawler_shop_update set res_crawler=1 where cate_name='arkda' and sync_date='{$currentDateTime}' and type=2 order by id desc limit 1";

mysqli_query($self_con,$sql_update);
?>