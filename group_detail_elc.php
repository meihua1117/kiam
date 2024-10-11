<?
$path="./";
include_once $path."lib/rlatjd_fun.php";
$sql_serch=" mem_id ='{$_SESSION['one_member_id']}' ";
if($_REQUEST[grp_id])
$sql_serch.=" and grp_id = '$_REQUEST[grp_id]' ";
if($_REQUEST[grp_2])
$sql_serch.=" and grp_2 = '$_REQUEST[grp_2]' ";
if($_REQUEST[deta_select] && $_REQUEST[deta_text])
$sql_serch.=" and $_REQUEST[deta_select] like '$_REQUEST[deta_text]%' ";
$sql="select count(idx) as cnt from Gn_MMS_Receive where $sql_serch ";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($result);
$intRowCount=$row['cnt'];
if($_POST['page'])
$page=(int)$_POST['page'];
else
$page=1;
if($_POST['page2'])
$page2=(int)$_POST['page2'];
else
$page2=1;
if (!$_POST['lno']) 
$intPageSize =20;
else 
$intPageSize = $_POST['lno'];
$int=($page-1)*$intPageSize;
if($_REQUEST['order_status'])
$order_status=$_REQUEST['order_status'];
else
$order_status="asc"; 
if($_REQUEST['order_name'])
$order_name=$_REQUEST['order_name'];
else
$order_name="idx";
$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
$sql="select * from Gn_MMS_Receive where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>무제 문서</title>
<link href='<?=$path?>css/nanumgothic.css' rel='stylesheet' type='text/css'/>
<link href='<?=$path?>css/main.css' rel='stylesheet' type='text/css'/>
<script language="javascript" src="<?=$path?>js/jquery-1.7.1.min.js"></script>
<script language="javascript" src="<?=$path?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
<style>
ul,li { list-style:none; font-size:13px ; }
.send_class{
  display:none;
}
.send_class.filled{
  display:inline-block;
}
</style>
</head>
<body>
<?
include_once $path."open_div.php";
?>
<form name="group_detail_form" action="" method="post" >
<input type="hidden" name="order_name" value="<?=$order_name?>"  />
<input type="hidden" name="order_status" value="<?=$order_status?>"/>
<input type="hidden" name="page" value="<?=$page?>" />
<input type="hidden" name="page2" value="<?=$page2?>" />
<input type="hidden" name="grp_id" value="<?=$_REQUEST[grp_id]?>" />
<input type="hidden" name="grp_2" value="<?=$_REQUEST[grp_2]?>" />
<div class="grp_detail">
<a href="group_detail_elc.php?grp_id=<?=$_REQUEST[grp_id]?>" class="a_btn_2">전체</a>
    <select name="deta_select">
    <?
    $select_deta_arr=array("recv_num"=>"전화번호","grp"=>"소그룹명","name"=>"이름");
    foreach($select_deta_arr as $key=>$v)
    {
        $selected=$_REQUEST[deta_select]==$key?"selected":"";
        ?>
        <option value="<?=$key?>" <?=$selected?>><?=$v?></option>                                        
        <?
    }
    ?>
    </select>
    <input type="text" name="deta_text" value="<?=$_REQUEST[deta_text]?>" />
    <input type="image" src="images/sub_button_703.jpg" />
    <a href="javascript:deleteGroupMember('<?=$_REQUEST[grp_id]?>')"><img src="/images/sub_button_16.jpg" /></a>
