<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_REQUEST);

    $sql_serch= " 1=1 ";

	$sql_table = " Gn_MMS ";
	$sql_serch .=" and result >= 0 and send_num=recv_num and type=5 and self_memo != ''";
	
	if($_REQUEST['search_key'])
	{
		$sql_serch.=" and (title like '{$_REQUEST['search_key']}%' or content like '{$_REQUEST['search_key']}%') ";	
	}
    
    if($_REQUEST['search_num'])
	{
		$sql_serch.=" and send_num like '{$_REQUEST['search_num']}%' ";	
	}
	// 상태 검색 추가
	if($_REQUEST['result'] == 1) {
	    $sql_serch .= " and result = 0 and up_date is not null ";
	} elseif($_REQUEST['result'] == 2) {
	    $sql_serch .= " and result = 1 and up_date is null ";
	} elseif($_REQUEST['result'] == 3) {
	    $sql_serch .= " and result = 3";
	}					
	$sql="select count(*) as cnt from $sql_table where $sql_serch ";
	$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
	$row=mysqli_fetch_array($result);
	mysqli_free_result($result);
	$intRowCount=$row['cnt'];
	$intPageSize =10;				
	if($_REQUEST['page'])
	{
	  $page=(int)$_REQUEST['page'];
	  $sort_no=$intRowCount-($intPageSize*$page-$intPageSize); 
	}
	else
	{
	  $page=1;
	  $sort_no=$intRowCount;
	}
	if($_REQUEST['page2'])
	$page2=(int)$_REQUEST['page2'];
	else
	$page2=1;
	$int=($page-1)*$intPageSize;
	if($_REQUEST['order_status'])
	$order_status=$_REQUEST['order_status'];
	else
	$order_status="desc"; 
	if($_REQUEST['order_name'])
	$order_name=$_REQUEST['order_name'];
	else
	$order_name="reg_date";
	$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
	$sql="select * from $sql_table where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
	$excel_sql="select * from $sql_table where $sql_serch order by $order_name $order_status ";
	$excel_sql=str_replace("'","`",$excel_sql);					
	$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));		
	?>
					



