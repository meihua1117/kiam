<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

// 아이엠 상세 정보
$query = "select * from Gn_Iam_automem where memid='{$memid}'";
$res = mysqli_query($self_con,$query);
$data = mysqli_fetch_array($res);
?>
<style>
    .box-body th {background:#ddd;}
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script>
<script language="javascript">
//수정
function form_save(){
    var msg = confirm('저장하시겠습니까?');
    if(msg){
        var data = $('#dForm').serialize();
        $.ajax({
          type:"POST",
          url:"iam_auto_change_ajax.php",
          data: data,
          success:function(){
            alert('저장되었습니다.');
            //location.reload();
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
            아이엠자동생성관리
            <small>아이엠 자동생성을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">아이엠자동생성관리</li>
          </ol>
        </section>

        <form method="post" id="dForm" name="dForm" >
        <input type="hidden" name="mem_id" value="<?=$data['memid']?>" />
        <!-- Main content -->
        <section class="content">
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">상세정보</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
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
                            <th>아이디</th>
                            <td>
                                <input type="text" style="width:400px;" name="memid" id="memid" value="<?=$data['memid']?>" > </td>
                            <th>성명</th>
                            <td> <input type="text" style="width:400px;" name="mem_name" id="mem_name" value="<?=$data['mem_name']?>" > </td>
                            <th>출처</th>
                            <td> <input type="text" style="width:400px;" name="db_source" id="db_source" value="<?=$data['db_source']?>" > </td>
                      </tr>
                      <tr>
                            <th>자기소개</th>
                            <td> <input type="text" style="width:400px;" name="info" id="info" value="<?=$data['profile_self_info']?>" > </td>
                            <th>상호명</th>
                            <td> <input type="text" style="width:400px;" name="company" id="company" value="<?=$data['profile_company']?>" > </td>
                            <th>추천인</th>
                            <td> <input type="text" style="width:400px;" name="rID" id="rID" value="<?=$data['recommend_id']?>" > </td>
                      </tr>
                      <tr>
                          <th>스마트폰번호</th>
                          <td> <input type="text" style="width:400px;" name="phone" id="phone" value="<?=$data['profile_telno']?>" > </td>
                          <th>이메일</th>
                          <td> <input type="text" style="width:400px;" name="email" id="email" value="<?=$data['profile_email']?>" > </td>
                          <th>홈피</th>
                          <td> <input type="text" style="width:400px;" name="homepage" id="homepage" value="<?=$data['profile_homepage']?>" > </td>
                      </tr>
                      <!--tr>
                          <th>대표이미지1</th>
                          <td>
                              <div>
                                  <a href="<?=$data['image1']?>" target="_blank">
                                      <img class="zoom" src="<?=$data['image1']?>" style="width:50px;">
                                  </a>
                              </div>
                          </td>
                          <th>대표이미지2</th>
                          <td>
                              <div>
                                  <a href="<?=$data['image2']?>" target="_blank">
                                      <img class="zoom" src="<?=$data['image2']?>" style="width:50px;">
                                  </a>
                              </div>
                          </td><th>대표이미지3</th>
                          <td>
                              <div>
                                  <a href="<?=$data['image3']?>" target="_blank">
                                      <img class="zoom" src="<?=$data['image3']?>" style="width:50px;">
                                  </a>
                              </div>
                          </td>
                      </tr-->
                      <tr>
                          <th>주소</th>
                          <td> <input type="text" style="width:400px;" name="address" id="address" value="<?=$data['profile_address']?>" > </td>
                          <th>아이엠링크</th>
                          <td><input type="text" style="width:400px;" name="iam_making" id="iam_making" value="<?=$data['iam_making']?>" > </td>
                          <th>신청링크</th>
                          <td><input type="text" style="width:400px;" name="apply_link" id="apply_link" value="<?=$data['apply_link']?>" > </td>
                      </tr>
    
                      <tr>  
                          <th>신청상태</th>
                          <td><input type="text" style="width:400px;" name="iam_apply" id="iam_apply" value="<?=$data['iam_apply']?>" > </td>

                      </tr>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='iam_auto_making_db.php';return false;"><i class="fa fa-list"></i> 아이엠자동생성목록</button>
            </div>            
          </div><!-- /.row -->
        </section><!-- /.content -->
        </form>
      </div><!-- /.content-wrapper -->

      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      