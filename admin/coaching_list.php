<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 256;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
$(function(){
    $('.switch').on("change", function() {
        var no = $(this).find("input[type=checkbox]").val();
        var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
		$.ajax({
			 type:"POST",
			 url:"/admin/ajax/lecture.proc.php",
			 data:{
				 mode:"update_status",
				 lecture_id:no,
				 status:status,
				 },
			 success:function(data){
			    //alert('신청되었습니다.');location.reload();
			 }
			})    
    }); 
});

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
     background: #d5ffd5!important;   
    }
.disagree{
     background: #ffd5d5!important;   
    }
.pop_right {
    position: relative;
    right: 2px;
    display: inline;
    margin-bottom: 6px;
    width: 5px;
}    

.btn-comment {padding: 1px;padding-left: 5px;padding-right: 5px;}
.w200 {width:200px}
.list_table1 tr:first-child td{border-top:1px solid #CCC;}
.list_table1 tr:first-child th{border-top:1px solid #CCC;}
.list_table1 td{height:40px;border-bottom:1px solid #CCC;}
.list_table1 th{height:40px;border-bottom:1px solid #CCC;}
.list_table1 input[type=text]{width:600px;height:30px;}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
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
            코칭정보관리
            <small>코치/코티/코칭수행정보관리</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">코칭정보관리페이지</li>
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
            <div class="col-xs-6" style="padding-bottom:20px">
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
            <div class="col-xs-6" style="padding-bottom:20px">
               <div style="padding:10px">
              </div>
              <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
              <?php }?>
             <form method="get" name="search_form" id="search_form1">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;float:right;">    
                    <input type="radio" name="finish_type" onchange="finish_typeChanged()" value="" <?php echo $finish_type==""?"checked":""?>>전체
                    <input type="radio" name="finish_type" onchange="finish_typeChanged()" value="finish" <?php echo $finish_type=="finish"?"checked":""?>>진행완료
                    <input type="radio" name="finish_type" onchange="finish_typeChanged()" value="perform" <?php echo $finish_type=="perform"?"checked":""?>>진행중        
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

          <div class="alert alert-success fade in m-b-15">
              <strong> 자동승인! </strong> &nbsp;&nbsp;&nbsp;&nbsp;    
              <? 
              $convertedTime = date('Y-m-d',strtotime('-3 day',strtotime($date_today)));

              $sql1 = "update gn_coaching_info set agree = 1, site_value=3, coty_value=3, coaching_status=2  where reg_date  < '$convertedTime' and agree=0";
              $sql1_res = mysqli_query($self_con,$sql1) or die(mysqli_error($self_con));
              echo $convertedTime."  이전에 등록한 코칭정보가 자동승인이 되었습니다.";
              //echo $sql1;
                ?>
              <span class="close" data-dismiss="alert">×</span>
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
                      <col width="200px">
                      <col width="100px">
                      <col width="220px">
                      <col width="120px">
                      <col width="120px">
                      <col width="120px">
                     </colgroup>
                    <thead>
                      <tr>
                        <th style="width:2%;">No</th>
                        <th style="width:2%;">코칭<br>회차</th>
                        <th style="width:5%;">코치<br>ID</th>
                        <th style="width:5%;">코치<br>이름</th>
                        <th style="width:2%;">코티<br>ID</th>
                        <th style="width:4%;">코티<br>이름</th>           
                        <th style="width:7%;">신청<br>일시</th>
                        <th style="width:4%;">계약<br>기간</th>
                        <th style="width:5%;">계약<br>시간</th>
                        <th style="width:5%;">코칭<br>비용</th>
                        <th style="width:7%;">코칭<br>일시</th>
                        <th style="width:4%;">코칭<br>시간</th>
                        <th style="width:4%;">잔여<br>일시</th>
                        <th style="width:10%;">코칭<br>제목</th>
                        <th style="width:15%;">코칭<br>내용</th>            
                        <th style="width:10%;">첨부<br>파일</th>            
                        <th style="width:7%;">등록<br>일시</th>
                        <th style="width:4%;">코치<br>평가</th>   
                        <th style="width:4%;">코티<br>평가</th>
                        <th style="width:6%;">본사<br>평가</th>
                        <th style="width:6%;">평균<br>총점</th>
                        <th style="width:5%;">대기<br>승인</th>
                        <th style="width:4%;">수정<br>삭제</th>
                      </tr>
                    </thead>
                    <tbody>


                    

                  <?
                    

                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (a.search_text LIKE '%".$search_key."%' )" : null;
 
                    if($category) $searchStr .= " and category='$category'";


                    if($finish_type == "finish") 
                        $searchStr .= " and coaching_status=2";
                    if($finish_type == "perform") 
                        $searchStr .= " and coaching_status=1";
                	






                	$order = $order?$order:"desc"; 		
                	
                	$query = "
                        	SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	    a.*
                        	FROM gn_coaching_info a 
                        	WHERE 1=1 
                	              $searchStr";
                	              
                	$res	    = mysqli_query($self_con,$query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                	
                    $orderQuery .= "
                    	ORDER BY a.reg_date DESC
                    	$limitStr
                    ";            	
                	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";


                  // echo $query."<br>";

                	$res = mysqli_query($self_con,$query) or die(mysqli_error($self_con));



                    while($coaching_info_data = mysqli_fetch_array($res)) {                       	
                        $sql_num="select * from gn_coach_apply a left join Gn_Member b on a.mem_code = b.mem_code where a.coach_id='$coaching_info_data[coach_id]' ";
                        $resul_num=mysqli_query($self_con,$sql_num);
                        $coach_data=mysqli_fetch_array($resul_num); 

                        $sql_num="select * from gn_coaching_apply a left join Gn_Member b on a.mem_code = b.mem_code where a.coty_id='$coaching_info_data[coty_id]' ";
                        $resul_num=mysqli_query($self_con,$sql_num);
                        $coaching_data=mysqli_fetch_array($resul_num); 
                  ?>



                     <tr class="<? if($coaching_info_data[agree]==0){
                        echo "disagree";
                     }else{
                        echo "agree";
                     }
                      ?>">
                        <td><?=$number--?></td>
                        <td style="font-size:12px;"><?=$coaching_info_data[coaching_turn]?></td>
                        <td style="font-size:12px;"><?=$coach_data['mem_id']?></td>
                        <td style="font-size:12px;"><?=$coach_data['mem_name']?></td>
                        <td style="font-size:12px;"><?=$coaching_info_data[coty_id]?></td>
                        <td style="font-size:12px;"><?=$coaching_data['mem_name']?></td>
                        <td style="font-size:12px;"><?=$coaching_data[reg_date]?></td>
                        <td style="font-size:12px;color:red;"><?=$coaching_data[cont_term]?> 일</td>
                        <td style="font-size:12px;color:red;"><?=$coaching_data[cont_time]?> 시간</td>
                        <td style="font-size:12px;"><?=$coaching_data[coaching_price]/10000?>만원</td>
                        <td style="font-size:12px;"><?=$coaching_info_data[coaching_date]?></td>
                        <td style="font-size:12px;"><?=$coaching_info_data[coaching_time]?> 분</td>
                        <td style="font-size:12px;color:red;">
                              <?
                            // 잔여일시 계산

                            // $sql_startdate="select coaching_date from gn_coaching_info where coty_id='$coaching_info_data[coty_id]' and coach_id='$coaching_info_data[coach_id]' and coaching_turn= 1 ";
                            // $resul_num=mysqli_query($self_con,$sql_startdate);
                            // $startdate_data=mysqli_fetch_array($resul_num);


                            // $enddate = date('Y-m-d H:i:s',strtotime('+'.$coaching_data[cont_term].' day',strtotime($startdate_data[coaching_date])));

                            // $currentTime = date("Y-m-d H:i:s");

                            // if($enddate < $currentTime){
                            //     echo 0;
                            // }else{
                            //     $date1 = strtotime($currentTime);
                            //     $date2 = strtotime( $enddate);
                            //     $diff = floor(abs($date2 - $date1)/3600 / 24) + 1;


                            //     echo $diff >$coaching_data[cont_term]?$coaching_data[cont_term]:$diff;
    
                            // }


                            
                                  $date1 = strtotime($coaching_info_data['end_date']);
                                $date1 = strtotime(date('Y-m-d', $date1));
                                //echo $date1."일<br>";

                                $date2 = strtotime($coaching_info_data[coaching_date]);
                                $date2 = strtotime(date('Y-m-d', $date2));
                                //echo $date2."일<br>";

                                $diff = floor(abs($date1 - $date2)/3600 / 24);

                                echo $diff ;

                            ?>
                            일 <br>
                            <?

                            // 잔여시간

 // 잔여시간
                                    $remain_tatal_min =  ($coaching_data[cont_time] * 60) - $coaching_info_data[past_time_sum] - $coaching_info_data[coaching_time];
                                    $remain_hour = floor($remain_tatal_min / 60);
                                    $remain_min = $remain_tatal_min % 60;
                                    if($remain_min < 10){
                                        $remain_min = "0".$remain_min;
                                    }
                                    echo $remain_hour.":".$remain_min;

                            ?>
                        </td>
                        <td style="font-size:12px;"><?=$coaching_info_data[coaching_title]?></td>
                        <td style="font-size:12px;"><?
                        //echo substr($coaching_info_data[coaching_content], 0, 32); // abcd
                        echo $coaching_info_data[coaching_content];
                        ?></td>
                        <td style="font-size:12px;">
                          <a href="<?=$coaching_info_data[coaching_file]?>">
                            <? if($coaching_info_data[coaching_file]){
                                        echo "파일";
                                    } ?>
                        </a>
                        </td>
                        <td style="font-size:12px;"><?=$coaching_info_data[reg_date]?></td>
                        <td style="font-size:12px;"><?=$coaching_info_data[coach_value]?></td>
                        <td style="font-size:12px;"><?=$coaching_info_data[coty_value]?></td>
                        <td style="font-size:12px; text-align: center;">
                            <?=$coaching_info_data[site_value]?>
                            <br>
                          


                          <? 

                            $label = "평가";
                            $isCommented = false;
                            if($coaching_info_data[site_value] != 0){
                              $isCommented = true;
                              if($coaching_info_data[site_comment] != ""){
                                $label = "A 평가";
                              }else{
                                $label = "M 평가";
                              }  
                            }
                            


                           ?>
                            <button type="button" class="btn btn-sm btn-comment 

                                <? if($isCommented){ echo "btn-primary"; }else{ echo "btn-success";}?>

                                " data-toggle="modal" data-target="#myModal_<?=$coaching_info_data[coaching_id]?>" style="width: 50px;height: 20px;"  


                                    >
                                    <? echo $label;?> 
                               </button>




                                <div class="modal inmodal" id="myModal_<?=$coaching_info_data[coaching_id]?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content animated bounceInRight">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                <h3 class="modal-title"><?=$coaching_info_data[coaching_title]?></h3>
                                                <small class="font-bold"><?=$coaching_info_data[coaching_content]?></small>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row" ><div class="col-md-3" style="font-size: 16px;">본사평가</div> <div class="col-md-9">
                                                    <input type="email" class="site_value_input form-control" placeholder="5=훌륭해요 4=좋았어요 3=괜찮아요 2=보완해요 1=대안필요" value="<?=$coaching_info_data[site_value]?>"   <? if($coaching_info_data[site_comment] != ""){ echo disabled; }?> ></div>

                                                </div>

                                                    <br>
                                                         <? if($coaching_info_data[site_comment] == ""){  ?>
                                                          5=훌륭해요, 4=좋았어요, 3=괜찮아요, 2=보완해요, 1=대안필요
                                                          
                                                    <br>
                                                <br>
                                            <? } ?>
                                                <div class="row" ><div class="col-md-3" style="font-size: 16px;">본사의견</div> <div class="col-md-9">
                                                    <input type="email" class="site_comment_input form-control"  value="<?=$coaching_info_data[site_comment]?>"  <? if($coaching_info_data[site_comment] != ""){ echo disabled; }?>></div></div>
                                                <br>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-white" data-dismiss="modal">취소</button>
                                                <? if($coaching_info_data[site_comment] == ""){  ?>
                                                
                                                <button type="button" class="btn btn-primary" onclick="onSiteComment('<?=$coaching_info_data[coaching_id]?>')">저장</button>
                                                <? } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div> 

                                <script type="text/javascript">
                                    
                                function onSiteComment(coaching_id){
                                    var selector = "#myModal_"+coaching_id;
                                    var site_value_input = $(selector + " .site_value_input").val();
                                    var site_comment_input = $(selector + " .site_comment_input").val();

                                    if(site_value_input.trim()=="" || site_comment_input.trim() == ""){
                                        alert("값을 정확히 입력해주세요.");
                                        return;

                                    }

                                    if(site_value_input.trim() > 5){
                                        alert("1~5 사이의 값을 입력해주세요.");
                                        $(selector + " .site_value_input").focus();
                                        return;

                                    }

                                    $.ajax({
                                        type:"POST",
                                        url:"/mypage.proc.php",
                                        data:{mode:"update_coaching_info_site_comment",site_value:site_value_input, site_comment:site_comment_input,coaching_id:coaching_id},
                                        dataType: 'html',
                                        success:function(data){
                                            alert('평가를 진행하였습니다.');
                                            location.reload();
                                            //$(selector).modal("hide");
                                        },
                                        error: function(){
                                             alert('정상적으로 신청되지 않았습니다.');
                                            $(selector).modal("hide");
                                        }
                                    });

                                }

                                </script>





                        </td>
                       <td style="font-size:12px;" title="회차점수/누적점수" >
                                
                           
                               <?

                                $sql_1="select SUM(coty_value) as coty_sum,SUM(coach_value) as coach_sum,SUM(site_value) as site_sum from gn_coaching_info where coty_id='$coaching_info_data[coty_id]' and coach_id='$coaching_info_data[coach_id]' and coaching_turn <= '$coaching_info_data[coaching_turn]'";
                                //echo $sql_1;
                                $res_1=mysqli_query($self_con,$sql_1);
                                $sum=mysqli_fetch_array($res_1);


                                //누적 합점
                                $save_value = ($sum[coty_sum] + $sum[coach_sum] + $sum[site_sum]);
                                //누적 만점
                                $full_value = (15 * $coaching_info_data[coaching_turn]);
                                //누적 평균- 백분율
                                $save_aver_value = ($save_value / $full_value ) * 100;

                                //턴 점수 
                                $turn_aver_value =(($coaching_info_data[coach_value] + $coaching_info_data[coty_value]+$coaching_info_data[site_value]) / 15) * 100;

                                echo (round($turn_aver_value*10)/10)." / ".(round($save_aver_value*10)/10)."";
                                ?>
                            </td>
                            

                             <td style="font-size:12px;"><?php echo $coaching_info_data['agree']==1?"승인":"대기"?> 
                            </td> 

                            <!--  <td style="font-size:12px;">
                                <label class="switch">
                                  <input type="checkbox" class="chkagree" name="status" id="coaching_id_<?php echo $coaching_info_data['coaching_id'];?>"<?php echo $coaching_info_data['agree']==1?"checked":""?> >
                                  <span class="slider round" name="status_round" id="stauts_round_<?php echo $coaching_info_data['coaching_id_'];?>"></span>
                                </label>                            
                            </td>  -->



                            <td style="font-size:12px;">
                                <a class="label label-success label-sm" onclick="onView('<?=$coaching_info_data[coaching_id]; ?>')">보기</a><BR>
                                <!-- <a class="label label-danger label-sm" href="javascript:;;" onclick="removeRow('<?php echo $coaching_info_data['coaching_id'];?>')">삭제</a> -->


                              <!-- 코티정보모달 -->
                              <div class="modal fade" id="modal-coaching-info_<?=$coaching_info_data[coaching_id]; ?>">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                              <h3 class="modal-title" style="text-align: center;">코칭정보조회</h3>
                                          </div>
                                          <div class="modal-body">
                                        
                                            <div class="p1">
                                                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <th>코티이름</th>
                                                    <td><?=$coaching_data['mem_name']?></td>
                                                    <th>코티ID</th>
                                                    <td><?=$coaching_data['mem_id']?></td>
                                                </tr>       
                                                <tr>
                                                    <th>전화번호</th>
                                                    <td><?=$coaching_data[mem_phone]?></td>
                                                    <th>이메일</th>
                                                    <td><?=$coaching_data[mem_email]?></td>
                                                </tr>        
                                                <tr>
                                                    <th>계약기간</th>
                                                    <td><?=$coaching_data[cont_term]?>일</td>
                                                    <th>계약시간</th>
                                                    <td><?=$coaching_data[cont_time]?>시간</td>
                                                </tr>                    
                                 
                                                <tr>
                                                    <th>코칭비용</th>
                                                    <td><?=$coaching_data[coaching_price]/10000?>만원</td>
                                                    <th></th>
                                                    <td></td>
                                                </tr>                      
                                                <tr>
                                                    <th>코칭시작</th>
                                                    <td><?=$coaching_info_data[start_date]?></td>
                                                    <th>코칭종료</th>
                                                    <td><?=$coaching_info_data['end_date']?></td>
                                                </tr>                    
                                 
                                                <tr>
                                                    <th>진행상태</th>
                                                    <td>
                                                        <? 
                                                        $currentTime = date("Y-m-d H:i:s");

                                                         if($currentTime < $coaching_info_data[start_date]){
                                                            echo "<label class='label label-sm label-warning'>대기</label>";

                                                         }else if($currentTime > $coaching_info_data[start_date] && $currentTime < $coaching_info_data['end_date']){
                                                            echo "<label class='label label-sm label-primary'>진행중</label>";
                                                         }
                                                         else if($currentTime > $coaching_info_data['end_date']){
                                                            echo "<label class='label label-sm label-danger'>종료</label>";
                                                         }

                                                        // echo $coaching_info_data[coaching_status]==1?"진행중":"완료";


                                                        ?>
                                                    </td>
                                                    <th></th>
                                                    <td></td>
                                                </tr>                      
                                                                
                                                </table>
                                            </div>
                                              
                                          <div class="p1">
                                              <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                  <th class="w200">코치이름</th>
                                                  <td><?=$coach_data['mem_name'] ?></td>
                                              </tr>    
                                              <tr>
                                                  <th class="w200">코칭일시</th>
                                                  <td>
                                                      <?=$coaching_info_data[coaching_date]?>
                                                   </td>
                                              </tr>                    
                                              <tr>
                                                  <th class="w200">코칭제목</th>
                                                  <td><?=$coaching_info_data[coaching_title]?></td>
                                              </tr>                    
                                              <tr>
                                                  <th class="w200">코칭내용</th>
                                                  <td><?=$coaching_info_data[coaching_content]?></td>
                                              </tr>                    
                                              <tr>
                                                  <th class="w200">파일첨부</th>
                                                  <td>
                                                      <a href="<?=$coaching_info_data[coaching_file]?>" style="color:blue;">
                                                          파일
                                                      </a>
                                                      
                                                          
                                                      </td>
                                              </tr>                    
                                              <tr>
                                                  <th class="w200">코치평가</th>
                                                  <td>
                                                  <?
                                                      $coach_value = $coaching_info_data[coach_value];
                                                      echo (($coach_value / 5) * 100 )."점( ".$coach_value." / 5 )";
                                                  ?>
                                                  </td>
                                              </tr>    
                                              <tr>
                                                  <th class="w200">코티평가</th>
                                                  <td>
                                                    
                                                  <?
                                                      $coty_value = $coaching_info_data[coty_value];
                                                      echo (($coty_value / 5) * 100 )."점( ".$coty_value." / 5 )";
                                                  ?>  
                                                  </td>
                                              </tr>    
                                              <tr>
                                                  <th class="w200">본사평가</th>
                                                  <td>
                                                    
                                                  <?
                                                      $site_value = $coaching_info_data[site_value];
                                                      echo (($site_value / 5) * 100 )."점( ".$site_value." / 5 )";
                                                  ?>  
                                                  </td>
                                              </tr>    

                                              <tr>
                                                  <th class="w200">과제안내</th>
                                                  <td><?=$coaching_info_data[home_work]?></td>
                                              </tr>                    
                                              <tr>
                                                  <th class="w200">코치의견</th>
                                                  <td><?=$coaching_info_data[coach_comment]?></td>
                                              </tr>                 
                                              <tr>
                                                  <th class="w200">코티의견</th>
                                                  <td><?=$coaching_info_data[coty_comment]?></td>
                                              </tr>                    
                                              <tr>
                                                  <th class="w200">본부의견</th>
                                                  <td><?=$coaching_info_data[site_comment]?></td>
                                              </tr>                    
                                                              
                                              </table>
                                          </div>

                                          </div>
                                          <div class="modal-footer" style="text-align: center;">
                                              <a style="color:white;" class="btn btn-danger" data-dismiss="modal"> 종 료 </a>
                                          </div>
                                      </div>
                                  </div>
                              </div>


                            </td>    




                                            
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
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>   
      </div><!-- /content-wrapper -->

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	

<script language="javascript">

    
function removeRow(no) {
    if(confirm('삭제하시겠습니까?')) {
        $.ajax({
             type:"POST",
             url:"ajax/update_coach_status.php",
             data:{
                 mode:"delete_coaching_info",
                 coaching_id:no
                 },
             success:function(data){
                // alert('삭제되었습니다.');
                location.reload();
                }
            })

    }
}
function viewEvent(str){
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
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

function finish_typeChanged(){
  $('#search_form1').submit();
}



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

    var coaching_id = id.split("_")[2];

    //alert(coaching_id);
    $.ajax({
         type:"POST",
         url:"/admin/ajax/update_coach_status.php",
         data:{
            mode:"update_coaching_info_agree",coaching_id:coaching_id, status:status
          },
         dataType: 'html',
         success:function(data1){
           //$('#phone_list').html(data);
           //alert("저장되었습니다.");
           location.reload(true);
         },
         error: function(){
           alert('로딩 실패');
         }
       });            
  });



function onView(coaching_id){
    $.ajax({
       type:"POST",
       url:"/admin/ajax/update_coach_status.php",
       data:{
          mode:"read_coaching_info",coaching_id:coaching_id
        },
       dataType: 'html',
       success:function(data1){
        // alert('success');
        // console.log(data1);

        $("#modal-coaching-info_"+coaching_id).modal("show");

       },
       error: function(){
         alert('로딩 실패');pmypage_coaching_list
       }
     });            
}

</script>