<div class="row">
	<div class="col-md-12" style="padding:30px;">
        <section class="content-header">
            <p><h3 class="section-title">셀폰 수발신 리스트</h3></p>
        </section>
        <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
               <div style="padding:10px">
              </div>
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 350px;">
                    <select name="result" class="form-control input-sm pull-right" style="width:32%;">
                        <option value="">전체</option>
                        <option value="1" <?=$_REQUEST['result']==1?"selected":""?>>성공</option>
                        <option value="2" <?=$_REQUEST['result']==2?"selected":""?>>대기</option>
                        <option value="3" <?=$_REQUEST['result']==3?"selected":""?>>실패</option>                                
                    </select>	
                    <input type="text" name="search_num" id="search_num" class="form-control input-sm pull-right" style="width:32%;" placeholder="폰번호" value="<?=$_REQUEST['search_num']?>">
                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" style="width:32%;" placeholder="콘텐츠" value="<?=$_REQUEST['search_key']?>">

                    <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                    </div>
                    <input type="hidden" name="order_status" value="<?=$order_status?>"/>
                    <input type="hidden" name="page" value="<?=$page?>" />
                    <input type="hidden" name="page2" value="<?=$page2?>" />  
                </div>
              </form>
              </div>            
              </div>            
          </div>
        <table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width:7%;"><label><input type="checkbox" onclick="check_all(this,'fs_idx');" />번호</label></td>
                <td style="width:8%;">아이디</td>
                <td style="width:8%;">이름</td>
                <td style="width:12%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소속<br>(셀링 / IAM)</td>
                <td style="width:8%;">수발신폰</td>
                <td style="width:16%;">발신내용</td>
                <td style="width:5%;">이미지</td>
                <td style="width:8%;">발송일자</td> 
                <td style="width:8%;">발신처</td>
            </tr>
            <?
            if($intRowCount)
            {
                
                $c=0;
                while($row=mysqli_fetch_array($result))
                {
                    $sql_s="select * from Gn_MMS_status where idx={$row['idx']} ";
                    $resul_s=mysqli_query($self_con, $sql_s);
                    $row_s=mysqli_fetch_array($resul_s);
                    mysqli_free_result($resul_s);
                                                                
                    $sql_n="select memo from Gn_MMS_Number where mem_id='{$row['mem_id']}' and sendnum='{$row['send_num']}' ";
                    $resul_n=mysqli_query($self_con, $sql_n);
                    $row_n=mysqli_fetch_array($resul_n);
                    mysqli_free_result($resul_n);

                    $sql_m="select site, site_iam from Gn_Member where mem_id='{$row['mem_id']}'";
                    $resul_m=mysqli_query($self_con, $sql_m);
                    $row_m=mysqli_fetch_array($resul_m);
                    mysqli_free_result($resul_m);
                    
                    $recv_num = $recv_cnt=explode(",",$row['recv_num']);
                    $recv_num_in = "'".implode("','", $recv_num)."'";
                    $date = $row['up_date'];

                    $sql="select count(seq) as cnt from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 and  send_num='{$row['send_num']}' and recv_num in ($recv_num_in) and recv_num like '01%'  and regdate >= '$date' and sms not like '[%'";
                    $kresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
                    $krow=mysqli_fetch_array($kresult);
                    $intRowCount=$krow['cnt'];											
                    if($date == "") $intRowCount = "";
                    
                    $sql_as="select count(idx) as cnt from Gn_MMS_status where idx={$row['idx']} ";
                    $resul_as=mysqli_query($self_con, $sql_as);
                    $row_as=mysqli_fetch_array($resul_as);
                    $status_total_cnt = $row_as[0];											
                    
                    $sql_cs="select count(idx) as cnt from Gn_MMS_status where idx={$row['idx']} and status='0'";
                    $resul_cs=mysqli_query($self_con, $sql_cs);
                    $row_cs=mysqli_fetch_array($resul_cs);
                    $success_cnt = $row_cs[0];

                    $sql_sn="select * from Gn_MMS where idx={$row['idx']} ";
                    $resul_sn=mysqli_query($self_con, $sql_sn);
                    $row_sn=mysqli_fetch_array($resul_sn);											
                    $recv_cnt=explode(",",$row_sn['recv_num']);
                    
                    $total_cnt = count($recv_cnt);													
                    $reg_date = strtotime($row['reg_date']);
                    $reg_date_1hour = strtotime("{$row['reg_date']} +1hours"); 								
                    
                    if($success_cnt > $total_cnt) $success_cnt = $total_cnt;
                ?>

                <tr>
                    <td><label><input type="checkbox" name="fs_idx" value="<?=$row['idx']?>" /><?=$sort_no?></label></td>
                    <td><?=$row['mem_id']?></td>	
                    <td><?=$row_n['memo']?></td>
                    <td><?echo $row_m['site'] . " / " . $row_m['site_iam'];?></td>										
                    <td><?=$row['send_num']?></td>
                    <td style="font-size:12px;"><a href="javascript:void(0)" onclick="show_recv('show_content','<?=$c?>','발신내용')"><?=str_substr($row['content'],0,30,'utf-8')?></a><input type="hidden" name="show_content" value="<?=$row['content']?>"/></td>
                    <td>
                        <?if ($_REQUEST['status2']==2){ echo substr($row['reservation'],0,16); }else{?>
                        <a href="javascript:void(0)" onclick="show_recv('show_jpg','<?=$c?>','첨부파일')"><?=str_substr($row['jpg'],0,20,'utf-8')?></a><input type="hidden" name="show_jpg" value="<?=$row['jpg']?>"/>
                        <a href="javascript:void(0)" onclick="show_recv('show_jpg1','<?=$c?>','첨부파일')"><?=str_substr($row['jpg1'],0,20,'utf-8')?></a><input type="hidden" name="show_jpg1" value="<?=$row['jpg1']?>"/>
                        <a href="javascript:void(0)" onclick="show_recv('show_jpg2','<?=$c?>','첨부파일')"><?=str_substr($row['jpg2'],0,20,'utf-8')?></a><input type="hidden" name="show_jpg2" value="<?=$row['jpg2']?>"/>                     
                        <?}?>                        
                    </td>
                    <td style="font-size:12px;">                        
                       <? if($row['reservation'] == "") echo substr($row['reg_date'],0,16) ; else echo substr($row['reservation'],0,16);?>
                      
                    </td>
                    <td><?=$row['self_memo'];?></td>
                </tr>
                <?
                $c++;
                $sort_no--;
                }
                ?>
                <tr>
                    <td colspan="13">
                    <?
                    page_f($page,$page2,$intPageCount,"search_form");
                    ?>
                    </td>
                </tr>                                        
                <?
            }
            else
            {
            ?>
                <tr>
                    <td colspan="13">
                    등록된 내용이 없습니다.
                    </td>
                </tr>                                     
            <?	
            }
            ?>
        </table>
        <form name="excel_down_form" action="" target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />        
        </form>
        <iframe name="excel_iframe" style="display:none;"></iframe>	 
    </div>
