<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}
function goPage(pgNum) {
    location.href = '?nowPage='+pgNum+'&user_id=<?=$user_id?>&data_type=<?=$data_type?>&company_type=<?=$company_type?>&address=<?=$address?>&search_key=<?=$search_key?>&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>';
}
$(function() {
  var contHeaderH = $(".main-header").height();
  var navH = $(".navbar").height();
  if(navH != contHeaderH)
      contHeaderH += navH - 50;
  $(".content-wrapper").css("margin-top",contHeaderH);
  var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 196;
  if(height < 375)
      height = 375;
  $(".box-body").css("height",height);
});
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
            디비수집관리
            <small>수집된 디비를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">디비수집관리</li>
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

            <? if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                  <button id="btn_excel_down" class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_crawler_data_list.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
            <? }?>
 
              <form method="get" name="search_form" id="search_form" class="form-inline">
              <div class="box-tools">
                <div class="input-group" >
                  <div class="form-group">
                    <input type="text" name="user_id" id="user_id" class="form-control input-sm pull-right" placeholder="아이디" value="<?=$_REQUEST['user_id']?>">
                  </div>
                  <div class="form-group">
                    <select name="data_type" class="form-control input-sm " >
                          <option value="">웹종류</option>
                          <option value="지도" <?if($_REQUEST['data_type'] == "지도") echo "selected"?>>지도</option>
                          <option value="블로그" <?if($_REQUEST['data_type'] == "블로그") echo "selected"?>>블로그</option>
                          <option value="웹사이트" <?if($_REQUEST['data_type'] == "웹사이트") echo "selected"?>>웹사이트</option>
                          <option value="뉴스" <?if($_REQUEST['data_type'] == "뉴스") echo "selected"?>>뉴스</option>
                          <option value="쇼핑" <?if($_REQUEST['data_type'] == "쇼핑") echo "selected"?>>쇼핑</option>
                          <option value="카페" <?if($_REQUEST['data_type'] == "카페") echo "selected"?>>카페</option>
                          <option value="포스트" <?if($_REQUEST['data_type'] == "포스트") echo "selected"?>>포스트</option>
                          <option value="지마켓" <?if($_REQUEST['data_type'] == "지마켓") echo "selected"?>>지마켓</option>
                      </select>
                  </div>
                  <div class="form-group">
                    <input type="text" name="company_type" id="company_type" class="form-control input-sm pull-right" placeholder="업종" value="<?=$_REQUEST['company_type']?>">
                  </div>
