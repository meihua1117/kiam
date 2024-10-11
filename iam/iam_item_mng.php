<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

$sql="select * from Gn_Member where mem_id='$_SESSION[one_member_id]' and site != '' ";
$resul=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($resul);

$mem_id = $_POST['memid'];
$mem_point = 0;
$cur_time_com_mms = time() - (86400 * 3);
$cur_time_mms_com = date("Y-m-d H:i:s", $cur_time_com_mms);

// $get_point = "select current_point from Gn_Item_Pay_Result where buyer_id='{$mem_id}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
// echo $get_point; exit;
$get_point = "select mem_point, mem_cash from Gn_Member where mem_id='{$mem_id}'";
$result_point = mysqli_query($self_con,$get_point);
$row_point = mysqli_fetch_array($result_point);

if(isset($_POST['buy'])){
    $sql = "select TotPrice, member_type, payMethod from tjd_pay_result where buyer_id='{$mem_id}' order by no desc limit 1";
    $result = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($result);
    
    if($row['payMethod'] == "BANK"){
        echo 1;
    }
    else{
        $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$data['mem_phone']}', item_name='{$row['member_type']}', item_price={$row['TotPrice']}, pay_percent=90, current_point={$row_point['mem_point']}, current_cash={$row_point['mem_cash']}+{$row['TotPrice']}, pay_status='Y', VACT_InputName='{$data[mem_name]}', type='buy', pay_method='{$row['payMethod']}', pay_date=now(), point_val=1";
        mysqli_query($self_con,$sql);

        $sql_update = "update Gn_Member set mem_cash=mem_cash+{$row['TotPrice']} where mem_id='{$mem_id}'";
        mysqli_query($self_con,$sql_update);
    }
}

if(isset($_POST['memid']) && isset($_POST['use'])){
    $sql_ai_point = "select * from Gn_Search_Key where key_id='ai_card_making'";
    $res_ai = mysqli_query($self_con,$sql_ai_point);
    $row_ai = mysqli_fetch_array($res_ai);
    $point_ai = $row_ai['key_content'];

    $sql_auto_point = "select * from Gn_Search_Key where key_id='auto_member_join'";
    $res_auto = mysqli_query($self_con,$sql_auto_point);
    $row_auto = mysqli_fetch_array($res_auto);
    $point_auto = $row_auto['key_content'];
    
    $mem_id = $_POST['memid'];
    $mem_type = $_POST['mem_type'];
    $channel = $_POST['channel'];
    $keyword = $_POST['keyword'];
    $id_status = $_POST['id_status'];
    $method = $channel."/".$keyword;

    $sql_service = "select * from Gn_Iam_Service where mem_id='{$mem_id}'";//분양사아이디이면.
    $res_service = mysqli_query($self_con,$sql_service);
    if(mysqli_num_rows($res_service)){
        $row = mysqli_fetch_array($res_service);
        if($row['ai_point_end'] < $date_today){
            $min_point = $point_ai;
        }
        else{
            if($row['ai_card_point'] == ''){
                $min_point = $point_ai;
            }
            else if($row['ai_card_point'] == 0){
                $min_point = 0;
            }
            else{
                $min_point = $row['ai_card_point'] * 1;
            }
        }

        if(isset($_POST['reg_name'])){
            $method = $_POST['reg_name']."/".$_POST['reg_id'];

            if($row['automem_point_end'] < $date_today){
                $min_point = $point_auto;
            }
            else{
                if($row['auto_member_point'] == ''){
                    $min_point = $point_auto;
                }
                else if($row['auto_member_point'] == 0){
                    $min_point = 0;
                }
                else{
                    $min_point = $row['auto_member_point'] * 1;
                }
            }
        }
    }
    else{
        $min_point = $point_ai;
        if(isset($_POST['reg_name'])){
            $min_point = $point_auto;
            $method = $_POST['reg_name']."/".$_POST['reg_id'];
        }
    }

    $sql_card_link = "select * from crawler_iam_info where mem_id='{$id_status}' order by id desc limit 1";
    $res_link = mysqli_query($self_con,$sql_card_link);
    $row_link = mysqli_fetch_array($res_link);

    $iam_link = $row_link['iam_link'];
    
    $sql_mem_code = "select mem_code from Gn_Member where mem_id='{$id_status}'";
    $res_code = mysqli_query($self_con,$sql_mem_code);
    $row_code = mysqli_fetch_array($res_code);
    $mem_code = $row_code['mem_code'];

    $iam_url = "http://" . $HTTP_HOST . "/?" . $iam_link . $mem_code;

    $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$data['mem_phone']}', site='{$iam_url}', item_name='{$mem_type}', item_price={$min_point}, pay_percent=90, current_cash={$row_point['mem_cash']}, current_point={$row_point['mem_point']}-{$min_point}, pay_status='Y', VACT_InputName='{$data[mem_name]}', type='use', pay_method='{$method}', pay_date=now(), point_val=1, seller_id='{$id_status}'";
    mysqli_query($self_con,$sql);

    $sql_update = "update Gn_Member set mem_point=mem_point-{$min_point} where mem_id='{$mem_id}'";
    mysqli_query($self_con,$sql_update);

    $sql_point = "select mem_id, mem_point, mem_phone from Gn_Member where mem_id='{$mem_id}'";
	$res_point = mysqli_query($self_con,$sql_point);
	$row_point = mysqli_fetch_array($res_point);

	$mem_phone = str_replace('-', '', $row_point['mem_phone']);
	$point = (int)$row_point['mem_point'];
	if($point <= 10000 && $point > 5000) $mem_point = 10000;
	if($point <= 5000 && $point > 3000) $mem_point = 5000;
	if($point <= 3000) $mem_point = 3000;

	$s++;
	$uni_id=time().sprintf("%02d",$s);
	if($mem_point != 0){
		$sql_mms_send = "select reg_date, recv_num from Gn_MMS where title='포인트 충전 안내' and content='".$mem_id.", 고객님의 잔여 포인트가 ".$mem_point." 포인트 이하입니다. 포인트가 부족할 경우 현재 이용중이신 기능이 중지되오니 충전해주시길 바랍니다. 감사합니다.' and mem_id='{$mem_id}' order by idx desc limit 1";
		$res_mms_send = mysqli_query($self_con,$sql_mms_send);
		if(mysqli_num_rows($res_mms_send)){
			while($row_mms_send = mysqli_fetch_array($res_mms_send)){
				$reg_date_msg = $row_mms_send['reg_date'];
				if($reg_date_msg < $cur_time_mms_com){
					send_mms($mem_id, $mem_phone, $uni_id, $mem_point);
				}
			}
		}
		else{
			send_mms($mem_id, $mem_phone, $uni_id, $mem_point);
		}
	}
}
echo 1;

function send_mms($mem_id, $mem_phone, $uni_id, $mem_point){
	$title = "포인트 충전 안내";
	$txt = $mem_id.", 고객님의 잔여 포인트가 ".$mem_point." 포인트 이하입니다. 포인트가 부족할 경우 현재 이용중이신 기능이 중지되오니 충전해주시길 바랍니다. 감사합니다.";

    sendmms(5, $mem_id, $mem_phone, $mem_phone, "", $title, $txt, "", "", "", "Y");

}
?>