</div> 
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:400px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
<style>
.loading_div {
    display:none;
    position: fixed;
    left: 50%;
    top: 50%;
    display: none;
    z-index: 1000;
}
#open_recv_div li{list-style: none;}
</style>
<script>
function deleteAddress() {
    var checked = false;
    var seq = "";
    $('input[name=idx_box]').each(function() {
        if($(this).is(":checked") == true)  {
            checked = true;
            if(seq != "") seq += ",";
            seq += $(this).val();
        }
            
    });
    if(checked == false ) {
        alert('삭제할 번호를 선택해주세요');
        return;
    }

    var values = {"seq":seq}; 
    $.ajax({ type: 'post', 
             dataType: 'json', 
             url:'/ajax/truncate_num.php', 
             data: values, 
             success: function (dataObj) {
                
                
                alert('완료되었습니다.');
 
        },
        error: function (request, status, error) {
            console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
        }
    });    
    
}

function selected_msg_del(){
	var idx_arr = new Array();
	$('input[name=fs_idx]').each(function() {
		if($(this).is(":checked") == true) {
			idx_arr.push($(this).val());
		}
	});

	if(idx_arr.length == 0){
		alert("삭제할 메시지를 선택하세요.");
		return;
	}

	// console.log(idx_arr.toString());
	if(confirm("삭제 하시겠습니까?")){
		$.ajax({
			type:"POST",
			url:"/admin/ajax/delete_func.php",
			dataType:"json",
			data:{admin:0, delete_name:"mms_del", id:idx_arr.toString()},
			success: function(data){
				console.log(data);
				if(data == 1){
					alert('삭제 되었습니다.');
					window.location.reload();
				}
			}
		})
	}
}

function all_msg_del(type){
	// console.log(type);
	if(confirm("모든 페이지 데이타를 모두 삭제합니다.  삭제하시겠어요?")){
		$.ajax({
			type:"POST",
			url:"/admin/ajax/delete_func.php",
			dataType:"json",
			data:{admin:0, delete_name:"mms_del", mem_id:'<?=$_SESSION['one_member_id']?>', type:type},
			success: function(data){
				console.log(data);
				if(data == 1){
					alert('삭제 되었습니다.');
					window.location.reload();
				}
			}
		})
	}
}

function check_all(tthis,name)
{
	var cbs=document.getElementsByName(name)
	for (var i = 0; i < cbs.length; i ++){
		if (cbs[i].value && !cbs[i].disabled)
			cbs[i].checked = tthis.checked;
   }
}

function page_p(e1,e2,e3)
{
   e3.page.value=e1
   if(e2%parseInt(e2)==0)
      {
	  e3.page2.value=e2   
	  }
	else
	  {
	 e3.page2.value=parseInt(e2)+1 
	  }  
  e3.submit();
}

function show_recv(name, c, t, status) {
	if (!document.getElementsByName(name)[c].value)
		return;
	open_div(open_recv_div, 100, 1, status);
	if (name == "show_jpg")
		$($(".open_recv")[0]).html("<img src='" + "../" + document.getElementsByName(name)[c].value + "' />");
	else if (name == "show_jpg1")
		$($(".open_recv")[0]).html("<img src='" + "../" + document.getElementsByName(name)[c].value + "' />");
	else if (name == "show_jpg2")
		$($(".open_recv")[0]).html("<img src='" + "../" + document.getElementsByName(name)[c].value + "' />");
	else
		$($(".open_recv")[0]).html(document.getElementsByName(name)[c].value.replace(/\n/g, "<br/>"));
	$($(".open_recv_title")[0]).html(t);
}

function open_div(show_div,mus_top,mus_left,status)
{
	if(!status)
	{
	var cbs = document.getElementsByTagName("div");
	for (var i = 0; i < cbs.length; i ++)
	   {
		var cb = cbs[i];
		 if (cb.id.indexOf("open")!=-1)
			{
			$("#"+cb.id).fadeOut(250)  
			}
	   }
	}
    $(show_div).fadeIn(250);	   
	if(mus_top && mus_left)
	  {	
	$(show_div).css("top",$(window).scrollTop()+mus_top);
	$(show_div).css("left",($("body").get(0).offsetWidth/2)-($(show_div).get(0).offsetWidth/2)+mus_left);
	  }	  
}

function close_div(e)
{
    $(e).fadeOut(250)	
}

var d=0;
var x;
var y;
var action_event;
function down_notice(e,eve)
{
    d=e;
  action_event=window.event?event:eve;
  x=action_event.clientX-($(d).get(0).style.left.replace("px",""));
  y=action_event.clientY-($(d).get(0).style.top.replace("px",""));	
}  
function move(eve)
{
	action_event=window.event?event:eve;
	if(d==0)
	  {
	  return false
	  }
	else
	  {
	  $(d).get(0).style.left=(action_event.clientX-x)+"px"
	  $(d).get(0).style.top=(action_event.clientY-y)+"px"
	  }
}

function up()
{
  d=0;	
}

</script>
<?

include_once "_foot.php";
?>
