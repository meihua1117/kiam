<?
include_once $path."lib/rlatjd_fun.php";
$_REQUEST[status] = 1;
extract($_GET);
$mem_id = $_GET["mem_id"];
$solution_type = $_GET["solution_type"];
$solution_name = $_GET["solution_name"];
$sql = "select mem_pass from Gn_Member where mem_id = '$mem_id'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>온리원분양사 소속 변경</title>
<link href='<?=$path?>css/nanumgothic.css' rel='stylesheet' type='text/css'/>
<link href='<?=$path?>css/main.css' rel='stylesheet' type='text/css'/>
<script language="javascript" src="<?=$path?>js/jquery-1.7.1.min.js"></script>
<script language="javascript" src="<?=$path?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
<script language="javascript">
	function check_password(){
		var old_passwd = $('#old_pwd').val();
		var confirm_pwd = CryptoJS.MD5($('#passwd').val());
		if(old_passwd == confirm_pwd)
		{
			console.log('<?=$solution_type?>');
			$.ajax({
				type:"POST",
				url:"change_solution.php",
				data:{
					mem_id : '<?=$mem_id?>',
					solution_type : '<?=$solution_type?>',
					solution_name : '<?=$solution_name?>'
				},
				success:function(data){
					alert('분양사소속이 변경되었습니다.');
					if('<?=$solution_type?>' == 'site')
						opener.location.href='./';
					else
						opener.location.href='./iam';
					window.close();
				},
				error: function(){
					alert('변경 실패');
					window.close();
				}
			});
		}
		else{
			alert('비밀번호가 일치되지 않습니다.다른 아이디를 이용해주세요');
			opener.$('#id_status').val('');
			opener.$('#id').val('');
			opener.$('#id').focus();
			opener.$('#id_html').html('&nbsp;&nbsp; ※ 아이디 중복확인을 클릭해주세요.');
			window.close();
		}
	}
</script>
<style>
.m_div {width:600px;}
</style>
</head>
<body>
<div class="big_sub">
		<table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 12px">
			<input type="hidden" name = "old_pwd" id = "old_pwd" value='<?=$row['mem_pass']?>'/>
			<tr>
				<td style="width: 30%"><label>아이디</label></td>
				<td style="width: 100%"><input type="text" name="mem_id" id="mem_id" value='<?=$mem_id?>' readonly/></td>
			</tr>
			<tr>
				<td style="width: 30%"><label>비밀번호</label></td>
				<td style="width: 100%"><input type="password" name="passwd" id="passwd"/></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center">
					<!--a href="javascript:;;" onclick="check_password()" style="border:1px solid #000;padding:5px">확인</a-->
					<input type="button" onclick="check_password()" value="확인"/>
				</td>
			</tr>
		</table>
</div>

