<?
include_once "../lib/rlatjd_fun.php";

if($_SESSION[one_member_id]){
    $seq = $_POST['seq'];

	$sql="select * from sm_log where mem_id='$_SESSION[one_member_id]' and seq in ($seq) ";
	//echo $sql;
	$result = mysql_query($sql);
	while($row=mysql_fetch_array($result)) {
	    $query = "delete from Gn_MMS_Receive where mem_id='$_SESSION[one_member_id]' and recv_num='$row[dest]'";
	    //echo $row[dest];
	    //echo $query."<BR>";
	    mysql_query($query);
	
	}

}
mysql_close($self_con);
?>