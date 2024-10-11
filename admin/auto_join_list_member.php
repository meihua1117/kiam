<?
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
    <div class="wrapper">

      <!-- Top 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>      
      
      <!-- Left 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            오토회원 가입 리스트
            <small>오토회원 가입 리스트를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">오토회원 가입 리스트</li>
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
               <div style="padding:10px">
              </div>
              <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
              <?php }?>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='step_event_list.php';return false;"><i class="fa fa-download"></i> 이벤트 신청 리스트</button>
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이벤트영문명/이벤트한글명/신청경로">
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
                      <col width="3%">
                      <col width="6%">
                      <col width="6%">
                      <col width="6%">
                      <col width="10%">
                      <col width="15%">
                      <col width="5%">
                      <col width="10%">
                      <col width="5%">
                      <col width="5%">

                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>도메인</th>
                        <th>아이디</th>
                        <th>이름</th>
                        <th>타이틀</th>
                        <th>콘텐츠</th>
                        <th>미리보기</th>
                        <th>조회/해지</th>
                        <th>등록일</th>
                        <th>수정/삭제</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	$memname = "";
                  $memsite = "";
                  $mem_select = "";
                  $memjoin = "";
                    // 검색 조건을 적용한다.
                  $searchStr .= $search_key ? " AND (b.event_title LIKE '%".$search_key."%' or b.event_desc like '%".$search_key."%' )" : null;
                  if($mem_name){
                    $mem_select = ", c.* ";
                    $memjoin = " inner join Gn_Member c on b.m_id=c.mem_id ";
                    $memname = " AND (b.m_id LIKE '%".$mem_name."%')";
                  }
                  if($mem_site){
                    $mem_select = ", c.* ";
                    $memjoin = " inner join Gn_Member c on b.m_id=c.mem_id ";
                    $memsite = " AND (c.site LIKE '%".$mem_site."%')";
                  }
                	
                  $sql = "select b.* ".$mem_select." from Gn_event b ".$memjoin. " where event_name_kor='단체회원자동가입및아이엠카드생성' ".$searchStr.$memname.$memsite;
                  $res	    = mysqli_query($self_con,$sql);
                  $totalCnt	=  mysqli_num_rows($res);

                  $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;

                  $orderQuery .= "
                    ORDER BY event_idx DESC
                    $limitStr
                  ";

                	$i = 1;
                	$c=0;
                  $sql .= "$orderQuery";
                	$res = mysqli_query($self_con,$sql);
                    while($row = mysqli_fetch_array($res)) {
                      $sql_site = "select site, mem_name from Gn_Member where mem_id='{$row['m_id']}'";
                      $res_site = mysqli_query($self_con,$sql_site);
                      $row_site = mysqli_fetch_array($res_site);
                      if($row['allow_state'] == 1){
                          $checked = "checked";
                      }
                      else{
                          $checked = "";
                      }

                      $sql_sel_service_mem = "select * from Gn_Member where mem_callback={$row[idx]}";
                      $res_sel_service = mysqli_query($self_con,$sql_sel_service_mem);
                      $cnt_sel_service = mysqli_num_rows($res_sel_service);

                      $id_sql = "select count(event_id) as cnt from Gn_Member where event_id={$row['event_idx']} and mem_type='A'";
                      $res_id = mysqli_query($self_con,$id_sql);
                      $row_id = mysqli_fetch_array($res_id);
                      if($row_id['cnt'] != null){
                          $cnt_join = $row_id['cnt'];
                      }
                      else{
                          $cnt_join = 0;
                      }

                      $sql_sel_mem = "select mem_id, mem_name, phone_callback, mun_callback from Gn_Member where mem_callback={$row[idx]} and (phone_callback={$row['idx']} or mun_callback={$row[idx]})";
                      $res_sel_mem = mysqli_query($self_con,$sql_sel_mem);
                      $cnt_sel_mem = mysqli_num_rows($res_sel_mem);

                      $cnt_unsel_mem = $cnt_sel_service * 1 - $cnt_sel_mem * 1;
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td><?php echo $row_site[0]?></td>
                        <td><?php echo $row['m_id']?></td>
                        <td><?php echo $row_site[1]?></td>
                        <td><?php echo cut_str($row['event_title'], 25)?></td>
                        <td><?php echo cut_str($row['event_desc'], 60)?></td>
                        <td>
                          <input type="button" value="미리보기" class="button" onclick="newpop('<?php echo $row['short_url']?>')"><br>
                          <input type="button" value="링크복사" class="button copyLinkBtn" onclick="copyHtml('<?php echo $row['short_url']?>')">
                        </td>
                        <td><?php echo $row['read_cnt']?> / <?=$cnt_join?></td>
                        <td><?php echo $row['regdate']?></td>           
                        <td> <a href="auto_join_list_detail.php?idx=<?=$row['event_idx']?>">수정</a>/<a href="javascript:delete_ev(<?=$row['event_idx']?>)">삭제</a> </td>
                      </tr>
                    <?
                    $c++;
                    $i++;
                    }
                    if($i == 1) {
                    ?>
                        <tr>
                            <td colspan="11" style="text-align:center;background:#fff">
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
function newpop(str){
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}
function copyHtml(url){
    //oViewLink = $( "ViewLink" ).innerHTML;
    ////alert ( oViewLink.value );
    //window.clipboardData.setData("Text", oViewLink);
    //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 소스코드를 복사하시겠습니까?")) {
            window.clipboardData.setData("Text", url);
        } 
    } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", url);
    }

}
function delete_ev(id){
    if(confirm("삭제하시겠습니까?")){
        $.ajax({
            type:"POST",
            url:"/ajax/edit_event.php",
            dataType:"html",
            data:{del:true, id:id},
            success: function(data){
                console.log(data);
                if(data == 1){
                    alert('삭제 되었습니다.');
                    window.location.reload();
                }
            }
        })
    }
    else{
        return;
    }
}
</script>