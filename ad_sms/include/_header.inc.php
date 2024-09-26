<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
// if($_SESSION['one_member_admin_id'] == "") {
//     if($_SESSION['one_member_id'] == "lecturem" || $_SESSION['one_member_id'] == "sungmheo" || ($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) || ($_SESSION['iam_member_subadmin_id'] != "" && $_SESSION['iam_member_subadmin_domain'] == $HTTP_HOST)) {} else {
//     header('Location: /');
//     exit;
//     }
// }
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
<html style="height:100%">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>온리원셀링솔루션</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!-- jQuery 2.1.4 -->
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <!-- <script src="/iam/js/jquery-3.1.1.min.js"></script> -->
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>

    <!-- dataTable -->
    <script src="/admin/plugins/datatables/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="/admin/plugins/datatables/jquery.dataTables.min.css">
    
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
    <style>
    .loading_div{position:fixed;left:50%;top:50%;display:none;z-index:1000;}    
    .open_1{position:absolute;z-index:10;background-color:#FFF;display:none;border:1px solid #000}
    .open_2{padding-left:5px;height:30px;cursor:move;}
    .open_2_1{float:left;line-height:30px;font-size:16px;font-weight:bold;}
    .open_2_2{float:right;}
    .open_2_2 a:link, 
    .open_2_2 a:visited,
    .open_2_2 a:active{text-decoration:none; color:#FFF; }
    .open_2_2 a:hover{text-decoration:none;color:#FF0;}
    .open_3{padding:10px;}    
    </style>
    

    
    
  </head>
  <body class="hold-transition skin-blue sidebar-mini" style="height:100%">