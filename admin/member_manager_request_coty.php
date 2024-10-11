<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>


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
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
user agent stylesheet
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
.agree{
     /*background: #d5ffd5!important;   */
    }
.disagree{
     background: #ffd5d5!important;   
    }
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
</style>


<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 246;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
$(function(){
  $('.chkagree').click(function(e) {
    var msg = this.checked?"승인할가요?":"해치할가요?";
    if(!confirm(msg)) {
      e.preventDefault();
      return;
    }
  }); 
  $('.chkagree').change(function() {

    var id = $(this)[0].id;
    var status = $(this)[0].checked;

    if($(this)[0].checked){
      status = 1;
    }else{
      status = 0;
    }

    var coty_id = id.split("_")[2];

    $.ajax({
         type:"POST",
         url:"/admin/ajax/update_coach_status.php",
         data:{
            mode:"update_coaching_agree",coty_id:coty_id, status:status
          },
         dataType: 'html',
         success:function(data1){
           //$('#phone_list').html(data);
           alert("저장되었습니다.");
           location.reload(true);
         },
         error: function(){
           alert('로딩 실패');
         }
       });            
  }); 


});
 

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
   location.href = '?<?=$nowPage?>&nowPage='+pgNum;
  }
</script>   
     
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
            수강신청관리
            <small>수강신청회원을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">수강신청관리</li>
          </ol>
        </section>
        
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
        <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />        
        </form>                

        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름"
                  value="<?=$search_key?>" 
                  >
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
                      <col width="100px">
                      <col width="150px">
                      <col width="100px">

                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <!-- <th>회원정보</th> -->
                        <th>신청자ID</th>
                        <th>신청자이름</th>
                        <th>신청자직급</th>
                        <th>유효기간</th>
                        <th>코칭시간</th>
                        <th>코칭비용</th>
                        <th>희망코칭</th>
                        <th>신청일시</th>
                        <th>코치배정</th>
                        <th>승인확인</th>
                        <th>승인일시</th>
                        <th>삭제</th>                        
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                    // 검색 조건을 적용한다.
                  $searchStr .= $search_key ? " AND (b.mem_id LIKE '%".$search_key."%' or b.mem_phone like '%".$search_key."%' or b.mem_name like '%".$search_key."%' )" : null;
                	$order = $order?$order:"desc"; 		
                	$query = "
                        	SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	    *
                        	FROM gn_coaching_apply a
                          INNER JOIN Gn_Member b
                                 on b.mem_code =a.mem_code
                        	WHERE 1=1 
                	              $searchStr";
                	      //echo $query."<br>";        
                	$res	    = mysqli_query($self_con,$query) or die(mysqli_error($self_con));

                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                    $orderQuery .= "
                    	ORDER BY a.reg_date DESC
                    	$limitStr
                    ";            	
                	
                	$i = 1;
                	$query .= "$orderQuery";
                  //echo $query;

                	$res = mysqli_query($self_con,$query);
                  while($row = mysqli_fetch_array($res)) {                       	
                    	
                    

                  ?>
                      
                     <tr class="<? if($row[agree]==0){
                        echo "disagree";
                     }else{
                        echo "agree";
                     }
                      ?>">

                        <td><?=$number--?></td>
                        <!-- <td>
                         
                            <a href="member_manager_detail.php?mem_code=<?=$sData['mem_code']?>&sendnum=<?=$row['sendnum']?>">수정</a> / 
                            <a href="javascript:del_member_info('<?=$sData['mem_code']?>&sendnum=<?=$row['sendnum']?>')">탈퇴</a></td> -->
                        <td><?=$row['mem_id']?></td>
                        <td><?=$row['mem_name']?></td>
                        <td><? if($row['service_type'] == "0"){echo "FREE";}
                              else if($row['service_type'] == "1"){echo "이용자";}
                              else if($row['service_type'] == "2"){echo "리셀러";}    
                              else if($row['service_type'] == "3"){echo "분양자";} ?>      
                        </td>
                        <td><?=$row['cont_term']?>일</td>
                        <td><?=$row['cont_time']?>시간</td>
                        <td><?=$row['coaching_price']/10000?>만원</td>
                        <td><?=$row['want_coaching']?></td>
                        <td><?=$row['reg_date']?></td>
                        <td>
                            <select id="coach_select_<?=$row['coty_id']?>" >
                                <option value="-1">없음</option>
                                <? $coach_sql = "select * from gn_coach_apply a inner join Gn_Member b on b.mem_code = a.mem_code where a.agree= 1";
                      
                                $coach_res = mysqli_query($self_con,$coach_sql);
                                while($coach_row = mysqli_fetch_array($coach_res)) {   
                                  ?>
                                      <option value="<?=$coach_row['coach_id']?>">[<?=$coach_row['mem_id']  ?>] <?=$coach_row['mem_name']  ?></option>
                                  <?
                                }
                                 ?>
                            </select>   
                            <input type="button" name="변경" value=" 변경 " onclick="changeCoach('<?=$row['coty_id']?>')">


                            <script type="text/javascript">
                              $("#coach_select_<?=$row['coty_id']?>").val("<?=$row['coach_id']?>");
                            </script>
                        </td>    

                         <td style="font-size:12px;">
                            <label class="switch">
                              <input type="checkbox" class="chkagree" name="status" id="coty_id_<?php echo $row['coty_id'];?>"<?php echo $row['agree']==1?"checked":""?> >
                              <span class="slider round" name="status_round" id="stauts_round_<?php echo $row['lecture_id'];?>"></span>
                            </label>                            
                        </td>                       
                        <td><?=$row['agree_date']?></td>
                        <td><a href="javascript:delContent('<?=$row['coty_id']?>')">삭제</a></td>

                      </tr>
                    <?
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
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?> 
      </div><!-- /content-wrapper -->

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	

<script language="javascript">
function loginGo(mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#login_form').submit();
}


function changeCoach(coty_id) {

    var data = {
      mode:"coty_update_coach_id",
      coty_id:coty_id,
      coach_id:$("#coach_select_"+coty_id).val()
    };

    $.ajax({
  		type:"POST",
  		url:"/admin/ajax/update_coach_status.php",
  		dataType:"json",
  		data:data,
  		success:function(data){
  			alert('변경이 완료되었습니다.');
  			location.reload();
  		},
  		error: function(){
  		  alert('변경실패');
  		}
  	});	
    
}

</script>
           