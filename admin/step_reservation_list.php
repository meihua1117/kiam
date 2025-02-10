<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today = date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
  function goPage(pgNum) {
    location.href = '?nowPage=' + pgNum + "&search_key=<?= $_GET['search_key']; ?>";
  }
  $(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if (navH != contHeaderH)
      contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top", contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - $("#list_paginate").height() - 266;
    if (height < 375)
      height = 375;
    $(".box-body").css("height", height);
  });
  $(function() {
    $('.chkagree').change(function() {
      var id = $(this)[0].id;
      var sendable = $(this)[0].checked ? 1 : 0;
      $.ajax({
        type: "POST",
        url: "/ajax/ajax_point_payment.php",
        data: {
          pay_type: "modify_sendable",
          idx: id,
          sendable: sendable
        },
        success: function(data) {
          console.log(data);
        },
        error: function() {
          alert('변경 실패');
        }
      });
    });
  });
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

  input[type="checkbox" i] {
    background-color: initial;
    cursor: default;
    -webkit-appearance: checkbox;
    box-sizing: border-box;
    margin: 3px 3px 3px 4px;
    padding: initial;
    border: initial;
  }

  input:checked+.slider {
    background-color: #2196F3;
  }

  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }

  input:checked+.slider {
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

  input:checked+.slider:before {
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

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  thead tr th {
    position: sticky;
    top: 0;
    background: #ebeaea;
    z-index: 10;
  }

  .wrapper {
    height: 100%;
    overflow: auto;
  }

  .content-wrapper {
    min-height: 80% !important;
  }

  .box-body {
    overflow: auto;
    padding: 0px !important
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
        퍼널예약문자 내역
        <small>퍼널예약문자 내역를 관리합니다.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">퍼널예약문자 내역페이지</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row" id="toolbox">
        <div class="col-xs-12" style="padding-bottom:20px">
          <div style="padding:10px">
          </div>
          <?php if ($_SESSION['one_member_admin_id'] != "onlyonemaket") { ?>
          <?php } ?>
          <form method="get" name="search_form" id="search_form">
            <div class="box-tools">
              <div class="input-group" style="width: 250px;">
                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이벤트영문명/이벤트한글명/신청경로">
                <div class="input-group-btn">
                  <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
          </form>
        </div>
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
                <col width="200px">
                <col width="100px">
                <col width="220px">
                <col width="120px">
                <col width="120px">
                <col width="120px">
              </colgroup>
              <thead>
                <tr>
                  <th></th>
                  <th>No</th>
                  <th>아이디</th>
                  <th>이름</th>
                  <th>예약문자세트제목</th>
                  <th>예약문자세트설명</th>
                  <th>단계</th>
                  <th>발신횟수/건수</th>
                  <th>복제가능</th>
                  <th>등록일</th>
                </tr>
              </thead>
              <tbody>
                <?
                $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
                $startPage = $nowPage ? $nowPage : 1;
                $pageCnt = 20;
                // 검색 조건을 적용한다.
                $searchStr .= $search_key ? " AND (a.reservation_title LIKE '" . $search_key . "%' or a.reservation_desc like '" . $search_key . "%'  )" : null;
                $order = $order ? $order : "desc";
                $query = "SELECT count(a.m_id) FROM Gn_event_sms_info a WHERE 1=1 $searchStr";
                $res      = mysqli_query($self_con, $query);
                //$totalCnt	=  mysqli_num_rows($res);	
                $totalRow  =  mysqli_fetch_array($res);
                $totalCnt = $totalRow[0];
                $query = "SELECT a.sms_idx, a.m_id, a.event_name_eng, a.reservation_title, a.reservation_desc, a.mobile,a.sendable, a.regdate FROM Gn_event_sms_info a WHERE 1=1 $searchStr";
                $limitStr       = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
                $number      = $totalCnt - ($nowPage - 1) * $pageCnt;
                $orderQuery .= " ORDER BY a.sms_idx DESC $limitStr ";
                $i = 1;
                $c = 0;
                $query .= $orderQuery;
                $res = mysqli_query($self_con, $query);
                while ($row = mysqli_fetch_array($res)) {
                  $query = "SELECT mem_name from Gn_Member where mem_id='{$row['m_id']}'";
                  $sres = mysqli_query($self_con, $query);
                  $srow = mysqli_fetch_array($sres);

                  $sql = "select count(*) as cnt from Gn_event_sms_step_info where sms_idx='{$row['sms_idx']}'";
                  $sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
                  $strow = mysqli_fetch_array($sresult);
                ?>
                  <tr>
                    <td><input type="checkbox" name=""></td>
                    <td><?= $number-- ?></td>
                    <td style="font-size:12px;"><?= $row['m_id'] ?></td>
                    <td style="font-size:12px;"><?= $srow['mem_name'] ?></td>
                    <td style="font-size:12px;"><?= $row['reservation_title'] ?></td>
                    <td><?= $row['reservation_desc'] ?></td>
                    <td><a href="/mypage_reservation_create.php?sms_idx=<?= $row['sms_idx'] ?>" target="_blank"><?= number_format($strow['cnt']) ?></a></td>
                    <td><?= number_format($cnt) ?>/<?= number_format($cnt) ?></td>
                    <td>
                      <label class="switch">
                        <input type="checkbox" class="chkagree" id="<?= $row['sms_idx']; ?>" <?= $row['sendable'] == 1 ? "checked" : "" ?>>
                        <span class="slider round"></span>
                      </label>
                    </td>
                    </td>
                    <td><?= $row['regdate'] ?></td>
                  </tr>
                <?
                  $c++;
                  $i++;
                }
                if ($i == 1) { ?>
                  <tr>
                    <td colspan="11" style="text-align:center;background:#fff">
                      등록된 내용이 없습니다.
                    </td>
                  </tr>
                <? } ?>
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div><!-- /.row -->
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
    </section><!-- /.content -->
  </div><!-- /content-wrapper -->
  <!-- Footer -->
  <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>
  <form id="excel_down_form" name="excel_down_form" target="excel_iframe" method="post">
    <input type="hidden" name="grp_id" value="" />
    <input type="hidden" name="box_text" value="" />
    <input type="hidden" name="one_member_id" id="one_member_id" value="" />
    <input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
  </form>
  <iframe name="excel_iframe" style="display:none;"></iframe>