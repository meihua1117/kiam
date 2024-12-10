<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");
// 가입 회원 상세 정보
$query = "select * from crawler_member_real where cmid='{$cmid}'";
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
	if($('#password').val() != "") {
	    if($('#password').val() != $('#password_re').val()) {
	        alert('비밀번호를 확인해주세요');
	        return;
	    }
	}
	if(msg){
      var data = $('#dForm').serialize();
			$.ajax({
				type:"POST",
				url:"/admin/ajax/crawler_user_change.php",
				data: data,
				success:function(){
					alert('저장되었습니다.');
					location = 'crawler_member_list.php';
					location.reload();
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
            디버회원관리
            <small>디버회원관리을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">디버회원관리</li>
          </ol>
        </section>

        <form method="post" id="dForm" name="dForm">
        <input type="hidden" name="cmid" value="<?=$data['cmid']?>" />
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
              디버회원정보 수정
              </div>            
          </div>
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">가입회원상세정보</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="16%">
                      <col width="84%">
                     </colgroup>
                    <tbody>
                      <tr>
                        <th>회원아이디</th>
                        <td><input type="text" style="width:200px;" name="user_id" value="<?=$data['user_id']?>" > </td>
                      </tr>
                      <tr>
                        <th>회원이름</th>
                        <td><input type="text" style="width:200px;" name="user_name" value="<?=$data['user_name']?>" > </td>
                      </tr>
                      <tr>
                        <th>비밀번호</th>
                        <td><input type="password" style="width:200px;" name="password" id="password" value="" > </td>
                      </tr>
                      <tr>
                        <th>비밀번호 확인</th>
                        <td><input type="password" style="width:200px;" name="password_re" id="password_re" value="" > </td>
                      </tr>
                      <tr>
                        <th>전화번호</th>
                        <td><input type="text" style="width:200px;" name="cell" value="<?=$data['cell']?>" > </td>
                      </tr>
                      <tr>
                        <th>이메일</th>
                        <td><input type="text" style="width:200px;" name="email" value="<?=$data['email']?>" > </td>
                      </tr>                      
                      <tr>
                        <th>회원주소</th>
                        <td><input type="text" style="width:200px;" name="address" value="<?=$data['address']?>" > </td>
                      </tr>
                      <tr>
                        <th>폰디비승인관리</th>
                        <td>
                            <select name="status">
                                <option value="Y" <?php echo $data['status']=="Y"?"selected":""?>>사용</option> 
                                <option value="N" <?php echo $data['status']=="N"?"selected":""?>>미사용</option> 
                            </select>
                            <input type="text" style="width:200px;" name="use_cnt" value="<?=$data['use_cnt']?>" > 
                    	    <input type="date" style="width:200px;" name="term" id="term" value="<?=$data['term']?>" > </td>
                      </tr>    
                      <tr>
                        <th>폰디비추가구매</th>
                        <td>
                            <input type="text" style="width:200px;" name="extra_db_cnt" value="<?=$data['extra_db_cnt']?>" > 
                        </td>
                      </tr>    
                      <tr>
                        <th>메일디비승인관리</th>
                        <td>
                            <select name="search_email_yn">
                                <option value="Y" <?php echo $data['search_email_yn']=="Y"?"selected":""?>>사용</option> 
                                <option value="N" <?php echo $data['search_email_yn']=="N"?"selected":""?>>미사용</option> 
                            </select>
                            <input type="text" style="width:200px;" placeholder="승인건수" name="search_email_cnt" id="search_email_cnt" value="<?=$data['search_email_cnt']?>" >
                            <input type="date" style="width:200px;" name="search_email_date" id="search_email_date" value="<?=$data['search_email_date']?>" >
                        </td>
                      </tr>     
                      <tr>
                        <th>메일디비추가구매</th>
                        <td>
                            <input type="text" style="width:200px;" name="extra_email_cnt" value="<?=$data['extra_email_cnt']?>" > 
                        </td>
                      </tr>    
                      <tr>
                        <th>쇼핑디비승인관리</th>
                        <td>
                            <select name="shopping_yn">
                                <option value="Y" <?php echo $data['shopping_yn']=="Y"?"selected":""?>>사용</option> 
                                <option value="N" <?php echo $data['shopping_yn']=="N"?"selected":""?>>미사용</option> 
                            </select>

                            <input type="text" style="width:200px;" name="shopping_cnt" value="<?=$data['shopping_cnt']?>" > 

                            <input type="date" style="width:200px;" name="shopping_end_date" id="shopping_end_date" value="<?=$data['shopping_end_date']?>" >
                        </td>
                      </tr>                                               
                      <tr>
                        <th>쇼핑디비추가구매</th>
                        <td>
                            <input type="text" style="width:200px;" name="extra_shopping_cnt" value="<?=$data['extra_shopping_cnt']?>" > 
                        </td>
                      </tr>    
                      <tr>
                        <th>시리얼번호</th>
                        <td><input type="text" style="width:200px;" name="serial" value="<?=$data['serial']?>" > </td>
                      </tr>                        
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='crawler_member_list.php';return false;"><i class="fa fa-list"></i> 회원목록</button>
            </div>            
          </div><!-- /.row -->          
        </section><!-- /.content -->
        </form>
      </div><!-- /.content-wrapper -->
      <!-- Footer -->
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      