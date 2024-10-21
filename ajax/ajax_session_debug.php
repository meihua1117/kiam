<?
include_once "../lib/rlatjd_fun.php";
//앱체크
if($_POST['select_app_check_num']){
	$num_arr=$_POST['select_app_check_num'];
	$uni_id=time();
	$i=$_POST['select_app_check_i'];
	$url = 'https://fcm.googleapis.com/v1/projects/onepagebookmms5/messages:send';
    $headers = array (
        'Authorization: key=' . GOOGLE_SERVER_KEY,
        'Content-Type: application/json'
    );
    for($k = 0 ; $k < count($num_arr); $k++) {
        $sendnum[] = $num_arr[$k];
    }
    
	$d=$i*10+15;
	$reg_date="DATE_ADD(now(), INTERVAL -{$d} second)";
	    
	if($i>1){
        $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' and sendnum in ('".implode("','",$sendnum)."')";
        $result = mysqli_query($self_con,$query);
        while($info = mysqli_fetch_array($result)) {
            $pkey[$info['sendnum']] = $info['pkey']; 	
    		$sql="select idx from Gn_MMS where mem_id='{$_SESSION['one_member_id']}' and send_num='{$info['sendnum']}' and result=0 and content like '%app_check_process%' order by idx desc limit 0,1";
    		$resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    		$row=mysqli_fetch_array($resul);
            $id = $info['pkey'];
            $sidx = $row['idx'];
            $title='{"MMS Push"}';
            $message='{"Send":"Start","idx":"'.$sidx.'","send_type":"1"}';
            $fields = array (
                'data' => array (
                            "body" => $message,
                            "title" => "app_check_process"
                )
            );
            $fields['to'] = $id;
            $fields['priority'] = "high";
            $fields = json_encode ($fields);
            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_POST, true );
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
            $kresult = curl_exec ( $ch );
            if ($kresult === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close ( $ch );
        }		    
	}elseif($i==1){
		foreach($num_arr as $key=>$v){
			$title = "app_check_process";
			$content = $_SESSION['one_member_id'].", app_check_process";
			sendmms(7, $_SESSION['one_member_id'], $v, $v, "", $title, $content, "", "", "", "N");				
		}
	}
	sleep(10);
	$d=$i*10+15;
	$reg_date="DATE_ADD(now(), INTERVAL -{$d} second)";
	foreach($num_arr as $key=>$v){
		if($check_status_arr[$key]=="on")
		    continue;
		$sql="select idx,send_num,recv_num from Gn_MMS where mem_id='{$_SESSION['one_member_id']}' and reg_date>$reg_date and send_num='$v' and result=0 and  content like '%app_check_process%' limit 0,1";
		$resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
		$row=mysqli_fetch_array($resul);
		$sql="select status from Gn_MMS_status where send_num='{$row['send_num']}' and  recv_num='{$row['recv_num']}' order by regdate desc limit 1 ";
		$sresul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
		$srow=mysqli_fetch_array($sresul);		
		if($row['idx'] && $srow[0]==0){
			$check_status_arr[$key]="on";
			$check_status=true;
		}else{
			$check_status_arr[$key]="off";
			$check_status=false;	
		}
		$check_num_arr[$key]=$v;
	}
	$i++;
	$check_status_str=implode(",",$check_status_arr);	
	$check_num_str=implode(",",$check_num_arr);
	?> 
	<script language="javascript">
		var check_status_str="<?=$check_status_str?>";
		var check_status_arr=check_status_str.split(",");
		var check_num_str="<?=$check_num_str?>";
		var check_num_arr=check_num_str.split(",");	
		function app_check(num_arr,i){
            $.ajax({
                 type:"POST",
                 url:"ajax/ajax_session_debug.php",
                 data:{
                        select_app_check_num:num_arr,
                        select_app_check_i:i
                      },
                 success:function(data){$("#ajax_div").html(data)}
            });
		}
	<?
	if($check_status || $i==30){
	?>
		for(var i=0; i<check_status_arr.length; i++){
			if(check_status_arr[i]=="on"){
				$("#btn_"+check_num_arr[i]).addClass("btn_option_blue");
				$("#btn_"+check_num_arr[i]).html("on");			
			}else{
				$("#btn_"+check_num_arr[i]).addClass("btn_option_red");
				$("#btn_"+check_num_arr[i]).html("off");			
			}
			
		}
		alert('앱상태 체크 완료되었습니다.');
	<?
	}else{
	?>
	    app_check(check_num_arr,'<?=$i?>');
	<?
	}
	?>
    </script>
    <?
}
?>