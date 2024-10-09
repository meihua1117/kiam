<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
$startPage = $nowPage?$nowPage:1;
$pageCnt = 20;

// 검색 조건을 적용한다.
$order = $order?$order:"desc"; 		

$query = "select * from Gn_MMS_Number where 1=1 and ( not (cnt1 = 10 and cnt2 = 20)) and mem_id ='$mem_id' ";
$res	    = mysql_query($query);
$query .= "
	ORDER BY reg_date DESC
";            	
$i = 1;
$res = mysql_query($query);
while($row = mysql_fetch_array($res)) {      
?>
<tr>
    <td><?=$i?></td>
    <td><?=$row['sendnum']?></td>
    <td><?=$row['reg_date']?></td>
</tr>
<?
$i++;
}
if($i == 1) {
?>
<tr>
    <td colspan="3">정보가 없습니다.</td>
</tr>
<?
}
?>          