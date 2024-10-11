<?
$path="./";
header("Content-Type: text/html; charset=UTF-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($_SESSION[one_member_id] != 'obmms01')
{
?>
<script language="javascript">
alert('관리자만 접근 가능합니다.');
location.replace('/ma.php');
</script>
<?
exit;
}else{

	$date_today=date("Y-m-d g:i:s");
	$dateLimit = date("Y-m-d 23:59:59",strtotime($date_today."+6 months")); //가입일 +6개월
	
	//로그인 id pw
	$userId = trim($_POST["id"]);
	$totalAmount = trim($_POST["totalamount"]);
	$buyertel = trim($_POST["num"]);
	$userNum = str_replace(array("-"," ",","),"",$buyertel);

	if(strlen($userId) == 0 or strlen($totalAmount) == 0 or strlen($buyertel) == 0){
		?>		
		<!doctype html>
		<html lang="kr">
		 <head>
		  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 </head>
		 <body>
		 <form id="" name="" method="post" action="permit_number.php">
			아이디 : <input type="text" id="id" name="id" value="" />	<br>
			폰번호 : <input type="text" id="num" name="num" value="" /> (- 표시 포함)<br>
			입금액 : <input type="text" id="totalamount" name="totalamount" value="" />
			<input type="submit" value="전송">
		  </form> 
		 </body>
		</html>
		<?
		exit;
	}else{

			$sql = "INSERT INTO tjd_pay_result (phone_cnt,fujia_status,month_cnt,end_date,end_status,TotPrice,pc_mobile, date, cancel_status,buyertel,buyer_id) SELECT 300,'Y',6,'".$dateLimit."',end_status,'".$totalAmount."',pc_mobile, '".$date_today."', cancel_status,'".$buyertel."','".$userId."'  FROM tjd_pay_result WHERE no=30";

			mysqli_query($self_con,$sql);

			$sql = "update Gn_Member set fujia_date2 = '".$dateLimit."' WHERE mem_id = '".$userId."';";
			mysqli_query($self_con,$sql);
		?>

		<!doctype html>
		<html lang="kr">
		 <head>
		  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 </head>
		 <body>
				<script language="javascript">
				alert('처리 되었습니다.');
				document.location.href="/";
				</script>
		 </body>
		</html>
		<?
		}
}
?>