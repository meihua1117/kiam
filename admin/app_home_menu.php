<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");

if($site == "kiam"){
	$domain1 = "www";
}
else{
	$domain1 = $site;
}
$domain = $domain1.".kiam.kr";

$sql_service = "select admin_app_home from Gn_Iam_Service where sub_domain like '%".$domain."%'";
$res_service = mysqli_query($self_con, $sql_service);
$row_service = mysqli_fetch_array($res_service);
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
var mem_id="";

function logo_form_save(idx){
  if($('input[name=logo_move_url]').val() == ''){
    alert('로고 링크를 입력해 주세요.');
    return;
  }
  else{
    $("form[name=logo_Form_"+idx+"]").submit();
  }
}

function rolling_form_save(idx){
  if($('input[name=rolling_move_url]').val() == ''){
    alert('배너 링크를 입력해 주세요.');
    return;
  }
  else{
    $("form[name=rolling_Form_"+idx+"]").submit();
  }
}

function menu_form_save(idx){
  if($('input[name=menu_move_url]').val() == ''){
    alert('메뉴 링크를 입력해 주세요.');
    return;
  }
  else{
    $("form[name=menu_Form_"+idx+"]").submit();
  }
}

function market_form_save(idx){
  if($('input[name=market_move_url]').val() == ''){
    alert('메뉴 링크를 입력해 주세요.');
    return;
  }
  else{
    $("form[name=market_Form_"+idx+"]").submit();
  }
}

function card_form_save(idx){
  if($('input[name=card_move_url]').val() == ''){
    alert('메뉴 링크를 입력해 주세요.');
    return;
  }
  else{
    $("form[name=card_Form_"+idx+"]").submit();
  }
}

function banner_form_save(idx){
  if($('input[name=banner_move_url]').val() == ''){
    alert('메뉴 링크를 입력해 주세요.');
    return;
  }
  else{
    $("form[name=banner_Form_"+idx+"]").submit();
  }
}

function change_form_save(idx){
  if($('input[name=change_move_url]').val() == ''){
    alert('메뉴 링크를 입력해 주세요.');
    return;
  }
  else{
    $("form[name=change_Form_"+idx+"]").submit();
  }
}

function delete_row_roll(val){
  var idx = $("form[name=rolling_Form_"+val+"]").find('input[name=idx]').val();
  if(!idx){
    $("form[name=rolling_Form_"+val+"]").hide();
  }
  else{
    if(confirm('삭제하시겠습니까?')){
      $.ajax({
        type:'post',
        url:"ajax/app_home_ajax.php",
        data:{mode:'del', idx:idx},
        dataType:'json',
        success:function(data){
          location.reload();
        }
      })
    }
  }
}

function delete_row_menu(val){
  var idx = $("form[name=menu_Form_"+val+"]").find('input[name=idx]').val();
  if(!idx){
    $("form[name=menu_Form_"+val+"]").hide();
  }
  else{
    if(confirm('삭제하시겠습니까?')){
      $.ajax({
        type:'post',
        url:"ajax/app_home_ajax.php",
        data:{mode:'del', idx:idx},
        dataType:'json',
        success:function(data){
          location.reload();
        }
      })
    }
  }
}

function delete_row_market(val){
  var idx = $("form[name=market_Form_"+val+"]").find('input[name=idx]').val();
  if(!idx){
    $("form[name=market_Form_"+val+"]").hide();
  }
  else{
    if(confirm('삭제하시겠습니까?')){
      $.ajax({
        type:'post',
        url:"ajax/app_home_ajax.php",
        data:{mode:'del', idx:idx},
        dataType:'json',
        success:function(data){
          location.reload();
        }
      })
    }
  }
}

function delete_row_card(val){
  var idx = $("form[name=card_Form_"+val+"]").find('input[name=idx]').val();
  if(!idx){
    $("form[name=card_Form_"+val+"]").hide();
  }
  else{
    if(confirm('삭제하시겠습니까?')){
      $.ajax({
        type:'post',
        url:"ajax/app_home_ajax.php",
        data:{mode:'del', idx:idx},
        dataType:'json',
        success:function(data){
          location.reload();
        }
      })
    }
  }
}

function delete_row_banner(val){
  var idx = $("form[name=banner_Form_"+val+"]").find('input[name=idx]').val();
  if(!idx){
    $("form[name=banner_Form_"+val+"]").hide();
  }
  else{
    if(confirm('삭제하시겠습니까?')){
      $.ajax({
        type:'post',
        url:"ajax/app_home_ajax.php",
        data:{mode:'del', idx:idx},
        dataType:'json',
        success:function(data){
          location.reload();
        }
      })
    }
  }
}

