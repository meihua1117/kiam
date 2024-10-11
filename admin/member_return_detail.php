<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);

$sql = "select * from Gn_MMS where idx='{$_GET['idx']}'";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$krow = mysqli_fetch_array($result);
$date = $krow['up_date'];

$recv_num = explode(",", $krow['recv_num']);
$recv_num_in = "'" . implode("','", $recv_num) . "'";

$sql_serch .= " and  send_num='$send_num' and recv_num in ($recv_num_in) and recv_num like '01%'  and regdate >= '$date' and sms not like '[%'";
if ($_REQUEST['status2'])
  $sql_serch .= " and msg_flag='{$_REQUEST['status2']}' ";
if ($_REQUEST['serch_colum'] && $_REQUEST['serch_text']) {
  $sql_serch .= " and {$_REQUEST['serch_colum']} like '%{$_REQUEST['serch_text']}%' ";
}
$sql = "select count(seq) as cnt from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 $sql_serch ";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$row = mysqli_fetch_array($result);
$intRowCount = $row['cnt'];
if (!$_POST['lno'])
  $intPageSize = 20;
else
  $intPageSize = $_POST['lno'];

if ($_POST['page']) {
  $page = (int)$_POST['page'];
  $sort_no = $intRowCount - ($intPageSize * $page - $intPageSize);
} else {
  $page = 1;
  $sort_no = $intRowCount;
}
if ($_POST['page2'])
  $page2 = (int)$_POST['page2'];
else
  $page2 = 1;

$int = ($page - 1) * $intPageSize;
if ($_REQUEST['order_status'])
  $order_status = $_REQUEST['order_status'];
else
  $order_status = "desc";
if ($_REQUEST['order_name'])
  $order_name = $_REQUEST['order_name'];
else
  $order_name = "seq";

$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);

?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
  var mem_id = "";

  function page_view(mem_id) {
    $('.ad_layer1').lightbox_me({
      centered: true,
      onLoad: function() {
        $.ajax({
          type: "POST",
          url: "/admin/ajax/member_list_page1.php",
          data: {
            mem_id: mem_id
          },
          dataType: 'html',
          success: function(data) {
            $('#phone_list').html(data);
          },
          error: function() {
            alert('로딩 실패');
          }
        });
      }
    });
    $('.ad_layer1').css({
      "overflow-y": "auto",
      "height": "300px"
    });
  }
  $(function() {});

  //폰정보 수정
  function modify_phone_info() {

    var phno = $("#pno").val();

    if (!phno) {
      alert('폰 정보가 없습니다.');
      return false;
    } else {
      $.ajax({
        type: "POST",
        url: "ajax/modify_phoneinfo.php",
        data: {
          pno: phno,
          name: $("#detail_name").val(),
          company: $("#detail_company").val(),
          rate: $("#detail_rate").val()
        },
        success: function(data) {
          location.reload();
        },
        error: function() {
          alert('수정 실패');
        }
      });
    }
  }

  //계정 삭제
  function del_member_info(mem_code) {

    var msg = confirm('정말로 삭제하시겠습니까?');

    if (msg) {

      $.ajax({
        type: "POST",
        url: "/admin/ajax/user_leave.php",
        data: {
          mem_code: mem_code
        },
        success: function() {
          alert('삭제되었습니다.');
          location.reload();
        },
        error: function() {
          alert('삭제 실패');
        }
      });

    } else {
      return false;
    }
  }

  //주소록 다운
  function excel_down_() {
    $("#excel_down_form").submit();
    return false;
  }


  function goPage(pgNum) {
    location.href = '?<?= $nowPage ?>&nowPage=' + pgNum + "&search_key=<?php echo $_GET['search_key']; ?>";
  }

  //주소록 다운
  function excel_down_p_group(pno, one_member_id) {
    $($(".loading_div")[0]).show();
    $($(".loading_div")[0]).css('z-index', 10000);
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yy = today.getFullYear().toString().substr(2, 2);
    if (dd < 10) {
      dd = '0' + dd
    }
    if (mm < 10) {
      mm = '0' + mm
    }

    $.ajax({
      type: "POST",
      dataType: 'json',
      url: "/ajax/ajax_session_admin.php",
      data: {
        group_create_ok: "ok",
        group_create_ok_nums: pno,
        group_create_ok_name: pno.substr(3, 8) + '_' + '' + mm + '' + dd,
        one_member_id: one_member_id
      },
      success: function(data) {
        $($(".loading_div")[0]).hide();
        $('#one_member_id').val(one_member_id);
        parent.excel_down('/excel_down/excel_down_.php?down_type=1&one_member_id=' + one_member_id, data.idx);
      }

    });
  }
</script>
<style>
  .loading_div {
    display: none;
    position: fixed;
    left: 50%;
    top: 50%;
    display: none;
    z-index: 1000;
  }
