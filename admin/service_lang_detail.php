<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
// 가입 회원 상세 정보
$query = "select *
            from Gn_Iam_lang where no='$no'";
$res = mysqli_query($self_con,$query);
$data = mysqli_fetch_array($res);

// 기부회원 상세정보

?>
<style>
    .box-body th {background:#ddd;}
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
    .wrapper{height:100%;overflow:auto;}
    .content-wrapper{min-height : 80% !important;}
    .box-body{overflow:auto;padding:0px !important}
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script>
<script language="javascript">
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 313;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
//수정
function form_save(){
    var recommender = $("#recommend_id").val();
    var userid = $("#divID").text();
    if(recommender == userid)
    {
      alert('자신의 아이디는 추천에 입력되지 않습니다.');      
      return;
    }
    if($('#is_exist_recommender').val() == "N" )
		{
			alert('추천인을 정확히 입력해 주세요.');
			return;			
		}
    if(recommender == '')
    {
      alert('추천인을 입력해주세요');
      return;
    }

    var msg = confirm('저장하시겠습니까?');

    if(msg){
              var data = $('#dForm').serialize();
        $.ajax({
          type:"POST",
          url:"/admin/ajax/Iam_lang_save.php",
          data: data,
          success:function(){
            alert('저장되었습니다.');
            location='/admin/iam_auto_lang.php';
          },
          error: function(){
            alert('저장 실패');
          }
        });		

    }else{
      return false;
    }
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
            <h1>다국어IAM관리<small>아이엠 다국어정보를 관리합니다.</small></h1>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">아이엠 다국어관리</li>
          </ol>
        </section>
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post">
        <input type="hidden" name="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" value="<?=$data['mem_code']?>" />        
        </form>

        <form method="post" id="dForm" name="dForm">
        <input type="hidden" name="no" value="<?=$data['no']?>" />
        <input type="hidden" name="mode" value="<?=$data['no']?"updat":"inser"?>" />
        <!-- Main content -->
        <section class="content">
          <?
          if(str_replace("-", "",$data['mem_phone'])==$_GET['sendnum'] || $_GET['sendnum'] == "") {
          ?>
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
              </div>            
          </div>
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">메뉴항목 추가/수정</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="16%">
                      <col width="84%">
                     </colgroup>
                    <tbody>
                      <tr>
                        <th>메뉴</th>
                        <td><input type="text" style="width:150px;" name="menu" id="menu" value="<?=$data['menu']?>"></td>
                      </tr>
                      <tr>
                        <th>위치</th>
                        <td><input type="text" style="width:150px;" name="pos" id="pos" value="<?=$data['pos']?>"></td>
                      </tr>                      
                      <tr>
                        <th>한국어</th>
                        <td><textarea name="kr" id="kr" style="width:50%;height:50px;"><?=$data['kr']?></textarea></td>
                      </tr>                                                                                                                                                                                                    
                      <tr>
                        <th>영어</th>
                        <td><textarea name="en" id="en" style="width:50%;height:50px;"><?=$data['en']?></textarea></td>
                      </tr>     
                      <tr>
                        <th>중국어</th>
                        <td><textarea name="cn" id="cn" style="width:50%;height:50px;"><?=$data['cn']?></textarea></td>
                      </tr>       
                      <tr>
                        <th>일본어</th>
                        <td><textarea name="jp" id="jp" style="width:50%;height:50px;"><?=$data['jp']?></textarea></td>
                      </tr>       
                      <tr>
                        <th>인도어</th>
                        <td><textarea name="id" id="id" style="width:50%;height:50px;"><?=$data['id']?></textarea></td>
                      </tr>       
                      <tr>
                        <th>프랑스어</th>
                        <td><textarea name="fr" id="fr" style="width:50%;height:50px;"><?=$data['fr']?></textarea></td>
                      </tr>                       
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          <?} else {?>
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">기부회원 상세정보</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail2" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="16%">
                      <col width="16%">
                      <col width="16%">
                      <col width="16%">
                      <col width="16%">
                      <col width="20%">
                     </colgroup>
                    <tbody>
                      <tr>
                        <th>기부폰 번호</th>
                        <td><?=$donation_data['sendnum']?></td>
                        <th>앱설치일자</th>
                        <td><?=$donation_data['reg_date']?></td>
                        <th>가입자 아이디</th>
                        <td><?=$data['mem_id']?></td>
                      </tr>
                      <tr>
                        <th>최근발송건</th>
                        <td><?=$donation_data['reservation_time']?></td>
                        <th>기부비율</th>
                        <td>
                            <input type="text" style="width:100px;" name="donation_rate" value="<?=$donation_data['donation_rate']?>"> %
                            <div><span style="font-size:10px">※ 설치 기록이 없으면 비율이 변경되지 않음.</span></div>
                        </td>
                        <th>가입자 이름</th>
                        <td><?=$data['mem_name']?></td>                       
                      </tr>
                      <tr>
                        <th>최근발송일</th>
                        <td><?=$donation_data['reservation_time']?></td>
                        <th>기부문자</th>
                        <td>
                            <?=number_format($donation_data['daily_limit_cnt'] * ($donation_data['donation_rate'] * 0.01))?> / <?=number_format($donation_data['daily_limit_cnt'])?>
                        </td>
                        <th>가입자 전화번호</th>
                        <td><?=$data['mem_phone']?></td>                     
                      </tr>                                                                                                                                                                
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
        <?}?>
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='iam_auto_lang.php';return false;"><i class="fa fa-list"></i> 목록</button>
            </div>            
          </div><!-- /.row -->          
        </section><!-- /.content -->
        </form>
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>   
      </div><!-- /content-wrapper -->
         