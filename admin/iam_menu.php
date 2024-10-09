<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");

if($site == "kiam"){
  $domain = "www";
}else{
	$domain = $site;
}
$domain .= ".kiam.kr";

if(!isset($_GET['menu_type']))
  $menu_type = "T";

$sql_service = "select admin_iam_menu from Gn_Iam_Service where sub_domain like '%".$domain."%'";
$res_service = mysql_query($sql_service);
$row_service = mysql_fetch_array($res_service);
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>

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

    $('#menu_type').on("change", function() {
        location.href = "iam_menu.php?site=<?=$site?>&menu_type="+$(this).val();
    });
    $('.switch_menu').on("change", function() {
      var id = $(this).find("input[type=checkbox]").val();
      var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
      $.ajax({
          type:"POST",
          url:"/admin/ajax/iam_menu_ajax.php",
          data:{
              mode:'status', 
              id:id,
              status:status
          },
          success:function(data){
              // alert('신청되었습니다.');
              //location.reload();
          }
      })
    });
  });
  function add_menu(type){
      var cnt = $("#menu_set").find('form').length * 1;
      cnt++;
      var html = '<form method="post" id="menu_Form_'+cnt+'" name="menu_Form_'+cnt+'" enctype="multipart/form-data">'+
                    '<input type="hidden" name="mode" value="save" />'+
                    '<input type="hidden" name="site_iam" value="<?=$site?>" />'+
                    '<input type="hidden" name="idx" value="" />'+
                    '<input type="hidden" name="menu_type" value="<?=$menu_type?>" />'+
                    '<div class="box" id="menu_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">'+
                      '<p class="menu_title">메뉴'+cnt+'번</p>'+
                      '<input type="file" name="menu_icon" class="input_file" accept=".jpg,.jpeg,.png,.gif">'+
                      '<img src="" style="width:50px;">'+
                      '<input type="text" name="menu_move_url" placeholder="링크입력" style="width:14%;">'+
                      '<input type="text" name="menu_title" placeholder="제목입력" style="width:14%;margin-left:5px;">'+
                      '<input type="text" name="menu_desc" placeholder="설명글입력" style="width:20%;margin-left:5px;">'+
                      '<input type="text" name="menu_display_order" placeholder="노출순서" style="width:30px;margin-left:10px;">';
      /*if(type == "T")*/{
        html += '<select id="page_type">'+
                  '<option value="all" selected>전체</option>'+
                  '<option value="my_info">마이콘</option>'+
                  '<option value="we_story">위콘</option>'+
                  '<option value="gmarket">지마켓</option>'+
                  '<option value="calliya">콜이야</option>'+
                  '<option value="alarm">알림</option>'+
                  '<option value="iam_mall">쇼핑</option>'+
                  '<option value="login">로그인용</option>'+
                  '<option value="payment">결제용</option>'+
                  '<option value="sub_admin">분양자용</option>'+
                '</select>';
    }
        html += '<a href="javascript:delete_row_menu('+cnt+')" class="delete_a">X</a>'+
                      '<button class="btn btn-primary" style="margin-right: 5px;padding: 3px 10px;" onclick="menu_form_save('+cnt+');return false;">저장</button>'+
                    '</div>'+
                  '</form>';
      $("#menu_set").append(html);
  }
  function delete_row_menu(val){
    var idx = $("form[name=menu_Form_"+val+"]").find('input[name=idx]').val();
    if(!idx){
      $("form[name=menu_Form_"+val+"]").remove();
      var cnt = $("#menu_set").find('form').length * 1;
      for(var i=val;i<=cnt;i++){
        $("form[name=menu_Form_"+(i+1)+"]").find(".menu_title").html("메뉴"+ i + "번");
        $("form[name=menu_Form_"+(i+1)+"]").find(".delete_a").prop('href',"javascript:delete_row_menu("+i+");");
        $("form[name=menu_Form_"+(i+1)+"]").find("button").attr('onclick',"menu_form_save("+i+");");
        $("form[name=menu_Form_"+(i+1)+"]").prop("id","menu_Form_"+i);
        $("form[name=menu_Form_"+(i+1)+"]").prop("name","menu_Form_"+i);
      }
    }else{
      if(confirm('삭제하시겠습니까?')){
        $.ajax({
          type:'post',
          url:"ajax/iam_menu_ajax.php",
          data:{mode:'del', idx:idx},
          dataType:'json',
          success:function(data){
            location.reload();
          }
        })
      }
    }
  }
  function menu_form_save(idx){
    if($('input[name=menu_move_url]:eq('+(idx-1)+')').val() == ''){
      alert('메뉴 링크를 입력해 주세요.');
      return;
    }else{
      var form = $("form[name=menu_Form_"+idx+"]");
      var data = new FormData();
      data.append("mode",form.find('input[name=mode]').val());
      data.append("site_iam",form.find('input[name=site_iam]').val());
      data.append("idx",form.find('input[name=idx]').val());
      data.append("menu_type",form.find('input[name=menu_type]').val());
      data.append("img_path",form.find('input[name=img_path]').val());
      data.append("menu_move_url",form.find('input[name=menu_move_url]').val());
      data.append("menu_title",form.find('input[name=menu_title]').val());
      data.append("menu_desc",form.find('input[name=menu_desc]').val());
      data.append("menu_display_order",form.find('input[name=menu_display_order]').val());
      data.append("page_type",form.find('#page_type').val());
      data.append("menu_icon",form.find('.input_file')[0].files[0]);
			$.ajax({
				type:"POST",
				url:"/admin/ajax/iam_menu_ajax.php",
				data: data,
        dataType:"json",
        contentType: false,
        processData: false,
				success:function(data){
          if(data.result == "success"){
            alert(data.msg);
            if(data.idx != "" && data.mode == "save"){
					    $("form[name=menu_Form_"+idx+"]").find(".check_img").show();
            }else{
              location.reload();
            }
          }
				},
				error: function(request,status,error){
				  alert('저장 실패');
          console.log(request + " | " + status + " | " + error);
				}
			});		
    }
  }
  function set_show(val){
    $.ajax({
        type:"POST",
        url:"/admin/ajax/iam_menu_ajax.php",
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
.switch_menu input {
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
.switch_menu {
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
  margin:0 25px;
  z-index:10;
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
  width:80px;
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
#menu_type{margin-left:20px;}
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
      <h1>아이엠 메뉴관리<small>아이엠 메뉴를 관리합니다.</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">아이엠 메뉴관리</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row" id="toolbox">
        <div class="col-xs-12" style="padding-bottom:20px">
          <?if(!$row_service['admin_iam_menu']){
              $checked1 = "checked";
              $checked2 = "";
            }else{
              $checked1 = "";
              $checked2 = "checked";
            }
            ?>
          <input type="radio" name="set_show_menu" id="admin" value="0" <?=$checked1?> onclick="set_show('0')">본사용
          <input type="radio" name="set_show_menu" id="service" value="1" <?=$checked2?> onclick="set_show('1')">분양용
        </div>
        <div>
          <select id="menu_type">
            <option value="T" <?=$menu_type=="T"?"selected":"";?>>상단메뉴</option>
            <option value="B" <?=$menu_type=="B"?"selected":"";?>>하단메뉴</option>
            <option value="TR" <?=$menu_type=="TR"?"selected":"";?>>상단우측메뉴</option>
            <option value="BR" <?=$menu_type=="BR"?"selected":"";?>>하단우측메뉴</option>
          </select>
        </div>
      </div>
      <div class="row box-body"  style="overflow: auto !important;margin-top:20px">
        <div class="row" id="menu_set" style="text-align:center;">
          <?
          $index = 0;
          $sql_menu_data = "select count(idx) from Gn_Iam_Menu where site_iam='{$site}' and menu_type='{$menu_type}'";
          $res_menu_data = mysql_query($sql_menu_data);
          $menu_data = mysql_fetch_array($res_menu_data);
          if($menu_data[0] == 0){
            $sql_data = "select * from Gn_Iam_Menu where site_iam='kiam' and menu_type='{$menu_type}' order by display_order";
            $mode = "save";
          }else{
            $sql_data = "select * from Gn_Iam_Menu where site_iam='{$site}' and menu_type='{$menu_type}' order by display_order";
            $mode = "updat";
          }
          $res_data = mysql_query($sql_data);
          if(mysql_num_rows($res_data)){
            while($data = mysql_fetch_array($res_data)){
              $index++;
          ?>
          <form method="post" id="menu_Form_<?=$index?>" name="menu_Form_<?=$index?>" enctype="multipart/form-data">
            <input type="hidden" name="mode" value="<?=$mode?>" />
            <input type="hidden" name="site_iam" value="<?=$site?>" />
            <input type="hidden" name="idx" value="<?=$data['idx']?>" />
            <input type="hidden" name="menu_type" value="<?=$menu_type?>" />
            <input type="hidden" name="img_path" value="<?=$data['img_url']?>" />
            <div class="box" id="menu_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
              <p class="menu_title">메뉴<?=$index?>번</p>
              <input type="file" name="menu_icon" class="input_file" accept=".jpg,.jpeg,.png,.gif">
              <?php if($data['img_url']){?>
                <a href="<?=$data['img_url']?>" target="_blank">
                  <img class="zoom" src="<?=$data['img_url']?>" style="height:30px;">
                </a>
              <?php }
              else{?>
                <img src="" style="width:50px;">
              <?}?>
              <input type="text" name="menu_move_url" value="<?=$data['move_url']?>" placeholder="링크입력" style="width:14%;">
              <input type="text" name="menu_title" value="<?=$data['title']?>" placeholder="제목입력" style="width:20%;margin-left:5px;">
              <input type="text" name="menu_desc" value="<?=$data['menu_desc']?>" placeholder="설명글입력" style="width:20%;margin-left:5px;">
              <input type="text" name="menu_display_order" value="<?=$data['display_order']?>" placeholder="노출순서" style="width:30px;margin-left:10px;">
              <?/*if($menu_type == "T")*/{?>
                <select id="page_type">
                  <option value="all" <?=$menu_type=="all"?"selected":"";?>>전체</option>
                  <option value="my_info" <?=$data['page_type']=="my_info"?"selected":"";?>>마이콘</option>
                  <option value="we_story" <?=$data['page_type']=="we_story"?"selected":"";?>>위콘</option>
                  <option value="gmarket" <?=$data['page_type']=="gmarket"?"selected":"";?>>지마켓</option>
                  <option value="calliya" <?=$data['page_type']=="calliya"?"selected":"";?>>콜이야</option>
                  <option value="alarm" <?=$data['page_type']=="alarm"?"selected":"";?>>알림</option>
                  <option value="iam_mall" <?=$data['page_type']=="iam_mall"?"selected":"";?>>쇼핑</option>
                  <option value="login" <?=$data['page_type']=="login"?"selected":"";?>>로그인용</option>
                  <option value="payment" <?=$data['page_type']=="payment"?"selected":"";?>>결제용</option>
                  <option value="sub_admin" <?=$data['page_type']=="sub_admin"?"selected":"";?>>분양자용</option>
                </select>
              <?}?>
              <?if($mode == "updat"){?>
              <label class="switch_menu" style="margin:0 25px;">
                  <input type="checkbox" name="status" id="stauts_<?=$data['idx'];?>" value="<?php echo $data['idx'];?>" <?php echo $data['use_yn']=="Y"?"checked":""?>>
                  <span class="slider round" name="status_round" id="stauts_round_<?php echo $data['idx'];?>"></span>
              </label>
              <a href="javascript:delete_row_menu(<?=$index?>)" class="delete_a">X</a>
              <?}?>
              <button class="btn btn-primary" style="margin-left: 5px;padding: 3px 10px;" onclick="menu_form_save(<?=$index?>);return false;"><?=$menu_data[0] == 0?"복사":"저장"?></button>
              <?if($mode == "save"){?>
                <img src='/images/check.gif' class="check_img" style="display: none"/>
              <?}?>
            </div>
          </form>
          <?}
        }else{?>
          <form method="post" id="menu_Form_1" name="menu_Form_1" enctype="multipart/form-data">
            <input type="hidden" name="mode" value="save" />
            <input type="hidden" name="site_iam" value="<?=$site?>" />
            <input type="hidden" name="idx" value="" />
            <input type="hidden" name="menu_type" value="<?=$menu_type?>" />
            <div class="box" id="menu_box" style="display: inline-flex;padding: 10px;height: 50px;margin-bottom: 0px;">
              <p class="menu_title">메뉴1번</p>
              <input type="file" name="menu_icon" class="input_file" accept=".jpg,.jpeg,.png,.gif">
              <img src="" style="width:50px;">
              <input type="text" name="menu_move_url" placeholder="링크입력" style="width:14%;">
              <input type="text" name="menu_title" placeholder="제목입력" style="width:20%;margin-left:5px;">
              <input type="text" name="menu_desc" placeholder="설명글입력" style="width:20%;margin-left:5px;">
              <input type="text" name="menu_display_order" placeholder="노출순서" style="width:10px;margin-left:10px;">
              <?/*if($menu_type == "T")*/{?>
                <select id="page_type">
                  <option value="all" selected>전체</option>
                  <option value="my_info">마이콘</option>
                  <option value="we_story">위콘</option>
                  <option value="gmarket">지마켓</option>
                  <option value="calliya">콜이야</option>
                  <option value="alarm">알림</option>
                  <option value="iam_mall">쇼핑</option>
                  <option value="login">로그인용</option>
                  <option value="payment">결제용</option>
                  <option value="sub_admin">분양자용</option>
                </select>
              <?}?>
              <button class="btn btn-primary" style="margin-left: 5px;padding: 3px 10px;" onclick="menu_form_save(1);return false;">저장</button>
              
            </div>
          </form>
        <?}?>
        </div>          
        <a class="add_menu_a" href="javascript:add_menu('<?=$menu_type?>')">메뉴추가하기</a>
      </div>
    </section><!-- /.content -->
  </div><!-- /content-wrapper -->
<!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
