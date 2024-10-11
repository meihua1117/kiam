<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");
if($case == 0 )
	$case = -1;
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
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&orderField=<?php echo $_GET['orderField'];?>&dir=<?php echo $_GET['dir'];?>&case=<?php echo $_GET['case'];?>";
}

//주소록 다운
function excel_down_p_group(pno,one_member_id){
	$($(".loading_div")[0]).show();
	$($(".loading_div")[0]).css('z-index',10000);
	location='/excel_down/excel_down__.php?pno='+pno+'&one_member_id='+one_member_id;
	$($(".loading_div")[0]).hide();
	/*
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
	 	//parent.excel_down('/excel_down/excel_down_.php?down_type=1&one_member_id='+one_member_id,data.idx);
	 	parent.excel_down('/excel_down/excel_down__.php?pno='+pno+'&one_member_id='+one_member_id,"");
	 }

	});	
	*/
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
            회원관리
            <small>회원을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">회원관리</li>
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
               <a href="?case=-1" style="border:1px solid
					<?if($case == -1){?>						
						black; background-color: lightblue;
					<?}else{?>
						#337ab7;
					<?}?>
					padding:5px">전체</a> 
               <a href="?case=1" style="border:1px solid 
					<?if($case == 1){?>						
						black; background-color: lightblue;
					<?}else{?>
						#337ab7;
					<?}?>
					padding:5px">회원/앱</a>
               <a href="?case=2" style="border:1px solid 
					<?if($case == 2){?>						
						black; background-color: lightblue;
					<?}else{?>
						#337ab7;
            <?}?>
					padding:5px">회원/미앱</a>
               <a href="?case=3" style="border:1px solid 
					<?if($case == 3){?>						
						black; background-color: lightblue;
					<?}else{?>
						#337ab7;           
					<?}?>
					padding:5px">추가폰</a>
               </div>
                               
              <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
              <?php if($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) {} else {?>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_member_down_.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
                <?php }?>
              <?php }?>
              <form method="get" name="search_form" id="search_form">
              <input type="hidden" name="case" value="<?php echo $_GET['case'];?>">
              <div class="box-tools">
                <div class="input-group" style="display:inline-flex;width: 250px;">
                  <input type="text" name="identifier" id="identifier" class="form-control input-sm pull-right" placeholder="아이디">
				  <input type="text" name="rec_id" id="rec_id" class="form-control input-sm pull-right" placeholder="추천인">
				  <input type="text" name="search_key" id="search_key" style="width:115px" class="form-control input-sm pull-right" placeholder="이름/휴대폰/소속">
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
                    <th>추가폰</th>
                    <th>설치일자</th>
                  </tr>
                </thead>
                <tbody id="phone_list">
                    
                </tbody>
                </table>
          </div>
          
          <?  
            if($_REQUEST['dir'] == "desc"){
              $dir = "asc";
            } 	else{
              $dir = "desc";
            }
          ?>
          
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
                      <col width="60px">
                      <col width="100px">
                      <col width="70px">
                      <col width="70px">
                      <col width="50px">
                      <col width="220px">
                      <col width="40px">
                      <col width="100px">
                      <col width="100px">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>회원정보</th>
                        <th>소속</th>
                        <th>아이디</th>
                        <th>이름</th>
                        <th>전화번호</th>
                        <th>구분</th>
                        <!-- rcs s -->
                        <th><a href="?orderField=tcnt&dir=<?=$dir?>" class="sort-by">추가폰</a></th>
                        <!-- rcs e -->
                        <th>유료건수</th>

                        <th>총결제</th>
                        <th>추천인</th>                        
                        <th>가입일/접속일</th>
                        <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                        <th>동기</th>
                        <?php }?>
                        <th>레벨수정</th>
                        <th>셀링등급</th>
                        <th>IAM등급</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                    
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                    // 검색 조건을 적용한다.					
                    $searchStr .= $identifier ? " AND (a.mem_id LIKE '%".$identifier."%')" : null;
                    $searchStr .= $rec_id ? " AND (a.recommend_id like '%".$rec_id."%') " : null;
                    $searchStr .= $search_key ? "AND ( a.mem_phone like '%".$search_key."%' or a.mem_name like '%".$search_key."%' or a.site like  '%" .$search_key."%' or b.sendnum like '%".$search_key. "%' or a.zy like '%".$search_key."%')" : null;                    
                    if($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) {
                        $do = explode(".", $HTTP_HOST);
                        $searchStr .= " and a.site = '".$do[0]."'";
                    }
                        
                        
                    if($case == "1") {
                      $searchStr .= " and (REPLACE(a.mem_phone,'-','')=REPLACE(b.sendnum, '-','') and b.sendnum is not null and b.sendnum != '')";
                    } else if($case == "2") {
                        $searchStr .= " and (b.sendnum is null or b.sendnum = '')";
                    }else if ($case == "3") {
                        $searchStr .= " and (REPLACE(a.mem_phone,'-','')!=REPLACE(b.sendnum,'-','') and b.sendnum is not null and b.sendnum != '')";
                    }
                	
                	$order = $order?$order:"desc"; 		
                	
                	$query = "
                        	SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	    a.mem_leb, a.mem_code, a.mem_group, a.mem_id, a.mem_name, a.mem_nick, a.mem_phone, a.mem_id, a.web_pwd,a.site, a.recommend_id,
                        	    a.login_date, a.visited, a.level, a.fujia_date1, a.fujia_date2, a.is_leave, a.first_regist, 
                        	    (select count(*) from Gn_MMS_Number where 1=1 and ( not (cnt1 = 10 and cnt2 = 20)) and mem_id =a.mem_id) tcnt,
                        	    b.memo, b.sendnum, b.memo2, a.service_type, a.mem_iam_type
                        	FROM Gn_Member a
                        	LEFT JOIN Gn_MMS_Number b
                        	       on b.mem_id =a.mem_id
                        	WHERE 1=1 
                	              $searchStr";
                	              
                	$res	    = mysqli_query($self_con,$query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                  // rcs s
                  if(!$orderField){
                    $orderField = "a.mem_code";
                  }

                    $orderQuery .= "
                    	ORDER BY $orderField $dir ";            	
                	//rcs e         	
                	
                	$i = 1;
                    $query .= "$orderQuery";

                    $excel_sql = "";
                    $excel_sql = $query;

                    $query .= "$limitStr";


                    
                	$res = mysqli_query($self_con,$query);
                    while($row = mysqli_fetch_array($res)) {                       	
                        // =====================  유료결제건 시작 ===================== 
                    	$sql = "select phone_cnt from tjd_pay_result where buyer_id = '".$row['mem_id']."' and end_date > '$date_today' and end_status='Y' order by end_date desc limit 1";
                    	$res_result = mysqli_query($self_con,$sql);
                    	$buyPhoneCnt = mysqli_fetch_row($res_result);
                    	mysqli_free_result($res_result);
                    	
                    	if($buyPhoneCnt == 0){	
                    		$buyMMSCount = 0;
                    	}else{
                    		//$buyMMSCount = ($buyPhoneCnt[0] -1) * 9000;
                    		$buyMMSCount = $buyPhoneCnt[0];
                    	}                    	
                    	// ===================== 유료결제건 끝 ===================== 
                    	
                        // =====================  총결제금액 시작 ===================== 
                    	$sql = "select sum(TotPrice) totPrice from tjd_pay_result where buyer_id = '".$row['mem_id']."' and end_status='Y'";
                    	$res_result = mysqli_query($self_con,$sql);
                    	$totPriceRow = mysqli_fetch_row($res_result);
                    	mysqli_free_result($res_result);
                    	
                    	$totPrice = $totPriceRow[0];
                    	// ===================== 총결제금액 끝 =====================                     	
                    	
                    	// 부가서비스 이용 여부 확인
                    	// tjd_pay_result.fujia_status
                    	if($row['fujia_date2'] >= date("Y-m-d H:i:s")) {
                    	    $add_opt = "사용";
                    	} else {
                    	    $add_opt = "미사용";
                    	}
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td>
                            <?php if($_SESSION['one_member_subadmin_id']=="") {?>
                            <a href="member_detail.php?mem_code=<?=$row['mem_code']?>&sendnum=<?=$row['sendnum']?>">수정</a> / 
                            <a href="javascript:del_member_info('<?=$row['mem_code']?>')">탈퇴</a></td>
                            <?}?>
                        <td><?=$row['site']?></td>
                        <td>
                            <?php if($_SESSION['one_member_admin_id'] == "onlyonemaket" || $_SESSION['one_member_subadmin_id']!=""){?>
                            <?=$row['mem_id']?>
                            <?php }else{?>
                            <a href="javascript:loginGo('<?=$row['mem_id']?>', '<?=$row['web_pwd']?>', '<?=$row['mem_code']?>');"><?=$row['mem_id']?></a>
                            <?php }?>
                        	
                        	</td>
                        <td><?=$row['mem_name']?></td>
                        <td>
                            <?=str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum']?>
                        </td>
                        <td>
                            <?=str_replace("-", "",$row['mem_phone'])==$row['sendnum'] || $row['sendnum'] == ""?"회원폰":"추가폰"?>
                            <!--추가폰-->
                        </td>
                        <td onclick="page_view('<?=$row['mem_id']?>');"><?=number_format($row['tcnt'])?> <?php echo $row['memo'];?></td>
                        <td><?=number_format($buyMMSCount)?></td>
                        
                        <td><?=number_format($totPrice)?></td>
                        
                        <!--td><?=$add_opt?></td-->
                        <td><?=$row['recommend_id']?></td>
                        
                        <td><?=$row['first_regist']?>/<?=$row['login_date']?></td>
                        <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                        <td>
                         <?php if($_SESSION['one_member_admin_id'] == "onlyonemaket" || $_SESSION['one_member_subadmin_id']!=""){?>
                         <?php }else{?>
                         <a href="javascript:void(0);" onclick="excel_down_p_group('<?=str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum']?>','<?=$row['mem_id']?>')"><img src="/images/ico_xls.gif" border="0"></a>
                         <?php }?>
                        </td>
                        <?php }?>
                        <td>
                            <?php if(str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""){?>
                            <select name="mem_leb" id="mem_leb<?=$row['mem_code']?>">
                                <option value="22" <?php echo $row['mem_leb'] == "22"?"selected":""?>>일반</option>
                                <option value="50" <?php echo $row['mem_leb'] == "50"?"selected":""?>>사업</option>
                                <option value="21" <?php echo $row['mem_leb'] == "21"?"selected":""?>>강사</option>
                                <option value="60" <?php echo $row['mem_leb'] == "60"?"selected":""?>>홍보</option>
                            </select>
                            <?php if($_SESSION['one_member_admin_id'] == "onlyonemaket" || $_SESSION['one_member_subadmin_id']!=""){?>
                            <?php }else{?>
                            <input type="button" name="변경" value=" 변경 " onclick="changeLevel('<?=$row['mem_code']?>')">
                            <?php }?>
                            <?php }?>
                        </td>                        
                        <td>
                            <?php if(str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""){?>
                            <select name="service_type" id="service_type<?=$row['mem_code']?>">
                                <option value="0" <?php echo $row['service_type'] == "0"?"selected":""?>>FREE</option>
                                <option value="1" <?php echo $row['service_type'] == "1"?"selected":""?>>이용자</option>
                                <option value="2" <?php echo $row['service_type'] == "2"?"selected":""?>>리셀러</option>
                                <option value="3" <?php echo $row['service_type'] == "3"?"selected":""?>>분양자</option>
                            </select>
                            <?php if($_SESSION['one_member_admin_id'] == "onlyonemaket" || $_SESSION['one_member_subadmin_id']!=""){?>
                            <?php }else{?>                            
                            <input type="button" name="변경" value=" 변경 " onclick="changeServiceType('<?=$row['mem_code']?>')">
                            <?php }?>
                            <?php }?>
                        </td> 
                        <td>
                            <?php if(str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""){?>
                            <select name="mem_iam_type" id="mem_iam_type<?=$row['mem_code']?>">
                                <option value="0" <?php echo $row['mem_iam_type'] == "0"?"selected":""?>>FREE</option>
                                <option value="1" <?php echo $row['mem_iam_type'] == "1"?"selected":""?>>BASIC</option>
                                <option value="2" <?php echo $row['mem_iam_type'] == "2"?"selected":""?>>전문가</option>
                                <option value="3" <?php echo $row['mem_iam_type'] == "3"?"selected":""?>>회사용</option>
                                <option value="4" <?php echo $row['mem_iam_type'] == "4"?"selected":""?>>단체용</option>
                                <option value="5" <?php echo $row['mem_iam_type'] == "5"?"selected":""?>>특판용</option>
                            </select>
                            <?php if($_SESSION['one_member_admin_id'] == "onlyonemaket" || $_SESSION['one_member_subadmin_id']!=""){?>
                            <?php }else{?>                            
                            <input type="button" name="변경" value=" 변경 " onclick="changeIamType('<?=$row['mem_code']?>')">
                            <?php }?>
                            <?php }?>
                        </td>                                     
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
      location.reload();
		},
		error: function(){
		  alert('초기화 실패');
		}
	});	
    
//    alert(mem_code);
}

function changeServiceType(mem_code) {
    var service_type = $('#service_type'+mem_code+" option:selected").val();
    var data = {"mode":"change","mem_code":"'+mem_code+'","service_type":"'+service_type+'"};
    $.ajax({
		type:"POST",
		url:"/admin/ajax/user_service_type_change.php",
		dataType:"json",
		data:{mode:"change",mem_code:mem_code,service_type:service_type},
		success:function(data){
		    //console.log(data);
		    //location = "/";
			//location.reload();
			alert('변경이 완료되었습니다.');
      location.reload();
		},
		error: function(){
		  alert('초기화 실패');
		}
	});	
    
//    alert(mem_code);
}
function changeIamType(mem_code) {
  var mem_iam_type = $('#mem_iam_type'+mem_code+" option:selected").val();
  $.ajax({
		type:"POST",
		url:"/admin/ajax/user_service_type_change.php",
		dataType:"json",
		data:{mode:"change_iam_type",mem_code:mem_code,mem_iam_type:mem_iam_type},
		success:function(res){
		    //console.log(data);
		    //location = "/";
			//location.reload();
			alert('변경이 완료되었습니다.');
      location.reload();
		},
		error: function(){
		  alert('초기화 실패');
		}
	});	
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