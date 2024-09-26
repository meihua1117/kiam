<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
$(function(){
  $('.check').on("click",function(){
		if($(this).prop("id") == "check_all_member"){
			if($(this).prop("checked"))
				$('.check').prop("checked",true);
			else
				$('.check').prop("checked",false);
		}else if($(this).prop("id") == "check_one_member"){
			if(!$(this).prop("checked"))
				$('#check_all_member').prop("checked",false);
		}
	});
});
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 250;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
function deleteMultiRow() {
	var check_array = $("#example1").children().find(".check");
	var no_array = [];
	var index = 0;
	check_array.each(function(){
		if($(this).prop("checked") && $(this).val() > 0)
			no_array[index++] = $(this).val();
	});

	if(no_array.length == 0){
		alert("삭제할 회원을 선택하세요.");
		return;
	}
	if(confirm('삭제하시겠습니까?')) {
		$.ajax({
      type:"POST",
      url:"/admin/ajax/manage_customers.php",
      dataType:"json",
      data:{mode:'del', delete_name:"req_cust_list", id:no_array.toString()},
      success: function(data){
        console.log(data);
        if(data == 1){
          alert('삭제 되었습니다.');
          window.location.reload();
        }
      }
    });
	}
}

//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}


  function goPage(pgNum) {
    location.href = '?&nowPage='+pgNum+"&search_userid=<?=$_GET[search_userid]?>&search_comtype=<?=$_GET[search_comtype]?>&search_job=<?=$_GET[search_job]?>&search_addr2=<?=$_GET[search_addr2]?>&search_addr1=<?=$_GET[search_addr1]?>";
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
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
}
#open_recv_div li{list-style: none;}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
/* user agent stylesheet */
input[type="checkbox" i] {
    background-color: initial;
    cursor: default;
    -webkit-appearance: checkbox;
    box-sizing: border-box;
    margin: 3px 3px 3px 4px;
    padding: initial;
    border: initial;
}
input:checked + .slider {
    background-color: #2196F3;
}
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}
input:checked + .slider {
    background-color: #2196F3;
}
.slider.round {
    border-radius: 34px;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}
input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}
.slider.round:before {
    border-radius: 50%;
}
.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;

}

.disagree{
     background: #ffd5d5!important;   
    }
    th a.sort-by { 
	padding-right: 18px;
	position: relative;
}
a.sort-by:before,
a.sort-by:after {
	border: 4px solid transparent;
	content: "";
	display: block;
	height: 0;
	right: 5px;
	top: 50%;
	position: absolute;
	width: 0;
}
a.sort-by:before {
	border-bottom-color: #666;
	margin-top: -9px;
}
a.sort-by:after {
	border-top-color: #666;
	margin-top: 1px;
}
.zoom {
  transition: transform .2s; /* Animation */
}

.zoom:hover {
  transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  border:1px solid #0087e0;
  box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
}

.zoom-2x {
  transition: transform .2s; /* Animation */
}

