<?
	include_once "../lib/rlatjd_fun.php";
	extract($_REQUEST);
	if($_GET['sp']) $readonly="readonly";
	if($_POST['speech']) $sp=$_POST['speech'];
	if(!$pcode) $pcode="opb";
	// $meta_img = "";
	if($_GET['landing_idx'] != "") {
    	$landing_idx=$_GET['landing_idx'];
    	$sql="update Gn_landing set read_cnt = read_cnt+1 where landing_idx='$landing_idx'";
    	mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

    	$sql="select * from Gn_landing where landing_idx='$landing_idx'";
    	$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    	$data=mysqli_fetch_array($result);


    	if($data['status_yn'] == "N") {
    	    echo '<meta http-equiv="content-type" content="text/html; charset=euc-kr"/><Script>alert("?�용�?종료?� ?�?�입?�??");</script>';
    	    exit;
    	}

    	$m_id = $row['m_id'];
    	$event_idx = $row['event_idx'];
    	$pcode = $data['pcode'];
		$page_title = $data['title'];
		if(strpos($data['content'], '<img src="upload/') !== false){
			$meta_img1 = explode('<img src="upload/', $data['content']);
			$meta_img2 = explode('"', trim($meta_img1[1]));
			$meta_img = "http://www.kiam.kr/upload/".trim($meta_img2[0]);
		}
	} else {
    	$sql="update Gn_event set read_cnt = read_cnt+1 where pcode='$pcode'";
    	mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
		$page_title = "";
	}

	$sql="select * from Gn_event where pcode='$pcode' order by event_idx desc";
	$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
	$event_data = $row=mysqli_fetch_array($result);
	$m_id = $row['m_id'];
	$event_idx = $row['event_idx'];
	if($page_title == "") $page_title = $row['event_title'];


// ?�리?� ?�비?� ?�이�????��??�?�링??
	if($landing_idx == "783") {
		$direct_url = "https://smartstore.naver.com/misohealth/products/4899608486";
		echo("<script>location.href = '$direct_url';</script>");
	}			

//?�?�엠 ?�로?� ?�??착한기업?�개 	?�?�링??
	if($landing_idx == "787") {
		$direct_url = "https://smartstore.naver.com/misohealth/products/2878753669";
		echo("<script>location.href = '$direct_url';</script>");
	}
	
//	김?��? ?�?� ?�?�엠 ?�?� 링??
	if($landing_idx == "790") {
		$direct_url = "https://kiam.kr/?EtYTwm9OZn";
		echo("<script>location.href = '$direct_url';</script>");
	}	
	
//?�?�엠 공유?�보더보기 ?�?�링??
	if($landing_idx == "788") {
		$direct_url = "https://oog.kiam.kr/pages/page_3302.php";
		echo("<script>location.href = '$direct_url';</script>");
	}		
	$url_refer = str_replace("&", "###", $_SERVER['REQUEST_URI']);
?>

