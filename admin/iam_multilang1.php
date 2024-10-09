<style>
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
}
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

<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
var mem_id="";
function goPage(pgNum) {
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>";
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
             아이엠다국어 관리
            <small>아이엠 다국어를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">아이엠다국어관리</li>
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
            <div class="col-xs-12" style="padding-bottom:10px">
              <form method="get" name="search_form" id="search_form">
                <div class="box-tools">
                  <div class="input-group" style="width: 250px;">
                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름/소속직책" value="<?=$search_key?>">
                    <div class="input-group-btn">
                      <button class="btn btn-sm btn-default" style="height: 30px;"><i class="fa fa-search"></i></button>
                    </div>
                </div>
              </form>
            </div> 
              <!-- <a class="btn btn-primary" style="position:absolute;top:0px;right:20px;" onclick="alert(1)">이용자 보기</a> -->
            </div>            
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
                      <col width="5%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>NO</th>
                        <th>위치</th>
                        <th>한국어</th>
                        <th>영어</th>
                        <th>중국어</th>
                        <th>일본어</th>
                        <th>인도어</th>
                        <th>프랑스어</th>
                        <th>추가/수정/삭제</th>						
                      </tr>
                    </thead>
                    <tbody>
                    <?

                      //디폴트 아바타
                      $sql = "select * from Gn_Iam_multilang ";
                      $result=mysql_query($sql) or die(mysql_error());
                      $row=mysql_fetch_array($result);


                      $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                      $startPage = $nowPage?$nowPage:1;
                      $pageCnt = 20;
                        $count_query = "select count(no) from Gn_Iam_multilang";
                        $count_result = mysql_query($count_query);
                        $count_row = mysql_fetch_array($count_result);
                        $totalCnt	=  $count_row[0];
     
                    $query = "SELECT * FROM Gn_Iam_multilang";

                      $query = $query." WHERE 1=1 $searchStr";
                      //$res = mysql_query($query);
                      //$totalCnt	=  mysql_num_rows($res);
                      $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                      $number	= $totalCnt - ($nowPage - 1) * $pageCnt;             
                      if(!$orderField){
                        $orderField = "up_data";
                      }
                      $orderQuery .= " ORDER BY $orderField $dir $limitStr";            	
                      $i = 1;
                      $c=0;
                      $query .= "$orderQuery";
                      $res = mysql_query($query);
                      while($row = mysql_fetch_array($res)) {
                          $fquery = "select count(*) from Gn_Iam_multilang";
                          $fresult = mysql_query($fquery);
                          $frow = mysql_fetch_array($fresult);
                          //$friend_count	=  $frow[0];

                          $cquery = "select count(*) from Gn_Iam_multilang" ;
                          $cresult = mysql_query($cquery);
                          $crow = mysql_fetch_array($cresult);
                          //$contents_count	=  $crow[0];
                      ?>
                          <tr>
                            <td><?=$number--?></td>
                            <td><?=$row[position]?></td>
                            <td><?=$row[korean]?></td>
                            <td><?=$row[english]?></td>
                            <td><?=$row[china]?></td>
                            <td><?=$row[japan]?></td>
                            <td><?=$row[india]?></td>
                            <td><?=$row[France]?></td>

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
function delNameCard(card_idx){
    var msg = confirm('정말로 삭제하시겠습니까?');
    if(msg){
        $.ajax({
          type:"POST",
          url:"/admin/ajax/_db_controller.php",
          data:{
            mode:"delete_name_card",
            card_idx:card_idx
            },
          success:function(data){
            alert(data);
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

$(function(){
    $('.chkagree').change(function() {
      var id = $(this)[0].id;
      var phone_display = $(this)[0].checked;
      if($(this)[0].checked){
        phone_display = "Y";
      }else{
        phone_display = "N";
      }
      var card_idx = id.split("_")[2];
      $.ajax({
          type:"POST",
          url:"/admin/ajax/_db_controller.php",
          data:{
            mode:"update_name_card - phone_display",
            phone_display:phone_display,
            card_idx:card_idx

            },
          success:function(data){
      //      alert(data);
            location.reload();
          },
          error: function(){
            alert('삭제 실패');
          }
        });
    });
    $('.chkclick').change(function() {
      var id = $(this)[0].id;
      var sample_click = $(this)[0].checked;
      if(sample_click){
        sample_click = "Y";
      }else{
        sample_click = "N";
      }
      var card_idx = id.split("_")[2];
      $.ajax({
          type:"POST",
          url:"/admin/ajax/_db_controller.php",
          data:{
            mode:"update_name_card - sample_click",
            sample_click:sample_click,
            card_idx:card_idx

            },
          success:function(data){
    //        alert(data);
            location.reload();
          },
          error: function(){
            alert('삭제 실패');
          }
        });
    });
});
</script>
      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      