.zoom-2x:hover {
  transform: scale(2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  border:1px solid #0087e0;
  box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
</style>
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
            신청고객 정보관리
            <small>신청고객의 정보를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">신청고객 정보관리</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
              <button class="btn btn-primary pull-right" style="margin-right: 5px;background-color:#aba9a9;border: none;" onclick="location='customer_get_list.php';return false;">수집고객 정보관리</button>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;border: none;" onclick="location='customer_req_list.php';return false;">신청고객 정보관리</button>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;background-color:#aba9a9;border: none;" onclick="location='customer_reg_list.php';return false;">등록고객 정보관리</button>
              <!--<button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_member_down_.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>-->
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 560px;">
                  <input type="text" name="search_addr1" id="search_addr1" class="form-control input-sm pull-right" placeholder="자택주소" style="width:105px;" value="<?=$search_addr1?>">
                  <input type="text" name="search_addr2" id="search_addr2" class="form-control input-sm pull-right" placeholder="업체주소" style="width:105px;" value="<?=$search_addr2?>">
                  <input type="text" name="search_job" id="search_job" class="form-control input-sm pull-right" placeholder="직책" style="width:105px;" value="<?=$search_job?>">
                  <input type="text" name="search_comtype" id="search_comtype" class="form-control input-sm pull-right" placeholder="업종" style="width:105px;" value="<?=$search_comtype?>">
                  <input type="text" name="search_userid" id="search_userid" class="form-control input-sm pull-right" placeholder="유저아이디" style="width:105px;" value="<?=$search_userid?>">
                  <div class="input-group-btn">
                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
              </div>            
              </div>            
          </div>
          
          <!-- <form method="post" action="/admin/ajax/ad_sort_save.php" id="ssForm" name="ssForm"> -->
          <div class="row">
            
              <div class="box">
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="4%">
                      <col width="5%">
                      <col width="5%">
                      <col width="5%">
                      <col width="5%">
                      <col width="5%">
                      <col width="6%">
                      <col width="6%">
                      <col width="6%">
                      <col width="6%">
                      <col width="6%">
                      <col width="6%">
                      <col width="6%">
                      <col width="6%">
                      <col width="10%">
                      <col width="7%">

                     </colgroup>
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="check" id="check_all_member" value="0">번호</th>
                        <th>유저아이디</th>
                        <th>이름</th>
                        <th>휴대폰</th>
                        <th>일반폰</th>
                        <th>이메일</th>
                        <th>생년월일</th>
                        <th>업종</th>
                        <th>업체명</th>
                        <th>직책</th>
                        <th>업체주소</th>
                        <th>자택주소</th>
                        <th>링크</th>
                        <th>등록일시</th>
                        <th>메모</th>
                        <th><button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow()">삭제</button></th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                  // 검색 조건을 적용한다.
                  $searchStr .= $search_userid ? " AND m_id LIKE '%".$search_userid."%'" : null;
                  $searchStr .= $search_comtype ? " AND work_type LIKE '%".$search_comtype."%'" : null;
                  $searchStr .= $search_job ? " AND job LIKE '%".$search_job."%'" : null;
                  $searchStr .= $search_addr2 ? " AND addr LIKE '%".$search_addr2."%'" : null;
                  $searchStr .= $search_addr1 ? " AND addr1 LIKE '%".$search_addr1."%'" : null;
                	
                	$order = $order?$order:"desc"; 		
                	
                	$query = "
                          SELECT 
                          SQL_CALC_FOUND_ROWS 
                          a.*, b.short_url 
                      FROM Gn_event_request a inner join Gn_event b on a.event_idx=b.event_idx
                      WHERE 1=1 
                            $searchStr";
                	              
                	$res	    = mysqli_query($self_con, $query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                    $orderQuery .= "
                    	ORDER BY request_idx DESC
                    	$limitStr
                    ";            	
                	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con, $query);
                    while($row = mysqli_fetch_array($res)) {                       	
                      $sql_mem_reg = "select * from Gn_Member where mem_name='{$row['name']}' and mem_phone='{$row['mobile']}' and is_leave='N' limit 1";
                      $res_mem_reg = mysqli_query($self_con, $sql_mem_reg);
                      $row_mem_reg = mysqli_fetch_array($res_mem_reg);
                      $mem_code = '';
                      if($row_mem_reg['mem_code'] != ''){
                        $edit_type = "member_req_edit";
                        $mem_code = $row_mem_reg['mem_code'];
                        $user_id = $row_mem_reg['mem_id']?$row_mem_reg['mem_id']:'';
                        $name = $row_mem_reg['mem_name']?$row_mem_reg['mem_name']:'';
                        $phone1 = $row_mem_reg['mem_phone']?$row_mem_reg['mem_phone']:'';
                        $phone2 = $row_mem_reg['mem_phone1']?$row_mem_reg['mem_phone1']:'';
                        $email = $row_mem_reg['mem_email']?$row_mem_reg['mem_email']:'';
                        $birthday = $row_mem_reg['mem_birth']?$row_mem_reg['mem_birth']:'';
                        $work_type = $row_mem_reg['com_type']?$row_mem_reg['com_type']:'';
                        $company_name = $row_mem_reg['zy']?$row_mem_reg['zy']:'';
                        $job = $row_mem_reg['mem_job']?$row_mem_reg['mem_job']:'';
                        $company_addr = $row_mem_reg['com_add1']?$row_mem_reg['com_add1']:'';
                        $home_addr = $row_mem_reg['mem_add1']?$row_mem_reg['mem_add1']:'';
                        $memo = $row_mem_reg['mem_memo']?$row_mem_reg['mem_memo']:'';
                        $reg_date = $row_mem_reg['first_regist']?$row_mem_reg['first_regist']:'';

                        $query = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$row_mem_reg['mem_id']}' order by req_data asc";
                        $cres = mysqli_query($self_con, $query);
                        $crow = mysqli_fetch_array($cres);
                        $card_url = $crow[0];

                        $link = "card_list.php?mem_id=".$row_mem_reg['mem_id'];
                      }
                      else{
                          $edit_type = "req_cust_edit";
                          $idx = $row['request_idx'];
                          $user_id = $row['m_id']?$row['m_id']:'';
                          $name = $row['name']?$row['name']:'';
                          $phone1 = $row['mobile']?$row['mobile']:'';
                          $phone2 = $row['mobile1']?$row['mobile1']:'';
                          $email = $row['email']?$row['email']:'';
                          $birthday = $row['birthday']?$row['birthday']:'';
                          $work_type = $row['work_type']?$row['work_type']:'';
                          $company_name = $row['company_name']?$row['company_name']:'';
                          $job = $row['job']?$row['job']:'';
                          $company_addr = $row['addr']?$row['addr']:'';
                          $home_addr = $row['addr1']?$row['addr1']:'';
                          $link = $card_url = $row['short_url']?$row['short_url']:'';
                          $memo = $row['memo']?$row['memo']:'';
                          $reg_date = $row['regdate']?$row['regdate']:'';
                      }
                  ?>
                      <tr>
                        <td><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['request_idx']?>"><?=$number--?></td>
                        <td><a href="javascript:show_user_info('<?=$user_id?>')"><?php echo $user_id?></a></td>
                        <td><?php echo $name?></td>
                        <td><?php echo $phone1?></td>
                        <td><?php echo $phone2?></td>
                        <td><?php echo $email?></td>
                        <td><?php echo $birthday?></td>
                        <td><?php echo $work_type?></td>
                        <td><?php echo $company_name?></td>
                        <td><?php echo $job?></td>
                        <td><a href="javascript:show_str('<?=$company_addr?>')"><?php echo cut_str($company_addr, 20)?></a></td>
                        <td><a href="javascript:show_str('<?=$home_addr?>')"><?php echo cut_str($home_addr, 20)?></a></td>
                        <td><a href="<?=$link?>" target="_blank"><?php echo cut_str($card_url, 10)?></a></td>
                        <td><?php echo $reg_date?></td>
                        <td><textarea><?php echo $memo?></textarea></td>
                        <td> <a href="customer_info_detail.php?idx=<?=$row['request_idx']?>&edit_type=<?=$edit_type?>&mem_code=<?=$mem_code?>&pre_page=req_list">수정</a>/<a href="javascript:del_member_info('<?=$row['request_idx']?>')">삭제</a>/<a href="javascript:move_reg_list('<?=$row['request_idx']?>', 'req_cust')">이동</a> </td>
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
            <!-- </form> -->
            
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
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>  
      </div><!-- /content-wrapper -->

      <div id="show_address" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background: #5bd540;">
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 15px;font-weight: bold;margin-top: -10px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
                        <p style="font-size:16px;color:#6e6c6c" id="customer_address">
                        </p>
                    </div>
                </div>
            </div>
        </div>
      </div>

      <div id="show_user_info_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:400px">
            <!-- Modal content-->
            <div class="modal-content">
                <div class = "modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                    <label style="padding: 10px 0px;margin: 0px;">유저 정보</label>
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 17px;font-weight: bold;margin-top: 5px;margin-right:10px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: left;width: 70%;">
                        <p style="font-size:16px;color:#6e6c6c">
                          이름 : <span id="mem_id_cust"></span><br>
                          셀링소속 : <span id="mem_site_cust"></span><br>
                          아이엠소속 : <span id="mem_iam_cust"></span><br>
                          휴대폰 : <span id="mem_phone_cust"></span><br>
                          사업자 : <span id="mem_service_type_cust"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
      </div>

<script language="javascript">
//계정 삭제
function del_member_info(idx){
  var msg = confirm('정말로 삭제하시겠습니까?');
  if(msg){
      $.ajax({
        type:"POST",
        url:"/admin/ajax/manage_customers.php",
        data:{id:idx, mode:'del', delete_name:"req_cust_list"},
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

function show_str(val){
  $("#customer_address").html(val);
  $("#show_address").modal('show');
}

function move_reg_list(val, type){
    var msg = "이동 하시겠습니까?";

    if(confirm(msg)){
        $.ajax({
            type:"POST",
            url:"/admin/ajax/manage_customers.php",
            data:{mode:'move',type:type,idx:val},
            success:function(data){
                alert('이동되었습니다.');
                location.reload();
            },
            error: function(){
                alert('이동 실패');
            }
        });
    }
}

function show_user_info(id){
  $.ajax({
		type:"POST",
		url:"/ajax/get_mem_address.php",
		data:{mem_id:id, mode:'get_user_info'},
    dataType:'json',
		success:function(data){
      console.log(data);
      $("#mem_id_cust").text(data.mem_name);
      $("#mem_site_cust").text(data.site);
      $("#mem_iam_cust").text(data.site_iam);
      $("#mem_phone_cust").text(data.mem_phone);
      var type = '';
      if(data.service_type == '0'){
        type = "FREE";
      }
      if(data.service_type == '1'){
        type = "이용자";
      }
      if(data.service_type == '2'){
        type = "리셀러";
      }
      if(data.service_type == '3'){
        type = "분양자";
      }
      // if(data.service_type == '4'){
      //   type = "관리자";
      // }

      $("#mem_service_type_cust").text(type);
      $("#show_user_info_modal").modal("show");
		},
		error: function(){
			alert('유저정보 얻기 실패');
		}
  });
}
</script>
          
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