function add_menu(type){
  if(type == "rolling"){
    var cnt = $("#rolling_set").find('form').length * 1;
    cnt++;
    var html = '<form method="post" id="rolling_Form_'+cnt+'" name="rolling_Form_'+cnt+'" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data"><input type="hidden" name="position" value="rolling" /><input type="hidden" name="mode" value="save" /><input type="hidden" name="site_iam" value="<?=$site?>" /><input type="hidden" name="idx" value="" /><div class="box" id="rolling_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;"><p class="menu_title">배너'+cnt+'번</p><input type="file" name="app_rolling" class="input_file"><img src="" style="width:50px;"><input type="text" name="rolling_move_url" placeholder="배너클릭시 이동할 링크입력하세요." style="width:30%;"><input type="text" name="rolling_display_order" placeholder="노출순서" style="width:7%;margin-left:10px;"><a href="javascript:delete_row_roll('+cnt+')" class="delete_a">X</a><button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="rolling_form_save('+cnt+');return false;">저장</button></div></form>';

    $("#rolling_set").append(html);
  }
  else if(type == "menu"){
    var cnt = $("#menu_set").find('form').length * 1;
    cnt++;
    var html = '<form method="post" id="menu_Form_'+cnt+'" name="menu_Form_'+cnt+'" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data"><input type="hidden" name="position" value="menu" /><input type="hidden" name="mode" value="save" /><input type="hidden" name="site_iam" value="<?=$site?>" /><input type="hidden" name="idx" value="" /><div class="box" id="menu_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;"><p class="menu_title">매뉴'+cnt+'번</p><input type="file" name="app_menu" class="input_file"><img src="" style="width:50px;"><input type="text" name="menu_move_url" placeholder="링크입력" style="width:14%;"><input type="text" name="menu_title" placeholder="제목입력" style="width:14%;margin-left:5px;"><input type="text" name="menu_display_order" placeholder="노출순서" style="width:7%;margin-left:10px;"><a href="javascript:delete_row_menu('+cnt+')" class="delete_a">X</a><button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="menu_form_save('+cnt+');return false;">저장</button></div></form>';

    $("#menu_set").append(html);
  }
  else if(type == "market"){
    var cnt = $("#market_set").find('form').length * 1;
    cnt++;
    var html = '<form method="post" id="market_Form_'+cnt+'" name="market_Form_'+cnt+'" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data"><input type="hidden" name="position" value="market" /><input type="hidden" name="mode" value="save" /><input type="hidden" name="site_iam" value="<?=$site?>" /><input type="hidden" name="idx" value="" /><div class="box" id="market_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;"><p class="menu_title">매뉴'+cnt+'번</p><input type="file" name="app_market" class="input_file"><img src="" style="width:50px;"><input type="text" name="market_move_url" placeholder="링크입력" style="width:14%;"><input type="text" name="market_title" placeholder="제목입력" style="width:14%;margin-left:5px;"><input type="text" name="market_display_order" placeholder="노출순서" style="width:7%;margin-left:10px;"><a href="javascript:delete_row_market('+cnt+')" class="delete_a">X</a><button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="market_form_save('+cnt+');return false;">저장</button></div></form>';

    $("#market_set").append(html);
  }
  else if(type == "card"){
    var cnt = $("#card_set").find('form').length * 1;
    cnt++;
    var html = '<form method="post" id="card_Form_'+cnt+'" name="card_Form_'+cnt+'" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data"><input type="hidden" name="position" value="card" /><input type="hidden" name="mode" value="save" /><input type="hidden" name="site_iam" value="<?=$site?>" /><input type="hidden" name="idx" value="" /><div class="box" id="card_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;"><p class="menu_title">매뉴'+cnt+'번</p><input type="file" name="app_card" class="input_file"><img src="" style="width:50px;"><input type="text" name="card_move_url" placeholder="링크입력" style="width:14%;"><input type="text" name="card_title" placeholder="제목입력" style="width:14%;margin-left:5px;"><input type="text" name="card_display_order" placeholder="노출순서" style="width:7%;margin-left:10px;"><a href="javascript:delete_row_card('+cnt+')" class="delete_a">X</a><button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="card_form_save('+cnt+');return false;">저장</button></div></form>';

    $("#card_set").append(html);
  }
  else if(type == "banner"){
    var cnt = $("#banner_set").find('form').length * 1;
    cnt++;
    var html = '<form method="post" id="banner_Form_'+cnt+'" name="banner_Form_'+cnt+'" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data"><input type="hidden" name="position" value="banner" /><input type="hidden" name="mode" value="save" /><input type="hidden" name="site_iam" value="<?=$site?>" /><input type="hidden" name="idx" value="" /><div class="box" id="banner_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;"><p class="menu_title">배너'+cnt+'번</p><input type="file" name="app_banner" class="input_file"><img src="" style="width:50px;"><input type="text" name="banner_move_url" placeholder="배너클릭시 이동할 링크입력하세요." style="width:30%;"><input type="text" name="banner_display_order" placeholder="노출순서" style="width:7%;margin-left:10px;"><a href="javascript:delete_row_banner('+cnt+')" class="delete_a">X</a><button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="banner_form_save('+cnt+');return false;">저장</button></div></form>';

    $("#banner_set").append(html);
  }
}

$(function(){
  $('.switch_logo').on("change", function() {
      var id = $(this).find("input[type=checkbox]").val();
      var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
      $.ajax({
          type:"POST",
          url:"/admin/ajax/app_home_ajax.php",
          data:{
              mode:'status', 
              id:id,
              status:status
          },
          success:function(data){
              // alert('신청되었습니다.');
              location.reload();
          }
      })
  });
  $('.switch_roll').on("change", function() {
      var id = $(this).find("input[type=checkbox]").val();
      var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
      $.ajax({
          type:"POST",
          url:"/admin/ajax/app_home_ajax.php",
          data:{
              mode:'status', 
              id:id,
              status:status
          },
          success:function(data){
              // alert('신청되었습니다.');
              location.reload();
          }
      })
  });
  $('.switch_menu').on("change", function() {
      var id = $(this).find("input[type=checkbox]").val();
      var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
      $.ajax({
          type:"POST",
          url:"/admin/ajax/app_home_ajax.php",
          data:{
              mode:'status', 
              id:id,
              status:status
          },
          success:function(data){
              // alert('신청되었습니다.');
              location.reload();
          }
      })
  });
  $('.switch_market').on("change", function() {
      var id = $(this).find("input[type=checkbox]").val();
      var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
      $.ajax({
          type:"POST",
          url:"/admin/ajax/app_home_ajax.php",
          data:{
              mode:'status', 
              id:id,
              status:status
          },
          success:function(data){
              // alert('신청되었습니다.');
              location.reload();
          }
      })
  });
  $('.switch_card').on("change", function() {
      var id = $(this).find("input[type=checkbox]").val();
      var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
      $.ajax({
          type:"POST",
          url:"/admin/ajax/app_home_ajax.php",
          data:{
              mode:'status', 
              id:id,
              status:status
          },
          success:function(data){
              // alert('신청되었습니다.');
              location.reload();
          }
      })
  });
  $('.switch_banner').on("change", function() {
      var id = $(this).find("input[type=checkbox]").val();
      var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
      $.ajax({
          type:"POST",
          url:"/admin/ajax/app_home_ajax.php",
          data:{
              mode:'status', 
              id:id,
              status:status
          },
          success:function(data){
              // alert('신청되었습니다.');
              location.reload();
          }
      })
  });
  $('.switch_change').on("change", function() {
      var id = $(this).find("input[type=checkbox]").val();
      var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
      $.ajax({
          type:"POST",
          url:"/admin/ajax/app_home_ajax.php",
          data:{
              mode:'status', 
              id:id,
              status:status
          },
          success:function(data){
              // alert('신청되었습니다.');
              location.reload();
          }
      })
  });
});

