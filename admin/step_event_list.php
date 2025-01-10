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
    location.href = '?nowPage=' + pgNum + "&search_key=<?php echo $_GET['search_key']; ?>&case=<?php echo $_GET['case']; ?>";
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
        이벤트 신청페이지
        <small>이벤트 신청페이지를 관리합니다.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">이벤트 신청페이지</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row" id="toolbox">
        <div class="col-xs-12" style="padding-bottom:20px">
          <div style="padding:10px">
          </div>
          <?if ($_SESSION['one_member_admin_id'] != "onlyonemaket") { } ?>
          <!--button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='auto_join_list_member.php';return false;"><i class="fa fa-download"></i> 오토회원 가입 리스트</button-->
          <form method="get" name="search_form" id="search_form">
            <div class="box-tools">
              <div class="input-group" style="width: 250px;">
                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이벤트영문명/이벤트한글명/신청경로">
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
              <td></td>
              <td style="text-align: center;">No</td>
              <td style="text-align: center;">신청창/<br>리포트</td>
              <td style="text-align: center;">셀링/IAM<br>소속</td>
              <td style="text-align: center;">작성자<br>아이디</td>
              <td style="text-align: center;">작성자<br>이름</td>
              <td style="text-align: center;">신청그룹<br>제목</td>
              <td style="text-align: center;">신청대상/<br>이벤트설명</td>
              <td style="text-align: center;">링크복사</td>
              <td style="text-align: center;">발송번호</td>
              <td style="text-align: center;">스텝문자<br>회차</td>
              <td style="text-align: center;">중단문자<br>ON/OFF</td>
              <td style="text-align: center;">조회수/<br>신청수</td>
              <td style="text-align: center;">등록일</td>
            </tr>
          </thead>
          <tbody>
            <?
            $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
            $startPage = $nowPage ? $nowPage : 1;
            $pageCnt = 20;

            // 검색 조건을 적용한다.
            $searchStr .= $search_key ? " AND (a.event_name_eng LIKE '" . $search_key . "%' or a.event_name_kor like '" . $search_key . "%' or a.pcode like '" . $search_key . "%' )" : null;
            if ($case == 1) {
              $searchStr .= " and title = 'app_check_process'";
            } else if ($case == 2) {
              $searchStr .= " and title != 'app_check_process'";
            }

            $order = $order ? $order : "desc";

            $query = "SELECT count(a.m_id) FROM Gn_event a WHERE event_name_kor!='단체회원자동가입및아이엠카드생성' AND event_name_kor!='콜백메시지관리자설정동의' AND event_name_kor!='데일리문자세트자동생성' $searchStr";
            $res      = mysqli_query($self_con, $query);
            //$totalCnt	=  mysqli_num_rows($res);	
            $totalRow  =  mysqli_fetch_array($res);
            $totalCnt = $totalRow[0];
            $query = "SELECT a.event_idx, a.m_id, a.event_name_eng, a.event_name_kor, a.pcode, a.short_url, a.mobile, a.regdate, a.read_cnt
                  FROM Gn_event a WHERE event_name_kor!='단체회원자동가입및아이엠카드생성' AND event_name_kor!='콜백메시지관리자설정동의' AND event_name_kor!='데일리문자세트자동생성' $searchStr";
            $limitStr       = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
            $number      = $totalCnt - ($nowPage - 1) * $pageCnt;
            $orderQuery .= " ORDER BY a.event_idx DESC $limitStr ";

            $i = 1;
            $c = 0;
            $query .= "$orderQuery";
            $res = mysqli_query($self_con, $query);
            while ($row = mysqli_fetch_array($res)) {
              $query = "SELECT mem_name,site,site_iam from Gn_Member where mem_id='{$row['m_id']}'";
              $sres = mysqli_query($self_con, $query);
              $srow = mysqli_fetch_array($sres);
            ?>
              <tr>
                <td><input type="checkbox" name="event_idx" value="<?php echo $row['event_idx']; ?>"></td>
                <td><?= $number-- ?></td>
                <td></td>
                <td style="font-size:12px;"><?= $srow['site']."/".$srow['site_iam'] ?></td>
                <td style="font-size:12px;"><?= $row['m_id'] ?></td>
                <td style="font-size:12px;"><?= $srow['mem_name'] ?></td>
                <td style="font-size:12px;"></td>
                <td style="font-size:12px;"></td>
                <td>
                  <?
                  /*if ($row['event_name_kor'] == "단체회원자동가입및아이엠카드생성") {
                    $pop_url = '/event/automember.php?pcode=' . $row['pcode'] . '&eventidx=' . $row['event_idx'];
                  } else if ($row['event_name_kor'] == "콜백메시지관리자설정동의") {
                    $pop_url = '/event/callbackmsg.php?pcode=' . $row['pcode'] . '&eventidx=' . $row['event_idx'];
                  } else if ($row['event_name_kor'] == "데일리문자세트자동생성") {
                    $pop_url = '/event/dailymsg.php?pcode=' . $row['pcode'] . '&eventidx=' . $row['event_idx'];
                  } else {
                    $pop_url = '/event/event.html?pcode=' . $row['pcode'] . '&sp=' . $row['event_name_eng'];
                  }*/
                  $pop_url = '/event/event.html?pcode=' . $row['pcode'] . '&sp=' . $row['event_name_eng'];
                  ?>
                  <input type="button" value="미리보기" class="button" onclick="newpop('<?= $pop_url ?>')"><br>
                  <input type="button" value="링크복사" class="button" id="copyBtn" onclick="copyHtml('<?php echo $row['short_url'] ?>')">
                </td>
                <td><?= $row['mobile'] ?></td>
                <td></td>
                <td></td>
                <td><?= $row['read_cnt'] ?></td>
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
      <?
      echo drawPagingAdminNavi($totalCnt, $nowPage);
      ?>
    </div>
  </div>
</div><!-- /.row -->
</section><!-- /.content -->
<!-- Footer -->
<? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>
</div><!-- /content-wrapper -->

<div id='open_recv_div' class="open_1">
  <div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    <li class="open_recv_title open_2_1"></li>
    <li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>
  </div>
  <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">

  </div>
</div>
<script>
  function newpop(str) {
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");

  }

  function copyHtml(url) {
    //oViewLink = $( "ViewLink" ).innerHTML;
    ////alert ( oViewLink.value );
    //window.clipboardData.setData("Text", oViewLink);
    //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
    var IE = (document.all) ? true : false;
    if (IE) {
      if (confirm("이 소스코드를 복사하시겠습니까?")) {
        window.clipboardData.setData("Text", url);
      }
    } else {
      temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", url);
    }

  }
</script>