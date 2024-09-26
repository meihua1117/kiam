<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
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
   location.href = '?<?=$nowPage?>&nowPage='+pgNum;
  }
</script>
<!-- Top 메뉴 -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
<div class="wrapper" style="display: flex;overflow: initial;">
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-width: 767px;width: 100%">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>기본 관리<small>기본 정보를  관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">기본 정보 관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12" style="padding-bottom:20px"></div>
            </div>
            <form method="post" name="form1" id="form1" enctype="multipart/form-data" action="/admin/ajax/save_app.php">
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">앱 파일 관리</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="detail1" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="16%">
                                    <col width="84%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>사용자 파일</th>
                                        <td>
                                            <input type="file" name="app-release" />
                                            <?if(file_exists($_SERVER['DOCUMENT_ROOT']."/app/app-release.apk")) {?>
                                                <a href="http://www.kiam.kr/app/app-release_.apk">http://www.kiam.kr/app/app-release.apk</a>
                                            <?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>임시 파일 #1</th>
                                        <td>
                                            <input type="file" name="app_link1" />
                                            <?if(file_exists($_SERVER['DOCUMENT_ROOT']."/app/app_link1.apk")) {?>
                                                <a href="http://www.kiam.kr/app/app_link1.apk">http://www.kiam.kr/app/app_link1.apk</a>
                                            <?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>임시 파일 #2</th>
                                        <td>
                                            <input type="file" name="app_link2" />
                                            <?if(file_exists($_SERVER['DOCUMENT_ROOT']."/app/app_link2.apk")) {?>
                                                <a href="http://www.kiam.kr/app/app_link2.apk">http://www.kiam.kr/app/app_link2.apk</a>
                                            <?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>임시 파일 #3</th>
                                        <td>
                                            <input type="file" name="app_link3" />
                                            <?if(file_exists($_SERVER['DOCUMENT_ROOT']."/app/app_link3.apk")) {?>
                                                <a href="http://www.kiam.kr/app/app_link3.apk">http://www.kiam.kr/app/app_link3.apk</a>
                                            <?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>임시 파일 #4</th>
                                        <td>
                                            <input type="file" name="app_link4" />
                                            <?if(file_exists($_SERVER['DOCUMENT_ROOT']."/app/app_link4.apk")) {?>
                                                <a href="http://www.kiam.kr/app/app_link4.apk">http://www.kiam.kr/app/app_link4.apk</a>
                                            <?}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>디버</th>
                                        <td>
                                            <input type="file" name="dber" />
                                            <?if(file_exists($_SERVER['DOCUMENT_ROOT']."/app/db.exe")) {?>
                                                <a href="http://www.kiam.kr/app/db.exe">http://www.kiam.kr/app/db.exe</a>
                                            <?}?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
                <div class="box-footer" style="text-align:center">
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                </div>
            </form>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div>
<!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      