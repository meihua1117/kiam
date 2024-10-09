<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
//language 삭제
function del_language(idx){
	var msg = confirm('정말로 삭제하시겠습니까?');
	if(msg){
			$.ajax({
				type:"POST",
				url:"/admin/ajax/Iam_language_save.php",
				data:{mode:"del",idx:idx},
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
function goPage(pgNum) {
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>";
}
function loginGo(sub_domain,mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#sub_domain').val(sub_domain);
    $('#login_form').submit();
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
.agree{
     /*background: #d5ffd5!important;   */
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
</style>
<div class="loading_div" >
    <img src="/images/ajax-loader.gif">
</div>

<div class="wrapper">
  <!-- Top 메뉴 -->
  <?include_once "include/admin_header_menu.inc.php";?>
  <!-- Left 메뉴 -->
  <?include_once "include/admin_left_menu.inc.php";?>
      <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>다국어IAM관리<small>아이엠 다국어정보를 관리합니다.</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">아이엠 분양관리</li>
      </ol>
    </section>
        
    <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
    <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />
    <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />
    <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
    <input type="hidden" name="sub_domain" id="sub_domain" value="<?=$data['sub_domain']?>" />
    </form>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12" style="padding-bottom:20px">
          <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='service_multilang_detail.php';return false;"><i class="fa fa-download"></i> 등록</button>
        </div>
      </div>

      <div class="row">
        <div class="box">
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                 <colgroup>
                      <col width="30px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">

                 </colgroup>
                <thead>
                  <tr>
                    <th>번호</th>
                    <th>수정/삭제</th>
                    <th>한국어</th>
                    <th>영어</th>
                    <th>중국어</th>
                    <th>일본어</th>
                    <th>힌디어</th>
                    <th>프랑스어</th>
                    <th>프로필</th>
                    <th>스토리</th>
                    <th>연락처</th>
                    <th>프렌즈</th>
                    <th>관리자</th>

                  </tr>
                </thead>
                <tbody>
                  <?
                    $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                    $startPage = $nowPage?$nowPage:1;
                    $pageCnt = 20;
                    // 검색 조건을 적용한다.
                    $query = "
                            SELECT
                                SQL_CALC_FOUND_ROWS
                                *
                            FROM Gn_Iam_multilang ";
                    $res	    = mysql_query($query);
                    $totalCnt	=  mysql_num_rows($res);
                    $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                    $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                    $orderQuery .= " ORDER BY no ".$limitStr;
                    $i = 1;
                    $query .= "$orderQuery";
                    $res = mysql_query($query);
                    while($row = mysql_fetch_array($res)) {
                  ?>
                  <tr>
                    <td><?=$i?></td>
                    <td>
                        <a href="service_multilang_detail.php?idx=<?=$row['no']?>">수정</a> /
                        <a href="javascript:del_language('<?=$row['no']?>')">삭제</a>
                    </td>
                    <td><?=$row['korean']?></td>
                    <td><?=$row['english']?></td>
                    <td><?=$row['china']?></td>
                    <td><?=$row['japan']?></td>
                    <td><?=$row['india']?></td>
                    <td><?=$row['france']?></td>
                    <td><?=$row['profile_menu']?></td>
                    <td><?=$row['story_menu']?></td>
                    <td><?=$row['contact_menu']?></td>
                    <td><?=$row['friends_menu']?></td>
                    <td><?=$row['manager']?></td>

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
    </section><!-- /.content -->
  </div><!-- /.row -->
</div><!-- /.content-wrapper -->
<iframe name="excel_iframe" style="display:none;"></iframe>
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>