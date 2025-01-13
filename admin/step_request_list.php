<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today = date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
  var mem_id = "";
  //주소록 다운
  function excel_down_() {
    $("#excel_down_form").submit();
    return false;
  }

  function goPage(pgNum) {
    location.href = '?<?= $nowPage ?>&nowPage=' + pgNum + "&search_key=<?=$_GET['search_key']; ?>&case=<?=$_GET['case']; ?>";
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

  .tooltiptext-bottom {
    width: 420px;
    font-size: 15px;
    background-color: white;
    color: black;
    text-align: left;
    position: absolute;
    z-index: 200;
    top: 25%;
    left: 35%;
  }

  .title_app {
    text-align: center;
    background-color: rgb(130, 199, 54);
    padding: 10px;
    font-size: 20px;
    color: white;
  }

  @media only screen and (max-width: 450px) {
    .tooltiptext-bottom {
      width: 80%;
      left: 8%;
    }
  }

  #tutorial-loading {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 150;
    text-align: center;
    display: none;
    background-color: grey;
    opacity: 0.7;
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
        이벤트신청 리스트
        <small>이벤트신청 리스트를 관리합니다.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">이벤트신청 리스트</li>
      </ol>
    </section>

    <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
      <input type="hidden" name="one_id" id="one_id" value="<?= $data['mem_id'] ?>" />
      <input type="hidden" name="mem_pass" id="mem_pass" value="<?= $data['web_pwd'] ?>" />
      <input type="hidden" name="mem_code" id="mem_code" value="<?= $data['mem_code'] ?>" />
    </form>

    <!-- Main content -->
    <section class="content">
      <div class="row" id="toolbox">
        <div class="col-xs-12" style="padding-bottom:20px">
          <div style="padding:10px"></div>
          <form method="get" name="search_form" id="search_form">
            <div class="box-tools">
              <div class="input-group" style="width: 250px;">
                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이벤트명/신청자/휴대폰">
                <div class="input-group-btn">
                  <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
          </form>
        </div>
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
            <col width="100px">
            <col width="100px">
            <col width="100px">
            <col width="150px">
            <col width="120px">
            <col width="120px">
            <col width="120px">
            <col width="120px">
            <col width="120px">
            <col width="120px">
          </colgroup>
          <thead>
            <tr>
              <td><input type="checkbox" name="allChk" id="allChk" value="<?= $row['event_idx']; ?>"></td>
              <td>No</td>
              <td style="text-align: center;">신청창/<br>리포트</td>
              <td style="text-align: center;">셀링/IAM<br>소속</td>
              <td style="text-align: center;">작성자<br>아이디</td>
              <td style="text-align: center;">작성자<br>이름</td>
              <td style="text-align: center;">신청그룹<br>제목</td>
              <td style="text-align: center;">링크복사</td>
              <td style="text-align: center;">신청자<br>이름</td>
              <td style="text-align: center;">신청자<br>폰번호</td>
              <td style="text-align: center;">신청자<br>이메일</td>
              <td style="text-align: center;">신청자<br>직업</td>
              <td style="text-align: center;">신청자<br>기타</td>
              <td style="text-align: center;">등록일</td>
            </tr>
          </thead>
          <tbody>
            <?
            $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
            $startPage = $nowPage ? $nowPage : 1;
            $pageCnt = 20;

            // 검색 조건을 적용한다.
            $searchStr .= $search_key ? " AND (a.m_id LIKE '" . $search_key . "%' or a.name like '" . $search_key . "%'  or a.mobile like '" . $search_key . "%'  or b.event_title like '" . $search_key . "%'  )" : null;
            $order = $order ? $order : "desc";
            $query = "SELECT count(a.m_id) FROM Gn_event_request a inner join Gn_event b on b.event_idx=a.event_idx WHERE 1=1 $searchStr";
            $res      = mysqli_query($self_con, $query);
            //$totalCnt	=  mysqli_num_rows($res);	
            $totalRow  =  mysqli_fetch_array($res);
            $totalCnt = $totalRow[0];

            $query = "SELECT a.m_id, a.sp, a.name, a.mobile, a.email, a.job, a.event_code, a.event_idx, a.request_idx, a.regdate, a.other,b.event_title,b.event_info,b.pcode
                              FROM Gn_event_request a inner join Gn_event b on b.event_idx=a.event_idx WHERE 1=1 $searchStr";
            $limitStr       = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
            $number      = $totalCnt - ($nowPage - 1) * $pageCnt;
            $orderQuery .= " ORDER BY a.request_idx DESC $limitStr ";
            $i = 1;
            $c = 0;
            $query .= $orderQuery;
            $res = mysqli_query($self_con, $query);
            while ($row = mysqli_fetch_array($res)) {
              $query = "SELECT mem_name,site,site_iam from Gn_Member where mem_id='{$row['m_id']}'";
              $sres = mysqli_query($self_con, $query);
              $srow = mysqli_fetch_array($sres);

              if (strpos($row['event_info'], "other") !== false) {
                $event_other_txt = $row['other'];
              } else {
                $event_other_txt = "";
              }
              $req_repo = "단독";
              $req_sql = "SELECT count(landing_idx) AS cnt FROM Gn_landing WHERE pcode='{$row['pcode']}'";
              $req_res = mysqli_query($self_con, $req_sql);
              $req_row = mysqli_fetch_assoc($req_res);
              if ($req_row['cnt'] > 0)
                $req_repo .= "<br>신청창";
              $repo_sql = "SELECT count(id) AS cnt FROM gn_report_form WHERE pcode = '{$row['event_idx']}'";
              $repo_res = mysqli_query($self_con, $repo_sql);
              $repo_row = mysqli_fetch_assoc($repo_res);
              if ($repo_row['cnt'] > 0)
                $req_repo .= "<br>리포트";

            ?>
              <tr>
                <td><input type="checkbox" name="event_idx" value="<?=$row['event_idx']; ?>" data-name="<?= $row['name'] ?>" data-mobile="<?= $row['mobile'] ?>" data-email="<?= $row['email'] ?>" data-job="<?= $row['job'] ?>" data-event_code="<?= $row['event_code'] ?>" data-sp="<?= $row['sp'] ?>" data-request_idx="<?=$row['request_idx']; ?>"></td>
                <td><?= $number-- ?></td>
                <td><?= $req_repo ?></td>
                <td style="font-size:12px;"><?= $srow['site'] . "/" . $srow['site_iam'] ?></td>
                <td style="font-size:12px;"><?= $row['m_id'] ?></td>
                <td style="font-size:12px;"><?= $srow['mem_name'] ?></td>
                <td><?= $row['event_title'] ?></td>
                <td></td>
                <td style="font-size:12px;"><?= $row['name'] ?></td>
                <td><?= $row['mobile'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['job'] ?></td>
                <td><?= $row['other'] ?></td>
                <td><?= $row['regdate'] ?></td>
              </tr>
            <?
              $c++;
              $i++;
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
      <? echo drawPagingAdminNavi($totalCnt, $nowPage); ?>
    </div>
  </div>
</div><!-- /.row -->
</section><!-- /.content -->
<!-- Footer -->
<? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>
</div><!-- /content-wrapper -->

<form id="excel_down_form" name="excel_down_form" target="excel_iframe" method="post">
  <input type="hidden" name="grp_id" value="" />
  <input type="hidden" name="box_text" value="" />
  <input type="hidden" name="one_member_id" id="one_member_id" value="" />
  <input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
</form>
<iframe name="excel_iframe" style="display:none;"></iframe>
<span class="tooltiptext-bottom" id="event_other" style="display:none;">
  <p class="title_app">답변 내용<span onclick="cancel()" style="float:right;cursor:pointer;">X</span></p>
  <table class="table table-bordered" style="width: 97%;">
    <tbody>
      <tr class="hide_spec">
        <textarea name="set_event_other_req" id="set_event_other_req" style="border:none;width:100%; height:100px;font-size: 12px;padding:20px;background-color:white;" readonly disabled></textarea></td>
      </tr>
    </tbody>
  </table>
</span>
<div id="tutorial-loading"></div>
<script language="javascript">
  function viewEvent(str) {
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
  }
</script>

<div id='open_recv_div' class="open_1">
  <div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    <li class="open_recv_title open_2_1"></li>
    <li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>
  </div>
  <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
  </div>
</div>
<script>
  function show_txt_detail(txt) {
    $("#set_event_other_req").val(txt);
    $("#event_other").show();
    $("#tutorial-loading").show();
  }

  function cancel() {
    $("#event_other").hide();
    $("#tutorial-loading").hide();
  }
</script>