function save_menu_desc(){
  var txt = $("#menu_desc").val();
  $.ajax({
      type:"POST",
      url:"/admin/ajax/app_home_ajax.php",
      data:{
          mode:'menu_desc', 
          txt:txt,
          site:'<?=$site?>'
      },
      success:function(data){
          // alert('신청되었습니다.');
          location.reload();
      }
  });
}

function save_market_desc(){
  var txt = $("#market_desc").val();
  $.ajax({
      type:"POST",
      url:"/admin/ajax/app_home_ajax.php",
      data:{
          mode:'market_desc', 
          txt:txt,
          site:'<?=$site?>'
      },
      success:function(data){
          // alert('신청되었습니다.');
          location.reload();
      }
  });
}

function save_card_desc(){
  var txt = $("#card_desc").val();
  $.ajax({
      type:"POST",
      url:"/admin/ajax/app_home_ajax.php",
      data:{
          mode:'card_desc', 
          txt:txt,
          site:'<?=$site?>'
      },
      success:function(data){
          // alert('신청되었습니다.');
          location.reload();
      }
  });
}

function save_market_title(){
  var txt = $("#market_t_title").val();
  $.ajax({
      type:"POST",
      url:"/admin/ajax/app_home_ajax.php",
      data:{
          mode:'market_title', 
          txt:txt,
          site:'<?=$site?>'
      },
      success:function(data){
          // alert('신청되었습니다.');
          location.reload();
      }
  });
}

function save_card_title(){
  var txt = $("#card_t_title").val();
  $.ajax({
      type:"POST",
      url:"/admin/ajax/app_home_ajax.php",
      data:{
          mode:'card_title', 
          txt:txt,
          site:'<?=$site?>'
      },
      success:function(data){
          // alert('신청되었습니다.');
          location.reload();
      }
  });
}

function save_notice_title(){
  var txt = $("#notice_t_title").val();
  $.ajax({
      type:"POST",
      url:"/admin/ajax/app_home_ajax.php",
      data:{
          mode:'notice_title', 
          txt:txt,
          site:'<?=$site?>'
      },
      success:function(data){
          // alert('신청되었습니다.');
          location.reload();
      }
  });
}

