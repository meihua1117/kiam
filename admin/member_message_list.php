<?
/* ------------------------------------------------------------------------------

title: 셀링솔루션 관리자
sub_title: 수발신관리
update line is from 193 line
update : 2020.05.20
updated by rturbo
-------------------------------------------------------------------------------*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
var mem_id="";
function page_view(mem_id) {
    $('.ad_layer1').lightbox_me({
    	centered: true,
    	onLoad: function() {
    		$.ajax({
    			type:"POST",
    			url:"/admin/ajax/member_list_page1.php",
    			data:{mem_id:mem_id},
    			dataType: 'html',
    			success:function(data){
    				$('#phone_list').html(data);
    			},
    			error: function(){
    			  alert('로딩 실패');
    			}
    		});			    
    	}
    });
    $('.ad_layer1').css({"overflow-y":"auto", "height":"300px"});
}
$(function(){
});

//폰정보 수정
function modify_phone_info(){

	var phno = $("#pno").val();

	if(!phno){
		alert('폰 정보가 없습니다.');
		return false;
	}else{
		$.ajax({
			type:"POST",
			url:"ajax/modify_phoneinfo.php",
			data:{
				pno:phno,
				name:$("#detail_name").val(),
				company:$("#detail_company").val(),
				rate:$("#detail_rate").val()
			},
			success:function(data){
				location.reload();
			},
			error: function(){
			  alert('수정 실패');
			}
		});		
	}
}

//계정 삭제
function del_member_info(mem_code){

	var msg = confirm('정말로 삭제하시겠습니까?');

	if(msg){

			$.ajax({
				type:"POST",
				url:"/admin/ajax/user_leave.php",
				data:{mem_code:mem_code},
				success:function(){
					alert('삭제되었습니다.');
					location.reload();
				},
				error: function(){
				  alert('삭제 실패');
				}
			});		

	}else{
		return false;
	}
}

//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}


function goPage(pgNum) {
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&case=<?php echo $_GET['case'];?>";
}

//주소록 다운
function excel_down_p_group(pno,one_member_id){
	$($(".loading_div")[0]).show();
	$($(".loading_div")[0]).css('z-index',10000);
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yy = today.getFullYear().toString().substr(2,2);
	if(dd<10) {
		dd='0'+dd
	} 
	if(mm<10) {
		mm='0'+mm
	} 

	$.ajax({
	 type:"POST",
	 dataType : 'json',
	 url:"/ajax/ajax_session_admin.php",
	 data:{
			group_create_ok:"ok",
			group_create_ok_nums:pno,
			group_create_ok_name:pno.substr(3,8)+'_'+''+ mm+''+dd,
			one_member_id:one_member_id
		  },
	 success:function(data){
	 	$($(".loading_div")[0]).hide();
	 	$('#one_member_id').val(one_member_id);
	 	parent.excel_down('/excel_down/excel_down_.php?down_type=1&one_member_id='+one_member_id,data.idx);
	 }

	});	
}


</script>   
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
    <div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>      
    <div class="wrapper" style = "display:flex;overflow:initial">
      <!-- Left 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            공유메시지관리
            <small>공유메시지를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">메시지관리</li>
          </ol>
        </section>
        
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
        <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />        
        </form>                

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
              <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_member_down_.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
              <?php }?>
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/휴대폰/발신내용">
                  <div class="input-group-btn">
                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
              </div>            
              </div>            
          </div>
          
          <div class="ad_layer1" style="display:none" style="overflow-y:auto !important;height:150px !important;">
              <table id="phone_table" class="table table-bordered table-striped" style="background:#fff !important">
                 <colgroup>
                  <col width="60px">
                  <col width="100px">
                  <col width="180px">
                </colgroup>
                <thead>
                  <tr>
                    <th>번호</th>
                    <th>기부폰</th>
                    <th>설치일자</th>
                  </tr>
                </thead>
                <tbody id="phone_list">
                    
                </tbody>
                </table>
          </div>
          
          <div class="row">
            
              <div class="box">
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="60px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="120px">
                      <col width="100px">
                      <col width="50px">
                      <col width="50px">
                      <col width="50px">
                      <col width="50px">
                      <col width="100px">

                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>아이디</th>
                        <th>발신번호</th>
                        <th>작성자명</th>
                        <th>제목</th>
                        <th>내용</th>
                        <th>대상</th>
                        <th>발송결과</th>
                        <th>발송일시</th>                        
                        <th>회신비율</th>
                        <th>신청비율</th>
                        <th>클릭비율</th>
                        <th>총반응율</th>
                        <th>수정<br>삭제</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (a.mem_id LIKE '".$search_key."%' or a.send_num like '".$search_key."%' or a.content like '".$search_key."%' )" : null;
                    if($case == 1 ) {
                        $searchStr .= " and title = 'app_check_process'";
                    } else if($case == 2) {
                        $searchStr .= " and title != 'app_check_process'";
                    }
                	
                	$order = $order?$order:"desc"; 		
                	
                	$query = "
                        	SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	    a.*
                        	FROM Gn_MMS a
                        	WHERE 1=1 
                	              $searchStr";
                	              
                	$res	    = mysqli_query($self_con,$query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                	
                    $orderQuery .= "
                    	ORDER BY a.idx DESC
                    	$limitStr
                    ";            	
                	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con,$query);
                    while($row = mysqli_fetch_array($res)) {                       	
                        $sql_s="select * from Gn_MMS_status where idx='{$row['idx']}' ";
						$resul_s=mysqli_query($self_con,$sql_s);
						$row_s=mysqli_fetch_array($resul_s);
						mysqli_free_result($resul_s);
																    
						$sql_n="select memo from Gn_MMS_Number where sendnum='{$row['send_num']}' ";
						$resul_n=mysqli_query($self_con,$sql_n);
						$row_n=mysqli_fetch_array($resul_n);
						mysqli_free_result($resul_n);
						
						$recv_num = $recv_cnt=explode(",",$row['recv_num']);
						$recv_num_in = "'".implode("','", $recv_num)."'";
						$date = $row['up_date'];

						$sql="select count(seq) as cnt from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 and  send_num='{$row['send_num']}' and recv_num in ($recv_num_in) and recv_num like '01%'  and regdate >= '$date' and sms not like '[%'";
						$kresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
						$krow=mysqli_fetch_array($kresult);
						$intRowCount=$krow['cnt'];											
						
        				$sql_as="select count(idx) as cnt from Gn_MMS_status where idx='{$row['idx']}' ";
        				$resul_as=mysqli_query($self_con,$sql_as);
        				$row_as=mysqli_fetch_array($resul_as);
        				$status_total_cnt = $row_as[0];											
        				
        				$sql_cs="select count(idx) as cnt from Gn_MMS_status where idx='{$row['idx']}' and status='0'";
        				$resul_cs=mysqli_query($self_con,$sql_cs);
        				$row_cs=mysqli_fetch_array($resul_cs);
        				$success_cnt = $row_cs[0];

        				$sql_sn="select * from Gn_MMS where idx='{$row['idx']}' ";
        				$resul_sn=mysqli_query($self_con,$sql_sn);
        				$row_sn=mysqli_fetch_array($resul_sn);									
        				$recv_cnt=explode(",",$row_sn['recv_num']);        				
        				$total_cnt = count($recv_cnt);			 

                        $sql_gn="select * from Gn_MMS_group where idx='{$row['idx']}' ";
        				$resul_gn=mysqli_query($self_con,$sql_gn);
                        $row_gn=mysqli_fetch_array($resul_gn);	
						mysqli_free_result($resul_gn); 
						$reg_date_1hour = strtotime("$row[reg_date] +1hours"); 
								
                  ?>
                      <tr>
                        <td><?=$number--?></td>
    					<td><?=$row['mem_id']?></td>											
                        <td><?=$row['send_num']?></td>
                        <td><?=$row_n['memo']?></td>
                        <td style="font-size:12px;"><a href="javascript:void(0)" onclick="show_recv('show_title','<?=$c?>','문자제목')"><?=str_substr($row['title'],0,30,'utf-8')?></a><input type="hidden" name="show_title" value="<?=$row['title']?>"/></td>
                        <td style="font-size:12px;"><a href="javascript:void(0)" onclick="show_recv('show_content','<?=$c?>','문자내용')"><?=str_substr($row['content'],0,30,'utf-8')?></a><input type="hidden" name="show_content" value="<?=$row['content']?>"/></td>
                        <td style="font-size:12px;"><?=substr($row[grp])?></td>
    					<td>
    					    <?if($row[reservation]) {?>
    					    예약
    					    <?}?>
    					    <?if($success_cnt==0){?>
								<?if(time() > $reg_date_1hour && $row['up_date'] == "") {?>
									<?php if($row[reservation] > date("Y-m-d H:i:s")){?>
									<?}else{?>
										실패
									<?}?>
								<?}else{?>
									<?if(time() > $reg_date_1hour && $row_s['up_date'] == "") {?>
										발송실패
									<?}else{?>
										발송중
									<?}?>
								<?}?>
    					    <?}else{?>
    					        <?=$success_cnt?>/<?php echo $total_cnt-$success_cnt;?>
    					    <?}?>
    					    </td>
                        <td style="font-size:12px;"><?=substr($row[reg_date],0,16)?></td>
    					<td style="font-size:12px;"><a href="member_return_detail.php?idx=<?php echo $row['idx']?>&send_num=<?=$row['send_num']?>"><?=$intRowCount;?></a> </td>           
                      </tr>
                    <?
                    $c++;
                    $i++;
                    }
                    if($i == 1) {
                    ?>
                        <tr>
                            <td colspan="10" style="text-align:center;background:#fff">
                    	        등록된 내용이 없습니다.
                            </td>
                        </tr>
                    <?
                    }
                    ?> 
                       
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                	<?
                    	echo drawPagingAdminNavi($totalCnt, $nowPage);
                    ?>
                </div>
            </div>
          </div><!-- /.row -->
          
          

        
          
          
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	

<script language="javascript">
function changeLevel(mem_code) {
    var mem_leb = $('#mem_leb'+mem_code+" option:selected").val();
    var data = {"mode":"change","mem_code":"'+mem_code+'","mem_leb":"'+mem_leb+'"};
    $.ajax({
		type:"POST",
		url:"/admin/ajax/user_level_change.php",
		dataType:"json",
		data:{mode:"change",mem_code:mem_code,mem_leb:mem_leb},
		success:function(data){
		    //console.log(data);
		    //location = "/";
			//location.reload();
			alert('변경이 완료되었습니다.');
		},
		error: function(){
		  alert('초기화 실패');
		}
	});	
    
//    alert(mem_code);
}

function loginGo(mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#login_form').submit();
}
</script>
      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_recv_title open_2_1"></li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
<script>
function show_recv(name,c,t,status)
{
    console.log(name);console.log(c);
    ;
	if(!document.getElementsByName(name)[c].value)
	return;
	open_div(open_recv_div,100,1,status);
	if(name=="show_jpg")
	$($(".open_recv")[0]).html("<img src='"+document.getElementsByName(name)[c].value+"' />");
	else if(name=="show_jpg1")
	    $($(".open_recv")[0]).html("<img src='"+document.getElementsByName(name)[c].value+"' />");
	else if(name=="show_jpg2")
	    $($(".open_recv")[0]).html("<img src='"+document.getElementsByName(name)[c].value+"' />");
	else
	$($(".open_recv")[0]).html(document.getElementsByName(name)[c].value.replace(/\n/g,"<br/>"));
	$($(".open_recv_title")[0]).html(t);	
}    
</script>