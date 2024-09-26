<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today = date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
  $(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if (navH != contHeaderH)
      contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top", contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 235;
    if (height < 375)
      height = 375;
    console.log("height=" + height);
    $(".box-body").css("height", height);
  });

  function goPage(pgNum) {
    location.href = '?<?= $nowPage ?>&nowPage=' + pgNum;
  }
  $('.type_phone').click(function() {
    var id = $(this)[0].id;
    var phone_set = $(this)[0].checked;
    var msg;
    var type = 0;
    if (phone_set) {
      msg = confirm('전화수신으로 설정하시겠습니까?');
      type = 1;
    } else {
      msg = confirm('전화수신을 해지하시겠습니까?');
    }
    if (msg) {
      var idx = id.split("_")[1];
      $.ajax({
        type: "POST",
        url: "/admin/ajax/mms_callback_save.php",
        data: {
          mode: "type",
          idx: idx,
          type: type
        },
        success: function(data) {
          location.reload();
        },
        error: function() {
          alert('설정 실패');
        }
      });
    } else {
      $(this).prop("checked", !phone_set);
    }
  });

  $('.type_mms').click(function() {
    var id = $(this)[0].id;
    var phone_set = $(this)[0].checked;
    var type = 0;
    var msg;
    if (phone_set) {
      msg = confirm('문자수신으로 설정하시겠습니까?');
      type = 2;
    } else {
      msg = confirm('문자수신을 해지하시겠습니까?');
    }
    if (msg) {
      var idx = id.split("_")[1];
      $.ajax({
        type: "POST",
        url: "/admin/ajax/mms_callback_save.php",
        data: {
          mode: "type",
          idx: idx,
          type: type
        },
        success: function(data) {
          location.reload();
        },
        error: function() {
          alert('설정 실패');
        }
      });
    } else {
      $(this).prop("checked", !phone_set);
    }
  });

  //계정 삭제
  function del_member_info(idx) {
    var msg = confirm('정말로 삭제하시겠습니까?');
    if (msg) {
      $.ajax({
        type: "POST",
        url: "/admin/ajax/mms_callback_delete.php",
        data: {
          idx: idx
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

  .table-bordered>thead>tr>th,
  .table-bordered>tbody>tr>th,
  .table-bordered>tfoot>tr>th,
  .table-bordered>thead>tr>td,
  .table-bordered>tbody>tr>td,
  .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd !important;
  }

  thead tr th {
    position: sticky;
    top: 0;
    background: #ebeaea;
    z-index: 10;
  }

  .wrapper {
    height: 100%;
    overflow: auto !important;
  }

  .content-wrapper {
    min-height: 80% !important;
  }

  .box-body {
    overflow: auto;
    padding: 0px !important;
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
        콜백 메시지 관리
        <small>콜백 메시지를 관리합니다.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">콜백 메시지관리</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row" id="toolbox">
        <div class="col-xs-12" style="padding-bottom:20px">
          <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='mms_callback_detail.php';return false;"><i class="fa fa-download"></i> 등록</button>
          <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='mms_callback_list_member.php';return false;"><i class="fa fa-download"></i> 회원 콜백 메시지 관리</button>
          <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='mms_callback_list_service.php';return false;"><i class="fa fa-download"></i> 분양사 콜백 메시지 관리</button>
          <!--<button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_member_down_.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>-->
          <form method="get" name="search_form" id="search_form">
            <div class="box-tools">
              <div class="input-group" style="width: 250px;">
                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="타이틀/콘텐츠">
                <div class="input-group-btn">
                  <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <form method="post" action="/admin/ajax/ad_sort_save.php" id="ssForm" name="ssForm">
        <div class="row">
          <div class="box">
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <colgroup>
                  <col width="4%">
                  <col width="15%">
                  <col width="25%">
                  <col width="12%">
                  <col width="15%">
                  <col width="10%">
                  <col width="6%">
                  <col width="6%">
                  <col width="6%">
                </colgroup>
                <thead>
                  <tr>
                    <th>번호</th>
                    <th>타이틀</th>
                    <th>콘텐츠</th>
                    <th>이미지</th>
                    <th>링크주소</th>
                    <th>등록일</th>
                    <th>전화수신</th>
                    <th>문자수신</th>
                    <th>수정/삭제</th>
                    <!--<th>삭제</th>-->
                  </tr>
                </thead>
                <tbody>
                  <?
                  $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
                  $startPage = $nowPage ? $nowPage : 1;
                  $pageCnt = 20;
                  // 검색 조건을 적용한다.
                  $searchStr .= $search_key ? " AND (title LIKE '%" . $search_key . "%' or content like '%" . $search_key . "%' )" : null;
                  $order = $order ? $order : "desc";
                  $query = "SELECT SQL_CALC_FOUND_ROWS * FROM gn_mms_callback WHERE service_state=0 $searchStr";
                  $res      = mysqli_query($self_con, $query);
                  $totalCnt = mysqli_num_rows($res);
                  $limitStr = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
                  $number   = $totalCnt - ($nowPage - 1) * $pageCnt;
                  $orderQuery .= " ORDER BY idx DESC $limitStr";

                  $i = 1;
                  $c = 0;
                  $query .= $orderQuery;
                  $res = mysqli_query($self_con, $query);
                  while ($row = mysqli_fetch_array($res)) {
                  ?>
                    <tr>
                      <td><?= $number-- ?></td>
                      <td><?= $row['title'] ?></td>
                      <td><?= $row['content'] ?></td>
                      <td>
                        <div>
                          <?
                          if ($row['img']) {
                            $thumb_img =  $row['img'];
                          } else {
                            $thumb_img =  $default_img;
                          }
                          ?>
                          <a href="<?= $thumb_img ?>" target="_blank">
                            <img class="zoom" src="<?= $thumb_img ?>" style="width:90%;">
                          </a>
                        </div>
                      </td>
                      <td><?= $row['iam_link'] ?></td>
                      <td><?= $row['regdate'] ?></td>
                      <td> <input type="checkbox" class="type_phone" name="chk_phone" id="phone_<?= $row['idx']; ?>" <?= $row['type'] == "1" ? "checked" : "" ?>></td>
                      <td> <input type="checkbox" class="type_mms" name="chk_mms" id="mms_<?= $row['idx']; ?>" <?= $row['type'] == "2" ? "checked" : "" ?>> </td>
                      <td> <a href="mms_callback_detail.php?idx=<?= $row['idx'] ?>">수정</a>/<a href="javascript:del_member_info('<?= $row['idx'] ?>')">삭제</a> </td>
                    </tr>
                  <?
                    $c++;
                    $i++;
                  }
                  if ($i == 1) {
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
      </form>
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
  <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>
</div><!-- /content-wrapper -->