function set_show(val){
  $.ajax({
      type:"POST",
      url:"/admin/ajax/app_home_ajax.php",
      data:{
          mode:'set_show', 
          set_type:val,
          domain:'<?=$domain?>'
      },
      success:function(data){
          alert('신청되었습니다.');
          location.reload();
      }
  });
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 245;
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
.switch_logo input {
    opacity: 0;
    width: 0;
    height: 0;
}
.switch_roll input {
    opacity: 0;
    width: 0;
    height: 0;
}
.switch_menu input {
    opacity: 0;
    width: 0;
    height: 0;
}
.switch_market input {
    opacity: 0;
    width: 0;
    height: 0;
}
.switch_card input {
    opacity: 0;
    width: 0;
    height: 0;
}
.switch_banner input {
    opacity: 0;
    width: 0;
    height: 0;
}
.switch_change input {
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
    width: 55px;
    height: 28px;
}
.switch_logo {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 28px;
}
.switch_roll {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 28px;
}
.switch_menu {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 28px;
}
.switch_market {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 28px;
}
.switch_card {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 28px;
}
.switch_banner {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 28px;
}
.switch_change {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 28px;
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
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;

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
  margin:0 25px;
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

.menu_title{
  padding: 5px;
  width: 15%;
  text-align: center;
  background-color: #d4d4d4;
  margin: 0 14px;
}

.input_file{
  width:20%;
}

.delete_a{
  margin:0 10px;
  font-weight: bold;
  font-size: 20px;
}

.add_menu_a{
  border: 1px solid;
  padding: 5px;
  display: block;
  margin-top: 7px;
  width: 20%;
  margin-right: auto;
  margin-left: auto;
  background-color: #d4d4d4;
  color: #666464;
  text-align: center;
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
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
            앱홈 매뉴관리
            <small>앱홈 매뉴를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">앱홈 매뉴관리</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
            <?
            if(!$row_service[admin_app_home]){
              $checked1 = "checked";
              $checked2 = "";
            }
            else{
              $checked1 = "";
              $checked2 = "checked";
            }
            ?>
              <input type="radio" name="set_show_menu" id="admin" value="0" <?=$checked1?> onclick="set_show('0')">본사용
              <input type="radio" name="set_show_menu" id="service" value="1" <?=$checked2?> onclick="set_show('1')">분양용
            </div>
          </div>
          <div class="row box-body"  style="overflow: auto !important">
          <div>
            <h4>로고관리</h4>
            <div class="row" id="logo_set" style="text-align:center;">
              <?
              $sql_menu_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='L'";
              $res_menu_data = mysqli_query($self_con, $sql_menu_data);
              if(!mysqli_num_rows($res_menu_data)){
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='kiam' and ad_position='L'";
                $mode = "save";
              }
              else{
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='L'";
                $mode = "updat";
              }
              $res_data = mysqli_query($self_con, $sql_data);
              if(mysqli_num_rows($res_data)){
                while($data = mysqli_fetch_array($res_data)){
              ?>
              <form method="post" id="logo_Form_<?=$data['idx']?>" name="logo_Form_<?=$data['idx']?>" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="logo" />
                <input type="hidden" name="mode" value="<?=$mode?>" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="<?=$data['idx']?>" />
                <div class="box" id="logo_box" style="display: inline-flex;padding: 10px;height: 50px;">
                  <p class="menu_title">앱로고입력</p>
                  <input type="file" name="app_logo" class="input_file">
                  <?php if($data['img_url']){?>
                    <a href="<?=$data['img_url']?>" target="_blank">
                      <img class="zoom" src="<?=$data['img_url']?>" style="height:30px;">
                    </a>
                  <?php }
                  else{?>
                  <img src="" style="width:50px;">
                  <?}?>
                  <input type="text" name="logo_move_url" value="<?=$data['move_url']?>" placeholder="로고클릭시 이동할 링크입력하세요." style="width:30%;">
                  <?if($mode == "updat"){?>
                  <label class="switch_logo" style="margin:0 25px;">
                      <input type="checkbox" name="status" id="stauts_logo_<?php echo $data['idx'];?>" value="<?php echo $data['idx'];?>" <?php echo $data['use_yn']=="Y"?"checked":""?>>
                      <span class="slider round" name="status_round" id="stauts_round_<?php echo $data['idx'];?>"></span>
                  </label>
                  <?}?>
                  <button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="logo_form_save('<?=$data['idx']?>');return false;">저장</button>
                </div>
              </form>
              <?}
              }
              else{?>
              <form method="post" id="logo_Form_0" name="logo_Form_0" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="logo" />
                <input type="hidden" name="mode" value="save" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="" />
                <div class="box" id="logo_box" style="display: inline-flex;padding: 10px;height: 50px;">
                  <p class="menu_title">앱로고입력</p>
                  <input type="file" name="app_logo" class="input_file">
                  <img src="" style="width:50px;">
                  <input type="text" name="logo_move_url" placeholder="로고클릭시 이동할 링크입력하세요." style="width:30%;">
                  <!-- <label class="switch" style="margin:0 25px;">
                      <input type="checkbox" name="status" id="stauts_1" value="1">
                      <span class="slider round" name="status_round" id="stauts_round_1"></span>
                  </label> -->
                  <button class="btn btn-primary" style="margin-left: 5px;padding: 3px 10px;" onclick="logo_form_save('0');return false;">저장</button>
                </div>
              </form>
              <?}?>
            </div>          
          </div>

          <div>
            <h4>롤링매뉴</h4>
            <div class="row" id="rolling_set" style="text-align:center;">
              <?
              $R_no = 0;
              $sql_menu_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='R'";
              $res_menu_data = mysqli_query($self_con, $sql_menu_data);
              if(!mysqli_num_rows($res_menu_data)){
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='kiam' and ad_position='R'";
                $mode = "save";
              }
              else{
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='R'";
                $mode = "updat";
              }
              $res_data = mysqli_query($self_con, $sql_data);
              if(mysqli_num_rows($res_data)){
                while($data = mysqli_fetch_array($res_data)){
                  $R_no++;
              ?>
              <form method="post" id="rolling_Form_<?=$R_no?>" name="rolling_Form_<?=$R_no?>" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="rolling" />
                <input type="hidden" name="mode" value="<?=$mode?>" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="<?=$data['idx']?>" />
                <div class="box" id="rolling_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
                  <p class="menu_title">배너<?=$R_no?>번</p>
                  <input type="file" name="app_rolling" class="input_file">
                  <?php if($data['img_url']){?>
                    <a href="<?=$data['img_url']?>" target="_blank">
                      <img class="zoom" src="<?=$data['img_url']?>" style="height:30px;">
                    </a>
                  <?php }
                  else{?>
                  <img src="" style="width:50px;">
                  <?}?>
                  <input type="text" name="rolling_move_url" value="<?=$data['move_url']?>" placeholder="배너클릭시 이동할 링크입력하세요." style="width:30%;">
                  <input type="text" name="rolling_display_order" value="<?=$data[display_order]?>" placeholder="노출순서" style="width:7%;margin-left:10px;">
                  <?if($mode == "updat"){?>
                  <label class="switch_roll" style="margin:0 25px;">
                      <input type="checkbox" name="status" id="stauts_<?php echo $data['idx'];?>" value="<?php echo $data['idx'];?>" <?php echo $data['use_yn']=="Y"?"checked":""?>>
                      <span class="slider round" name="status_round" id="stauts_round_<?php echo $data['idx'];?>"></span>
                  </label>
                  <a href="javascript:delete_row_roll(<?=$R_no?>)" class="delete_a">X</a>
                  <?}?>
                  <button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="rolling_form_save(<?=$R_no?>);return false;">저장</button>
                </div>
              </form>
              <?}
              }
              else{?>
              <form method="post" id="rolling_Form_0" name="rolling_Form_0" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="rolling" />
                <input type="hidden" name="mode" value="save" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="" />
                <div class="box" id="rolling_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
                  <p class="menu_title">배너1번</p>
                  <input type="file" name="app_rolling" class="input_file">
                  <img src="" style="width:50px;">
                  <input type="text" name="rolling_move_url" placeholder="배너클릭시 이동할 링크입력하세요." style="width:30%;">
                  <input type="text" name="rolling_display_order" placeholder="노출순서" style="width:7%;margin-left:10px;">
                  <button class="btn btn-primary" style="margin-left: 5px;padding: 3px 10px;" onclick="rolling_form_save(0);return false;">저장</button>
                </div>
              </form>
              <?}?>
            </div>
            <a class="add_menu_a" href="javascript:add_menu('rolling')">매뉴추가하기</a>
          </div>

          <div>
            <h4>기본매뉴</h4>
            <div class="row" id="menu_set" style="text-align:center;">
              <?
              $M_no = 0;
              $sql_menu_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='M'";
              $res_menu_data = mysqli_query($self_con, $sql_menu_data);
              if(!mysqli_num_rows($res_menu_data)){
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='kiam' and ad_position='M'";
                $mode = "save";
              }
              else{
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='M'";
                $mode = "updat";
              }
              $res_data = mysqli_query($self_con, $sql_data);
              if(mysqli_num_rows($res_data)){
                while($data = mysqli_fetch_array($res_data)){
                  $M_no++;
              ?>
              <form method="post" id="menu_Form_<?=$M_no?>" name="menu_Form_<?=$M_no?>" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="menu" />
                <input type="hidden" name="mode" value="<?=$mode?>" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="<?=$data['idx']?>" />
                <div class="box" id="menu_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
                  <p class="menu_title">매뉴<?=$M_no?>번</p>
                  <input type="file" name="app_menu" class="input_file">
                  <?php if($data['img_url']){?>
                    <a href="<?=$data['img_url']?>" target="_blank">
                      <img class="zoom" src="<?=$data['img_url']?>" style="height:30px;">
                    </a>
                  <?php }
                  else{?>
                  <img src="" style="width:50px;">
                  <?}?>
                  <input type="text" name="menu_move_url" value="<?=$data['move_url']?>" placeholder="링크입력" style="width:14%;">
                  <input type="text" name="menu_title" value="<?=$data['title']?>" placeholder="제목입력" style="width:14%;margin-left:5px;">
                  <input type="text" name="menu_display_order" value="<?=$data[display_order]?>" placeholder="노출순서" style="width:7%;margin-left:10px;">
                  <?if($mode == "updat"){?>
                  <label class="switch_menu" style="margin:0 25px;">
                      <input type="checkbox" name="status" id="stauts_<?php echo $data['idx'];?>" value="<?php echo $data['idx'];?>" <?php echo $data['use_yn']=="Y"?"checked":""?>>
                      <span class="slider round" name="status_round" id="stauts_round_<?php echo $data['idx'];?>"></span>
                  </label>
                  <a href="javascript:delete_row_menu(<?=$R_no?>)" class="delete_a">X</a>
                  <?}?>
                  <button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="menu_form_save(<?=$M_no?>);return false;">저장</button>
                </div>
              </form>
              <?}
              }
              else{?>
              <form method="post" id="menu_Form_0" name="menu_Form_0" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="menu" />
                <input type="hidden" name="mode" value="save" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="" />
                <div class="box" id="menu_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
                  <p class="menu_title">매뉴1번</p>
                  <input type="file" name="app_menu" class="input_file">
                  <img src="" style="width:50px;">
                  <input type="text" name="menu_move_url" placeholder="링크입력" style="width:14%;">
                  <input type="text" name="menu_title" placeholder="제목입력" style="width:14%;margin-left:5px;">
                  <input type="text" name="menu_display_order" placeholder="노출순서" style="width:7%;margin-left:10px;">
                  <button class="btn btn-primary" style="margin-left: 5px;padding: 3px 10px;" onclick="menu_form_save(0);return false;">저장</button>
                </div>
              </form>
              <?}?>
            </div>          
            <div>
              <?
              $sql_desc = "select menu_desc from Gn_App_Home_Manager where menu_desc!='' and site_iam='{$site}'";
              $res_desc = mysqli_query($self_con, $sql_desc);
              $row_desc = mysqli_fetch_array($res_desc);
              ?>
              <input type="text" name="menu_desc" id="menu_desc" value="<?=$row_desc[menu_desc]?$row_desc[menu_desc]:'';?>" placeholder="기본매뉴 설명문입력" style="width:60%;margin-top:5px;">
              <button class="btn btn-primary" style="margin-left: 5px;margin-top:3px;padding: 3px 10px;" onclick="save_menu_desc();return false;">저장</button>
            </div>
            <a class="add_menu_a" href="javascript:add_menu('menu')">매뉴추가하기</a>
          </div>

          <div>
            <?
            $sql_title = "select market_title from Gn_App_Home_Manager where site_iam='{$site}' and market_title!=''";
            $res_title = mysqli_query($self_con, $sql_title);
            $row_title = mysqli_fetch_array($res_title);
            ?>
            <h4><?=$row_title[market_title]?$row_title[market_title]:'IAM마켓';?></h4>
            <div class="row" id="market_set" style="text-align:center;">
              <?
              $I_no = 0;
              $sql_menu_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='I'";
              $res_menu_data = mysqli_query($self_con, $sql_menu_data);
              if(!mysqli_num_rows($res_menu_data)){
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='kiam' and ad_position='I'";
                $mode = "save";
              }
              else{
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='I'";
                $mode = "updat";
              }
              $res_data = mysqli_query($self_con, $sql_data);
              if(mysqli_num_rows($res_data)){
                while($data = mysqli_fetch_array($res_data)){
                  $I_no++;
              ?>
              <form method="post" id="market_Form_<?=$I_no?>" name="market_Form_<?=$I_no?>" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="market" />
                <input type="hidden" name="mode" value="<?=$mode?>" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="<?=$data['idx']?>" />
                <div class="box" id="market_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
                  <p class="menu_title">매뉴<?=$I_no?>번</p>
                  <input type="file" name="app_market" class="input_file">
                  <?php if($data['img_url']){?>
                    <a href="<?=$data['img_url']?>" target="_blank">
                      <img class="zoom" src="<?=$data['img_url']?>" style="height:30px;">
                    </a>
                  <?php }
                  else{?>
                  <img src="" style="width:50px;">
                  <?}?>
                  <input type="text" name="market_move_url" value="<?=$data['move_url']?>" placeholder="링크입력" style="width:14%;">
                  <input type="text" name="market_title" value="<?=$data['title']?>" placeholder="제목입력" style="width:14%;margin-left:5px;">
                  <input type="text" name="market_display_order" value="<?=$data[display_order]?>" placeholder="노출순서" style="width:7%;margin-left:10px;">
                  <?if($mode == "updat"){?>
                  <label class="switch_market" style="margin:0 25px;">
                      <input type="checkbox" name="status" id="stauts_<?php echo $data['idx'];?>" value="<?php echo $data['idx'];?>" <?php echo $data['use_yn']=="Y"?"checked":""?>>
                      <span class="slider round" name="status_round" id="stauts_round_<?php echo $data['idx'];?>"></span>
                  </label>
                  <a href="javascript:delete_row_market(<?=$I_no?>)" class="delete_a">X</a>
                  <?}?>
                  <button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="market_form_save(<?=$I_no?>);return false;">저장</button>
                </div>
              </form>
              <?}
              }
              else{?>
              <form method="post" id="market_Form_0" name="market_Form_0" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="market" />
                <input type="hidden" name="mode" value="save" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="" />
                <div class="box" id="market_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
                  <p class="menu_title">매뉴1번</p>
                  <input type="file" name="app_market" class="input_file">
                  <img src="" style="width:50px;">
                  <input type="text" name="market_move_url" placeholder="링크입력" style="width:14%;">
                  <input type="text" name="market_title" placeholder="제목입력" style="width:14%;margin-left:5px;">
                  <input type="text" name="market_display_order" placeholder="노출순서" style="width:7%;margin-left:10px;">
                  <button class="btn btn-primary" style="margin-left: 5px;padding: 3px 10px;" onclick="market_form_save(0);return false;">저장</button>
                </div>
              </form>
              <?}?>
            </div>          
            <div>
              <?
              $sql_desc = "select market_desc from Gn_App_Home_Manager where market_desc!='' and site_iam='{$site}'";
              $res_desc = mysqli_query($self_con, $sql_desc);
              $row_desc = mysqli_fetch_array($res_desc);
              ?>
              <input type="text" name="market_desc" id="market_desc" value="<?=$row_desc[market_desc]?$row_desc[market_desc]:'';?>" placeholder="IAM마켓 설명문입력" style="width:60%;margin-top:5px;">
              <button class="btn btn-primary" style="margin-left: 5px;margin-top:3px;padding: 3px 10px;" onclick="save_market_desc();return false;">저장</button>
            </div>
            <div>
              <input type="text" name="market_t_title" id="market_t_title" value="<?=$row_title[market_title]?$row_title[market_title]:'';?>" placeholder="타이틀입력" style="width:60%;margin-top:5px;">
              <button class="btn btn-primary" style="margin-left: 5px;margin-top:3px;padding: 3px 10px;" onclick="save_market_title();return false;">저장</button>
            </div>
            <a class="add_menu_a" href="javascript:add_menu('market')">매뉴추가하기</a>
          </div>

          <div>
            <?
            $sql_title = "select card_title from Gn_App_Home_Manager where site_iam='{$site}' and card_title!=''";
            $res_title = mysqli_query($self_con, $sql_title);
            $row_title = mysqli_fetch_array($res_title);
            ?>
            <h4><?=$row_title['card_title']?$row_title['card_title']:'IAM카드';?></h4>
            <div class="row" id="card_set" style="text-align:center;">
              <?
              $C_no = 0;
              $sql_menu_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='C'";
              $res_menu_data = mysqli_query($self_con, $sql_menu_data);
              if(!mysqli_num_rows($res_menu_data)){
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='kiam' and ad_position='C'";
                $mode = "save";
              }
              else{
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='C'";
                $mode = "updat";
              }
              $res_data = mysqli_query($self_con, $sql_data);
              if(mysqli_num_rows($res_data)){
                while($data = mysqli_fetch_array($res_data)){
                  $C_no++;
              ?>
              <form method="post" id="card_Form_<?=$C_no?>" name="card_Form_<?=$C_no?>" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="card" />
                <input type="hidden" name="mode" value="<?=$mode?>" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="<?=$data['idx']?>" />
                <div class="box" id="card_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
                  <p class="menu_title">매뉴<?=$C_no?>번</p>
                  <input type="file" name="app_card" class="input_file">
                  <?php if($data['img_url']){?>
                    <a href="<?=$data['img_url']?>" target="_blank">
                      <img class="zoom" src="<?=$data['img_url']?>" style="height:30px;">
                    </a>
                  <?php }
                  else{?>
                  <img src="" style="width:50px;">
                  <?}?>
                  <input type="text" name="card_move_url" value="<?=$data['move_url']?>" placeholder="링크입력" style="width:14%;">
                  <input type="text" name="card_title" value="<?=$data['title']?>" placeholder="제목입력" style="width:14%;margin-left:5px;">
                  <input type="text" name="card_display_order" value="<?=$data[display_order]?>" placeholder="노출순서" style="width:7%;margin-left:10px;">
                  <?if($mode == "updat"){?>
                  <label class="switch_card" style="margin:0 25px;">
                      <input type="checkbox" name="status" id="stauts_<?php echo $data['idx'];?>" value="<?php echo $data['idx'];?>" <?php echo $data['use_yn']=="Y"?"checked":""?>>
                      <span class="slider round" name="status_round" id="stauts_round_<?php echo $data['idx'];?>"></span>
                  </label>
                  <a href="javascript:delete_row_card(<?=$C_no?>)" class="delete_a">X</a>
                  <?}?>
                  <button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="card_form_save(<?=$C_no?>);return false;">저장</button>
                </div>
              </form>
              <?}
              }
              else{?>
              <form method="post" id="card_Form_0" name="card_Form_0" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="card" />
                <input type="hidden" name="mode" value="save" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="" />
                <div class="box" id="card_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
                  <p class="menu_title">매뉴1번</p>
                  <input type="file" name="app_card" class="input_file">
                  <img src="" style="width:50px;">
                  <input type="text" name="card_move_url" placeholder="링크입력" style="width:14%;">
                  <input type="text" name="card_title" placeholder="제목입력" style="width:14%;margin-left:5px;">
                  <input type="text" name="card_display_order" placeholder="노출순서" style="width:7%;margin-left:10px;">
                  <button class="btn btn-primary" style="margin-left: 5px;padding: 3px 10px;" onclick="card_form_save(0);return false;">저장</button>
                </div>
              </form>
              <?}?>
            </div>          
            <div>
              <?
              $sql_desc = "select card_desc from Gn_App_Home_Manager where card_desc!='' and site_iam='{$site}'";
              $res_desc = mysqli_query($self_con, $sql_desc);
              $row_desc = mysqli_fetch_array($res_desc);
              ?>
              <input type="text" name="card_desc" id="card_desc" value="<?=$row_desc[card_desc]?$row_desc[card_desc]:'';?>" placeholder="IAM카드 설명문입력" style="width:60%;margin-top:5px;">
              <button class="btn btn-primary" style="margin-left: 5px;margin-top:3px;padding: 3px 10px;" onclick="save_card_desc();return false;">저장</button>
            </div>
            <div>
              <input type="text" name="card_t_title" id="card_t_title" value="<?=$row_title['card_title']?$row_title['card_title']:'';?>" placeholder="타이틀입력" style="width:60%;margin-top:5px;">
              <button class="btn btn-primary" style="margin-left: 5px;margin-top:3px;padding: 3px 10px;" onclick="save_card_title();return false;">저장</button>
            </div>
            <a class="add_menu_a" href="javascript:add_menu('card')">매뉴추가하기</a>
          </div>

          <div style="margin-bottom: 40px;">
            <?
            $sql_title = "select notice_title from Gn_App_Home_Manager where site_iam='{$site}' and notice_title!=''";
            $res_title = mysqli_query($self_con, $sql_title);
            $row_title = mysqli_fetch_array($res_title);
            ?>
            <h4><?=$row_title[notice_title]?$row_title[notice_title]:'IAM공지';?></h4>
            <div class="row" id="other_set" style="text-align:center;">
              <form method="post" id="other_Form" name="other_Form" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <div class="box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom:0px;">
                  <p class="menu_title">본사 공지사항</p>
                  <input type="text" placeholder="해당 도메인이 노출됨" readonly style="width:30%;">
                  <input type="text" placeholder="all" readonly style="width:5%;">
                </div>
                <div class="box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom:0px;">
                  <p class="menu_title">총판 공지사항</p>
                  <input type="text" placeholder="해당 도메인이 노출됨" readonly style="width:30%;">
                  <input type="text" placeholder="1건" readonly style="width:5%;">
                </div>
                <div class="box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom:0px;">
                  <p class="menu_title">리셀러 공지사항</p>
                  <input type="text" placeholder="해당 도메인이 노출됨" readonly style="width:30%;">
                  <input type="text" placeholder="1건" readonly style="width:5%;">
                </div>
              </form>
            </div>  
            <div>
              <input type="text" name="notice_t_title" id="notice_t_title" value="<?=$row_title[notice_title]?$row_title[notice_title]:'';?>" placeholder="타이틀입력" style="width:60%;margin-top:5px;">
              <button class="btn btn-primary" style="margin-left: 5px;margin-top:3px;padding: 3px 10px;" onclick="save_notice_title();return false;">저장</button>
            </div>       
          </div>

          <div>
            <h4>배너광고</h4>
            <div class="row" id="banner_set" style="text-align:center;">
              <?
              $B_no = 0;
              $sql_menu_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='B'";
              $res_menu_data = mysqli_query($self_con, $sql_menu_data);
              if(!mysqli_num_rows($res_menu_data)){
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='kiam' and ad_position='B'";
                $mode = "save";
              }
              else{
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='B'";
                $mode = "updat";
              }
              $res_data = mysqli_query($self_con, $sql_data);
              if(mysqli_num_rows($res_data)){
                while($data = mysqli_fetch_array($res_data)){
                  $B_no++;
              ?>
              <form method="post" id="banner_Form_<?=$B_no?>" name="banner_Form_<?=$B_no?>" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="banner" />
                <input type="hidden" name="mode" value="<?=$mode?>" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="<?=$data['idx']?>" />
                <div class="box" id="banner_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
                  <p class="menu_title">배너<?=$B_no?>번</p>
                  <input type="file" name="app_banner" class="input_file">
                  <?php if($data['img_url']){?>
                    <a href="<?=$data['img_url']?>" target="_blank">
                      <img class="zoom" src="<?=$data['img_url']?>" style="height:30px;">
                    </a>
                  <?php }
                  else{?>
                  <img src="" style="width:50px;">
                  <?}?>
                  <input type="text" name="banner_move_url" value="<?=$data['move_url']?>" placeholder="배너클릭시 이동할 링크입력하세요." style="width:30%;">
                  <input type="text" name="banner_display_order" value="<?=$data[display_order]?>" placeholder="노출순서" style="width:7%;margin-left:10px;">
                  <?if($mode == "updat"){?>
                  <label class="switch_banner" style="margin:0 25px;">
                      <input type="checkbox" name="status" id="stauts_<?php echo $data['idx'];?>" value="<?php echo $data['idx'];?>" <?php echo $data['use_yn']=="Y"?"checked":""?>>
                      <span class="slider round" name="status_round" id="stauts_round_<?php echo $data['idx'];?>"></span>
                  </label>
                  <a href="javascript:delete_row_banner(<?=$B_no?>)" class="delete_a">X</a>
                  <?}?>
                  <button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="banner_form_save(<?=$B_no?>);return false;">저장</button>
                </div>
              </form>
              <?}
              }
              else{?>
              <form method="post" id="banner_Form_0" name="banner_Form_0" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="banner" />
                <input type="hidden" name="mode" value="save" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="" />
                <div class="box" id="banner_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
                  <p class="menu_title">배너1번</p>
                  <input type="file" name="app_banner" class="input_file">
                  <img src="" style="width:50px;">
                  <input type="text" name="banner_move_url" placeholder="배너클릭시 이동할 링크입력하세요." style="width:30%;">
                  <input type="text" name="banner_display_order" placeholder="노출순서" style="width:7%;margin-left:10px;">
                  <button class="btn btn-primary" style="margin-left: 5px;padding: 3px 10px;" onclick="banner_form_save(0);return false;">저장</button>
                </div>
              </form>
              <?}?>
            </div>
            <a class="add_menu_a" href="javascript:add_menu('banner')">매뉴추가하기</a>
          </div>

          <?
          $sql_service_chk = "select duplicate_idx from Gn_Iam_Service where sub_domain like '%".$domain."%'";
          $res_service_chk = mysqli_query($self_con, $sql_service_chk);
          $row_service_chk = mysqli_fetch_array($res_service_chk);

          if($row_service_chk[duplicate_idx]){
            $sql_ori_site = "select sub_domain from Gn_Iam_Service where idx='{$row_service_chk[duplicate_idx]}'";
            $res_ori_site = mysqli_query($self_con, $sql_ori_site);
            $row_ori_site = mysqli_fetch_array($res_ori_site);
          ?>
          <div>
            <h4>전환하기</h4>
            <div class="row" id="change_set" style="text-align:center;">
              <?
              $sql_change_data = "select * from Gn_App_Home_Manager where ad_position='E' and site_iam='{$site}'";
              $res_change_data = mysqli_query($self_con, $sql_change_data);
              if(!mysqli_num_rows($res_change_data)){
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='kiam' and ad_position='E'";
                $mode = "save";
              }
              else{
                $sql_data = "select * from Gn_App_Home_Manager where site_iam='{$site}' and ad_position='E'";
                $mode = "updat";
              }
              $res_data = mysqli_query($self_con, $sql_data);
              $data = mysqli_fetch_array($res_data);
              ?>
              <form method="post" id="change_Form_0" name="change_Form_0" action="/admin/ajax/app_home_save.php" enctype="multipart/form-data">
                <input type="hidden" name="position" value="change" />
                <input type="hidden" name="site_iam" value="<?=$site?>" />
                <input type="hidden" name="idx" value="<?=$data['idx']?>" />
                <div class="box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom:0px;">
                  <p class="menu_title">배너 1번</p>
                  <input type="file" name="app_change" class="input_file">
                  <?php if($data['img_url']){?>
                    <a href="<?=$data['img_url']?>" target="_blank">
                      <img class="zoom" src="<?=$data['img_url']?>" style="height:30px;">
                    </a>
                  <?php }
                  else{?>
                  <img src="" style="width:50px;">
                  <?}?>
                  <input type="text" name="change_move_url" value="<?=$row_ori_site['sub_domain']?>" readonly placeholder="링크입력" style="width:14%;">
                  <label class="switch_change" style="margin:0 25px;">
                      <input type="checkbox" name="status" id="stauts_<?php echo $data['idx'];?>" value="<?php echo $data['idx'];?>" <?php echo $data['use_yn']=="Y"?"checked":""?>>
                      <span class="slider round" name="status_round" id="stauts_round_<?php echo $data['idx'];?>"></span>
                  </label>
                  <button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="change_form_save(0);return false;">저장</button>
                </div>
              </form>
            </div>      
          </div>
          </div>
          <?}?>
        </section><!-- /.content -->
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
</script>
      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
<script>
function show_recv(name,c,t,status)
{
	if(!document.getElementsByName(name)[c].value)
	return;
	open_div(open_recv_div,100,1,status);
	if(name=="show_jpg")
	$($(".open_recv")[0]).html("<img src='"+document.getElementsByName(name)[c].value+"' />");
	else if(name=="show_jpg1")
	    $($(".open_recv")[0]).html("<img src='"+document.getElementsByName(name)[c].value+"' />");
	else if(name=="show_jpg2")
	    $($(".open_recv")[0]).html("<img src='"+document.getElementsByName(name)[c].value+"' />");
	else
	$($(".open_recv")[0]).html(document.getElementsByName(name)[c].value.replace(/\n/g,"<br/>"));
	$($(".open_recv_title")[0]).html(t);	
}    
</script>