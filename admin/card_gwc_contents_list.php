<style>
  .table-bordered>thead>tr>th,
  .table-bordered>tbody>tr>th,
  .table-bordered>tfoot>tr>th,
  .table-bordered>thead>tr>td,
  .table-bordered>tbody>tr>td,
  .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd !important;
  }

  input,
  select,
  textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
  }

  /* user agent stylesheet */
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
    location.href = '?nowPage=' + pgNum + "&search_name=<?= $search_name ?>&search_id=<?= $search_id ?>&search_group=<?= $search_group ?>&search_title=<?= $search_title ?>&orderField=<?= $orderField ?>&dir=<?= $dir ?>&show_type=<?= $show_type ?>";
  }
  $(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if (navH != contHeaderH)
      contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top", contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - $("#list_paginate").height() - 212;
    if (height < 375)
      height = 375;
    $(".box-body").css("height", height);
  });
</script>
<!-- Top 메뉴 -->
<? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header_menu.inc.php"; ?>
<div class="wrapper">
  <!-- Left 메뉴 -->
  <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_left_menu.inc.php"; ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>굿마켓 상품관리<small>굿마켓 상품정보를 관리합니다.</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">굿마켓 상품관리</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row" id="toolbox">
        <div>
          <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow()">선택삭제</button>
          <button class="btn btn-primary pull-right" style="margin-right: 5px;<?= $show_type == 3 ? 'background-color:#00c0ef;' : '' ?>" onclick="show_type(3)">전체보기</button>
          <button class="btn btn-primary pull-right" style="margin-right: 5px;<?= $show_type == 2 ? 'background-color:#00c0ef;' : '' ?>" onclick="show_type(2)">비노출</button>
          <button class="btn btn-primary pull-right" style="margin-right: 5px;<?= $show_type == 1 ? 'background-color:#00c0ef;' : '' ?>" onclick="show_type(1)">노출</button>
        </div>
        <form method="get" name="search_form" id="search_form">
          <div class="box-tools">
            <div class="input-group" style="display:flex;width:350px">
              <!-- <input type="text" name="search_name" id="search_name" class="form-control input-sm pull-right" placeholder="이름" value="<?= $search_name ?>"> -->
              <input type="text" name="search_id" id="search_id" class="form-control input-sm pull-right" placeholder="아이디" value="<?= $search_id ?>">
              <!-- <input type="text" name="search_group" id="search_group" class="form-control input-sm pull-right" placeholder="그룹명" value="<?= $search_group ?>"> -->
              <input type="text" name="search_title" id="search_title" class="form-control input-sm pull-right" placeholder="콘텐츠제목" value="<?= $search_title ?>">
              <div class="input-group-btn">
                <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <? $dir = $_REQUEST['dir'] == "desc" ? "asc" : "desc"; ?>
      <div class="row">
        <div class="box">
          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
              <colgroup>
                <col width="4%">
                <col width="5%">
                <col width="5%">
                <!-- <col width="5%"> -->
                <col width="5%">
                <col width="7%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
              </colgroup>
              <thead>
                <tr>
                  <th><input type="checkbox" class="check" id="check_all_member" value="0">번호</th>
                  <!-- <th>노출여부</th> -->
                  <th>유통사</th>
                  <!-- <th>공급사</th> -->
                  <th>셀링/IAM소속</th>
                  <th>이름/아이디</th>
                  <th>상품출처</a></th>
                  <th>이미지</th>
                  <th>제목</th>
                  <th>카드제목</th>
                  <th>분류정보</th>
                  <th>시중가</th>
                  <th>할인가</th>
                  <th>세후가</th>
                  <th>공급가</th>
                  <th>생산가</th>
                  <th>관리가</th>
                  <th>등록일</th>
                  <th>수정</th>
                </tr>
              </thead>
              <tbody>
                <?
                $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
                $startPage = $nowPage ? $nowPage : 1;
                $pageCnt = 20;
                // 검색 조건을 적용한다.
                // $searchStr .= $search_name ? " AND (cards.card_name like '%".$search_name."%' )" : null;
                $searchStr .= $search_id ? " AND (contents.mem_id LIKE '%" . $search_id . "%' )" : null;
                // $searchStr .= $search_group ? " AND (gi.name LIKE '%".$search_group."%' )" : null;
                $searchStr .= $search_title ? " AND (contents.contents_title like '%" . $search_title . "%' )" : null;
                $order = $order ? $order : "desc";

                if ($show_type == 1) {
                  $searchStr .= " AND (contents.public_display='Y')";
                } else if ($show_type == 2) {
                  $searchStr .= " AND (contents.public_display='N')";
                } else if ($show_type == 3) {
                  $searchStr .= " AND (contents.public_display='N' or contents.public_display='Y')";
                }

                if ($searchStr == null) {
                  $count_query = "select count(*) from Gn_Iam_Contents_Gwc contents where contents.gwc_con_state!=0";
                } else {
                  $count_query = "select count(*) from Gn_Iam_Contents_Gwc contents where contents.gwc_con_state!=0 $searchStr";
                }
                $count_result = mysqli_query($self_con, $count_query);
                $count_row = mysqli_fetch_array($count_result);
                $totalCnt  =  $count_row[0];

                $query = "SELECT  contents.idx, contents.mem_id,contents.contents_type,contents.contents_img,contents.group_id," .
                  "contents.contents_title,contents.contents_url,contents.card_short_url,contents.westory_card_url,contents.contents_sell_price," .
                  "contents.contents_temp,contents.contents_like,contents.contents_share_text,contents.contents_share_count,contents.up_data,contents.sample_display,contents.sample_order,contents.public_display,contents.gwc_con_state,contents.contents_price,contents.product_seperate,contents.send_provide_price,contents.prod_manufact_price,contents.card_idx,contents.prod_sehu_price " .
                  "FROM Gn_Iam_Contents_Gwc contents WHERE contents.gwc_con_state!=0 $searchStr";
                $limitStr = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
                $number = $totalCnt - ($nowPage - 1) * $pageCnt;
                if (!$orderField) {
                  $orderField = "up_data";
                }
                $orderQuery .= " ORDER BY $orderField $dir $limitStr";
                $i = 1;
                $c = 0;
                $query .= $orderQuery;
                $res = mysqli_query($self_con, $query);
                while ($row = mysqli_fetch_array($res)) {
                  $mem_sql = "select site, site_iam, gwc_provider_name, mem_name, gwc_manage_price, mem_code from Gn_Member where mem_id='{$row['mem_id']}'";
                  $mem_res = mysqli_query($self_con, $mem_sql);
                  $mem_row = mysqli_fetch_array($mem_res);
                  if ($mem_row['gwc_manage_price']) {
                    $mng_price = $mem_row['gwc_manage_price'];
                  } else {
                    $mng_price = $row['send_provide_price'] * 1 - $row['prod_manufact_price'] * 1;
                  }

                  if (!$row['prod_sehu_price']) {
                    $sehu_price = $row['contents_sell_price'] * 1 - ceil($row['contents_sell_price'] * 1 * 0.1) - ceil($row['contents_sell_price'] * 1 * 0.03);
                  } else {
                    $sehu_price = $row['prod_sehu_price'];
                  }

                  if (strpos($row['contents_img'], ",") !== false) {
                    $img_link_arr = explode(",", $row['contents_img']);
                    $img_link = trim($img_link_arr[0]);
                  } else {
                    $img_link = $row['contents_img'];
                  }
                  $sql_card = "select card_title from Gn_Iam_Name_Card where idx='{$row['card_idx']}'";
                  $res_card = mysqli_query($self_con, $sql_card);
                  $row_card = mysqli_fetch_array($res_card);
                ?>
                  <tr>
                    <td><input type="checkbox" class="check" id="check_one_member" name="" value="<?= $row['idx'] ?>"><?= $number-- ?></td>
                    <td>온리원</td>
                    <td>
                      <div style="overflow-x:hidden;width:100px;">
                        <?= $mem_row[0] ?>/<br><?= $mem_row[1] ?>
                      </div>
                    </td>
                    <td>
                      <div style="overflow-x:hidden;width:100px;">
                        <?= $mem_row['mem_name'] ?>/<br><?= $row['mem_id'] ?>
                      </div>
                    </td>
                    <td><?= $row['gwc_con_state'] == 1 ? "well" : "공동구매용" ?></td>
                    <td>
                      <a href="/iam/contents_gwc.php?contents_idx=<?= $row['idx'] ?>&gwc=Y" target="_blank">
                        <img class="zoom" src="<?= $img_link ?>" style="width:50px;">
                      </a>
                    </td>
                    <td><?= $row['contents_title'] ?></td>
                    <td><?= $row_card['card_title'] ?></td>
                    <td><?= $row['product_seperate'] ?></td>
                    <td><?= $row['contents_price'] ?></td>
                    <td><?= $row['contents_sell_price'] ?></td>
                    <td><input type="number" name="sehu_price_<?= $row['idx'] ?>" value="<?= $sehu_price ?>" style="width:65px;font-size: 11px;"><button onclick="save_sehu_price('<?= $row['idx'] ?>');return false;" style="font-size: 11px;color: white;background-color: black;padding: 0px 5px;">저장</button></td>
                    <td><?= $row['send_provide_price'] ?></td>
                    <td><?= $row['prod_manufact_price'] ?></td>
                    <td><input type="number" name="manage_price_<?= $mem_row['mem_code'] ?>" value="<?= $mng_price ?>" style="width:65px;font-size: 11px;"><button onclick="save_manage_price('<?= $mem_row['mem_code'] ?>');return false;" style="font-size: 11px;color: white;background-color: black;padding: 0px 5px;">저장</button></td>
                    <td><?= $row['up_data'] ?></td>
                    <td><a href="card_contents_detail_list.php?idx=<?= $row['idx'] ?>">수정</a></td>
                  </tr>
                <?
                  $c++;
                  $i++;
                }
                if ($i == 1) {
                ?>
                  <tr>
                    <td colspan="18" style="text-align:center;background:#fff">
                      등록된 내용이 없습니다.
                    </td>
                  </tr>
                <?
                }
                ?>
              </tbody>
            </table>
          </div><!-- /.box-body -->
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
        </div><!-- /.row -->
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
</div>
<script language="javascript">
  function delContent(idx) {
    var msg = confirm('정말로 삭제하시겠습니까?');
    if (msg) {

      $.ajax({
        type: "POST",
        url: "/admin/ajax/_db_controller.php",
        data: {
          mode: "delete_gwc_contents",
          idx: idx
        },
        success: function(data) {
          alert(data);
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
    $('.check').on("click", function() {
      if ($(this).prop("id") == "check_all_member") {
        if ($(this).prop("checked"))
          $('.check').prop("checked", true);
        else
          $('.check').prop("checked", false);
      } else if ($(this).prop("id") == "check_one_member") {
        if (!$(this).prop("checked"))
          $('#check_all_member').prop("checked", false);
      }
    });
  });

  function deleteMultiRow() {
    var check_array = $("#example1").children().find(".check");
    var no_array = [];
    var index = 0;
    check_array.each(function() {
      if ($(this).prop("checked") && $(this).val() > 0)
        no_array[index++] = $(this).val();
    });

    if (no_array.length == 0) {
      alert("삭제할 상품을 선택하세요.");
      return;
    }
    if (confirm('삭제하시겠습니까?')) {
      $.ajax({
        type: "POST",
        url: "/admin/ajax/_db_controller.php",
        dataType: "json",
        data: {
          mode: "delete_gwc_contents",
          id: no_array.toString()
        },
        success: function(data) {
          if (data == 1) {
            alert('삭제 되었습니다.');
            window.location.reload();
          }
        }
      })
    }
  }
  $(function() {
    $('.chkclick').change(function() {
      var id = $(this).val();
      var sample_click = $(this)[0].checked;
      if (sample_click) {
        sample_click = "Y";
      } else {
        sample_click = "N";
      }
      $.ajax({
        type: "POST",
        url: "/admin/ajax/_db_controller.php",
        data: {
          mode: "update-gwc-contents-display",
          sample_display: sample_click,
          cont_idx: id
        },
        success: function(data) {
          location.reload();
        },
        error: function() {
          alert('삭제 실패');
        }
      });
    });
  });

  function save_manage_price(mem_code) {
    var price = $("input[name=manage_price_" + mem_code + "]").val();
    $.ajax({
      type: "POST",
      url: "/admin/ajax/_db_controller.php",
      data: {
        mode: "update-gwc-manage-price",
        price: price,
        mem_code: mem_code
      },
      success: function(data) {
        location.reload();
      },
      error: function() {
        alert('실패');
      }
    });
  }

  function save_sehu_price(idx) {
    var price = $("input[name=sehu_price_" + idx + "]").val();
    $.ajax({
      type: "POST",
      url: "/admin/ajax/_db_controller.php",
      data: {
        mode: "update-gwc-sehu-price",
        price: price,
        idx: idx
      },
      success: function(data) {
        location.reload();
      },
      error: function() {
        alert('실패');
      }
    });
  }

  function show_type(type) {
    location.href = '?nowPage=' + <?= $nowPage ?> + "&search_name=<?= $search_name ?>&search_id=<?= $search_id ?>&search_group=<?= $search_group ?>&search_title=<?= $search_title ?>&orderField=<?= $orderField ?>&dir=<?= $dir ?>&show_type=" + type;
  }
</script>