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
    location.href = '?<?= $nowPage ?>&nowPage=' + pgNum;
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

  #open_recv_div li {
    list-style: none;
  }

  .table-bordered>thead>tr>th,
  .table-bordered>tbody>tr>th,
  .table-bordered>tfoot>tr>th,
  .table-bordered>thead>tr>td,
  .table-bordered>tbody>tr>td,
  .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd !important;
  }

  #open_recv_div li {
    list-style: none;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  input,
  select,
  textarea {
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

  .disagree {
    background: #ffd5d5 !important;
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
    transition: transform .2s;
    /* Animation */
  }

  .zoom:hover {
    transform: scale(4);
    /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    border: 1px solid #0087e0;
    box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
  }

  .zoom-2x {
    transition: transform .2s;
    /* Animation */
  }

  .zoom-2x:hover {
    transform: scale(2);
    /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    border: 1px solid #0087e0;
    box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
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
        회원 콜백 메시지 관리
        <small>회원 콜백 메시지를 관리합니다.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">회원 콜백 메시지관리</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row" id="toolbox">
        <div class="col-xs-12" style="padding-bottom:20px">
          <!-- <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='mms_callback_detail_service.php';return false;"><i class="fa fa-download"></i> 등록</button> -->
          <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='mms_callback_list_service.php';return false;"><i class="fa fa-download"></i> 분양사 콜백 메시지 관리</button>
          <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='mms_callback_list.php';return false;"><i class="fa fa-download"></i> 본사 콜백 메시지 관리</button>
          <!--<button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_member_down_.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>-->
          <form method="get" name="search_form" id="search_form">
            <div class="box-tools">
              <div class="input-group" style="width: 300px;display:flex;">
                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" value="<?= $_REQUEST['search_key'] ?>" placeholder="타이틀/콘텐츠">
                <input type="text" name="mem_id" id="mem_id" class="form-control input-sm pull-right" value="<?= $_REQUEST['mem_id'] ?>" placeholder="아이디">
                <!-- <input type="text" name="mem_site" id="mem_site" class="form-control input-sm pull-right" value="<?= $_REQUEST['mem_site'] ?>" placeholder="소속"> -->
                <div class="input-group-btn">
                  <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
          </form>
        </div>
      </div>
  </div>

  <form method="post" action="/admin/ajax/ad_sort_save.php" id="ssForm" name="ssForm">
    <div class="row">

      <div class="box">
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
            <colgroup>
              <col width="3%">
              <col width="6%">
              <col width="6%">
              <col width="6%">
              <col width="10%">
              <col width="15%">
              <col width="10%">
              <col width="15%">
              <col width="5%">
              <col width="5%">

            </colgroup>
            <thead>
              <tr>
                <th>번호</th>
                <th>도메인</th>
                <th>아이디</th>
                <th>이름</th>
                <th>타이틀</th>
                <th>콘텐츠</th>
                <th>이미지</th>
                <th>링크주소</th>
                <th>조회수</th>
                <th>수정/삭제</th>
              </tr>
            </thead>
            <tbody>
              <?
              $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
              $startPage = $nowPage ? $nowPage : 1;
              $pageCnt = 20;
              $memname = "";
              $memsite = "";
              $mem_select = "";
              $memjoin = "";
              // 검색 조건을 적용한다.
              $searchStr .= $search_key ? " AND (a.title LIKE '%" . $search_key . "%' or a.content like '%" . $search_key . "%' )" : null;
              if ($mem_id) {
                $mem_select = ", c.mem_name, c.site ";
                $memjoin = " inner join Gn_Member c on a.mb_id=c.mem_id ";
                $memname = " AND (c.mem_id = '" . $mem_id . "')";
              }
              $sql = "select count(*)  from gn_mms_callback a" . $memjoin . " where a.service_state=2 " . $searchStr . $memname . $memsite;
              $res      = mysqli_query($self_con, $sql);
              $row  =  mysqli_fetch_array($res);
              $totalCnt = $row[0];

              $sql = "select a.* " . $mem_select . " from gn_mms_callback a" . $memjoin . " where a.service_state=2 " . $searchStr . $memname . $memsite;
              $limitStr       = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
              $number      = $totalCnt - ($nowPage - 1) * $pageCnt;

              $orderQuery .= " ORDER BY idx DESC $limitStr";

              $i = 1;
              $c = 0;
              $sql .= "$orderQuery";
              $res = mysqli_query($self_con, $sql);
              while ($row = mysqli_fetch_array($res)) {
              ?>
                <tr>
                  <td><?= $number-- ?></td>
                  <td><?= $row['site'] ?></td>
                  <td><?= $row['mb_id'] ?></td>
                  <td><?= $row['mem_name'] ?></td>
                  <td><?= cut_str($row['title'], 25) ?></td>
                  <td><?= cut_str($row['content'], 60) ?></td>
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
                  <td><a href="<?= $row['iam_link'] ?>"><?= $row['iam_link'] ?></a></td>
                  <td><?= $row['mem_sel_cnt'] ?></td>
                  <td> <a href="mms_callback_detail_member.php?idx=<?= $row['idx'] ?>">수정</a>/<a href="javascript:del_member_info(<?= $row['idx'] ?>)">삭제</a> </td>
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
<script language="javascript">
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
  $(function() {
    $('.switch').on("change", function() {
      var id = $(this).find("input[type=checkbox]").val();
      var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
      $.ajax({
        type: "POST",
        url: "/ajax/edit_event_callback.php",
        data: {
          update_state: true,
          id: id,
          status: status
        },
        success: function(data) {
          alert('신청되었습니다.');
        }
      })
    });
  });
</script>
<!-- Footer -->
<div id='open_recv_div' class="open_1">
  <div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    <li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>
  </div>
  <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
  </div>
</div>