<div class="form-group">
                    <input type="text" name="cell" id="cell" class="form-control input-sm pull-right" placeholder="전화번호" value="<?=$_REQUEST['cell']?>">
                  </div>
                  <div class="form-group">
                    <input type="text" name="address" id="address" class="form-control input-sm pull-right" placeholder="주소" value="<?=$_REQUEST['address']?>">
                  </div>
                  <div class="form-group">
                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="검색어" value="<?=$_REQUEST['search_key']?>">
                  </div>
                  <div class="form-group">  
                    <input type="text" name="search_tag" id="search_tag" class="form-control input-sm pull-right" placeholder="태그" value="<?=$_REQUEST['search_tag']?>">
                  </div>
                  <div class="form-group">  
                    <input type="text" name="search_phone" id="search_phone" class="form-control input-sm pull-right" placeholder="폰관련" value="<?=$_REQUEST['search_phone']?>">
                  </div>
                  <div class="form-group">  
                      <input type="date" style="height: 30px" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST['search_start_date']?>"/> ~
                      <input type="date" style="height: 30px" name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST['search_end_date']?>"/>                  
                  </div>
                  <div class="input-group-btn">
                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
              </form>
 
            </div>            
          </div>
          
          
          <div class="row">
            
              <div class="box">
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="50px">
                      <col width="60px">
                      <col width="100px">
                      <col width="100px">
                      <col width="70px">
                      <col width="70px">
                      <col width="70px">
                      <col width="100px">
                      <col width="80px">
                      <col width="70px">
                      <col width="70px">
                      <col width="70px">
                      <col width="100px">
                      <col width="130px">
                      <col width="100px">
                      <col width="60px">
                      <col width="60px">
                      <col width="100px">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>선택</th>
                        <th>번호</th>
                        <th>회원아이디</th>
                        <th>검색어</th>
                        <th>포함</th>
                        <th>제외</th>
                        <th>웹종류</th>
                        <th>폰번호</th>
                        <th>이메일</th>
                        <th>대표자</th>
                        <th>상호</th>
                        <th>업종</th>
                        <th>페이지제목</th>
                        <th>주소</th>
                        <th>웹주소</th>
                        <th>태그</th>
                        <th>폰관련정보</th>
                        <th>수집일</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                  // 검색 조건을 적용한다.
                  $searchStr .= $search_key ? " AND (a.keyword LIKE '%".$search_key."%')" : null;                
                
                  if($search_start_date && $search_end_date) {
                      $searchStr .= " AND regdate >= '$search_start_date 00:00:00'  and regdate <= '$search_end_date 23:59:59'";
                  } 
                   
                  if($user_id ) {
                    $searchStr .= " AND (a.user_id LIKE '%".$user_id."%')";
                  }
                  if($company_type ) {
                      $searchStr .= " AND (a.company_type LIKE '%".$company_type."%')";
                  }
 	     if($cell) {
                      $searchStr .= " AND (a.cell LIKE '%".$cell."%')";
                  }

                  if($address) {
                      $searchStr .= " AND (a.address LIKE '%".$address."%')";
                  }
                  if($search_tag ) {
                    $searchStr .= " AND (a.tag LIKE '%".$search_tag."%')";
                  }
                  if($search_phone ) {
                    $searchStr .= " AND (a.info LIKE '%".$search_phone."%')";
                  }
                  if($data_type) {
                      $searchStr .= " AND data_type='$data_type'";
                  }      	

                	$query = "
                        	SELECT 
                        	    count(seq) cnt
                        	FROM crawler_data_2022 a 
                        	WHERE 1=1 
                	              $searchStr";
                	              
                	$res	    = mysql_query($query);
                	$totalRow	=  mysql_fetch_array($res);	                	
                	$totalCnt = $totalRow[0];
                	
                	                	
                	$query = "
                        	SELECT 
                              a.user_id, a.keyword, a.incword, a.exword, a.data_type, a.cell, a.email, a.ceo, a.company_name, a.company_type,
                              a.address, a.page_title, a.url, a.tag, a.info, a.regdate
                        	    
                        	FROM crawler_data_2022 a 
                        	WHERE 1=1 
                                $searchStr";
                                

                	//$res	    = mysql_query($query);
                	//$totalCnt	=  mysql_num_rows($res);	
                  $excel_sql = $query;
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                    $orderQuery .= "
                    	ORDER BY seq DESC
                    	$limitStr
                    ";            	
                	
                	$i = 1;
                  $query .= "$orderQuery";
                  //echo $query;              
                	              
                	$res = mysql_query($query);
                    while($row = mysql_fetch_array($res)) {                       	
                            $query = "Select * from crawler_member_real where user_id='$row[user_id]'";
                            $sres = mysql_query($query);
                            $srow = mysql_fetch_array($sres);                                                    
                       
                  ?>
                      <tr>
                        <td><input type="checkbox" name="diber" value="Y" data-no="<?php echo $row['']?>" <?php echo $row['']=="Y"?"checked":""?>></td>
                        <td><?=$number--?></td>
                        <td>
                            <?=$row['user_id']?>
                        </td>
                        <td><?=$row['keyword']?></td>
                        <td><a href="javascript:openInfo('<?=$row['incword']?>');"><? echo (mb_strlen($row['incword'], "UTF-8") > 8)?mb_substr($row['incword'], 0, 8, "UTF-8"):$row['incword'];?></a></td>
                        <td><a href="javascript:openInfo('<?=$row['exword']?>');"><? echo (mb_strlen($row['exword'], "UTF-8") > 8)?mb_substr($row['exword'], 0, 8, "UTF-8"):$row['exword'];?></a></td>
                        <td><?=$row['data_type']?></td>
                        <td><?=$row['cell']?></td>
                        <td><?=$row['email']?></td>
                        <td><?=$row['ceo']?></td>
                        <td><?=$row['company_name']?></td>
                        <td><?=$row['company_type']?></td>
                        <td><?=$row['page_title']?></td>
                        <td>
                            <?=strip_tags($row['address'])?>
                            </td>
                        <td>
                            <div style="overflow-x:hidden;width:100px;">
                            <a href="<?=strip_tags($row['url'])?>" target="_blank"><?=$row['url']?></a>
                            </div>
                            </td>
                        <td><a href="javascript:openInfo('<?=$row['tag']?>');"><? echo (mb_strlen($row['tag'], "UTF-8") > 8)?mb_substr($row['tag'], 0, 8, "UTF-8"):$row['tag'];?></a></td>
                        <td><a href="javascript:openInfo('<?=$row['info']?>');"><? echo (mb_strlen($row['info'], "UTF-8") > 8)?mb_substr($row['info'], 0, 8, "UTF-8"):$row['info'];?></a></td>
                            <!--기부폰-->
                        <td><?=$row['regdate']?></td>
 
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

        
          <? if($totalCnt > 200000){?>
            <script>
              $('#btn_excel_down').hide();
            </script>
          <?}?>
          
        </section><!-- /.content -->
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?> 
      </div><!-- /content-wrapper -->

      <div id="modal_info" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
          <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:768px;">
              <div class="modal-content">
                  <div class="modal-header" style="display: flex">
                      <h2 class="modal-title" style="text-align:center;">태그 리스트</h2>
                  </div>
                  <div class="modal-body">
                      <textarea id="txtInfo" style="width: 100%;border: 1px solid #b5b5b5;height: 150px;margin-bottom: 15px;" readonly></textarea>
                  </div>
              </div>
          </div>
      </div>

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

function openInfo(info){
  $("#txtInfo").val(info);
  $("#modal_info").modal("show");
}
</script>