</style>
<div class="loading_div"><img src="/images/ajax-loader.gif"></div>
<div class="wrapper">

  <!-- Top 메뉴 -->
  <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header_menu.inc.php"; ?>

  <!-- Left 메뉴 -->
  <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_left_menu.inc.php"; ?>

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
      <input type="hidden" name="one_id" id="one_id" value="<?= $data['mem_id'] ?>" />
      <input type="hidden" name="mem_pass" id="mem_pass" value="<?= $data['web_pwd'] ?>" />
      <input type="hidden" name="mem_code" id="mem_code" value="<?= $data['mem_code'] ?>" />
    </form>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12" style="padding-bottom:20px">
          <?php if ($_SESSION['one_member_admin_id'] != "onlyonemaket") { ?>
          <?php } ?>
          <form method="get" name="search_form" id="search_form">
            <div class="box-tools">
              <div class="input-group" style="width: 250px;">
                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름/휴대폰">
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
          <th>기부폰</th>
          <th>설치일자</th>
        </tr>
      </thead>
      <tbody id="phone_list">

      </tbody>
    </table>
  </div>

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
            <col width="120px">
            <col width="120px">
            <col width="220px">
          </colgroup>
          <thead>
            <tr>
              <th>번호</th>
              <th>발신번호</th>
              <th>소유자명</th>
              <th>발신일시</th>
              <th>발신내용</th>
              <th>회신번호</th>
              <th>회신자명</th>
              <th>회신일시</th>
              <th>회신내용</th>
            </tr>
          </thead>
          <tbody>
            <?
            $sql = "select * from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 $sql_serch order by $order_name $order_status ";
            $excel_sql = "select *   from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 $sql_serch order by $order_name $order_status";
            $excel_sql = str_replace("'", "`", $excel_sql);
            $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
            $c = 0;
            $totalCnt = mysqli_num_rows($result);
            while ($row = mysqli_fetch_array($result)) {
              $sql_n = "select memo from Gn_MMS_Number where sendnum='{$row['send_num']}' ";
              $resul_n = mysqli_query($self_con, $sql_n);
              $row_n = mysqli_fetch_array($resul_n);
              //$recv_num = split(",",$row['recv_num']);
              $recv_num = $row['recv_num'];

              //회신자명
              $sql_n = "select name from Gn_MMS_Receive where mem_id='{$_SESSION['one_member_id']}' and recv_num='{$row['recv_num']}' ";
              $resul_s = mysqli_query($self_con, $sql_n);
              $row_s = mysqli_fetch_array($resul_s);
            ?>
              <tr>
                <td><?= $totalCnt-- ?></td>
                <td><?= $row['send_num'] ?></td>
                <td><?= $row_n['memo'] ?></td>
                <td style="font-size:12px;"><?= substr($krow['up_date'], 0, 16) ?></td>
                <td><a href="javascript:void(0)" onclick="show_recv('show_content','<?= $c ?>','문자내용')"><?= str_substr($krow['content'], 0, 30, 'utf-8') ?></a><input type="hidden" name="show_content" value="<?= $krow['content'] ?>" /></td>
                <td><?= $recv_num ?></td>
                <td><?= $row_s['name'] ?></td>
                <td style="font-size:12px;"><?= substr($row['regdate'], 0, 16) ?></td>
                <td><?= $row['sms'] ?></td>
              </tr>
            <?
              $i++;
              $c++;
            }
            if ($i == 1) {
            ?>
              <tr>
                <td colspan="10" style="text-align:center;background:#fff">
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
      <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?= $totalCnt ?> 건</div>
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
<div id='ajax_div'></div>
<div class="loading_div"><img src="/images/ajax-loader.gif"></div>
<div id='open_recv_div' class="open_1">
  <div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    <li class="open_recv_title open_2_1"></li>
    <li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>
  </div>
  <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">

  </div>
</div>

<form id="excel_down_form" name="excel_down_form" target="excel_iframe" method="post">
  <input type="hidden" name="grp_id" value="" />
  <input type="hidden" name="box_text" value="" />
  <input type="hidden" name="one_member_id" id="one_member_id" value="" />
  <input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
</form>
<iframe name="excel_iframe" style="display:none;"></iframe>

<script language="javascript">
  function changeLevel(mem_code) {
    var mem_leb = $('#mem_leb' + mem_code + " option:selected").val();
    var data = {
      "mode": "change",
      "mem_code": "'+mem_code+'",
      "mem_leb": "'+mem_leb+'"
    };
    $.ajax({
      type: "POST",
      url: "/admin/ajax/user_level_change.php",
      dataType: "json",
      data: {
        mode: "change",
        mem_code: mem_code,
        mem_leb: mem_leb
      },
      success: function(data) {
        //console.log(data);
        //location = "/";
        //location.reload();
        alert('변경이 완료되었습니다.');
      },
      error: function() {
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
</script>

<!-- Footer -->

<? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>
<script>
  function show_recv(name, c, t, status) {
    if (!document.getElementsByName(name)[c].value)
      return;
    open_div(open_recv_div, 100, 1, status);

    $($(".open_recv")[0]).html(document.getElementsByName(name)[c].value.replace(/\n/g, "<br/>"));

    $($(".open_recv_title")[0]).html(t);
  }

  function open_div(show_div, mus_top, mus_left, status) {
    if (!status) {
      var cbs = document.getElementsByTagName("div");
      for (var i = 0; i < cbs.length; i++) {
        var cb = cbs[i];
        if (cb.id.indexOf("open") != -1) {
          $("#" + cb.id).fadeOut(250)
        }
      }
    }
    $(show_div).fadeIn(250);
    if (mus_top && mus_left) {
      $(show_div).css("top", $(window).scrollTop() + mus_top);
      $(show_div).css("left", ($("body").get(0).offsetWidth / 2) - ($(show_div).get(0).offsetWidth / 2) + mus_left);
    }
  }

  function close_div(e) {
    $(e).fadeOut(250)
  }
</script>