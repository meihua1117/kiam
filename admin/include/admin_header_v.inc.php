<?
if($_SESSION['one_member_id'] != "db") {
    header('Location: /');
    exit;
}
// 파일 확장자추출
//$fileInfo = explode("/",$_SERVER[REQUEST_URI]);
$fileInfo = explode("/",$_SERVER[PHP_SELF]);
// 파일명
$fileName = $fileInfo[count($fileInfo) - 1];
// 폴더명
$folderName = $fileInfo[count($fileInfo) - 2];

//기본 REQUEST 값 셋팅
$action = $_REQUEST["action"];
$nowPage = $_REQUEST["nowPage"];

// 현재페이지
if( $nowPage == "" ) $nowPage = 1;

//페이지 이동용 폼생성
$pagingFormString = '<form name="pagingForm" id="pagingForm" action="'.$_SERVER["PHP_SELF"].'">'.chr(13);
$pagingFormString.= '<input type="hidden" id="nowPage" name="nowPage" value="'.$nowPage.'">'.chr(13);
$pagingFormString.= '<input type="hidden" id="b_id" name="b_id">'.chr(13);
$pagingFormString.= '<input type="hidden" id="bbsId" name="bbsId">'.chr(13);

foreach( $_POST as $key => $val ){
	if( $key != "nowPage" && $key != "b_id" && $key != "bbsId"  )
		$pagingFormString.= '<input type="hidden" name="'.$key.'" value="'.$val.'">'.chr(13);
}

foreach( $_GET as $key => $val ){
	if( $key != "nowPage" && $key != "b_id" && $key != "bbsId" )
		$pagingFormString.= '<input type="hidden" name="'.$key.'" value="'.$val.'">'.chr(13);
}

$pagingFormString.= '</form>'.chr(13);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>온리원 셀링 관리자</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/admin/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/admin/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/admin/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/admin/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/admin/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- jQuery 2.1.4 -->
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
  </head>
  <body class="hold-transition skin-blue sidebar-mini">