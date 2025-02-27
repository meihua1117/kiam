<?
include_once "../lib/rlatjd_fun.php";
if ($_GET['sp'])
	$readonly = "readonly";
if ($_POST['speech'])
	$sp = $_POST['speech'];
if (!$pcode)
	$pcode = "opb";
$meta_img = "";
if ($_GET['landing_idx'] != "") {
	$landing_idx = $_GET['landing_idx'];
	$sql = "UPDATE Gn_landing SET read_cnt = read_cnt+1 WHERE landing_idx='{$landing_idx}'";
	mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

	$sql = "SELECT * FROM Gn_landing WHERE landing_idx='{$landing_idx}'";
	$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
	$data = mysqli_fetch_array($result);

	if ($data['status_yn'] == "N") {
		echo '<meta http-equiv="content-type" content="text/html; charset=euc-kr"/><Script>alert("사용이 종료된 랜딩입니다.");</script>';
		exit;
	}

	$m_id = $row['m_id'];
	$event_idx = $row['event_idx'];
	$pcode = $data['pcode'];
	$page_title = $data['title'];
	if ($data['img_thumb'] != "") {
		$meta_img = $data['img_thumb'];
	} else {
		// 기존에디터로 편집된 랜딩페이지를 위해서 추가됨
		if (strpos($data['content'], '<img src="upload/') !== false) {
			$meta_img1 = explode('<img src="upload/', $data['content']);
			$meta_img2 = explode('"', trim($meta_img1[1]));
			$meta_img = "/upload/" . trim($meta_img2[0]);
		} else if (strpos($data['content'], '<img src="/naver_editor/upload_editor/') !== false) {
			$meta_img1 = explode('<img src="/naver_editor/upload_editor/', $data['content']);
			$meta_img2 = explode('"', trim($meta_img1[1]));
			$meta_img = "/naver_editor/upload_editor/" . trim($meta_img2[0]);
		}
	}
} else {
	$sql = "UPDATE Gn_event SET read_cnt = read_cnt+1 WHERE pcode='{$pcode}'";
	mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
	$page_title = "";
}

$sql = "SELECT * FROM Gn_event WHERE pcode='{$pcode}' order by event_idx desc";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$event_data = $row = mysqli_fetch_array($result);
$m_id = $row['m_id'];
$event_idx = $row['event_idx'];
$step_idx1 = $row['sms_idx1'];
$step_idx2 = $row['sms_idx2'];
$step_idx3 = $row['sms_idx3'];
$stop_event_idx = $row['stop_event_idx'];
if ($page_title == "")
	$page_title = $row['event_title'];
// 온리원 나비서 네이버샵 홍보 자동링크
if ($landing_idx == "783") {
	$direct_url = "https://smartstore.naver.com/misohealth/products/4899608486";
	echo ("<script>location.href = '$direct_url';</script>");
}

//아이엠 프로필 상단 착한기업소개 	자동링크
if ($landing_idx == "787") {
	$direct_url = "https://smartstore.naver.com/misohealth/products/2878753669";
	echo ("<script>location.href = '$direct_url';</script>");
}

//	김세규 대표 아이엠 자동 링크
if ($landing_idx == "790") {
	$direct_url = "https://kiam.kr/?EtYTwm9OZn";
	echo ("<script>location.href = '$direct_url';</script>");
}

//아이엠 공유정보더보기 자동링크
if ($landing_idx == "788") {
	$direct_url = "https://oog.kiam.kr/pages/page_3302.php";
	echo ("<script>location.href = '$direct_url';</script>");
}
$url_refer = str_replace("&", "###", $_SERVER['REQUEST_URI']);
?>

