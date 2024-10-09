<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($_SESSION[one_member_id] == "") {
    echo "<script>location ='/m/login.php';</script>";
    exit;
}

$query = "select * from Gn_Member where mem_id='$_SESSION[one_member_id]' and site != ''";
$sresul=mysql_query($query);
$data=mysql_fetch_array($sresul);	
if($data[tagby_yn] == "Y") {
    echo "<script>location ='http://obmms.aplat.kr/api.php?id=$_SESSION[one_member_id]';</script>";
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>온리원 셀링</title>
	<link rel="stylesheet" href="/m/css/notokr.css">
	<link rel="stylesheet" href="/m/css/font-awesome.min.css">
	<link rel="stylesheet" href="/m/css/style.css">
	<link rel="stylesheet" href="/m/css/grid.min.css">
	<link rel="stylesheet" href="/m/css/slick.min.css">
    <style>
    .button-wrap { margin-top: 15px; font-size: 0; text-align: center; }
    .button-wrap .buttons { display: inline-block; width: 110px; margin: 0 5px; padding: 5px; background-color: #ddd; border-radius: 4px; font-size: 14px; line-height: 20px; text-align: center; }
    .button-wrap .buttons:first-child { background-color: #2196f3; color: #fff; font-weight: 500; }    
.button-wrap { margin-top: 25px; font-size: 0; text-align: center; }
.button-wrap .button { display: inline-block; width: 120px; margin: 0 5px; padding: 5px 0; border: 1px solid #888; color: #888; font-size: 16px; font-weight: 500; line-height: 20px; text-align: center; }
.button-wrap .button.is-grey { background-color: #888; color: #fff; }    
    </style>
	<script language="javascript" src="/js/jquery-1.7.1.min.js"></script>
	<script>
	    function login_check()
	    {
	        if($("#one_id").val() == "") {
	            alert('아이디를 입력하세요.');
	            $("#one_id").focus();
	            return false;
	        }
	        
	        if($("#one_pwd").val() == "") {
	            alert('비밀번호를 입력하세요.');
	            $("#one_pwd").focus();
	            return false;
	        }	        
	    }
	</script>
</head>
<body>
	<div id="wrap" class="common-wrap">

		<main id="guide" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="inner-wrap">
                        <img src="img/main/200324_01.png" style="margin-bottom:10px">

            <div class="right_sub_menu">&nbsp;
		       <button type="button" style="background:#D8D8D8">
                <a href="/mypage.php">회원정보수정</a> </button>	
		       <button type="button" style="background:#D8D8D8">
                <a href="/mypage_payment.php">결제정보</a></button> 
            </div>
							<h1 class="title">약관동의</h1>

							<div class="tab-contents">
								<div class="tab-inner active" id="selling" style="font-size:15px"> 본 약관은 (주)온리원셀링 솔루션 본인 회원정보를 마이샵과 인플루언서 광고 솔루션을 이용하기 위해서 회원정보를 연동하는데 동의합니다.<br><br>

※이용안내<br>
1. 이용 절차를 알고 싶다면 상단의 [이용절차안내]를 보시고 다시 접속하세요.<br>
2. [이용하기]클릭 시 [메일주소가 없습니다]라는 메시지가 나오면 온리원셀링 회원정보에 이메일을 추가하고 나서 [이용하기]를 눌러주세요.<br>
3. 마이샵으로 연동이 끝나면 부족한 정보를 채우시기바랍니다.
								</div>
                                <div class="button-wrap">
    								<a href="/" class="button is-grey">취소</a>
    								<a href="http://obmms.aplat.kr/api.php?id=<?php echo $_SESSION[one_member_id]?>" class="button is-pink">이용하기</a>
    							</div>
							
                            
                           
							</div>
						</div>
					</div>
				</div>
			</div>
		</main><!-- // 컨텐츠 영역 시작 -->


	</div>
<?php include_once $_SERVER['DOCUMENT_ROOT']."/m/include_once/footer.inc.php"; ?>
<script>
	$('.tab-items a').on('click', function(){
		var tabName = $(this).data('tab');

		$('.tab-inner').removeClass('active');
		$('#'+tabName).addClass('active');
	})
</script>