<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:title" content="<?=$page_title?>">
	<?if($_GET['landing_idx'] == "") {?>
	<meta property="og:image" content="http://www.kiam.kr/images/event_apply.png">
	<?}?>
	<link rel="stylesheet" href="style.css" type="text/css">
	<link rel="stylesheet" href="global_sub.css" type="text/css" />

	<link rel="stylesheet" href="css/slick.css" type="text/css">
	<link rel="stylesheet" href="../css/responsive.css" type="text/css">
	<link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
	<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>	
	<script language="javascript" type="text/javascript" src="jquery-3.1.1.min.js"></script>
	<script language="javascript" type="text/javascript" src="common.js"></script>
	<script language="javascript" type="text/javascript" src="jquery.rotate.js"></script>
	<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
	<style>
		.div_content {overflow-y:hidden;}
		.list_table td{text-align:center;height:40px;border-bottom:1px solid #CCC;}
		.list_table td li{float:left;margin-right:5px;}
		.list_table .d_font{font-size:16px;font-weight:600}
		.list_table .x_font{vertical-align:bottom;padding-bottom:5px;color:#666;}
		.list_table .xt_font{position:absolute; margin-top:-35px;font-size:16px; font-weight:600;margin-left:50px;color:#000;}
		.list_table input[type=text]{width:90%;height:30px;}
		.list_table tr:first-child td{height:40px; background-color:#f4f4f4;border-bottom:1px solid #000; border-top:1px solid #000;}
		.list_table tr:first-child td {
		height: 40px;
		/* background-color: #f4f4f4; */
		/* border-bottom: 1px solid #000; */
		border-top: 1px solid #CCC;
		}
		</style>
	<script language="javascript">
		$(document).ready(function(){
			var mode = get('show_ori');
			if(mode == "Y"){
				$("#iam_mem").prop('checked', true);
				if($("#no_mem").prop('checked')){
					$("#no_txt").show();
					$("#iam_txt").hide();
					$('input[name =join_yn]').val('Y');
					$('input[name =name]').val('');
					$('input[name =mobile]').val('');
					$('input[name =email]').val('');
					$('input[name =addr]').val('');
					$('input[name =birthday]').val('');
					$('input[name =job]').val('');
					$("#div_account").show();
				}
				else{
					$("#iam_txt").show();
					$("#no_txt").hide();
					$("#div_account").hide();
					$('input[name =join_yn]').val('N');
					load_userinfo();
				}
			}
		})

		function get(name){
			if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
			return decodeURIComponent(name[1]);
		}

		function gotoLogin(id, pass, code) {
			$.ajax({
				type:"POST",
				url:"/admin/ajax/login_iamuser.php",
				data:{one_id:id, mem_pass:pass, mem_code:code},
				success:function(){
					location.href = "/?cur_win=request_list";
				},
				error: function(){
					alert('초기화 ?��?);
				}
			});
			return false;
		}
		
		//< !--
			function resize() {
				var w = 630 + 4;
				var h = 510 + 24;
				window.resizeTo(w, h);
				window.moveTo((screen.width - w) / 2, (screen.height - h) / 2);
			}
		//-->
		var emailnum = "2";
		function func_check(type, val) {
			if (type == "mem_id") {
				val = document.getElementById('id1').value + '-' + document.getElementById('id2').value + '-' + document.getElementById('id3').value;
			}
			val = encodeURIComponent(val);
			//alert(val);
			$.ajax({
				url: '/opb/proc/mem_check.html',
				type: 'POST',
				data: 'type=' + type + '&val=' + val,
				success: function (response) {
					if (response == "id=dup") {
						document.getElementById('id_reg').value = 0;
						document.getElementById('id_text').innerHTML = '<span class="jftxt_r">중복된  ?�?�디 ?�?�??</span>';
					} else if (response == "id=nouse") {
						document.getElementById('id_reg').value = 0;
						document.getElementById('id_text').innerHTML = '<span class="jftxt_r">?�용할 ?� ?�?� ?�?�디 ?�?�??</span>';
					} else if (response == "id=use") {
						document.getElementById('id_reg').value = 1;
						document.getElementById('id_text').innerHTML = '<span class="jftxt_b">?�용�??�합니??</span>';
					} else if (response == "email=dup") {
						document.getElementById('email_reg').value = 0;
						document.getElementById('email_text').innerHTML = '<span class="jftxt_r">중복된 ?��????�?�??</span>';
					} else if (response == "email=reuse") {
						document.getElementById('email_reg').value = 0;
						document.getElementById('email_text').innerHTML = '<span class="mftxt_r">?�용�????��????�?�??</span>';
					} else if (response == "email=nouse") {
						document.getElementById('email_reg').value = 0;
						document.getElementById('email_text').innerHTML = '<span class="mftxt_r">?�용할 ?� ?�?� ?��????�?�??</span>';
					} else if (response == "email=use") {
						document.getElementById('email_reg').value = 1;
						document.getElementById('email_text').innerHTML = '<span class="mftxt_b">?�용�??�합니??</span>';
					} else if (response == "nick=dup") {
						document.getElementById('nick_reg').value = 0;
						document.getElementById('nick_text').innerHTML = '<span class="jftxt_r">중복된 ?�?�임 ?�?�??</span>';
					} else if (response == "nick=nouse") {
						document.getElementById('nick_reg').value = 0;
						document.getElementById('nick_text').innerHTML = '<span class="jftxt_r">?�용할 ?� ?�?� ?�?�임 ?�?�??</span>';
					} else if (response == "nick=use") {
						document.getElementById('nick_reg').value = 1;
						document.getElementById('nick_text').innerHTML = '<span class="jftxt_b">?�용�??�합니??</span>';
					} else if (response == "id2=dup") {
						document.getElementById('id_reg').value = 0;
						document.getElementById('id2_text').innerHTML = '<span class="jftxt_r">중복된 ?�?�디 ?�?�??</span>';
					} else if (response == "id2=nouse") {
						document.getElementById('id_reg').value = 0;
						document.getElementById('id2_text').innerHTML = '<span class="jftxt_r">?�용할 ?� ?�?� ?�?�디 ?�?�??</span>';
					} else if (response == "id2=use") {
						document.getElementById('id_reg').value = 1;
						document.getElementById('id2_text').innerHTML = '<span class="jftxt_b">?�용�??�합니??</span>';
					} else {
						document.getElementById('id_reg').value = 0;
						document.getElementById('id_text').innerHTML = '<span class="jftxt_r">?�용할 ?� ?�?� ?�?�디 ?�?�??</span>';
						document.getElementById('id_text').innerHTML = response;
						document.getElementById('email_reg').value = 0;
						document.getElementById('email_text').innerHTML = '<span class="mftxt_r">?�용할 ?� ?�?� ?��????�?�??</span>';
					}
				}
			});
		}
		function hp_certify_send() {
			var pattern = /^01[0-9][-]{0,1}[0-9]{3,4}[-]{0,1}[0-9]{4}$/;
			var hp = document.getElementById('id1').value + document.getElementById('id2').value + document.getElementById('id3').value;
			var reg_id = document.getElementById('id_reg').value;

			if (reg_id != 1) {
				alert("가?�?� ?� ?�?� ?�대??번?��? ?�?�해주?�요.");
				document.getElementById('id1').focus();
				return false;
			}
			if (!pattern.test(hp)) {
				alert("?�대??번?��? ?�?�?� 맞게 ?�?��?주세요. '-' �??�었?�지 ?�?�하?�요.");
				document.getElementById('id1').focus();
				return false;
			}
			$.ajax({
				url: '/opb/proc/sms_approval.html',
				type: 'POST',
				data: 'hp=' + hp + '&val=0',
				success: function (response) {
					if (response == "000 " || response == "000") {
						document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">?��?번?��? ?�?�하?�?�니??</span>';

					} else {
						document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">?��?번??발?�에 ?�패했?�니?? [' + response + ']</span>';
					}
				}
			});
		}
		function hp_certify() {
			var pattern = /^01[0-9][-]{0,1}[0-9]{3,4}[-]{0,1}[0-9]{4}$/;
			var hp = document.getElementById('id1').value + document.getElementById('id2').value + document.getElementById('id3').value;
			var sms_approval = document.getElementById('sms_approval').value;
			var reg_id = document.getElementById('id_reg').value;

			if (sms_approval.length != 6) {
				document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">?��?번?��? ?�?�해주?�요.</span>';
			}
			//if(!pattern.test(hp)) {
			//	alert("?�대??번?��? ?�?�?� 맞게 ?�?��?주세요. '-' �??�었?�지 ?�?�하?�요.");
			//	document.getElementById('id1').focus();
			//	return false;
			//}
			document.getElementById('approval_reg').value = 0;
			$.ajax({
				url: '/opb/proc/sms_check.html',
				type: 'POST',
				data: 'val=' + sms_approval + '&vals=1',
				success: function (response) {
					if (response == "1") {
						document.getElementById('approval_reg').value = 1;
						document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">?��?번?��? 맞?�니??</span>';
					} else {
						document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">?��?번?��? 맞지 ?�?�니??</span>';
					}
				}
			});
		}
		function hp_certify_focus() {
			var reg_id = document.getElementById('id_reg').value;
			if (reg_id != 1) {
				document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">?�용할 ?� ?�?� ?�대?�을 ?�?�해주?�요.</span>';
			} else {
				document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">?��?번?��?기�? ?�?�주?�요.</span>';
			}
		}

		function chkInput(mode, cid, val) {
			var obj = document.getElementById(cid);
			if (mode == 'in') {
				if (obj.value == val) obj.value = "";
			} else if (mode == 'out') {
				if (obj.value == "") obj.value = val;
			}
		}
		function changeIdType(idType) {
			if (idType == 'email') {
				document.getElementById('hpid1').style.display = 'none';
				document.getElementById('hpid2').style.display = 'none';
				document.getElementById('hpid3').style.display = 'none';
				document.getElementById('emailid1').style.display = 'inline';
				document.getElementById('emailid2').style.display = 'inline';
				document.getElementById('emailid3').style.display = 'none';
				document.getElementById('id_type').value = 'email';
				emailnum = "";
			} else if (idType == 'hp') {
				document.getElementById('hpid1').style.display = 'inline';
				document.getElementById('hpid2').style.display = 'inline';
				document.getElementById('hpid3').style.display = 'inline';
				document.getElementById('emailid1').style.display = 'none';
				document.getElementById('emailid2').style.display = 'none';
				document.getElementById('emailid3').style.display = 'inline';
				document.getElementById('id_type').value = 'hp';
				emailnum = "2";
			}
		}
	</script>
</head>
<?
	$PG_table = $GnTable["member"];
	$JO_table = $GnTable["memberlevel"];
	$DOMAIN = str_replace('www.', '', $DOMAIN);
	function alertOn($msg) {
	echo "<script>alert('".$msg."');</script>";
	}
	if($mode && $chk && $over=="yes") {
	//referer_check();
	if($chk!=$_SESSION['join']) alert('중복�??�??방�? ?�?�?�니??');
	$ipcheck=$_SERVER['REMOTE_ADDR'];
	$sql="insert into Gn_event_request set landing_idx='$landing_idx',
	event_idx='$event_idx',
	event_code='$event_code',
	name='$name',
	mobile='$mobile',
	email='$email',
	job='$job',
	other='$other',
	pcode='$pcode',
	sp='$sp',
	ip_addr='$ipcheck',
	regdate=now()

	";
	sql_query($sql);
	alert("?�?� ?�?�?�니?? 감?�합니??","/opb/index.php");
	} elseif($mode && $chk) {
	}
	if(!$mode) $mode="JOIN";
	$_SESSION['join'] = md5(time());
	?>
<?
	function alerting($msg) {
	echo "<script>alert('".$msg."');</script>";
	}


	if($_POST['mobile'] != "")
	{
		$sql="select mem_phone from Gn_Member where mem_phone='{$_POST['mobile']}'";
		$res=mysqli_query($self_con, $sql);
		$row333=mysqli_fetch_array($res);	
	}


	if($_POST['mode']=="speech") {
		if($_POST['name']=="") {
			alerting('?�청?�명?� ?�?�해주?�요');
			//} else if($_POST['email']=="") {
			//	alerting('?�청?� ?��??�을 ?�?�해주?�요.');
			//} else if(strpos($_POST['email'],"@")<=0) {
			//alerting('?��????�?�?� 맞게 ?�?�해주?�요.');
			} else if($_POST['mobile']=="") {
			alerting('?�?�번?��? ?�?�해주?�요. ');
			//} else if($_POST['job']=="") {
			//	alerting('직?�?� ?�?�해주?�요.');
			} else if($_POST['sp']=="") {
			alerting('?�청강좌�??�?�해주?�요.');
			} 
		// 	else if($join_yn =='Y' && $row333){
		// 		alerting('�??�?�번?�는 ?��??��??�?�?�니??');
		// }
		else {
			$ipcheck=$_SERVER['REMOTE_ADDR'];
			extract($_POST);

			$sql="select * from Gn_event where pcode='$pcode'";
			$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
			$event_data = $row=mysqli_fetch_array($result);
			$mem_id = $event_data['m_id'];
			$send_num = $event_data['mobile'];


			if(strstr($event_data['event_info'],"sms") && $join_yn =='Y') {
				$sql="select * from Gn_Event_Check_Sms where mem_phone='$mobile' order by idx desc";
				$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
				$check_data = $row=mysqli_fetch_array($result);
				if($check_data['secret_key'] != $rnum) {
					echo "<script>alert('?��?번?��? ?�?�해주?�요.');history.go(-1);</script>";
					exit;
				}
			}

			if($id) {
				$m_id1 = $id;
			}
			else{
				$m_id1 = $_SESSION['one_member_id'];
			}

			$sql="insert into Gn_event_request set landing_idx='$landing_idx',
			event_idx='$event_idx',
			event_code='$event_code',
			m_id='$m_id',
			req_id='$m_id1',
			name='$name',
			sex='$sex',
			addr='$addr',
			birthday='$birthday',
			consult_date='$consult_date',
			join_yn='$join_yn',
			other='$other',
			mobile='$mobile',
			email='$email',
			rnum='$rnum',
			job='$job',
			pcode='$pcode',
			sp='$sp',
			ip_addr='$ipcheck',
			regdate=now()
			";

			
			$res1=mysqli_query($self_con, $sql);
			$request_idx = mysqli_insert_id($self_con);
			$recv_num = $mobile;
			$recv_num = str_replace("-","", $recv_num);

			/*$sql_recv_mem = "select a.* from Gn_Member a left join Gn_MMS_Number b on a.mem_id=b.mem_id where REPLACE(a.mem_phone,'-','')='{$recv_num}' and (REPLACE(a.mem_phone,'-','')=REPLACE(b.sendnum, '-','') and b.sendnum is not null and b.sendnum != '')";
			$res_recv_mem = mysqli_query($self_con, $sql_recv_mem);
			if(mysqli_num_rows($res_recv_mem)){
				$row_recv_mem = mysqli_fetch_array($res_recv_mem);
				$mem_id = $row_recv_mem['mem_id'];
				$send_num = $recv_num;
			}*/


			if($mem_id == "") $mem_id=$m_id;
			if($mem_id != "")
			{
				$reg = time();

				// ?�?�?��??�?�?�?�게 ?�??
				$stitle = "?�벤???�청 ?�역";
				$scontent = "
				?�청?�주?�?� 감?�합니??\n\n
				$name ?�!\n
				?�청?�?� ?�용�??� ?�?�?�?�?�니??\n
				?�후 ?�?�?� ?�?�나 ?�보�? ?�락드리�??�니??\n
				?� ?�복하?�요!! \n";
				
				sendmms(1, $mem_id, $send_num, $recv_num, "", $stitle, $scontent, "", "", "", "Y");	
	
				$sql="select * from Gn_event_sms_info where event_name_eng='$pcode'";
				$lresult=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
				while($lrow=mysqli_fetch_array($lresult)) {
	
					$sms_idx = $lrow['sms_idx'];
	
					//$send_num = $lrow['mobile'];
	
					//?�?�?��?
					$sql="select * from Gn_event_sms_info where sms_idx='$sms_idx'";
					$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
					$row=mysqli_fetch_array($result);
	
	
					$sql="select * from Gn_event_sms_step_info where sms_idx='$sms_idx'";
					$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
					
					$k = 0;
					while($row=mysqli_fetch_array($result)) {
						// ?�간 ?�??
						$k++;
	
						$send_day = $row['send_day'];
						$send_time = $row['send_time'];
	
						//echo "====".$send_day;
						//echo date("Y-m-d $send_time:00",strtotime("+$send_day days")) . "<br>";
						if($send_time == "") $send_time = "09:30";
						if($send_time == "00:00") $send_time = "09:30";
						if($send_day == 0) {
							//$send_time = date("H:00", strtotime("+1 hours"));
							
							$reservation = "";
						} else {
							$reservation = date("Y-m-d $send_time:00",strtotime("+$send_day days"));
						}
						
						$jpg = $jpg1 = $jpg2 = '';
						if($row['image'])
							$jpg = "https://kiam.kr/adjunct/mms/thum/".$row['image'];
						if($row['image1'])
							$jpg1 = "https://kiam.kr/adjunct/mms/thum/".$row['image1'];
						if($row['image2'])
							$jpg2 = "https://kiam.kr/adjunct/mms/thum/".$row['image2'];
	
						$row['content'] = htmlspecialchars($row['content'],ENT_QUOTES );
	
						sendmms(4, $mem_id, $send_num, $recv_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, "Y", $row['sms_idx'], $row['sms_detail_idx'], $request_idx, "", $row['send_deny']);	
	
						$query = "insert into Gn_MMS_Agree set mem_id='$mem_id',
						send_num='$send_num',
						recv_num='$recv_num',
						content='{$row['content']}',
						title='{$row['title']}',
						jpg='$jpg',
						reg_date=NOW(),
						reservation='$reservation',
						sms_idx='{$row['sms_idx']}',
						sms_detail_idx='{$row['sms_detail_idx']}',
						request_idx='$request_idx'
						";
						//echo $query."<BR>";
						mysqli_query($self_con, $query) or die(mysqli_error($self_con));
	
					}
	
				}
	
	
				if($join_yn=='Y') {
				
					//?�?�가?�
					$sql3="select sub_domain FROM Gn_Service WHERE sub_domain like '%kiam.kr' And mem_id='$m_id'";
					$res=mysqli_query($self_con, $sql3);
					$row1 = mysqli_fetch_array($res);
					if($row1['sub_domain'])
					{
						$parse = parse_url($row1['sub_domain']);
						$sites = explode(".", $parse['host']);
						$site = $sites[0];
					}
					else
					{
						$sql3 = "select site from Gn_Member WHERE mem_id='$m_id'";
						$res=mysqli_query($self_con, $sql3);
						$row1 = mysqli_fetch_array($res);
						$site = $row1['site'];
					}
					$sql3_iam="select sub_domain FROM Gn_Service_Iam WHERE sub_domain like '%kiam.kr' And mem_id='$m_id'";
					$res=mysqli_query($self_con, $sql3_iam);
					$row1_iam = mysqli_fetch_array($res);
					if($row1_iam['sub_domain'])
					{
						$parse = parse_url($row1_iam['sub_domain']);
						$sites = explode(".", $parse['host']);
						$site_iam = $sites[0];
					}
					else
					{
						$sql3_iam = "select site from Gn_Member WHERE mem_id='$m_id'";
						$res=mysqli_query($self_con, $sql3_iam);
						$row1_iam = mysqli_fetch_array($res);
						$site_iam = $row1_iam['site'];
					}
					$userid = $id;
					$passwd = substr($mobile, -4);

					$query = "insert into Gn_Member set mem_id='$userid',
					mem_leb='22',
					web_pwd=password('$passwd'),
					mem_pass=md5('$passwd'),
					mem_name='$name',
					mem_nick='$name',
					mem_type='A',
					recommend_id = '$m_id',
					site='$site',
					site_iam='$site_iam',
					mem_phone='$mobile',
					zy='$job',
					first_regist=now() ,
					mem_check=now(),
					mem_add1='$addr',
					mem_email='$email',
					mem_sex='$sex',
					join_ip='{$_SERVER['REMOTE_ADDR']}'
					";
					mysqli_query($self_con, $query);
					$mem_code = mysqli_insert_id($self_con);
	
				}
	
			}
			if(!$res1) {
				alerting('?�청?�?�?� ?�패했?�니??');
			} else {
				echo "<script>var msg = ''; ";
				echo "msg+='?�청?�주?�?� 감?�합니??\\n\\n';";
				echo "msg+='$name ?�!\\n';";
				echo "msg+='?�청?�?� ?�용�??� ?�?�?�?�?�니??\\n';";
				echo "msg+='?�후 ?�?�?� ?�?�나 ?�보�? ?�락드리�??�니??\\n';";
				echo "msg+='\\n';";
				echo "msg+='?� ?�복하?�요!!\\n';";

				/*
				echo "msg+='?�청?�주?�?� 감?�합니??\\n';";
				echo "msg+='고객?�?� ?�청?�보는 ?�음�?같?�니?? \\n';";
				echo "msg+='1)?�청?� : $name \\n';";
				echo "msg+='2)?��???: $email \\n';";
				echo "msg+='3)직?� : $job \\n';";
				echo "msg+='4)?�청?�??: $pcode \\n';";
				echo "msg+='5)?�청경�? : $sp \\n\\n';";
				echo "msg+='?�?� ?�보�? 고객?�?�게 ?�기적?��? 계?�?�?� ?�벤?�정보와 ?�?�정보�? 보내드리�??�니?? \\n\\n';";
				echo "msg+='감?�합니??';";
				*/
				if($join_yn=='Y') {
					echo "alert(msg);gotoLogin('$userid','$passwd','$mem_code');</script>";
				}
				else{
					echo "alert(msg);</script>";
				}
				//echo "<script>self.close();</script>";
				unset($_POST);
			}
		}
	}
	?>

<body class="body-event">
	<style>
		.mftxt { font-size:14pt; color:#4e6e7e; height:30px; padding-top:3px; }
.mftxt_r { font-weight:bold; font-size:14pt; color:#bb5f5f; height:30px; padding-top:10px; padding-left:0px; }
.mftxt_b { font-weight:bold; font-size:14pt; color:#4772a2; height:30px; padding-top:10px; padding-left:0px;}
.content img {max-width:100%}

.m_div input[type=radio] {height:15px;width:15px;}

.main_info{color:blue;font-size: 14px;}
</style>
	<div class="content">
		<?php echo str_replace('img src="upload/', 'img src="/upload/', $data['content']);?>
	</div>
	<?php if($data['move_url'] != "") {?>
	<iframe width="100%" height="618" src="<?php echo $data['move_url'];?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
	 allowfullscreen></iframe>
	<?php }?>
	<script>
		function checkForm() {
			if ($('#agree').is(":checked") == false) {
				alert('개?�정보이용동?� ?�주?�요.')
				return false;
			}
			if($("#no_mem").prop('checked') && $("#id_html").text() == ''){
				alert('?�?�디 중복확?�을 ?�주?�요.')
				return false;
			}
		}
	</script>

	<script>
		$(function () {
			$('#writeBtn').on("click", function () {
				if ($('#writeForm').css("display") == "none") {
					$('#writeForm').css("display", "block");
				} else {
					$('#writeForm').css("display", "none");
				}
			});
			if ($('#join_yn').prop("checked")) {
				$("#div_account").show();
				$("#phone_verify").show();
			}

			$('#join_yn').on("change", function () {
				if (this.checked) {
					$("#div_account").show();
					$("#phone_verify").show();
				}
				else {
					$("#div_account").hide();
					$("#phone_verify").hide();
				}

			});

			$("input[name=mem_type]").on("change", function (){
				if($("#no_mem").prop('checked')){
					$("#no_txt").show();
					$("#iam_txt").hide();
					$('input[name =join_yn]').val('Y');
					$('input[name =name]').val('');
					$('input[name =mobile]').val('');
					$('input[name =email]').val('');
					$('input[name =addr]').val('');
					$('input[name =birthday]').val('');
					$('input[name =job]').val('');
					$("#div_account").show();
				}
				else{
					$("#iam_txt").show();
					$("#no_txt").hide();
					$("#div_account").hide();
					$('input[name =join_yn]').val('N');
					load_userinfo();
				}
			});
		});

		//?�?�디 중복확??
		function id_check(frm, frm_str) {
			if (!frm.id.value) {
				frm.id.focus();
				return;
			}
			if (frm.id.value.length < 4) {
				alert('?�?�디?� 4?� ?�상 ?�용�?가?�합니??')
				frm.id.focus();
				return;

			}
			var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
			if (!pattern.test(frm.id.value)) {
				document.getElementById('id_html').innerHTML = '?��?�??�?�?�?�디 ?�?�???�?�?�??';
				frm.id_status.value = ''
				frm.id.value = ''
				frm.id.focus();
				return
			}
			else
				document.getElementById('id_html').innerHTML = '';
			$.ajax({
				type: "POST",
				url: "../ajax/ajax.php",
				data: {
					id_che: frm.id.value,
					id_che_form: frm_str
				},
				success: function (data) { $("#ajax_div").html(data) }
			})
		}

		//비밀번???�확??
		function pwd_cfm_check(i) {
			if ($('#join_yn').prop("checked")) {
				if (!document.getElementsByName('pwd_cfm')[i].value) return;
				if (document.getElementsByName('pwd_cfm')[i].value != document.getElementsByName('pwd')[i].value) {
					$($(".pwd_cfm_html")[i]).html("?�번 ?�?�한 비밀번?��? ?�립니??");
					document.getElementsByName('pwd_status')[i].value = '';
					document.getElementsByName('pwd_cfm')[i].focus();
					return
				}
				else {
					document.getElementsByName('pwd_status')[i].value = 'ok';
					$($(".pwd_cfm_html")[i]).html("");
				}
			}


		}
	</script>
	<div id="table" class="form-body">
		<?if($data['file']){?>
		<div style="text-align:center">
			<a href="/upload/<?php echo $data['file'];?>" style="font-weight:bold;font-size:16px" target="_blank">첨�??�???�운�??�</a>
		</div>
		<?}?>
		<?php
			if(($landing_idx != "" && $data['lecture_yn'] == "Y") || ($landing_idx != "" && $data['lecture_yn'] == "Y")) {?>
		<div class="big_sub">
			<div class="m_div">
				<?php include "mypage_left_menu.php";?>
				<div class="m_body">
					<div style="text-align:center;background:#4b657c">
						<p style="font-size:2.125rem;font-weight:bold;font-family:KoPubDotum; color: #FFFFFF;">?�리?�?�?��??�국 ?��??� 및 교???�정</p>
						<a href="https://blog.naver.com/onlyonemj18/222646448643" style="font-weight:bold;font-size:25px;color:yellow" target="_blank">[?�국지?�?�터와 ?�업??보기]   </a>
					</div>
					<form name="pay_form" action="" method="post" class="my_pay">
						<input type="hidden" name="lecture_id" value="<?php echo $row['lecture_id'];?>" />
						<input type="hidden" name="event_idx" value="<?php echo $event_idx;?>" />
						<input type="hidden" name="pcode" value="<?php echo $pcode;?>" />
						<input type="hidden" name="sp" value="<?php echo $sp;?>" />
						<input type="hidden" name="landing_idx" value="<?php echo $landing_idx;?>" />
						<input type="hidden" name="page" value="<?=$page?>" />
						<input type="hidden" name="page2" value="<?=$page2?>" />
						<div class="a1" style="margin-top:15px; margin-bottom:15px;color: #FFFFFF">
							<li style="float:left;">강??교?�과?� ?�?�결과보�?/li>
							<li style="float:right;"></li>
							<p style="clear:both"></p>
						</div>
						<div>
							<div class="p1 table-utils clearfix">
								<div class="left">
									<label class="check-item">
										<input type="radio" name="category" value="" checked>
										?��?
									</label>
									<label class="check-item">
										<input type="radio" name="category" value="강?? <?php echo $_REQUEST['category']=="강?? ?"checked":""?>>
										강??
									</label>
									<label class="check-item">
										<input type="radio" name="category" value="교?? <?php echo $_REQUEST['category']=="교?? ?"checked":""?>>
										교??
									</label>
									<label class="check-item">
										<input type="radio" name="category" value="?�?�" <?php echo $_REQUEST['category']=="?�?�" ?"checked":""?>>
										?�?�
									</label>
									<input type="text" name="search_text" placeholder="" id="search_text" value="<?=$_REQUEST['search_text']?>" />
									<a href="javascript:void(0)" id="searchC" onclick="$('#pay_form').submit()"><img src="/images/sub_mypage_11.jpg" /></a>
								</div>


								<div class="right">
									<label class="check-item"><input type="radio" name="end_date" value="">?��?/label>
									<label class="check-item"><input type="radio" name="end_date" value="Y" <?php echo ($_REQUEST['end_date']=="Y"||$_REQUEST['end_date']=="")?"checked":""?>>진?�?�료</label>
									<label class="check-item"><input type="radio" name="end_date" value="N" <?php echo $_REQUEST['end_date']=="N"?"checked":""?>>진?�중</label>
								</div>
							</div>

							<div style="text-align:center; color:#0075FF;font-size:15px;"> <b>?��??�?�청방�? : 참여하고 ?�은 강?�?�목을 ?�?�한 ?�?� 참여신청?��?버?�을 ?�릭?�?�?�
									?�청?�니??/b></style>
							</div>

							<script>
								$(function () {
									$('input[name=category]').bind("click", function () {
										$.ajax({
											type: "POST",
											url: "/event/event.ajax.html",
											data: {
												mode: "review_save",
												category: $('input[name=category]:checked').val(),
												end_date: $('input[name=end_date]:checked').val(),
												search_text: $('#search_text').val(),
												landing_idx: '<?php echo $_REQUEST['landing_idx'];?>'
											},
											success: function (data) {
												$('#lecture_list').html(data);
												$('.paging').on("click", function () {
													ajaxView($(this).data("page"));
												});
											}
										});
									});
									$('input[name=end_date]').bind("click", function () {
										$.ajax({
											type: "POST",
											url: "/event/event.ajax.html",
											data: {
												mode: "review_save",
												category: $('input[name=category]:checked').val(),
												end_date: $('input[name=end_date]:checked').val(),
												search_text: $('#search_text').val(),
												landing_idx: '<?php echo $_REQUEST['landing_idx'];?>'
											},
											success: function (data) {
												$('#lecture_list').html(data);
												$('.paging').on("click", function () {
													ajaxView($(this).data("page"));
												});
											}
										});
									});
									$('#searchC').bind("click", function () {
										$.ajax({
											type: "POST",
											url: "/event/event.ajax.html",
											data: {
												mode: "review_save",
												category: $('input[name=category]:checked').val(),
												end_date: $('input[name=end_date]:checked').val(),
												search_text: $('#search_text').val(),
												landing_idx: '<?php echo $_REQUEST['landing_idx'];?>'
											},
											success: function (data) {
												$('#lecture_list').html(data);
												$('.paging').on("click", function () {
													ajaxView($(this).data("page"));
												});
											}
										});
									});

								})
								function ajaxView(page) {
									$.ajax({
										type: "POST",
										url: "/event/event.ajax.html",
										data: {
											mode: "review_save",
											category: $('input[name=category]:checked').val(),
											end_date: $('input[name=end_date]:checked').val(),
											search_text: $('#search_text').val(),
											landing_idx: '<?php echo $_REQUEST['landing_idx'];?>',
											page: page,
										},
										success: function (data) {
											$('#lecture_list').html(data);
											$('.paging').on("click", function () {
												ajaxView($(this).data("page"));
											});
										}
									});
								}
							</script>
							<?
								//?�보렙?��?�?체크한??	
								$member_id = $data['m_id'];								
								$sql="select mem_leb FROM Gn_Member WHERE mem_id='$member_id'";
								$res=mysqli_query($self_con, $sql);
								$memrow = mysqli_fetch_array($res);
								?>
							<div class="table-wrap">
								<table class="list_table" border="0" cellspacing="0" cellpadding="0">
									<thead>
										<tr>
											<th class="no">No</th>
											<th class="category">분??/th>
											<th class="date">?�정/기�?</th>
											<th class="day">?�??/th>
											<th class="time">강?�<br>?�간</th>
											<th class="title">강?�?��?/a></th>
											<th class="teacher">강?�이�?</th>
											<th class="place">지?�/?�소<br>(?�릭)</th>
											<th class="object">참여대?�</th>
											<th class="limit">?�?�</th>
											<th class="price">비??/th>
											<?if($memrow['mem_leb'] != "60"){?>
											<th class="request">?�청</th>
											<?}?>
											<!--
												<th>?�?�/?�?�</th>
												-->
										</tr>
									</thead>
									<tbody id="lecture_list">
										<?

											if(isset($_REQUEST['end_date'])) {

											} else {
											    $_REQUEST['end_date']= "N";
											}

											$sql_serch=" status='Y'";

											if($data['m_id'] != "obmms02" && $memrow['mem_leb'] != "60") {
											    $sql_serch.=" and mem_id ='".$data['m_id']."'";
											}

											//if($_REQUEST['category']=="")
											//    $sql_serch.=" and category ='강??";

											$photo_sql = $sql_serch;
											if($_REQUEST['category'])
											{
											$sql_serch.=" and category ='$category'";

											}
											$now = date("Y-m-d");
											if($_REQUEST['end_date'] == "Y")
											{
											$sql_serch.=" and end_date < '$now'";
											}
											if($_REQUEST['end_date'] == "N")
											{
											$sql_serch.=" and end_date >= '$now'";
											}

											if($_REQUEST['search_text'])
											{
											    $search_text = $_REQUEST['search_text'];
											    $sql_serch.=" and (lecture_info like '%$search_text%' or area like '%$search_text%'or instructor like '%$search_text%')";
											}

											$sql="select count(lecture_id) as cnt from Gn_lecture where $sql_serch ";
											$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
											$row=mysqli_fetch_array($result);
											$intRowCount=$row['cnt'];
											if($intRowCount)
											{
											    if (!$_POST['lno'])
											        $intPageSize =30;
											    else
											    $intPageSize = $_POST['lno'];
											    if($_POST['page'])
											    {
											        $page=(int)$_POST['page'];
											        $sort_no=$intRowCount-($intPageSize*$page-$intPageSize);
											    }
											    else
											    {
											        $page=1;
											        $sort_no=$intRowCount;
											    }
    											if($_POST['page2'])
    											    $page2=(int)$_POST['page2'];
    											else
    											    $page2=1;

    											$int=($page-1)*$intPageSize;

    											if($_REQUEST['order_status'])
    											    $order_status=$_REQUEST['order_status'];
    											else
    											    $order_status="asc";

    											if($_REQUEST['order_name'])
    											    $order_name=$_REQUEST['order_name'];
    											else
    											    $order_name="start_date";

    											$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
    											//$sql="select * from Gn_lecture where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
												$sql="select * from Gn_lecture where $sql_serch order by start_date desc limit $int,$intPageSize";

												echo "<!-- [ $sql ] -->";


    											$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
											?>


										<?
											while($row=mysqli_fetch_array($result))
											{
											//$num_arr=array();
											    $sql_num="select * from Gn_event where m_id='{$row['mem_id']}' and event_idx='{$row['event_idx']}' ";
											    $resul_num=mysqli_query($self_con, $sql_num);
											    $crow=mysqli_fetch_array($resul_num);


											?>



										<tr>
											<td>
												<?=$sort_no?>
											</td>
											<td>
												<?=$row['category']?>
											</td>
											<td>
												<?=$row['start_date']?>~<BR>
												<?=$row['end_date']?>
											</td>
											<td>
												<?=$row['lecture_day']?>
											</td>
											<td>
												<?=$row['lecture_start_time']?>~<BR>
												<?=$row['lecture_end_time']?>
											</td>

											<td>
												<?php if($row['lecture_url']) {?>
												<a href="<?php echo $row['lecture_url'];?>" target="_blank">
													<?php }?>
													<div style="font-size:13px; text-align:left; font-weight:bold;color:blue;">
														<?=$row['lecture_info']?>
														</a</div> </td> <!-- <td>
														<?php if($row['lecture_url']) {?>
														<a href="<?php echo $row['lecture_url'];?>" target="_blank">
															<?php }?>
															<?=$row['lecture_info']?></a></td> -->
											<td>
												<?=$row['instructor']?>
											</td>
											<td>
												<div style="width:155px;overflow-y:hidden;height:45px;cursor:pointer;" class="area_view">
													<?=$row['area']?>
												</div>
											</td>
											<td>
												<div style="text-align:left; color:#0075FF;">
												<?=$row['target']?>
												</div>
											</td>
											<td>
												<?=$row['max_num']?>명</td>
											<td>
												<?=$row['fee']==0?"무�?":$row['fee']."?�"?>
											</td>
											<?if($memrow['mem_leb'] != "60"){?>
											<td>
												<input type="button" value="?�청?��? class="button" onclick="viewEvent('<?php echo $crow['short_url']?>')">
											</td>
											<?}?>

											<!--
												<td>
													<a href='mypage_lecture_write.php?lecture_id=<?php echo $row['lecture_id'];?>'>?�?�</a>/<a href="javascript:;;" onclick="removeRow('<?php echo $row['lecture_id'];?>')">?�?�</a>
												</td>
												-->
										</tr>
										<?
											$sort_no--;
											}
											?>
										<tr>
											<td colspan="14">
												<?
													page_ajax($page,$page2,$intPageCount,"pay_form");
													?>
											</td>
										</tr>
										<?
											}
											else
											{
											?>
										<tr>
											<td colspan="14">
												검?�?� ?�용�??�?�니??
											</td>
										</tr>
										<?
											}
											?>
									</tbody>
								</table>
								<!--
									<input type="button" value="?�???�?��? ?�?�" class="button">
									-->
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="review-button clearfix">
		<input type="button" value="강?�?�청?��? onClick="gotoApply()" style="height:60px; border:1px ;padding:15px 30px ;font-weight:bold;background-color:#0075FF;color:white;font-size: 20px;">>

	</div>

	<script>
		function gotoApply() {
			location.href = "#apply";
		}
	</script>
	<div class="footer-content">
		<?php echo $data['footer_content'];?>
	</div>
	<div id="review" class="review-body">
		<div class="big_sub">
			<div class="m_div">
				<div class="m_body">
					<div style="text-align:center;background:#4b657c">
						<p style="font-size:25px;font-weight:bold; font-family:KoPubDotum; color: #FFFFFF;">?�시간 리뷰보기</p>
						<p style="font-size:15px;font-weight:bold; font-family:KoPubDotum; color: #FFFFFF;">**리뷰�??��? ?�?��??�?�?� ?�?�을
							?�보해보?�요. 리뷰?�???�보�? ?��?지?�집?�??*</p>
					</div>
					<div>
						<div class="review-button clearfix">
							<input type="button" value="리뷰 ?�?�하�? class="button" id="writeBtn">
						</div>
						<form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">
							<input type="hidden" name="mode" value="<?=$lecture_id?" lecture_updat":"lecture_save_event";?>" />
							<input type="hidden" name="lecture_id" value="<?php echo $row['lecture_id'];?>" />
							<input type="hidden" name="event_idx" value="<?php echo $event_idx;?>" />
							<input type="hidden" name="pcode" value="<?php echo $pcode;?>" />
							<input type="hidden" name="sp" value="<?php echo $sp;?>" />
							<input type="hidden" name="landing_idx" value="<?php echo $landing_idx;?>" />
							<div id="writeForm" style="display:none">
								<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<th>?�가</th>
										<td>
											<input type="radio" name="score" value="5" checked><span style="color:#FFCE0B">?�?�?�?�?�</span>
											<input type="radio" name="score" value="4"><span style="color:#FFCE0B">?�?�?�?�</span>
											<input type="radio" name="score" value="3"><span style="color:#FFCE0B">?�?�?�</span>
											<input type="radio" name="score" value="2"><span style="color:#FFCE0B">?�?�</span>
											<input type="radio" name="score" value="1"><span style="color:#FFCE0B">?�</span>
										</td>
									</tr>
									<tr>
										<th>강?�?�?�</th>
										<td>
											<input type="hidden" name="lecture_id" id="lecture_id">
											<input type="text" name="lecture_info" id="lecture_info" readonly placeholder="?�측?� 강?�?�?� 버?�을 ?�릭?�??참여한 강?��??�?�?�주?�요.">
											<input type="button" value="강?�?�?�" class="button searchBtn">
										</td>
									</tr>
									<tr>
										<th>리뷰?��?/th>
										<td>
											<textarea name="content" id="content"></textarea>
										</td>
									</tr>
									<tr>
										<th>?�기소개</th>
										<td><input type="text" name="profile" maxlength="50" id="profile" placeholder="50?� ?�내�? ?�?� ???�비??그리고 ?�락�?�??�겨?� ?�로 ?�?�하?�요."></td>
									</tr>
									<tr>
										<td colspan="2" style="text-align:right;">
											<input type="button" value="취소" class="button" id="cancleBtn">
											<input type="button" value="글?�리�? class="button" id="saveBtn">
										</td>
									</tr>
								</table>
							</div>
						</form>

						<div class="slide-wrap">
							<div id="reviewSlider">
								<? 
									$sql="select * from Gn_lecture where $photo_sql order by start_date desc";
									$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									$count = 0;
									while($row=mysqli_fetch_array($result))
									{
										if($row['review_img1'])
										{
											?>
								<div class="slide-item"> <img src="upload/lecture/<?=$row['review_img1']?>"></div>
								<?
											$count++;
										}
										if($row['review_img2'])
										{
											?>
								<div class="slide-item"> <img src="upload/lecture/<?=$row['review_img2']?>"></div>
								<?
											$count++;
										}
										if($row['review_img3'])
										{
											?>
								<div class="slide-item"> <img src="upload/lecture/<?=$row['review_img3']?>"></div>
								<?
											$count++;
										}
										if($row['review_img4'])
										{
											?>
								<div class="slide-item"> <img src="upload/lecture/<?=$row['review_img4']?>"></div>
								<?
											$count++;
										}
										if($row['review_img5'])
										{
											?>
								<div class="slide-item"> <img src="upload/lecture/<?=$row['review_img5']?>"></div>
								<?
											$count++;
										}
									}

									if($count == 0)
									{
										?>
								<div class="slide-item"><img src="images/demo.jpg"> </div>
								<div class="slide-item"><img src="images/demo.jpg"> </div>
								<div class="slide-item"><img src="images/demo.jpg"> </div>
								<div class="slide-item"><img src="images/demo.jpg"> </div>
								<div class="slide-item"><img src="images/demo.jpg"> </div>
								<?
									}?>
							</div>
							<div class="slide-arrows"></div>
						</div>

						<div>
							<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
								<?
									$sql_serch=" 1=1 ";
									//$sql_serch = "";
									//if($_REQUEST['category'])
									//{
									//    $sql_serch.=" and category ='$category'";
									//}
									$now = date("Y-m-d");
									//if($_REQUEST['end_date'] == "Y")
									//{
									//    $sql_serch.=" and end_date < '$now'";
									//}
									//if($_REQUEST['end_date'] == "N")
									//{
									//    $sql_serch.=" and end_date >= '$now'";
									//}
									//?�보렙?��?�?체크한??
									if($data['m_id'] != "obmms02" && $memrow['mem_leb'] != "60") {
									    $sql_serch.=" and b.mem_id ='".$data['m_id']."'";
									}

									if($_REQUEST['search_text_review'])
									{
									    $search_text_review = $_REQUEST['search_text_review'];
									    $sql_serch.=" and (lecture_info like '%$search_text_review%' or area like '%$search_text_review%'or instructor like '%$search_text_review%')";
									}

									$sql="select count(review_id) as cnt from Gn_review a
									                               inner join Gn_lecture b
									                                       on  a.lecture_id = b.lecture_id
									                                where $sql_serch ";
									$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									$row=mysqli_fetch_array($result);
									$intRowCount=$row['cnt'];
									if($intRowCount)
									{
									if (!$_POST['lno'])
									$intPageSize =30;
									else
									$intPageSize = $_POST['lno'];
									if($_POST['page'])
									{
									$page=(int)$_POST['page'];
									$sort_no=$intRowCount-($intPageSize*$page-$intPageSize);
									}
									else
									{
									$page=1;
									$sort_no=$intRowCount;
									}
									if($_POST['page2'])
									$page2=(int)$_POST['page2'];
									else
									$page2=1;

									$int=($page-1)*$intPageSize;
									if($_REQUEST['order_status'])
									$order_status=$_REQUEST['order_status'];
									else
									$order_status="desc";
									if($_REQUEST['order_name'])
									$order_name=$_REQUEST['order_name'];
									else
									$order_name="review_id";
									$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
									$sql="select * from Gn_review a
									inner join Gn_lecture b
									on  a.lecture_id = b.lecture_id
									where $sql_serch order by start_date desc limit $int,$intPageSize";
									$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									?>
								<?
									while($row=mysqli_fetch_array($result))
									{
									?>

								<tr>
									<td class="photo" rowspan="3"><img src="/images/man.png" width= "60" height= "60" ></td>
									<td style="font-size:14px;font-weight:bold; text-align:left;" class="profile" colspan="3">
										<?=$row['lecture_info']?> /
										<?=$row['instructor']?> /
										<?=$row['start_date']?>~
										<?=$row['end_date']?> /
										<?=$row['area']?>
										<?php if($row['score']=="5"){?>?�?�?�?�?�
										<?php }?>
										<?php if($row['score']=="4"){?>?�?�?�?�
										<?php }?>
										<?php if($row['score']=="3"){?>?�?�?�
										<?php }?>
										<?php if($row['score']=="2"){?>?�?�
										<?php }?>
										<?php if($row['score']=="1"){?>?�
										<?php }?>
									</td>
								</tr>
								<tr>
									<td class="content" colspan="3" >
										<div class="div_content">
											<?=nl2br($row['content'])?>
										</div>
										<input type="button" value="+" class="button" id="viewMore" style="line-height: revert;font-size: 20px;right: 40px;">
										<div class="dropdown" style="position: absolute; right: 3px; bottom: 0px;">
											<button class="btn-link dropdown-toggle westory_dropdown" type="button" data-toggle="dropdown"  aria-expanded="false">
												<img src="/iam/img/menu/icon_dot.png" style="height:24px">
											</button>
											<ul class="dropdown-menu comunity" style="left: revert;right: 0;">
												<li><a onclick="set_block_contents('2972093')">??콘?�츠 차단하�?/a></li>
												<li><a onclick="set_report_contents('2972093')">??콘?�츠 ?�고?��?/a></li>
											</ul>
										</div>
									</td>
								</tr>
								<tr>
									<td style="font-size:12px;color:#7F7F7F;">
										<?=$row['mem_name']?>
									</td>
									<td style="font-size:12px;font-weight:bold;color:#2F5597;" class="intro">
										<?php echo $row['profile'];?>
									</td>
									<td class="date">
										<?=$row['regdate']?>
									</td>
								</tr>
								<?
									$sort_no--;
									}
									?>
								<tr>
									<td colspan="14">
										<?
											page_f($page,$page2,$intPageCount,"pay_form");
											?>
									</td>
								</tr>
								<?
									}
									else
									{
									?>
								<tr>
									<td colspan="14">
										검?�?� ?�용�??�?�니??
									</td>
								</tr>
								<?
									}
									?>
							</table>
							<!--
								<input type="button" value="?�???�?��? ?�?�" class="button">
								-->
						</div>
					</div>
					</form>
				</div>
			</div>
			<?php }?>
			<?php if($data['movie_url']){?>
			<iframe width="100%" height="600px" src="<?php echo $data['movie_url'];?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
			 allowfullscreen></iframe>
			<?php }?>
			<?php if(($landing_idx != "" && $data['request_yn'] == "Y") || $landing_idx == "") {?>
			<form id="dform1" name="dform1" method="post" action="<?=$SERVER['PHP_SELF']?>" onsubmit="return checkForm()">
				<input type="hidden" name="mode" value="speech">
				<input type="hidden" name="pcode" value="<?=$pcode?>">
				<input type="hidden" name="event_code" value="<?=$pcode?>">
				<input type="hidden" name="m_id" value="<?=$m_id?>">
				<input type="hidden" name="event_idx" value="<?=$event_idx?>">
				<input type="hidden" id="id_reg" name="id_reg" value="0">
				<input type="hidden" id="email_reg" name="email_reg" value="0">
				<input type="hidden" id="nick_reg" name="nick_reg" value="0">
				<input type="hidden" id="landing_idx" name="landing_idx" value="<?php echo $landing_idx;?>">
				<div id="apply" class="common-wrap" style="text-align:center;margin-top: 20px;max-width:600px;">
					<!-- ?�청?��?-->
					<div class="container" style="width: 100%;">
						<div class="row" style="margin: 0px;">
							<div class="col-12">
								<div class="inner-wrap">
									<h2 class="title">
										<!--<em>-->
										<?php if($event_data['event_title'] != ""){?>
										<?php echo $event_data['event_title'];?>
										<?php } else {?>
										?�청?��?
										<?php } ?>
										<!--</em>-->
									</h2>

									<section class="input-field">
										<h3 class="title">기본?��??�??/h3>
										<?if(strstr($event_data['event_info'],"join")){?>
										<div class="utils clearfix">
											<input type="radio" name="mem_type" id="no_mem" checked>
											<label for="no_mem" value="none_mem" style="font-size:17px;">비?�?�</label>
											<input type="radio" name="mem_type" id="iam_mem" style="margin-left:10px;">
											<label for="iam_mem" value="iam_mem_ori" style="font-size:17px;">IAM기존?�?�</label>
											<br>
											<span class="main_info" id="no_txt">비?�?� ?�청?� ?�?�디?� ?�대????4?�리�? 비번?��? ?�청?�보�? ?�?� 및 취소?� ?� ?�?�니??</span>
											<!-- <span class="main_info" id="iam_txt" hidden>계?�?� ?�?�하?��??�?�?��? ?�?�됩니??</span> -->
										</div>
										<?}?>
										<!-- <div class="utils clearfix">
											<span class="notice">?�?�가?��??�?�?� e?�로?�???�?�됩니??</span>
											<a href="javascript:;" onclick="load_userinfo()" class="button">?�?�?��?가?�오기(기존?�?�)</a> 
										</div> -->
										<?php if(strstr($event_data['event_info'],"join")) {?>
										<input type="hidden" name="join_yn" value="Y">
										<!-- <div class="form-wrap" style="background-color: lemonchiffon">
											<div class="attr-row">
												<div class="attr-name"><b>?�?�가?�</b></div>
												<div class="attr-value">
													<div class="input-wrap" style="font-size:14px;">
														<input type="checkbox" id="join_yn" name="join_yn" value="Y" <? if($join_yn=='Y' ) echo checked ?> >
														<label><span style="color:#DF0101"><b>??체크�???꼭 ?�릭?�야해요!!</b><br> ?�?�가?� 체크�??��? ?�릭?��??�청???�료?��? 추?� ?�보제공이나 ?�?�엠
																?��? 무�?문자, ?�버?��??��?가?�합니?? ?��?가?�?�분?� ?�?� ?�?�?�보�??�오기�??�릭?�?�요.</span></label>
													</div>
												</div>
											</div>
										</div> -->
										<br>
										<?php }
										else{?>
										<input type="hidden" name="join_yn" value="N">
										<?}?>
										<div class="form-wrap">
											<div id="div_account" style="display:block;">
												<?if(strstr($event_data['event_info'],"join")){?>
												<div class="attr-row ">
													<div class="attr-name">?�?�디</div>
													<div class="attr-value">
														<div class="input-wrap">
															<input type="text" id="id" name="id" value="<?=$_POST['id']?>" placeholder="6-15?�로 ?�?�하?�요." style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;" />
															<input type="button" style="margin: 5px;" value="중복확?? class="button is-grey" onClick="id_check(dform1,'dform1')" />
															<span id='id_html' style="width: 60px;"></span>
															<input type="hidden" name="id_status" value="<?=$_POST['id_status']?>" itemname='?�?�디중복확?? required />
															&nbsp;&nbsp; <p id="id_chk_str" style="display: inline-block;">???�?�디 중복확?�을 ?�릭?�주?�요.</p>

														</div>
													</div>
												</div>
												<?}?>
												<!-- <div class="attr-row ">
													<div class="attr-name">비밀번??/div>
													<div class="attr-value">
														<div class="input-wrap">
															<input type="password" id="pwd" name="pwd" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
															 value="<?=$_POST['pwd']?>" />
														</div>
													</div>
												</div>
												<div class="attr-row ">
													<div class="attr-name">비번?�??/div>
													<div class="attr-value">
														<div class="input-wrap">
															<input type="password" onblur="pwd_cfm_check('0')" name="pwd_cfm" value="<?=$_POST[pwd_cfm]?>" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;" />
															<input type="hidden" name="pwd_status" value="<?=$_POST[pwd_status]?>" />
														</div>
													</div>

												</div>
												<div class='pwd_cfm_html'></div> -->

											</div>
											<div class="attr-row is-account">
												<div class="attr-name">?��?</div>
												<div class="attr-value">
													<div class="input-wrap">
														<input type="text" name="name" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
														 id="name" value="<?=$_POST['name']?>" />

													</div>
												</div>
											</div>
											<div class="attr-row is-phone">
												<div class="attr-name">?�대??/div>
												<div class="attr-value">
													<div class="input-wrap">
														<?php if(strstr($event_data['event_info'],"sms")) {
															$width = "50%";
														}
														else{
															$width = "90%";
														}?>
														<input type="tel" name="mobile" style="width:<?=$width?>; height:40px; border:1px #cacaca solid;font-size: 14px;"
														 id="tel" onblur="checkPhon()" value="<?=$_POST['mobile']?>" placeholder="'-'�?빼�? ?�???� : 01012345678)" />
														 <?php if(strstr($event_data['event_info'],"sms")) {?>
														 <input type="button" value="?��?번??받�? class="button" onclick="chk_sms()" style=" height:42px; border:1px #cacaca solid;font-size: 14px;">
														 <?}?>
													</div>
												</div>
											</div>
											<?php if(strstr($event_data['event_info'],"sms")) {?>
											<div id="phone_verify" style="display: block;">
												<div class="attr-row is-phone">
													<div class="attr-name">?��?번??/div>
													<div class="attr-value">
														<div class="input-wrap">
															<input type="text" name="rnum" id="rnum" itemname='?��?번?? maxlength="10" style="width:50%; height:40px; border:1px #cacaca solid;font-size: 14px;" />
															<input type="button" value="?��?번???�?? class="button" onclick="chk_sms1()" style=" height:42px; border:1px #cacaca solid;font-size: 14px;">
															<span id="check_sms"></span>
														</div>
													</div>
												</div>
											</div>

											<?php } ?>

											<?php if(strstr($event_data['event_info'],"sex")) {?>
											<div class="attr-row">
												<div class="attr-name">?��?</div>
												<div class="attr-value">
													<div class="input-wrap" style="font-size: 14px;">
														<input type="radio" name="sex" value="m" checked> ??
														<input type="radio" name="sex" value="f"> ??
													</div>
												</div>
											</div>

											<?php }?>
											<?php if(strstr($event_data['event_info'],"email")) {?>
											<div class="attr-row is-mail">
												<div class="attr-name">?��???/div>
												<div class="attr-value">
													<div class="input-wrap">
														<input type="text" onblur="email_check()" name="email" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
														 id="email" value="<?=$_POST['email']?>" onKeyUp="func_check('mem_email',this.value)" />
													</div>
												</div>
											</div>

											<?php }?>
											<?php if(strstr($event_data['event_info'],"job")) {?>
											<div class="attr-row">
												<div class="attr-name">?�?�/직?�</div>
												<div class="attr-value">
													<div class="input-wrap">
														<input type="text" name="job" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
														 id="job" value="<?=$_POST['job']?>" placeholder="/로 ?�?��?직?� 분�??�?�해요" />
													</div>
												</div>
											</div>

											<?php }?>
											<?php if(strstr($event_data['event_info'],"address")) {?>
											<div class="attr-row">
												<div class="attr-name">주소</div>
												<div class="attr-value">
													<div class="input-wrap">
														<input type="text" name="addr" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
														 id="addr" value="" placeholder="?�벤?�에 ?�?�?�?� ?�?�?� ?�?�해요" />
													</div>
												</div>
											</div>
											<?php }?>
											<?php if(strstr($event_data['event_info'],"birth")) {?>

											<div class="attr-row">
												<div class="attr-name">출?�?�?�</div>
												<div class="attr-value">
													<div class="input-wrap">
														<input type="text" name="birthday" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
														 id="birthday" value="" placeholder="출?�?�?�만 ?�?�하?�요" />
													</div>
												</div>
											</div>
											<?php }?>



											<?if($memrow['mem_leb'] == "60"){?>

											<div class="attr-row">
												<div class="attr-name">?�청강좌명</div>
												<div class="attr-value">
													<div class="input-wrap">
														<!-- <input type="text" name="consult_date" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;" id="consult_date" value="" placeholder="참여�? ?�?�?� 강좌명?� ?�?�하?�요"/> -->

														<input type="hidden" name="consult_date" id="lecture_id_2">
														<input type="text" name="consult_date" id="lecture_info_2" readonly placeholder="참여�? ?�?�?� 강좌명?� ?�?�하?�요"
														 style="width:70%; height:40px; border:1px #cacaca solid;font-size: 14px;">
														<input type="button" value="강?�?�?�" class="button searchBtn" style="height: 42px;border: 1px #cacaca solid;font-size: 14px;">
													</div>
												</div>
											</div>



											<?php }?>
											<?php if(strstr($event_data['event_info'],"other")) {?>
											<div class="attr-row">
												<div class="attr-name">기타</div>
												<div class="attr-value">
													<div class="input-wrap">
														<textarea name="other" style="width:90%; height:40px; border:1px #cacaca solid; font-size:14px;"
														 id="other" value="" placeholder="<?=$event_data['event_req_link']?>"></textarea>
													</div>
												</div>
											</div>
											<?php }?>

											<div class="attr-row is-account">

												<div class="attr-name">?�청?�??/div>
												<div class="attr-value">
													<div class="input-wrap" style="font-size: 14px;">
														<?php echo $event_data['event_name_kor'];?>
														<input type="hidden" name="sp" style="width:90%; height:40px; border:1px #cacaca solid;" id="sp" value="<?=$sp?$sp:$pcode?>"
														 <?=$readonly?> />

													</div>
												</div>
											</div>
											<div class="apply inner-wrap agreement-field">
												<div class="agreement-wrap">
													<div class="agreement-item">
														<div class="check-wrap">
															<input type="checkbox" name="agree" id="agree" value="Y">
															<label for="agree" style="font-size: 14px;">개?�정보이용동?�</label>
														</div>
														<a href="#" onclick="newpop_('terms.php')">?�문보�?/a>
													</div>
												</div>
											</div>

											<!--        <div class="button-wrap">

	                                <input align="middle" src="pop_btn_regist3.png" type="image" class="button is-grey" value="?�청?��? />
								</div>  -->

											<div class="agreement-field">
												<div class="agreement-wrap">
													<?php if($event_data['event_desc']) {?>
													<div class="agreement-item">

														<?php echo nl2br($event_data['event_desc']);?>

													</div>

												</div>
												<?php } ?>
											</div>
											<?php } ?>

											<div class="button-wrap">
												<input align="middle" src="pop_btn_regist3.png" type="image" class="button is-grey" value="?�청?��? />
											</div>

									</section>
								</div>
							</div>
						</div>
					</div>
				</div><!-- // ?�청?��??� -->
				<div id="ajax_div" style="display:none"></div>
			</form>
			<Script>
				function viewEvent(str) {
					window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");

				}
				function newpop_(str) {
					window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=350");

				}
			</script>
			<Script>
				function newpop() {
					var win = window.open("/mypage_lecture_list_pop.php?landing_idx=<?php echo $_REQUEST['landing_idx'];?>", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");

				}
				$(function () {
					$('.area_view').on('click', function () {
						if ($(this).text().length > 10) {
							if ($(this).css("overflow-y") == "hidden") {
								$(this).css("overflow-y", "auto");
								$(this).css("height", "80px");

							} else {
								$(this).css("overflow-y", "hidden");
								$(this).css("height", "45px");
							}
						}
					});

					$('.searchBtn').on("click", function () {
						newpop();
					});
				})
				var saveChk = false;
				$(function () {
					$('#saveBtn').on("click", function () {

						if ($('#lecture_id').val() == "") {
							alert('강?��??�?�??주세요.');
							$('#lecture_id').focus();
							return;
						}

						if ($('#content').val() == "") {
							alert('리뷰?�용을 ?�?�??주세요.');
							$('#content').focus();
							return;
						}
						if (saveChk == false) {
							saveChk = true;
							$.ajax({
								type: "POST",
								url: "/mypage.proc.php",
								data: {
									mode: "review_save",
									score: $('input[name=score]:checked').val(),
									lecture_id: $('#lecture_id').val(),
									content: $('#content').val(),
									profile: $('#profile').val()
								},
								success: function (data) {

									alert('리뷰가 ?��??�?�?�니??');
									saveChk = false;
									location.reload();
								}
							});
						}
					});
					/*
					$('.copyLinkBtn').bind("click", function() {
					var trb = $(this).data("link");
					var IE=(document.all)?true:false;
					if (IE) {
					if(confirm("??링?��? 복사하?�겠?�니까?")) {
					window.clipboardData.setData("Text", trb);
					}
					} else {
					temp = prompt("Ctrl+C�??�???�립보드로 복사하?�요", trb);
					}
					});
					$('.switch').on("change", function() {
					var no = $(this).find("input[type=checkbox]").val();
					var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
							$.ajax({
								type:"POST",
								url:"mypage.proc.php",
								data:{
									mode:"land_updat_status",
									landing_idx:no,
									status:status,
									},
								success:function(data){
								//alert('?�청?�?�?�니??');location.reload();
								}
								})
		
					//console.log($(this).find("input[type=checkbox]").is(":checked"));
					//console.log($(this).find("input[type=checkbox]").val());
					});
					*/
				})
			</Script>
		</div>
		<div id="floating-menu">
			<ul>
				<?php if($data['lecture_yn'] == "Y") {?>
				<li class="menu"><a href="#" data-scroll="table"><img src="images/side_01.png" alt="강?�일정 바로가�? width="200px"></a></li>
				<li class="menu"><a href="#" data-scroll="review"><img src="images/side_02.png" alt="?�시간리뷰 바로가�? width="200px"></a></li>
				<li class="menu"><a href="#" data-scroll="apply"><img src="images/side_03.png" alt="강?��??�신청?��? width="200px"></a></li>
				<?php } ?>
			</ul>
		</div>
		<div id="contents_report_modal" class="modal fade in" tabindex="-1" role="dialog" aria-hidden="false">
			<div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:500px;">
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<img src="/iam/img/menu/icon_close.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="width:100%;font-size:18px;text-align: center;color:black;border-bottom: 1px solid #c8c9c8;">
						<label style="padding:15px 0px">?�고 ?��??�?�?��?/label>
					</div>
					<div class="modal-header" style="text-align:left;">
						<span style="font-size:15px;margin-bottom:15px;">?�?� ?�목을 ?�?�?�거나 ?�?�에 직?� ?�?�하기에 ?�고?�용을 ?�?�해주?�요.</span>
						<div>
							<input type="checkbox" name="report_title" value="1" id="sex">
							<label for="sex">?��????��?/label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="2" id="force">
							<label for="force">?�?�물 ?��?/label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="3" id="worry">
							<label for="worry">괴롭?� ?��?/label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="4" id="ownkill">
							<label for="ownkill">?�???�???��?/label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="3" id="wrong">
							<label for="wrong">?��?거�? ?��?/label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="3" id="spam">
							<label for="spam">불법 ?�팸�??��?/label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="3" id="unaccept">
							<label for="unaccept">무허가 ?��??��?/label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="3" id="dislike">
							<label for="dislike">?�??발???��?/label>
						</div>
						<input type="hidden" name="content_idx_report" id="content_idx_report" value="2972093">
					</div>
					<div class="modal-body" style="text-align:center;">
						<span style="font-size:15px;">?�고?��??��??��?/span>
						<textarea id="report_desc_msg" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;margin-bottom: 14px;" placeholder="?�고?�용을 ?�?�해주?�요. ?�정 게?�?��?괴롭?�?� 무작?� ?�고�??�?�?��??�???�락�?�?기재하?� 경�??��??� ?�?�해드립니??"></textarea>
						<textarea id="reporter_phone_num" style="width: 100%;border: 1px solid #b5b5b5;height: 30px;margin-bottom: 14px;" placeholder="?�락�?(?�대???� ?�?�해주?��??��? 결과�?문자로 보내드립니??"></textarea>
					</div>
					<div class="modal-footer" style="text-align: center;padding:0px;">
						<button type="button" class="btn-link" style="width: 50%;background: #c8c9c8;color: #6e6a6a;padding: 10px 0px;text-align: center;" onclick="set_report()">?�고?��??�?�하�?/button>
					</div>
				</div>
			</div>
		</div>
		<script src="/m/js/slick.min.js"></script>
		<script>
			$('[data-scroll]').on('click', function (e) {
				e.preventDefault();

				var w = $(this).data('scroll');
				w = $('#' + w).offset().top;

				$('html, body').animate({
					scrollTop: w
				}, 300)
			});

			function set_report_contents(cont_idx){
				$("#contents_report_modal").modal("show");
			}

			function set_block_contents(){
				if(confirm("??콘?�츠�?차단하?�겠?�니까?")){
					alert('차�??�?�?�니??');
				}
			}

			$(function () {
				$('#reviewSlider').slick({
					infinite: true,
					slidesToShow: 4,
					slidesToScroll: 1,
					appendArrows: $('.slide-arrows'),
					prevArrow: '<button type="button" class="arrow prev"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
					nextArrow: '<button type="button" class="arrow next"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
					responsive: [
						{
							breakpoint: 1024,
							settings: {
								slidesToShow: 3,
							}
						},
						{
							breakpoint: 767,
							settings: {
								slidesToShow: 2,
							}
						}
					]
				});
			});

			function email_check() {
				if (!documentgetElementById("email").value.includes('@')) {
					alert('?��????�?�?� 맞게 ?�?�해주?�요.');
					document.getElementById("email").value = "";
				}
			}

			function checkPhon() {
				if (document.getElementById("tel").value.substring(0, 3) != '010' && ($('#tel').val()).substring(0, 3) != '011' && ($('#tel').val()).substring(0, 3) != '016' && ($('#tel').val()).substring(0, 3) != '017' && ($('#tel').val()).substring(0, 3) != '018' && ($('#tel').val()).substring(0, 3) != '019') {
					alert('?�?�번??첫�?�??�리는 010, 011, 016, 017, 018, 019만 가?�합니??');
					document.getElementById("tel").value = "";
					return;
				}
			}

			function load_userinfo() {
				$.ajax({
					type: "GET",
					url: "/ajax/event.userinfo.php",
					cache: false,
					dataType: "json",
					success: function (data) {
						if (data.result == "success") {
							$('input[name =name]').val(data.card_name);
							$('input[name =mobile]').val(data.card_phone);
							$('input[name =email]').val(data.card_email);
							$('input[name =addr]').val(data.card_addr);
							$('input[name =birthday]').val(data.card_birth);
							$('input[name =job]').val(data.card_company);
						}
						else {
							location.href = "http://kiam.kr/m/login.php?refer2=<?=$url_refer?>";
						}
					}
				});
			}
			function chk_sms() {
				console.log($('#tel').val().substring(0, 3));
				if ($('#tel').val() == "") {
					alert('?��?받?��??�?�번?��? ?�?�해주?�요.')
					return;
				}
				if (($('#tel').val()).substring(0, 3) != '010' && ($('#tel').val()).substring(0, 3) != '011' && ($('#tel').val()).substring(0, 3) != '016' && ($('#tel').val()).substring(0, 3) != '017' && ($('#tel').val()).substring(0, 3) != '018' && ($('#tel').val()).substring(0, 3) != '019') {
					alert('?�?�번??첫�?�??�리는 010, 011, 016, 017, 018, 019만 가?�합니??')
					return;
				}
				if (($('#tel').val()).length < 11 || ($('#tel').val()).length > 12) {
					alert('?��?받?��??�?�번?��? ?�?�해주?�요.')
					return;
				}

				$.ajax({
					type: "POST",
					url: "/ajax/event.proc.php",
					cache: false,
					dataType: "json",
					data: {
						mode: "send_sms",
						rphone: $('#tel').val()
					},
					success: function (data) {
						if (data.result == "success")
							$('#check_rnum').val("Y");
						else
							$('#check_rnum').val("");

						alert(data.msg);
					}
				})
			}

			function chk_sms1()   {
				if(!$('#rnum').val()){
					alert('?��?번?��? 받?�주세요.');
					return;
				}
				$.ajax({
					type:"POST",
					url:"/ajax/event.proc.php",
					cache: false,
					dataType:"json",
					data:{
						mode:"check_sms",
						rphone:$('#tel').val(),
						rnum : $('#rnum').val()
					},
					success:function(data){
						if(data.result == "success") {
							$('#check_rnum').val("Y");
							$('#check_sms').html('<img src="/images/check.gif"> ?��??�?�?�니??</p>');
						} else {
							$('#check_rnum').val("");
							$('#check_sms').html('');
						}
						alert(data.msg);
					}
				})
			}
		</script>
 <script language="javascript">
    function checkUrl() {
        if($('#store_address').val() == "") {
            alert('?��??�?� ?�?�해주?�요');
            return;
        }        
        $('#viewFrame').html('<iframe id="sample_frame"  name="sample_frame" src="/admin/iam_auto_make_check_.php?memid='+$('#store_address').val()+'" style="width: 400px; height: 1200px; border-width: 1px; border-style: solid; border-color: gray;"></iframe>');
    }
    
    function hideFrame() {
        $('#sample_frame').remove();
    }
    
    function sendId() {
        if($('#mem_id').val() == "") {
            alert('?��??�?� ?�?�해주?�요');
            return;
        }
        if($('#rphone').val() == "") {
            alert('?�대?�을 ?�?�해주?�요');
            return;            
        }        
        $.ajax({
				url: '/ajax/join.proc.php',
				type: 'POST',
				dataType:"json",
				data: {"mode":"send_sms2","mem_id":$('#mem_id').val(), "rphone":$('#rphone').val()},
				success: function (data) {
					 alert(data.msg);
				}
			});        
    }
 </script>
 		
</body>

</html>