</div>
<table class="list_table_detail" id="detail_table" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="width:25%;"><a href="javascript:void(0)" onclick="order_sort(group_detail_form,'grp_2',group_detail_form.order_status.value)">소그룹명<? if($_REQUEST['order_name']=="grp_2"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
    <td style="width:25%;">
    <!-- <li><input type="checkbox" id="all_id" onclick="check_all(this,'name_box');box_ed()" /></li> -->
    <a href="javascript:void(0)" onclick="order_sort(group_detail_form,'name',group_detail_form.order_status.value)">이름<? if($_REQUEST['order_name']=="name"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
    <td style="width:25%;">전화번호</td>
    <td style="width:25%;">편집</td>
  </tr>
  <?
  $i=0;
  if($intRowCount)
  {
	  $i=0;
	  while($row=mysqli_fetch_array($result))
	  {	  
		  ?>
          <tr class="contact_nums">
            <td class="g_dt_grp_<?=$i?>"><a href="javascript:void(0)" onclick="group_detail_form.grp_2.value='<?=$row[grp_2]?>';group_detail_form.submit();"><?=$row[grp_2]?></a></td>
            <td class="g_dt_grp_<?=$i?>" style="display:none;"><input type="text" name="xiao_grp" value="<?=$row[grp_2]?>"/></td>            
            <td class="g_dt_name_<?=$i?>" ><li><label id="lbox_<?=$i?>"><?=$row['name']?></label></li></td>
            <td class="g_dt_name_<?=$i?>" style="display:none;"><input type="text" name="num_name" value="<?=$row['name']?>" /></td>
            <td class="g_dt_num_<?=$i?>" value="<?=$row['recv_num']?>"><?=$row['recv_num']?></td>
            <td class="g_dt_num_<?=$i?>" style="display:none;"><input type="text" name="recv_num" value="<?=$row['recv_num']?>" /></td>
            <td>
            	<a href="javascript:void(0)" class="modify_btn_<?=$i?>" onclick="g_dt_show_cencle('g_dt_name_','g_dt_num_','modify_btn_','<?=$i?>','g_dt_grp_')">수정</a>                
                <a href="javascript:void(0)" class="modify_btn_<?=$i?>" style="display:none;" onclick="g_dt_fun('modify','<?=$_REQUEST[grp_id]?>','<?=$row['idx']?>','<?=$i?>')">수정</a>               
                <a href="javascript:void(0)" onclick="g_dt_fun('del','<?=$_REQUEST[grp_id]?>','<?=$row['idx']?>','<?=$i?>')">삭제</a>
            </td>
          </tr>
          <?
		  $i++;
	  }
    ?>
  </table>
  <table class="list_table_detail" id="detail_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td colspan="4">
        <?
		page_f($page,$page2,$intPageCount,"group_detail_form");
		?>
        </td>
    </tr>
      <?
  }
  else
  {
	  ?>
	<tr>
    	<td colspan="4">
		내용이 없습니다.
        </td>
    </tr>	      
      <?  
  }
  ?>
  <tr>
  	<td colspan="4" style="text-align:center;">
    	새로 추가:
        <input type="text" name="xiao_grp" placeholder="소그룹명" style="width:90px;" />
        <input type="text" name="num_name" placeholder="이름" style="width:90px;" />
        <input type="text" name="recv_num" placeholder="전화번호" style="width:90px;" />
        <input type="button" value="저장" onclick="g_dt_fun('add','<?=$_REQUEST[grp_id]?>','','<?=$i?>')"  />
	</td>            
  </tr>
</table>

<div style="text-align:center;">
  <input type="button" value="선택" onclick="get_pos()" style="height: 42px;margin-right:20px;"/>
  <a id="send_msg" href="javascript:void(0)" class="send_class" onclick="send_elc()"><img src="images/sub_button_68.jpg" /></a>
</div>
</form>
<script language="javascript" src="js/mms_send.2020.js?<?=date("His")?>"></script>
  <script language="javascript">
    $(document).ready(function(){
      if(window.parent.document.getElementsByName('title')[0].value){
        // $("#send_msg").attr('style', 'display:inline-block;');
        $("#send_msg").addClass("filled");
      }
      document.addEventListener('mousedown', mouseDownHandler);
    })
    // Query the table
    const table = document.getElementById('detail_table');
    let draggingEle;        // The dragging element
    let startRowIndex;   // The index of dragging row
    let endRowIndex;
    const mouseDownHandler = function(e) {
        // Get the original row
        originalRow = e.target.parentNode;
        curIndex = [].slice.call(table.querySelectorAll('tr')).indexOf(originalRow);
        // Attach the listeners to `document`
        document.addEventListener('mousemove', mouseMoveHandler);
        document.addEventListener('mouseup', mouseUpHandler);
        if(curIndex == -1)
          return;
        startRowIndex = curIndex; 
        endRowIndex = startRowIndex;
        // console.log("start=" + startRowIndex);
        


    };

    const mouseMoveHandler = function(e){
        // Get the original row
        originalRow = e.target.parentNode;
        rowIndex = [].slice.call(table.querySelectorAll('tr')).indexOf(originalRow);  
        if(rowIndex > 0)
        {
            endRowIndex = rowIndex;
            if(startRowIndex <= 0)
                startRowIndex = rowIndex;
        }
        // console.log("start=" + startRowIndex);
        // console.log("end=" + endRowIndex);
    }
    const mouseUpHandler = function(e) {

        // Get the end index
        originalRow = e.target.parentNode;
        rowIndex = [].slice.call(table.querySelectorAll('tr')).indexOf(originalRow);
        if(rowIndex > 0)
            endRowIndex = rowIndex;
        // console.log("start=" + startRowIndex);
        // console.log("end=" + endRowIndex);

        
        // Remove the handlers of `mousemove` and `mouseup`
        document.removeEventListener('mousemove', mouseMoveHandler);
        document.removeEventListener('mouseup', mouseUpHandler);
    };
    // Query all rows
    table.querySelectorAll('tr').forEach(function(row, index) {
        // Ignore the header
        // We don't want user to change the order of header
        if (index === 0) {
            return;
        }

        // Attach event handler
        //row.addEventListener('mousedown', mouseDownHandler);
    });
    function get_pos(){
      if(startRowIndex == null || endRowIndex == null || startRowIndex == -1 || endRowIndex == -1){
        alert("전화번호를 정확히 선택해주세요.");
        return;
      }
      if($("#send_msg").hasClass("filled")){
        $(parent.document).find("#num").val('');
      }
      var name_arr = [];
      var group_arr=[];
      var j = 0;
        if(startRowIndex > endRowIndex){
            temp = startRowIndex;
            startRowIndex = endRowIndex;
            endRowIndex = temp;
        }
        // if(endRowIndex > 20) endRowIndex = 20;
        console.log("start=" + startRowIndex);
        console.log("end=" + endRowIndex);
        for(var i=startRowIndex; i<endRowIndex + 1; i++){
          name_arr[j] = $("td[class='g_dt_num_" + (i - 1) + "']").attr("value");
          // console.log(name_arr[j]);
          if(window.parent.document.getElementsByName('num')[0].value)
          group_arr=window.parent.document.getElementsByName('num')[0].value.split(",");

          if((jQuery.inArray(name_arr[j],group_arr)==-1) && (name_arr[j] != null))
          group_arr.push(name_arr[j]);

          window.parent.document.getElementsByName('num')[0].value=group_arr.join(",");
          // window.parent.document.getElementsByName('num')[0].focus();
          j++;
        }
        $(parent.document).find('.num_check_c:eq(1)').html(j);
        $(parent.document).find('.num_check_c:eq(2)').html(j);
    }

    function send_elc(){
      $(parent.document).find("#send_msg_btn").click();
    }
  </script>
</body>
</html>
<script language="javascript" src="<?=$path?>js/rlatjd_fun.js"></script>
<script language="javascript" src="<?=$path?>js/rlatjd.js"></script>
