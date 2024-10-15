<?
include_once "../lib/rlatjd_fun.php";
//앱체크
if($_POST['mode'] == "app_check"){
    $check_num_arr = $_POST["phone_num"];
    for( $k = 0; $k < count($check_num_arr); $k++){
        $title = "app_check_process";
        $content = $_SESSION['one_member_id'].", app_check_process";
        $ret=sendmms(7, $_SESSION['one_member_id'], $check_num_arr[$k], $check_num_arr[$k], "", $title, $content, "", "", "", "N");
        ?>
        <script>console.log('sendmms result=' + '<?=$ret?>');</script>
        <?
    }

?>
    <script>
		alert('앱상태 체크가 요청되었습니다.');
	    location.reload();
    </script>
<?
}
?>