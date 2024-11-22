<?
include_once "../lib/rlatjd_fun.php";

if($_SESSION['one_member_id']){
    $seq = $_POST['seq'];
	$sql="select * from sm_log where mem_id='{$_SESSION['one_member_id']}' and seq in ($seq) ";
	$result = mysqli_query($self_con,$sql);
	while($row=mysqli_fetch_array($result)) {
	    $query = "delete from Gn_MMS_Receive where mem_id='{$_SESSION['one_member_id']}' and recv_num='{$row['dest']}'";
	    mysqli_query($self_con,$query);
	
	}
}
mysqli_close($self_con);
echo json_encode(array("result"=>"success"));
?>