<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0" />
	<meta property="og:title" content="<?= $page_title ?>">
	<meta property="og:image" content="<?= $meta_img == '' ? 'http://www.kiam.kr/images/event_apply.png' : $meta_img; ?>">
	<link rel="stylesheet" href="style.css" type="text/css">
	<? if ($_GET['landing_idx'] = "") {	?>
		<link rel="stylesheet" href="global_sub.css" type="text/css" />
	<? } ?>

	<link rel="stylesheet" href="css/slick.css" type="text/css">
	<link rel="stylesheet" href="../css/responsive.css" type="text/css">
	<link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
	<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/ckeditor-styles.css">
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>
	<script language="javascript" type="text/javascript" src="jquery-3.1.1.min.js"></script>
	<script language="javascript" type="text/javascript" src="common.js"></script>
	<script language="javascript" type="text/javascript" src="jquery.rotate.js"></script>
	<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
	<style>
		.div_content {
			overflow-y: hidden;
		}

		.list_table td {
			text-align: center;
			height: 40px;
			border-bottom: 1px solid #CCC;
		}

		.list_table td li {
			float: left;
			margin-right: 5px;
		}

		.list_table .d_font {
			font-size: 16px;
			font-weight: 600
		}

		.list_table .x_font {
			vertical-align: bottom;
			padding-bottom: 5px;
			color: #666;
		}

		.list_table .xt_font {
			position: absolute;
			margin-top: -35px;
			font-size: 16px;
			font-weight: 600;
			margin-left: 50px;
			color: #000;
		}

		.list_table input[type=text] {
			width: 90%;
			height: 30px;
		}

		.list_table tr:first-child td {
			height: 40px;
			background-color: #f4f4f4;
			border-bottom: 1px solid #000;
			border-top: 1px solid #000;
		}

		.list_table tr:first-child td {
			height: 40px;
			/* background-color: #f4f4f4; */
			/* border-bottom: 1px solid #000; */
			border-top: 1px solid #CCC;
		}
	</style>
	<script language="javascript">
		$(document).ready(function() {
			var mode = get('show_ori');
			if (mode == "Y") {
				$("#iam_mem").prop('checked', true);
				if ($("#no_mem").prop('checked')) {
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
				} else {
					$("#iam_txt").show();
					$("#no_txt").hide();
					$("#div_account").hide();
					$('input[name =join_yn]').val('N');
					load_userinfo();
				}
			}
			$(window.parent.document).find('iframe[src=\'' + location.href + '\']').css('height', $(document).height());
		})

		function get(name) {
			if (name = (new RegExp('[?&]' + encodeURIComponent(name) + '=([^&]*)')).exec(location.search))
				return decodeURIComponent(name[1]);
		}

		function gotoLogin(id, pass, code) {
			$.ajax({
				type: "POST",
				url: "/admin/ajax/login_iamuser.php",
				data: {
					one_id: id,
					mem_pass: pass,
					mem_code: code
				},
				success: function() {
					location.href = "/?cur_win=request_list";
				},
				error: function() {
					alert('초기화 실패');
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
				success: function(response) {
					if (response == "id=dup") {
						document.getElementById('id_reg').value = 0;
						document.getElementById('id_text').innerHTML = '<span class="jftxt_r">중복된  아이디 입니다.</span>';
					} else if (response == "id=nouse") {
						document.getElementById('id_reg').value = 0;
						document.getElementById('id_text').innerHTML = '<span class="jftxt_r">사용할 수 없는 아이디 입니다.</span>';
					} else if (response == "id=use") {
						document.getElementById('id_reg').value = 1;
						document.getElementById('id_text').innerHTML = '<span class="jftxt_b">사용가능합니다.</span>';
					} else if (response == "email=dup") {
						document.getElementById('email_reg').value = 0;
						document.getElementById('email_text').innerHTML = '<span class="jftxt_r">중복된 이메일 입니다.</span>';
					} else if (response == "email=reuse") {
						document.getElementById('email_reg').value = 0;
						document.getElementById('email_text').innerHTML = '<span class="mftxt_r">사용중인 이메일 입니다.</span>';
					} else if (response == "email=nouse") {
						document.getElementById('email_reg').value = 0;
						document.getElementById('email_text').innerHTML = '<span class="mftxt_r">사용할 수 없는 이메일 입니다.</span>';
					} else if (response == "email=use") {
						document.getElementById('email_reg').value = 1;
						document.getElementById('email_text').innerHTML = '<span class="mftxt_b">사용가능합니다.</span>';
					} else if (response == "nick=dup") {
						document.getElementById('nick_reg').value = 0;
						document.getElementById('nick_text').innerHTML = '<span class="jftxt_r">중복된 닉네임 입니다.</span>';
					} else if (response == "nick=nouse") {
						document.getElementById('nick_reg').value = 0;
						document.getElementById('nick_text').innerHTML = '<span class="jftxt_r">사용할 수 없는 닉네임 입니다.</span>';
					} else if (response == "nick=use") {
						document.getElementById('nick_reg').value = 1;
						document.getElementById('nick_text').innerHTML = '<span class="jftxt_b">사용가능합니다.</span>';
					} else if (response == "id2=dup") {
						document.getElementById('id_reg').value = 0;
						document.getElementById('id2_text').innerHTML = '<span class="jftxt_r">중복된 아이디 입니다.</span>';
					} else if (response == "id2=nouse") {
						document.getElementById('id_reg').value = 0;
						document.getElementById('id2_text').innerHTML = '<span class="jftxt_r">사용할 수 없는 아이디 입니다.</span>';
					} else if (response == "id2=use") {
						document.getElementById('id_reg').value = 1;
						document.getElementById('id2_text').innerHTML = '<span class="jftxt_b">사용가능합니다.</span>';
					} else {
						document.getElementById('id_reg').value = 0;
						document.getElementById('id_text').innerHTML = '<span class="jftxt_r">사용할 수 없는 아이디 입니다.</span>';
						document.getElementById('id_text').innerHTML = response;
						document.getElementById('email_reg').value = 0;
						document.getElementById('email_text').innerHTML = '<span class="mftxt_r">사용할 수 없는 이메일 입니다.</span>';
					}
				}
			});
		}

		function hp_certify_send() {
			var pattern = /^01[0-9][-]{0,1}[0-9]{3,4}[-]{0,1}[0-9]{4}$/;
			var hp = document.getElementById('id1').value + document.getElementById('id2').value + document.getElementById('id3').value;
			var reg_id = document.getElementById('id_reg').value;

			if (reg_id != 1) {
				alert("가입할 수 있는 휴대폰 번호를 입력해주세요.");
				document.getElementById('id1').focus();
				return false;
			}
			if (!pattern.test(hp)) {
				alert("휴대폰 번호를 형식에 맞게 입력해 주세요. '-' 를 넣었는지 확인하세요.");
				document.getElementById('id1').focus();
				return false;
			}
			$.ajax({
				url: '/opb/proc/sms_approval.html',
				type: 'POST',
				data: 'hp=' + hp + '&val=0',
				success: function(response) {
					if (response == "000 " || response == "000") {
						document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">인증번호를 전송하였습니다.</span>';

					} else {
						document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">인증번호 발송에 실패했습니다. [' + response + ']</span>';
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
				document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">인증번호를 입력해주세요.</span>';
			}
			document.getElementById('approval_reg').value = 0;
			$.ajax({
				url: '/opb/proc/sms_check.html',
				type: 'POST',
				data: 'val=' + sms_approval + '&vals=1',
				success: function(response) {
					if (response == "1") {
						document.getElementById('approval_reg').value = 1;
						document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">인증번호가 맞습니다.</span>';
					} else {
						document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">인증번호가 맞지 않습니다.</span>';
					}
				}
			});
		}

		function hp_certify_focus() {
			var reg_id = document.getElementById('id_reg').value;
			if (reg_id != 1) {
				document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">사용할 수 있는 휴대폰을 입력해주세요.</span>';
			} else {
				document.getElementById('approval_text').innerHTML = '<span class="jftxt_b">인증번호받기를 눌러주세요.</span>';
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
function alertOn($msg)
{
	echo "<script>alert('" . $msg . "');</script>";
}
if ($mode && $chk && $over == "yes") {
	//referer_check();
	if ($chk != $_SESSION['join'])
		alert('중복가입이 방지 되었습니다.');
	$ipcheck = $_SERVER['REMOTE_ADDR'];
	$sql = "INSERT INTO Gn_event_request SET event_idx='{$event_idx}',
											event_code='{$event_code}',
											name='{$name}',
											mobile='{$mobile}',
											email='{$email}',
											job='{$job}',
											other='{$other}',
											pcode='{$pcode}',
											sp='{$sp}',
											ip_addr='{$ipcheck}',
											regdate=now(),
											step_end_time=now()";
	if ($landing_idx != '')
		$sql .= " ,landing_idx='{$landing_idx}'";
	mysqli_query($self_con, $sql);
	alert("접수 되었습니다. 감사합니다.", "/opb/index.php");
} elseif ($mode && $chk) {
}
if (!$mode) $mode = "JOIN";
$_SESSION['join'] = md5(time());
function alerting($msg)
{
	echo "<script>alert('" . $msg . "');</script>";
}
if ($_POST['mode'] == "speech") {
	if ($_POST['name'] == "") {
		alerting('신청자명을 입력해주세요');
	} else if ($_POST['mobile'] == "") {
		alerting('전화번호를 입력해주세요. ');
	} else if ($_POST['sp'] == "") {
		alerting('신청강좌를 입력해주세요.');
	} else {
		$ipcheck = $_SERVER['REMOTE_ADDR'];
		extract($_POST);

		$sql = "SELECT * FROM Gn_event WHERE pcode='{$pcode}'";
		$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
		$event_data = $row = mysqli_fetch_array($result);
		$mem_id = $event_data['m_id'];
		$send_num = $event_data['mobile'];

		if (strstr($event_data['event_info'], "sms") && $join_yn == 'Y') {
			$sql = "SELECT * FROM Gn_Event_Check_Sms WHERE mem_phone='{$mobile}' order by idx desc";
			$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
			$check_data = $row = mysqli_fetch_array($result);
			if ($check_data['secret_key'] != $rnum) {
				echo "<script>alert('인증번호를 확인해주세요.');history.go(-1);</script>";
				exit;
			}
		}

		if ($id) {
			$m_id1 = $id;
		} else {
			$m_id1 = $_SESSION['one_member_id'];
		}
		if ($event_data['reserv_type']) {
			$file1 = $_FILES['ai_file1']['tmp_name'];
			$file2 = $_FILES['ai_file2']['tmp_name'];
			$file3 = $_FILES['ai_file3']['tmp_name'];
			$fquery = "";
			if ($file1) {
				$file_arr = explode(".", $_FILES['ai_file1']['name']);
				$tmp_file_arr = explode("/", $file1);
				$file_name = "ai_ms_event" . date("Ymds") . "." . $file_arr[count($file_arr) - 1];
				$upload_file = "../upload/" . $file_name;
				if (move_uploaded_file($_FILES['ai_file1']['tmp_name'], $upload_file)) {
					uploadFTP($upload_file);
					$fquery .= " ,file1='/upload/{$file_name}' ";
				}
			}
			if ($file2) {
				$file_arr = explode(".", $_FILES['ai_file2']['name']);
				$tmp_file_arr = explode("/", $file2);
				$file_name = "ai_ms_event" . date("Ymds") . "." . $file_arr[count($file_arr) - 1];
				$upload_file = "../upload/" . $file_name;
				if (move_uploaded_file($_FILES['ai_file2']['tmp_name'], $upload_file)) {
					uploadFTP($upload_file);
					$fquery .= " ,file2='/upload/{$file_name}' ";
				}
			}
			if ($file3) {
				$file_arr = explode(".", $_FILES['ai_file3']['name']);
				$tmp_file_arr = explode("/", $file3);
				$file_name = "ai_ms_event" . date("Ymds") . "." . $file_arr[count($file_arr) - 1];
				$upload_file = "../upload/" . $file_name;
				if (move_uploaded_file($_FILES['ai_file3']['tmp_name'], $upload_file)) {
					uploadFTP($upload_file);
					$fquery .= " ,file3='/upload/{$file_name}' ";
				}
			}
			$sql = "INSERT INTO Gn_aievent_request SET event_idx='{$event_idx}',
												event_code='{$event_code}',
												m_id='{$m_id}',
												req_id='{$m_id1}',
												name='{$name}',
												sex='{$sex}',
												addr='{$addr}',
												birthday='{$birthday}',
												consult_date='{$consult_date}',
												join_yn='{$join_yn}',
												other='{$other}',
												mobile='{$mobile}',
												email='{$email}',
												job='{$job}',
												pcode='{$pcode}',
												sp='{$sp}',
												ip_addr='{$ipcheck}',
												regdate=now(),
												step_end_time=now(),
												target='4'
												$fquery";
		} else {
			$sql = "INSERT INTO Gn_event_request SET event_idx='{$event_idx}',
												event_code='{$event_code}',
												m_id='{$m_id}',
												req_id='{$m_id1}',
												name='{$name}',
												sex='{$sex}',
												addr='{$addr}',
												birthday='{$birthday}',
												consult_date='{$consult_date}',
												join_yn='{$join_yn}',
												other='{$other}',
												mobile='{$mobile}',
												email='{$email}',
												job='{$job}',
												pcode='{$pcode}',
												sp='{$sp}',
												ip_addr='{$ipcheck}',
												regdate=now(),
												step_end_time=now(),
												target='4'";
		}
		if ($landing_idx != '')
			$sql .= " ,landing_idx='{$landing_idx}'";
		if ($rnum != '')
			$sql .= " ,rnum='{$rnum}'";
		$res1 = mysqli_query($self_con, $sql);
		$request_idx = mysqli_insert_id($self_con);
		$recv_num = $mobile;
		$recv_num = str_replace("-", "", $recv_num);

		if ($mem_id == "")
			$mem_id = $m_id;
		if ($mem_id != "") {
			// 접수내용 접수자에게 전송
			$stitle = "이벤트 신청 내역";
			$scontent = "신청해주셔서 감사합니다.\n\n
				$name 님!\n
				신청하신 내용이 잘 접수되었습니다.\n
				이후 필요한 안내나 정보로 연락드리겠습니다.\n
				늘 행복하세요!! \n";

			sendmms(1, $mem_id, $send_num, $recv_num, "", $stitle, $scontent, "", "", "", "Y");

			$sql = "SELECT sms_idx FROM Gn_event_sms_info WHERE sms_idx='{$step_idx1}' or sms_idx='{$step_idx2}' or sms_idx='{$step_idx3}'";
			$lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
			while ($lrow = mysqli_fetch_array($lresult)) {
				$sms_idx = $lrow['sms_idx'];
				//알람등록
				$sql = "SELECT * FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}'";
				$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
				$step_end_time = date("Y-m-d H:i:s");
				while ($row = mysqli_fetch_array($result)) {
					// 시간 확인
					$send_day = $row['send_day'];
					$send_time = $row['send_time'];

					if ($send_time == "") $send_time = "09:30";
					if ($send_time == "00:00") $send_time = "09:30";
					if ($send_day == 0) {
						$reservation = "";
						$jpg = $jpg1 = $jpg2 = '';
						if ($row['image'])
							$jpg = "https://kiam.kr/adjunct/mms/thum/" . $row['image'];
						if ($row['image1'])
							$jpg1 = "https://kiam.kr/adjunct/mms/thum/" . $row['image1'];
						if ($row['image2'])
							$jpg2 = "https://kiam.kr/adjunct/mms/thum/" . $row['image2'];
						//ai 작업하면서 수정할것	
						sendmms(4, $mem_id, $send_num, $recv_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, "Y", $row['sms_idx'], $row['sms_detail_idx'], $request_idx, "", $row['send_deny']);

						$query = "INSERT INTO Gn_MMS_Agree SET mem_id='{$mem_id}',
															send_num='{$send_num}',
															recv_num='{$recv_num}',
															content='{$row['content']}',
															title='{$row['title']}',
															jpg='{$jpg}',
															jpg1='',
															jpg2='',
															reg_date=NOW(),
															reservation='{$reservation}',
															sms_idx='{$row['sms_idx']}',
															sms_detail_idx='{$row['sms_detail_idx']}',
															request_idx='{$request_idx}',
															up_date=NOW()";
						mysqli_query($self_con, $query) or die(mysqli_error($self_con));
					} else {
						$reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));
						if ($step_end_time < $reservation)
							$step_end_time = $reservation;
					}
				}
				$sql = "UPDATE Gn_event_request SET step_end_time='{$step_end_time}' WHERE request_idx = {$request_idx}";
				mysqli_query($self_con, $sql);
			}
			//중단예약처리를 진행한다.
			if ($stop_event_idx != 0) {
				$query = "SELECT sms_idx1, sms_idx2, sms_idx3 FROM Gn_event WHERE evne_idx='{$stop_event_idx}'";
				$stop_res = mysqli_query($self_con, $query);
				$stop_row = mysqli_fetch_array($stop_res);

				$query = "SELECT count(*) FROM Gn_MMS WHERE recv_num='{$recv_num}' and reservation is not null and result=1 and (sms_idx='{$stop_row[0]}' or sms_idx='{$stop_row[1]}' or sms_idx='{$stop_row[2]}')";
				$cnt_res = mysqli_query($self_con, $query);
				$cnt_row = mysqli_fetch_array($cnt_res);
				if ($cnt_row[0] > 0) {
					//중단정보테이블에 해당 값을 저장한다.
					$query = "INSERT INTO Gn_event_sms_step_info SET event_idx='{$stop_event_idx}', mobile='{$recv_num}'";
					mysqli_query($self_con, $query);

					//문자테이블에서 해당 예약을 삭제한다.
					$query = "delete FROM Gn_MMS WHERE recv_num='{$recv_num}' and reservation is not null and result=1 and (sms_idx='{$stop_row[0]}' or sms_idx='{$stop_row[1]}' or sms_idx='{$stop_row[2]}')";
					mysqli_query($self_con, $query);
				}
			}

			if ($join_yn == 'Y') {
				//회원가입
				$sql3 = "SELECT sub_domain FROM Gn_Service WHERE sub_domain like '%kiam.kr' And mem_id='{$m_id}'";
				$res = mysqli_query($self_con, $sql3);
				$row1 = mysqli_fetch_array($res);
				if ($row1['sub_domain']) {
					$parse = parse_url($row1['sub_domain']);
					$sites = explode(".", $parse['host']);
					$site = $sites[0];
				} else {
					$sql3 = "SELECT site FROM Gn_Member WHERE mem_id='{$m_id}'";
					$res = mysqli_query($self_con, $sql3);
					$row1 = mysqli_fetch_array($res);
					$site = $row1['site'];
				}
				$sql3_iam = "SELECT sub_domain FROM Gn_Service_Iam WHERE sub_domain like '%kiam.kr' And mem_id='{$m_id}'";
				$res = mysqli_query($self_con, $sql3_iam);
				$row1_iam = mysqli_fetch_array($res);
				if ($row1_iam['sub_domain']) {
					$parse = parse_url($row1_iam['sub_domain']);
					$sites = explode(".", $parse['host']);
					$site_iam = $sites[0];
				} else {
					$sql3_iam = "SELECT site FROM Gn_Member WHERE mem_id='{$m_id}'";
					$res = mysqli_query($self_con, $sql3_iam);
					$row1_iam = mysqli_fetch_array($res);
					$site_iam = $row1_iam['site'];
				}
				$userid = $id;
				$passwd = substr($mobile, -4);

				$query = "INSERT INTO Gn_Member SET mem_id='$userid',
													mem_leb='22',
													web_pwd=md5('$passwd'),
													mem_pass=md5('$passwd'),
													mem_name='{$name}',
													mem_nick='{$name}',
													mem_type='A',
													recommend_id = '{$m_id}',
													site='{$site}',
													site_iam='{$site_iam}',
													mem_phone='{$mobile}',
													zy='{$job}',
													first_regist=now() ,
													mem_check=now(),
													mem_add1='{$addr}',
													mem_email='{$email}',
													mem_sex='{$sex}',
													join_ip='{$_SERVER['REMOTE_ADDR']}'";
				mysqli_query($self_con, $query);
				$mem_code = mysqli_insert_id($self_con);
			}
		}
		if (!$res1) {
			alerting('신청접수에 실패했습니다.');
		} else {
			echo "<script>var msg = ''; ";
			echo "msg+='신청해주셔서 감사합니다.\\n\\n';";
			echo "msg+='$name 님!\\n';";
			echo "msg+='신청하신 내용이 잘 접수되었습니다.\\n';";
			echo "msg+='이후 필요한 안내나 정보로 연락드리겠습니다.\\n';";
			echo "msg+='\\n';";
			echo "msg+='늘 행복하세요!!\\n';";

			if ($join_yn == 'Y') {
				echo "alert(msg);gotoLogin('$userid','$passwd','$mem_code');</script>";
			} else {
				echo "alert(msg);</script>";
			}
			unset($_POST);
		}
	}
}
?>

<body class="body-event">
	<style>
		.mftxt {
			font-size: 14pt;
			color: #4e6e7e;
			height: 30px;
			padding-top: 3px;
		}

		.mftxt_r {
			font-weight: bold;
			font-size: 14pt;
			color: #bb5f5f;
			height: 30px;
			padding-top: 10px;
			padding-left: 0px;
		}

		.mftxt_b {
			font-weight: bold;
			font-size: 14pt;
			color: #4772a2;
			height: 30px;
			padding-top: 10px;
			padding-left: 0px;
		}

		.content img {
			max-width: 100%
		}

		.m_div input[type=radio] {
			height: 15px;
			width: 15px;
		}

		.main_info {
			color: blue;
			font-size: 14px;
		}
	</style>
	<div class="content ck-content" style="min-width: 100%;">
		<?= str_replace('img src="upload/', 'img src="/upload/', $data['content']); ?>
	</div>
	<? if ($data['move_url'] != "") { ?>
		<iframe width="100%" height="618" src="<?= $data['move_url']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
			allowfullscreen></iframe>
	<? } ?>
	<script>
		function checkForm() {
			if ($('#agree').is(":checked") == false) {
				alert('개인정보이용동의 해주세요.')
				return false;
			}
			if ($("#no_mem").prop('checked') && $("#id_html").text() == '') {
				alert('아이디 중복확인을 해주세요.')
				return false;
			}
		}
		$(function() {
			$('#writeBtn').on("click", function() {
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

			$('#join_yn').on("change", function() {
				if (this.checked) {
					$("#div_account").show();
					$("#phone_verify").show();
				} else {
					$("#div_account").hide();
					$("#phone_verify").hide();
				}

			});

			$("input[name=mem_type]").on("change", function() {
				if ($("#no_mem").prop('checked')) {
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
				} else {
					$("#iam_txt").show();
					$("#no_txt").hide();
					$("#div_account").hide();
					$('input[name =join_yn]').val('N');
					load_userinfo();
				}
			});
		});

		//아이디 중복확인
		function id_check(frm, frm_str) {
			if (!frm.id.value) {
				frm.id.focus();
				return;
			}
			if (frm.id.value.length < 4) {
				alert('아이디는 4자 이상 사용이 가능합니다.')
				frm.id.focus();
				return;

			}
			var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
			if (!pattern.test(frm.id.value)) {
				document.getElementById('id_html').innerHTML = '올바른 회원아이디 형식이 아닙니다.';
				frm.id_status.value = ''
				frm.id.value = ''
				frm.id.focus();
				return
			} else
				document.getElementById('id_html').innerHTML = '';
			$.ajax({
				type: "POST",
				url: "/ajax/ajax.php",
				data: {
					id_che: frm.id.value,
					id_che_form: frm_str
				},
				success: function(data) {
					$("#ajax_div").html(data)
				}
			})
		}

		//비밀번호 재확인
		function pwd_cfm_check(i) {
			if ($('#join_yn').prop("checked")) {
				if (!document.getElementsByName('pwd_cfm')[i].value) return;
				if (document.getElementsByName('pwd_cfm')[i].value != document.getElementsByName('pwd')[i].value) {
					$($(".pwd_cfm_html")[i]).html("두번 입력한 비밀번호가 틀립니다.");
					document.getElementsByName('pwd_status')[i].value = '';
					document.getElementsByName('pwd_cfm')[i].focus();
					return
				} else {
					document.getElementsByName('pwd_status')[i].value = 'ok';
					$($(".pwd_cfm_html")[i]).html("");
				}
			}


		}
	</script>
	<div id="table" class="form-body">
		<? if ($data['file']) { ?>
			<div style="text-align:center">
				<a href="<?= $data['file']; ?>" class="button-download" style="font-weight:bold;font-size:16px;border-radius: 200px;background-color: #418BCA;color:#FFFFFF;padding: 16px 28px;" target="_blank">첨부파일 다운로드</a>
			</div>
		<? } ?>
		<?
		if ($landing_idx != "" && $data['lecture_yn'] == "Y") { ?>
			<div class="big_sub">
				<div class="m_div">
					<? include "mypage_left_menu.php"; ?>
					<div class="m_body">
						<div style="text-align:center;background:#4b657c">
							<p style="font-size:2.125rem;font-weight:bold;font-family:KoPubDotum; color: #FFFFFF;">온리원플랫폼 전국 설명회 및 교육 일정</p>
							<a href="https://blog.naver.com/onlyonemj18/222646448643" style="font-weight:bold;font-size:25px;color:yellow" target="_blank">[전국지역센터와 사업단 보기] </a>
						</div>
						<form name="pay_form" action="" method="post" class="my_pay">
							<input type="hidden" name="lecture_id" value="<?= $row['lecture_id']; ?>" />
							<input type="hidden" name="event_idx" value="<?= $event_idx; ?>" />
							<input type="hidden" name="pcode" value="<?= $pcode; ?>" />
							<input type="hidden" name="sp" value="<?= $sp; ?>" />
							<input type="hidden" name="landing_idx" value="<?= $landing_idx; ?>" />
							<input type="hidden" name="page" value="<?= $page ?>" />
							<input type="hidden" name="page2" value="<?= $page2 ?>" />
							<div class="a1" style="margin-top:15px; margin-bottom:15px;color: #FFFFFF">
								<li style="float:left;">강연/교육과정 입력결과보기</li>
								<li style="float:right;"></li>
								<p style="clear:both"></p>
							</div>
							<div>
								<div class="p1 table-utils clearfix">
									<div class="left">
										<label class="check-item">
											<input type="radio" name="category" value="" checked>
											전체
										</label>
										<label class="check-item">
											<input type="radio" name="category" value="강연" <?= $_REQUEST['category'] == "강연" ? "checked" : "" ?>>
											강연
										</label>
										<label class="check-item">
											<input type="radio" name="category" value="교육" <?= $_REQUEST['category'] == "교육" ? "checked" : "" ?>>
											교육
										</label>
										<label class="check-item">
											<input type="radio" name="category" value="영상" <?= $_REQUEST['category'] == "영상" ? "checked" : "" ?>>
											영상
										</label>
										<input type="text" name="search_text" placeholder="" id="search_text" value="<?= $_REQUEST['search_text'] ?>" />
										<a href="javascript:void(0)" id="searchC" onclick="pay_form.submit()"><img src="/images/sub_mypage_11.jpg" /></a>
									</div>
									<div class="right">
										<label class="check-item"><input type="radio" name="end_date" value="">전체</label>
										<label class="check-item"><input type="radio" name="end_date" value="Y" <?= ($_REQUEST['end_date'] == "Y" || $_REQUEST['end_date'] == "") ? "checked" : "" ?>>진행완료</label>
										<label class="check-item"><input type="radio" name="end_date" value="N" <?= $_REQUEST['end_date'] == "N" ? "checked" : "" ?>>진행중</label>
									</div>
								</div>

								<div style="text-align:center; color:#0075FF;font-size:15px;"> <b>※강의신청방법 : 참여하고 싶은 강의제목을 확인한 후에 참여신청하기 버튼을 클릭하셔서
										신청합니다</b></style>
								</div>

								<script>
									$(function() {
										$('input[name=category]').bind("click", function() {
											$.ajax({
												type: "POST",
												url: "/event/event.ajax.html",
												data: {
													mode: "review_save",
													category: $('input[name=category]:checked').val(),
													end_date: $('input[name=end_date]:checked').val(),
													search_text: $('#search_text').val(),
													landing_idx: '<?= $_REQUEST['landing_idx']; ?>'
												},
												success: function(data) {
													$('#lecture_list').html(data);
													$('.paging').on("click", function() {
														ajaxView($(this).data("page"));
													});
												}
											});
										});
										$('input[name=end_date]').bind("click", function() {
											$.ajax({
												type: "POST",
												url: "/event/event.ajax.html",
												data: {
													mode: "review_save",
													category: $('input[name=category]:checked').val(),
													end_date: $('input[name=end_date]:checked').val(),
													search_text: $('#search_text').val(),
													landing_idx: '<?= $_REQUEST['landing_idx']; ?>'
												},
												success: function(data) {
													$('#lecture_list').html(data);
													$('.paging').on("click", function() {
														ajaxView($(this).data("page"));
													});
												}
											});
										});
										$('#searchC').bind("click", function() {
											$.ajax({
												type: "POST",
												url: "/event/event.ajax.html",
												data: {
													mode: "review_save",
													category: $('input[name=category]:checked').val(),
													end_date: $('input[name=end_date]:checked').val(),
													search_text: $('#search_text').val(),
													landing_idx: '<?= $_REQUEST['landing_idx']; ?>'
												},
												success: function(data) {
													$('#lecture_list').html(data);
													$('.paging').on("click", function() {
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
												landing_idx: '<?= $_REQUEST['landing_idx']; ?>',
												page: page,
											},
											success: function(data) {
												$('#lecture_list').html(data);
												$('.paging').on("click", function() {
													ajaxView($(this).data("page"));
												});
											}
										});
									}
								</script>
								<?
								//홍보렙인가를 체크한다		
								$member_id = $data['m_id'];
								$sql = "SELECT mem_leb FROM Gn_Member WHERE mem_id='$member_id'";
								$res = mysqli_query($self_con, $sql);
								$memrow = mysqli_fetch_array($res);
								?>
								<div class="table-wrap">
									<table class="list_table" border="0" cellspacing="0" cellpadding="0">
										<thead>
											<tr>
												<th class="no">No</th>
												<th class="category">분야</th>
												<th class="date">일정/기간</th>
												<th class="day">요일</th>
												<th class="time">강의<br>시간</th>
												<th class="title">강의제목</a></th>
												<th class="teacher">강사이름</th>
												<th class="place">지역/장소<br>(클릭)</th>
												<th class="object">참여대상</th>
												<th class="limit">정원</th>
												<th class="price">비용</th>
												<? if ($memrow['mem_leb'] != "60") { ?>
													<th class="request">신청</th>
												<? } ?>
												<!--
												<th>수정/삭제</th>
												-->
											</tr>
										</thead>
										<tbody id="lecture_list">
											<?

											if (isset($_REQUEST['end_date'])) {
											} else {
												$_REQUEST['end_date'] = "N";
											}

											$sql_serch = " status='Y'";

											if ($data['m_id'] != "obmms02" && $memrow['mem_leb'] != "60") {
												$sql_serch .= " and mem_id ='{$data['m_id']}'";
											}

											//if($_REQUEST[category]=="")
											//    $sql_serch.=" and category ='강연'";

											$photo_sql = $sql_serch;
											if ($_REQUEST['category']) {
												$sql_serch .= " and category ='{$category}'";
											}
											$now = date("Y-m-d");
											if ($_REQUEST['end_date'] == "Y") {
												$sql_serch .= " and end_date < '$now'";
											}
											if ($_REQUEST['end_date'] == "N") {
												$sql_serch .= " and end_date >= '$now'";
											}

											if ($_REQUEST['search_text']) {
												$search_text = $_REQUEST['search_text'];
												$sql_serch .= " and (lecture_info like '%{$search_text}%' or area like '%{$search_text}%'or instructor like '%{$search_text}%')";
											}

											$sql = "SELECT count(lecture_id) as cnt FROM Gn_lecture WHERE $sql_serch ";
											$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
											$row = mysqli_fetch_array($result);
											$intRowCount = $row['cnt'];
											if ($intRowCount) {
												if (!$_POST['lno'])
													$intPageSize = 30;
												else
													$intPageSize = $_POST['lno'];
												if ($_POST['page']) {
													$page = (int)$_POST['page'];
													$sort_no = $intRowCount - ($intPageSize * $page - $intPageSize);
												} else {
													$page = 1;
													$sort_no = $intRowCount;
												}
												if ($_POST['page2'])
													$page2 = (int)$_POST['page2'];
												else
													$page2 = 1;

												$int = ($page - 1) * $intPageSize;

												if ($_REQUEST['order_status'])
													$order_status = $_REQUEST['order_status'];
												else
													$order_status = "asc";

												if ($_REQUEST['order_name'])
													$order_name = $_REQUEST['order_name'];
												else
													$order_name = "start_date";

												$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
												//$sql="SELECT * FROM Gn_lecture WHERE $sql_serch order by $order_name $order_status limit $int,$intPageSize";
												$sql = "SELECT * FROM Gn_lecture WHERE $sql_serch order by start_date desc limit $int,$intPageSize";
												$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
												while ($row = mysqli_fetch_array($result)) {
													$sql_num = "SELECT * FROM Gn_event WHERE m_id='$row[mem_id]' and event_idx='$row[event_idx]' ";
													$resul_num = mysqli_query($self_con, $sql_num);
													$crow = mysqli_fetch_array($resul_num);
											?>
													<tr>
														<td>
															<?= $sort_no ?>
														</td>
														<td>
															<?= $row['category'] ?>
														</td>
														<td>
															<?= $row['start_date'] ?>~<BR>
															<?= $row['end_date'] ?>
														</td>
														<td>
															<?= $row['lecture_day'] ?>
														</td>
														<td>
															<?= $row['lecture_start_time'] ?>~<BR>
															<?= $row['lecture_end_time'] ?>
														</td>

														<td>
															<? if ($row['lecture_url']) { ?>
																<a href="<?= $row['lecture_url']; ?>" target="_blank">
																<? } ?>
																<div style="font-size:13px; text-align:left; font-weight:bold;color:blue;">
																	<?= $row['lecture_info'] ?>
																</div>
																</a>
														</td>
														<!-- <td>
														<? if ($row['lecture_url']) { ?>
														<a href="<?= $row['lecture_url']; ?>" target="_blank">
															<? } ?>
															<?= $row['lecture_info'] ?></a></td> 
														-->
														<td>
															<?= $row['instructor'] ?>
														</td>
														<td>
															<div style="width:155px;overflow-y:hidden;height:45px;cursor:pointer;" class="area_view">
																<?= $row['area'] ?>
															</div>
														</td>
														<td>
															<div style="text-align:left; color:#0075FF;">
																<?= $row['target'] ?>
															</div>
														</td>
														<td>
															<?= $row['max_num'] ?>명</td>
														<td>
															<?= $row['fee'] == 0 ? "무료" : $row['fee'] . "원" ?>
														</td>
														<? if ($memrow['mem_leb'] != "60") { ?>
															<td>
																<input type="button" value="신청하기" class="button" onclick="viewEvent('<?= $crow['short_url'] ?>')">
															</td>
														<? } ?>
														<!--
														<td>
															<a href='mypage_lecture_write.php?lecture_id=<?= $row['lecture_id']; ?>'>수정</a>/<a href="javascript:;;" onclick="removeRow('<?= $row['lecture_id']; ?>')">삭제</a>
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
														page_ajax($page, $page2, $intPageCount, "pay_form");
														?>
													</td>
												</tr>
											<?
											} else {
											?>
												<tr>
													<td colspan="14">
														검색된 내용이 없습니다.
													</td>
												</tr>
											<?
											}
											?>
										</tbody>
									</table>
									<!--
									<input type="button" value="랜딩 페이지 삭제" class="button">
									-->
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
	</div>
	<div class="review-button clearfix">
		<input type="button" value="강의신청하기" onClick="gotoApply()" style="height:60px; border:1px ;padding:15px 30px ;font-weight:bold;background-color:#0075FF;color:white;font-size: 20px;">>
	</div>
	<script>
		function gotoApply() {
			location.href = "#apply";
		}
	</script>
	<div class="footer-content ck-content" style="min-width: 100%;">
		<?= $data['footer_content']; ?>
	</div>
	<div id="review" class="review-body">
		<div class="big_sub">
			<div class="m_div">
				<div class="m_body">
					<div style="text-align:center;background:#4b657c">
						<p style="font-size:25px;font-weight:bold; font-family:KoPubDotum; color: #FFFFFF;">실시간 리뷰보기</p>
						<p style="font-size:15px;font-weight:bold; font-family:KoPubDotum; color: #FFFFFF;">**리뷰를 쓰고 자신과 자신의 자산을
							홍보해보세요. 리뷰없이 홍보만 하면 지워집니다**</p>
					</div>
					<div>
						<div class="review-button clearfix">
							<input type="button" value="리뷰 입력하기" class="button" id="writeBtn">
						</div>
						<form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">
							<input type="hidden" name="mode" value="<?= $lecture_id ? " lecture_update" : "lecture_save_event"; ?>" />
							<input type="hidden" name="lecture_id" value="<?= $row['lecture_id']; ?>" />
							<input type="hidden" name="event_idx" value="<?= $event_idx; ?>" />
							<input type="hidden" name="pcode" value="<?= $pcode; ?>" />
							<input type="hidden" name="sp" value="<?= $sp; ?>" />
							<input type="hidden" name="landing_idx" value="<?= $landing_idx; ?>" />
							<div id="writeForm" style="display:none">
								<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<th>평가</th>
										<td>
											<input type="radio" name="score" value="5" checked><span style="color:#FFCE0B">★★★★★</span>
											<input type="radio" name="score" value="4"><span style="color:#FFCE0B">★★★★</span>
											<input type="radio" name="score" value="3"><span style="color:#FFCE0B">★★★</span>
											<input type="radio" name="score" value="2"><span style="color:#FFCE0B">★★</span>
											<input type="radio" name="score" value="1"><span style="color:#FFCE0B">★</span>
										</td>
									</tr>
									<tr>
										<th>강의선택</th>
										<td>
											<input type="hidden" name="lecture_id" id="lecture_id">
											<input type="text" name="lecture_info" id="lecture_info" readonly placeholder="우측의 강의선택 버튼을 클릭하여 참여한 강의를 선택해주세요.">
											<input type="button" value="강의선택" class="button searchBtn">
										</td>
									</tr>
									<tr>
										<th>리뷰내용</th>
										<td>
											<textarea name="content" id="content"></textarea>
										</td>
									</tr>
									<tr>
										<th>자기소개</th>
										<td><input type="text" name="profile" maxlength="50" id="profile" placeholder="50자 이내로 나와 내 서비스 그리고 연락처를 남겨서 서로 소통하세요."></td>
									</tr>
									<tr>
										<td colspan="2" style="text-align:right;">
											<input type="button" value="취소" class="button" id="cancleBtn">
											<input type="button" value="글올리기" class="button" id="saveBtn">
										</td>
									</tr>
								</table>
							</div>
						</form>

						<div class="slide-wrap">
							<div id="reviewSlider">
								<?
								$sql = "SELECT * FROM Gn_lecture WHERE $photo_sql order by start_date desc";
								$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
								$count = 0;
								while ($row = mysqli_fetch_array($result)) {
									if ($row['review_img1']) {
								?>
										<div class="slide-item"> <img src="upload/lecture/<?= $row['review_img1'] ?>"></div>
									<?
										$count++;
									}
									if ($row['review_img2']) {
									?>
										<div class="slide-item"> <img src="upload/lecture/<?= $row['review_img2'] ?>"></div>
									<?
										$count++;
									}
									if ($row['review_img3']) {
									?>
										<div class="slide-item"> <img src="upload/lecture/<?= $row['review_img3'] ?>"></div>
									<?
										$count++;
									}
									if ($row['review_img4']) {
									?>
										<div class="slide-item"> <img src="upload/lecture/<?= $row['review_img4'] ?>"></div>
									<?
										$count++;
									}
									if ($row['review_img5']) {
									?>
										<div class="slide-item"> <img src="upload/lecture/<?= $row['review_img5'] ?>"></div>
									<?
										$count++;
									}
								}

								if ($count == 0) {
									?>
									<div class="slide-item"><img src="images/demo.jpg"> </div>
									<div class="slide-item"><img src="images/demo.jpg"> </div>
									<div class="slide-item"><img src="images/demo.jpg"> </div>
									<div class="slide-item"><img src="images/demo.jpg"> </div>
									<div class="slide-item"><img src="images/demo.jpg"> </div>
								<?
								} ?>
							</div>
							<div class="slide-arrows"></div>
						</div>
						<div>
							<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
								<?
								$sql_serch = " 1=1 ";
								$now = date("Y-m-d");
								//홍보렙인가를 체크한다
								if ($data['m_id'] != "obmms02" && $memrow['mem_leb'] != "60") {
									$sql_serch .= " and b.mem_id ='" . $data['m_id'] . "'";
								}

								if ($_REQUEST['search_text_review']) {
									$search_text_review = $_REQUEST['search_text_review'];
									$sql_serch .= " and (lecture_info like '%{$search_text_review}%' or area like '%{$search_text_review}%'or instructor like '%{$search_text_review}%')";
								}

								$sql = "SELECT count(review_id) as cnt FROM Gn_review a inner join Gn_lecture b on a.lecture_id = b.lecture_id
									                                WHERE $sql_serch ";
								$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
								$row = mysqli_fetch_array($result);
								$intRowCount = $row['cnt'];
								if ($intRowCount) {
									if (!$_POST['lno'])
										$intPageSize = 30;
									else
										$intPageSize = $_POST['lno'];
									if ($_POST['page']) {
										$page = (int)$_POST['page'];
										$sort_no = $intRowCount - ($intPageSize * $page - $intPageSize);
									} else {
										$page = 1;
										$sort_no = $intRowCount;
									}
									if ($_POST['page2'])
										$page2 = (int)$_POST['page2'];
									else
										$page2 = 1;

									$int = ($page - 1) * $intPageSize;
									if ($_REQUEST['order_status'])
										$order_status = $_REQUEST['order_status'];
									else
										$order_status = "desc";
									if ($_REQUEST['order_name'])
										$order_name = $_REQUEST['order_name'];
									else
										$order_name = "review_id";
									$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
									$sql = "SELECT * FROM Gn_review a inner join Gn_lecture b on  a.lecture_id = b.lecture_id
												WHERE $sql_serch order by start_date desc limit $int,$intPageSize";
									$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									while ($row = mysqli_fetch_array($result)) { ?>
										<tr>
											<td class="photo" rowspan="3"><img src="/images/man.png" width="60" height="60"></td>
											<td style="font-size:14px;font-weight:bold; text-align:left;" class="profile" colspan="3">
												<?= $row['lecture_info'] ?> /
												<?= $row['instructor'] ?> /
												<?= $row['start_date'] ?>~
												<?= $row['end_date'] ?> /
												<?= $row['area'] ?>
												<? if ($row['score'] == "5") { ?>★★★★★
												<? } else if ($row['score'] == "4") { ?>★★★★
												<? } else if ($row['score'] == "3") { ?>★★★
												<? } else if ($row['score'] == "2") { ?>★★
												<? } else if ($row['score'] == "1") { ?>★
											<? } ?>
											</td>
										</tr>
										<tr>
											<td class="content" colspan="3">
												<div class="div_content">
													<?= nl2br($row['content']) ?>
												</div>
												<input type="button" value="+" class="button" id="viewMore" style="line-height: revert;font-size: 20px;right: 40px;">
												<div class="dropdown" style="position: absolute; right: 3px; bottom: 0px;">
													<button class="btn-link dropdown-toggle westory_dropdown" type="button" data-toggle="dropdown" style="" aria-expanded="false">
														<img src="/iam/img/menu/icon_dot.png" style="height:24px">
													</button>
													<ul class="dropdown-menu comunity" style="left: revert;right: 0;">
														<li><a onclick="set_block_contents('2972093')">이 콘텐츠 차단하기</a></li>
														<li><a onclick="set_report_contents('2972093')">이 콘텐츠 신고하기</a></li>
													</ul>
												</div>
											</td>
										</tr>
										<tr>
											<td style="font-size:12px;color:#7F7F7F;">
												<?= $row['mem_name'] ?>
											</td>
											<td style="font-size:12px;font-weight:bold;color:#2F5597;" class="intro">
												<?= $row['profile']; ?>
											</td>
											<td class="date">
												<?= $row['regdate'] ?>
											</td>
										</tr>
									<?
										$sort_no--;
									}
									?>
									<tr>
										<td colspan="14">
											<?
											page_f($page, $page2, $intPageCount, "pay_form");
											?>
										</td>
									</tr>
								<?
								} else {
								?>
									<tr>
										<td colspan="14">
											검색된 내용이 없습니다.
										</td>
									</tr>
								<?
								}
								?>
							</table>
							<!--
								<input type="button" value="랜딩 페이지 삭제" class="button">
								-->
						</div>
					</div>
					</form>
				</div>
			</div>
		<? 	}
		if ($data['movie_url']) { ?>
			<iframe width="100%" height="600px" src="<?= $data['movie_url']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen></iframe>
		<? 	}
		if (($landing_idx != "" && $data['request_yn'] == "Y") || $landing_idx == "") { ?>
			<form id="dform1" name="dform1" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="return checkForm()" enctype="multipart/form-data">
				<input type="hidden" name="mode" value="speech">
				<input type="hidden" name="pcode" value="<?= $pcode ?>">
				<input type="hidden" name="event_code" value="<?= $pcode ?>">
				<input type="hidden" name="m_id" value="<?= $m_id ?>">
				<input type="hidden" name="event_idx" value="<?= $event_idx ?>">
				<input type="hidden" id="id_reg" name="id_reg" value="0">
				<input type="hidden" id="email_reg" name="email_reg" value="0">
				<input type="hidden" id="nick_reg" name="nick_reg" value="0">
				<input type="hidden" id="landing_idx" name="landing_idx" value="<?= $landing_idx; ?>">
				<div id="apply" class="common-wrap" style="text-align:center;margin-top: 20px;max-width:600px;">
					<!-- 신청하기 -->
					<div class="container" style="width: 100%;">
						<div class="row" style="margin: 0px;">
							<div class="col-12">
								<div class="inner-wrap">
									<h2 class="title">
										<!--<em>-->
										<? if ($event_data['event_title'] != "") { ?>
											<?= $event_data['event_title']; ?>
										<? } else { ?>
											신청하기
										<? } ?>
										<!--</em>-->
									</h2>

									<section class="input-field">
										<h3 class="title">기본정보 입력</h3>
										<? if (strstr($event_data['event_info'], "join")) { ?>
											<div class="utils clearfix">
												<input type="radio" name="mem_type" id="no_mem" checked>
												<label for="no_mem" value="none_mem" style="font-size:17px;">비회원</label>
												<input type="radio" name="mem_type" id="iam_mem" style="margin-left:10px;">
												<label for="iam_mem" value="iam_mem_ori" style="font-size:17px;">IAM기존회원</label>
												<br>
												<span class="main_info" id="no_txt">
													신청정보의 수정/취소를 위해서는 확인코드(6-10자)를 꼭 입력해주세요.
													비번은 휴대폰 뒷4자리로 자동 설정됩니다.
												</span>
												<!-- <span class="main_info" id="iam_txt" hidden>계정을 입력하시면 자동으로 입력됩니다.</span> -->
											</div>
										<? } ?>
										<!-- <div class="utils clearfix">
											<span class="notice">회원가입과 동시에 e프로필이 생성됩니다.</span>
											<a href="javascript:;" onclick="load_userinfo()" class="button">회원정보 가져오기(기존회원)</a> 
										</div> -->
										<? if (strstr($event_data['event_info'], "join")) { ?>
											<input type="hidden" name="join_yn" value="Y">
											<!-- <div class="form-wrap" style="background-color: lemonchiffon">
											<div class="attr-row">
												<div class="attr-name"><b>회원가입</b></div>
												<div class="attr-value">
													<div class="input-wrap" style="font-size:14px;">
														<input type="checkbox" id="join_yn" name="join_yn" value="Y" <? if ($join_yn == 'Y') echo 'checked' ?> >
														<label><span style="color:#DF0101"><b>※ 체크박스 꼭 클릭해야해요!!</b><br> 회원가입 체크박스를 클릭해야 신청이 완료되며, 추후 정보제공이나 아이엠
																사용, 무료문자, 디버사용 등이 가능합니다. 이미 가입된분은 위의 회원정보가져오기를 클릭하세요.</span></label>
													</div>
												</div>
											</div>
										</div> -->
											<br>
										<? } else { ?>
											<input type="hidden" name="join_yn" value="N">
										<? } ?>
										<div class="form-wrap">
											<div id="div_account" style="display:block;">
												<? if (strstr($event_data['event_info'], "join")) { ?>
													<div class="attr-row ">
														<div class="attr-name">신청확인코드</div>
														<div class="attr-value">
															<div class="input-wrap">
																<input type="text" id="id" name="id" value="<?= $_POST['id'] ?>" placeholder="영문 또는 숫자 6-10자로 입력하세요." style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;" />
																<input type="button" style="margin: 5px;" value="중복확인" class="button is-grey" onClick="id_check(dform1,'dform1')" />
																<span id='id_html' style="width: 60px;"></span>
																<input type="hidden" name="id_status" value="<?= $_POST['id_status'] ?>" itemname='확인코드중복확인' required />
																&nbsp;&nbsp; <p id="id_chk_str" style="display: inline-block;">※중복확인 꼭 클릭하세요</p>

															</div>
														</div>
													</div>
												<? } ?>
												<!-- <div class="attr-row ">
													<div class="attr-name">비밀번호</div>
													<div class="attr-value">
														<div class="input-wrap">
															<input type="password" id="pwd" name="pwd" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
															 value="<?= $_POST['pwd'] ?>" />
														</div>
													</div>
												</div>
												<div class="attr-row ">
													<div class="attr-name">비번확인</div>
													<div class="attr-value">
														<div class="input-wrap">
															<input type="password" onblur="pwd_cfm_check('0')" name="pwd_cfm" value="<?= $_POST['pwd_cfm'] ?>" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;" />
															<input type="hidden" name="pwd_status" value="<?= $_POST['pwd_status'] ?>" />
														</div>
													</div>

												</div>
												<div class='pwd_cfm_html'></div> -->

											</div>
											<div class="attr-row is-account">
												<div class="attr-name">이름</div>
												<div class="attr-value">
													<div class="input-wrap">
														<input type="text" name="name" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
															id="name" value="<?= $_POST['name'] ?>" />

													</div>
												</div>
											</div>
											<div class="attr-row is-phone">
												<div class="attr-name">휴대폰</div>
												<div class="attr-value">
													<div class="input-wrap">
														<? if (strstr($event_data['event_info'], "sms")) {
															$width = "50%";
														} else {
															$width = "90%";
														} ?>
														<input type="tel" name="mobile" style="width:<?= $width ?>; height:40px; border:1px #cacaca solid;font-size: 14px;"
															id="tel" onblur="checkPhon()" value="<?= $_POST['mobile'] ?>" placeholder="'-'를 빼고 입력(예 : 01012345678)" />
														<? if (strstr($event_data['event_info'], "sms")) { ?>
															<input type="button" value="인증번호 받기" class="button" onclick="chk_sms()" style=" height:42px; border:1px #cacaca solid;font-size: 14px;">
														<? } ?>
													</div>
												</div>
											</div>
											<? if (strstr($event_data['event_info'], "sms")) { ?>
												<div id="phone_verify" style="display: block;">
													<div class="attr-row is-phone">
														<div class="attr-name">인증번호</div>
														<div class="attr-value">
															<div class="input-wrap">
																<input type="text" name="rnum" id="rnum" itemname='인증번호' maxlength="10" style="width:50%; height:40px; border:1px #cacaca solid;font-size: 14px;" />
																<input type="button" value="인증번호 확인" class="button" onclick="chk_sms1()" style=" height:42px; border:1px #cacaca solid;font-size: 14px;">
																<span id="check_sms"></span>
															</div>
														</div>
													</div>
												</div>
											<? }
											if (strstr($event_data['event_info'], "sex")) { ?>
												<div class="attr-row">
													<div class="attr-name">성별</div>
													<div class="attr-value">
														<div class="input-wrap" style="font-size: 14px;">
															<input type="radio" name="sex" value="m" checked> 남
															<input type="radio" name="sex" value="f"> 여
														</div>
													</div>
												</div>

											<? }
											if (strstr($event_data['event_info'], "email")) { ?>
												<div class="attr-row is-mail">
													<div class="attr-name">이메일</div>
													<div class="attr-value">
														<div class="input-wrap">
															<input type="text" onblur="email_check()" name="email" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
																id="email" value="<?= $_POST['email'] ?>" onKeyUp="func_check('mem_email',this.value)" />
														</div>
													</div>
												</div>
											<? }
											if (strstr($event_data['event_info'], "job")) { ?>
												<div class="attr-row">
													<div class="attr-name">소속/직업</div>
													<div class="attr-value">
														<div class="input-wrap">
															<input type="text" name="job" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
																id="job" value="<?= $_POST['job'] ?>" placeholder="/로 소속과 직업 분리 입력해요" />
														</div>
													</div>
												</div>

											<? }
											if (strstr($event_data['event_info'], "address")) { ?>
												<div class="attr-row">
													<div class="attr-name">주소</div>
													<div class="attr-value">
														<div class="input-wrap">
															<input type="text" name="addr" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
																id="addr" value="" placeholder="이벤트에 필요하니 정확히 입력해요" />
														</div>
													</div>
												</div>
											<? }
											if (strstr($event_data['event_info'], "birth")) { ?>

												<div class="attr-row">
													<div class="attr-name">출생년도</div>
													<div class="attr-value">
														<div class="input-wrap">
															<input type="text" name="birthday" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
																id="birthday" value="" placeholder="출생년도만 입력하세요" />
														</div>
													</div>
												</div>
											<? }
											if ($memrow['mem_leb'] == "60") { ?>
												<div class="attr-row">
													<div class="attr-name">신청강좌명</div>
													<div class="attr-value">
														<div class="input-wrap">
															<!-- <input type="text" name="consult_date" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;" id="consult_date" value="" placeholder="참여를 원하는 강좌명을 입력하세요"/> -->

															<input type="hidden" name="consult_date" id="lecture_id_2">
															<input type="text" name="consult_date" id="lecture_info_2" readonly placeholder="참여를 원하는 강좌명을 입력하세요"
																style="width:70%; height:40px; border:1px #cacaca solid;font-size: 14px;">
															<input type="button" value="강의선택" class="button searchBtn" style="height: 42px;border: 1px #cacaca solid;font-size: 14px;">
														</div>
													</div>
												</div>
											<? }
											if (strstr($event_data['event_info'], "other")) { ?>
												<div class="attr-row">
													<div class="attr-name">기타</div>
													<div class="attr-value">
														<div class="input-wrap">
															<textarea name="other" style="width:90%; height:40px; border:1px #cacaca solid; font-size:14px;"
																id="other" value="" placeholder="<?= $event_data['event_req_link'] ?>"></textarea>
														</div>
													</div>
												</div>
											<? } ?>
											<div class="attr-row is-account">
												<div class="attr-name">파일등록<span style="margin-left:10px;font-weight: bold;cursor:pointer" onclick="add_file_tab();">+</span></div>
												<div class="attr-value"><?= "(pdf,csv,jpg,png,txt,json,xlsx 파일 " . $event_data['file_cnt'] . "개까지 업로드 가능)" ?></div>
											</div>
											<? for ($i = 1; $i <= $event_data['file_cnt']; $i++) { ?>
												<div class="attr-row is-account ai" style="display: none;" id="<?= 'ai_file_div' . $i ?>">
													<div style="display: flex;justify-content: center;">
														<span style="margin-right: 10px;"><?= "파일" . $i ?></span>
														<input type="file" id="<?= 'ai_file' . $i ?>" name="<?= 'ai_file' . $i ?>" accept=".jpg,.jpeg,.png,.csv,.pdf,.txt,.json,.xlsx">
														<span style="font-weight: bold;cursor:pointer" onclick="del_file_tab(<?= $i ?>);">X</span>
													</div>
												</div>
											<? } ?>
											<div class="attr-row is-account">
												<div class="attr-name">신청<br>행사</div>
												<div class="attr-value">
													<div class="input-wrap" style="font-size: 14px;">
														<?= $event_data['event_name_kor']; ?>
														<input type="hidden" name="sp" style="width:90%; height:40px; border:1px #cacaca solid;" id="sp" value="<?= $sp ? $sp : $pcode ?>"
															<?= $readonly ?> />
													</div>
												</div>
											</div>
											<div class="apply inner-wrap agreement-field">
												<div class="agreement-wrap">
													<div class="agreement-item">
														<div class="check-wrap">
															<input type="checkbox" name="agree" id="agree" value="Y">
															<label for="agree" style="font-size: 14px;">개인정보이용동의</label>
														</div>
														<a href="#" onclick="newpop_('terms.php')">전문보기</a>
													</div>
												</div>
											</div>
											<div class="agreement-field">
												<div class="agreement-wrap">
													<? if ($event_data['event_desc']) { ?>
														<div class="agreement-item">
															<?= nl2br($event_data['event_desc']); ?>
														</div>
												</div>
											<? } ?>
											</div>
											<div class="button-wrap">
												<input align="middle" src="pop_btn_regist3.png" type="image" class="button is-grey" value="신청하기" />
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
					</div>
				</div><!-- // 신청하기 끝 -->
				<div id="ajax_div" style="display:none"></div>
			</form>
		<? } ?>
		<script>
			function add_file_tab() {
				if ($("#ai_file_div1").css('display') == "none")
					$("#ai_file_div1").show();
				else if ($("#ai_file_div2").css('display') == "none")
					$("#ai_file_div2").show();
				else if ($("#ai_file_div3").css('display') == "none")
					$("#ai_file_div3").show();
			}

			function del_file_tab(idx) {
				if (idx == 3) {
					$("#ai_file_div3").hide();
					$("#ai_file3").val('');
				} else if (idx == 2) {
					if ($("#ai_file_div3").css('display') == "none") {
						$("#ai_file_div2").hide();
						$("#ai_file2").val('');
					} else {
						if ($("#ai_file3")[0].files.length > 0) {
							var file = $("#ai_file3")[0].files[0];
							var dataTransfer = new DataTransfer();
							dataTransfer.items.add(file);
							$("#ai_file2")[0].files = dataTransfer.files;
						}
						$("#ai_file3").val('');
						$("#ai_file_div3").hide();
					}
				} else {
					if ($("#ai_file_div2").css('display') == "none") {
						$("#ai_file_div1").hide();
						$("#ai_file1").val('');
					} else {
						if ($("#ai_file_div3").css('display') == "none") {
							if ($("#ai_file2")[0].files.length > 0) {
								var file = $("#ai_file2")[0].files[0];
								var dataTransfer = new DataTransfer();
								dataTransfer.items.add(file);
								$("#ai_file1")[0].files = dataTransfer.files;
							}
							$("#ai_file2").val('');
							$("#ai_file_div2").hide();
						} else {
							if ($("#ai_file2")[0].files.length > 0) {
								var file = $("#ai_file2")[0].files[0];
								var dataTransfer = new DataTransfer();
								dataTransfer.items.add(file);
								$("#ai_file1")[0].files = dataTransfer.files;
							}
							if ($("#ai_file3")[0].files.length > 0) {
								var file = $("#ai_file3")[0].files[0];
								var dataTransfer = new DataTransfer();
								dataTransfer.items.add(file);
								$("#ai_file2")[0].files = dataTransfer.files;
							}
							$("#ai_file3").val('');
							$("#ai_file_div3").hide();
						}
					}
				}
			}

			function viewEvent(str) {
				window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
			}

			function newpop_(str) {
				window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=350");
			}

			function newpop() {
				var win = window.open("/mypage_lecture_list_pop.php?landing_idx=<?= $_REQUEST['landing_idx']; ?>", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
			}
			$(function() {
				$('.area_view').on('click', function() {
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

				$('.searchBtn').on("click", function() {
					newpop();
				});
			})
			var saveChk = false;
			$(function() {
				$('#saveBtn').on("click", function() {
					if ($('#lecture_id').val() == "") {
						alert('강의를 선택해 주세요.');
						$('#lecture_id').focus();
						return;
					}

					if ($('#content').val() == "") {
						alert('리뷰내용을 선택해 주세요.');
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
							success: function(data) {

								alert('리뷰가 등록되었습니다.');
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
					if(confirm("이 링크를 복사하시겠습니까?")) {
					window.clipboardData.setData("Text", trb);
					}
					} else {
					temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
					}
					});
					$('.switch').on("change", function() {
					var no = $(this).find("input[type=checkbox]").val();
					var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
							$.ajax({
								type:"POST",
								url:"mypage.proc.php",
								data:{
									mode:"land_update_status",
									landing_idx:no,
									status:status,
									},
								success:function(data){
								//alert('신청되었습니다.');location.reload();
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
				<? if ($data['lecture_yn'] == "Y") { ?>
					<li class="menu"><a href="#" data-scroll="table"><img src="images/side_01.png" alt="강연일정 바로가기" width="200px"></a></li>
					<li class="menu"><a href="#" data-scroll="review"><img src="images/side_02.png" alt="실시간리뷰 바로가기" width="200px"></a></li>
					<li class="menu"><a href="#" data-scroll="apply"><img src="images/side_03.png" alt="강연교육신청하기" width="200px"></a></li>
				<? } ?>
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
						<label style="padding:15px 0px">신고 항목 선택하기</label>
					</div>
					<div class="modal-header" style="text-align:left;">
						<span style="font-size:15px;margin-bottom:15px;">아래 항목을 선택하거나 하단에 직접 입력하기에 신고내용을 입력해주세요.</span>
						<div>
							<input type="checkbox" name="report_title" value="1" id="sex">
							<label for="sex">성관련 정보</label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="2" id="force">
							<label for="force">폭력물 정보</label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="3" id="worry">
							<label for="worry">괴롭힘 정보</label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="4" id="ownkill">
							<label for="ownkill">자살 자해 정보</label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="3" id="wrong">
							<label for="wrong">왜곡 거짓 정보</label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="3" id="spam">
							<label for="spam">불법 스팸성 정보</label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="3" id="unaccept">
							<label for="unaccept">무허가 판매 정보</label>
						</div>
						<div>
							<input type="checkbox" name="report_title" value="3" id="dislike">
							<label for="dislike">혐오 발언 정보</label>
						</div>
						<input type="hidden" name="content_idx_report" id="content_idx_report" value="2972093">
					</div>
					<div class="modal-body" style="text-align:center;">
						<span style="font-size:15px;">신고내용 설명하기</span>
						<textarea id="report_desc_msg" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;margin-bottom: 14px;" placeholder="신고내용을 입력해주세요. 특정 게시자를 괴롭히는 무작위 신고를 제한하기 위해 연락처를 기재하는 경우 답변을 전송해드립니다."></textarea>
						<textarea id="reporter_phone_num" style="width: 100%;border: 1px solid #b5b5b5;height: 30px;margin-bottom: 14px;" placeholder="연락처(휴대폰)을 입력해주시면 답변 결과를 문자로 보내드립니다."></textarea>
					</div>
					<div class="modal-footer" style="text-align: center;padding:0px;">
						<button type="button" class="btn-link" style="width: 50%;background: #c8c9c8;color: #6e6a6a;padding: 10px 0px;text-align: center;" onclick="set_report()">신고내용 전송하기</button>
					</div>
				</div>
			</div>
		</div>
		<script src="/m/js/slick.min.js"></script>
		<script>
			$('[data-scroll]').on('click', function(e) {
				e.preventDefault();

				var w = $(this).data('scroll');
				w = $('#' + w).offset().top;

				$('html, body').animate({
					scrollTop: w
				}, 300)
			});

			function set_report_contents(cont_idx) {
				$("#contents_report_modal").modal("show");
			}

			function set_block_contents() {
				if (confirm("이 콘텐츠를 차단하시겠습니까?")) {
					alert('차단 되었습니다.');
				}
			}

			$(function() {
				$('#reviewSlider').slick({
					infinite: true,
					slidesToShow: 4,
					slidesToScroll: 1,
					appendArrows: $('.slide-arrows'),
					prevArrow: '<button type="button" class="arrow prev"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
					nextArrow: '<button type="button" class="arrow next"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
					responsive: [{
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
					alert('이메일 형식에 맞게 입력해주세요.');
					document.getElementById("email").value = "";
				}
			}

			function checkPhon() {
				if (document.getElementById("tel").value.substring(0, 3) != '010' && ($('#tel').val()).substring(0, 3) != '011' && ($('#tel').val()).substring(0, 3) != '016' && ($('#tel').val()).substring(0, 3) != '017' && ($('#tel').val()).substring(0, 3) != '018' && ($('#tel').val()).substring(0, 3) != '019') {
					alert('전화번호 첫번째 자리는 010, 011, 016, 017, 018, 019만 가능합니다.');
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
					success: function(data) {
						if (data.result == "success") {
							$('input[name =name]').val(data.card_name);
							$('input[name =mobile]').val(data.card_phone);
							$('input[name =email]').val(data.card_email);
							$('input[name =addr]').val(data.card_addr);
							$('input[name =birthday]').val(data.card_birth);
							$('input[name =job]').val(data.card_company);
						} else {
							location.href = "http://kiam.kr/m/login.php?refer2=<?= $url_refer ?>";
						}
					}
				});
			}

			function chk_sms() {
				console.log($('#tel').val().substring(0, 3));
				if ($('#tel').val() == "") {
					alert('인증받으실 전화번호를 입력해주세요.')
					return;
				}
				if (($('#tel').val()).substring(0, 3) != '010' && ($('#tel').val()).substring(0, 3) != '011' && ($('#tel').val()).substring(0, 3) != '016' && ($('#tel').val()).substring(0, 3) != '017' && ($('#tel').val()).substring(0, 3) != '018' && ($('#tel').val()).substring(0, 3) != '019') {
					alert('전화번호 첫번째 자리는 010, 011, 016, 017, 018, 019만 가능합니다.')
					return;
				}
				if (($('#tel').val()).length < 11 || ($('#tel').val()).length > 12) {
					alert('인증받으실 전화번호를 확인해주세요.')
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
					success: function(data) {
						if (data.result == "success")
							$('#check_rnum').val("Y");
						else
							$('#check_rnum').val("");

						alert(data.msg);
					}
				})
			}

			function chk_sms1() {
				if (!$('#rnum').val()) {
					alert('인증번호를 받아주세요.');
					return;
				}
				$.ajax({
					type: "POST",
					url: "/ajax/event.proc.php",
					cache: false,
					dataType: "json",
					data: {
						mode: "check_sms",
						rphone: $('#tel').val(),
						rnum: $('#rnum').val()
					},
					success: function(data) {
						if (data.result == "success") {
							$('#check_rnum').val("Y");
							$('#check_sms').html('<img src="/images/check.gif"> 인증되었습니다.</p>');
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
				if ($('#store_address').val() == "") {
					alert('샵계정을 입력해주세요');
					return;
				}
				$('#viewFrame').html('<iframe id="sample_frame"  name="sample_frame" src="/admin/iam_auto_make_check_.php?memid=' + $('#store_address').val() + '" style="width: 400px; height: 1200px; border-width: 1px; border-style: solid; border-color: gray;"></iframe>');
			}

			function hideFrame() {
				$('#sample_frame').remove();
			}

			function sendId() {
				if ($('#mem_id').val() == "") {
					alert('샵계정을 입력해주세요');
					return;
				}
				if ($('#rphone').val() == "") {
					alert('휴대폰을 입력해주세요');
					return;
				}
				$.ajax({
					url: '/ajax/join.proc.php',
					type: 'POST',
					dataType: "json",
					data: {
						"mode": "send_sms2",
						"mem_id": $('#mem_id').val(),
						"rphone": $('#rphone').val()
					},
					success: function(data) {
						alert(data.msg);
					}
				});
			}
		</script>
</body>

</html>