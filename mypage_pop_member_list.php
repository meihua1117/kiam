<?
$path = "./";
include_once $path . "lib/rlatjd_fun.php";
?>
<!DOCTYPE html>
<html lang="ko">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>신청자 리스트</title>
  <meta name="description" content=" 온리원셀링,온리원문자,PC-스마트폰 연동문자, 대량문자, 장문/포토 모두0.7원, 수신거부/수신동의/없는번호/번호변경 자동관리, 수신처관리" />
  <meta name="keywords" content="인간능력향상프로젝트 온리원교육주도력 인지력 관계력 인공지능대비 학습능력 업무능력 10배향상프로젝트 천재의일반화" />
  <!-- <link href='<?= $path ?>css/nanumgothic.css' rel='stylesheet' type='text/css'/> -->
  <link href='<?= $path ?>css/main.css' rel='stylesheet' type='text/css' />
  <link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
  <link href='<?= $path ?>css/responsive.css' rel='stylesheet' type='text/css' /><!-- 2019.11 반응형 CSS -->
  <link href='<?= $path ?>css/font-awesome.min.css' rel='stylesheet' type='text/css' /><!-- 2019.11 반응형 CSS -->
  <script language="javascript" src="<?= $path ?>js/jquery-1.7.1.min.js"></script>
  <script language="javascript" src="<?= $path ?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
  <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
  <script type="text/javascript" src="/jquery.cookie.js"></script>
</head>

<body>
  <? if (!$_SESSION['one_member_id']) { ?>
    <script language="javascript">
      location.replace('/ma.php');
    </script>
  <?
    exit;
  }
  if (isset($_GET['eventid'])) {
    $sql = "select * from Gn_event where event_idx='{$_GET['eventid']}' ";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $data = mysqli_fetch_array($result);
  }

  ?>
  <script>
    function copyHtml() {
      //oViewLink = $( "ViewLink" ).innerHTML;
      ////alert ( oViewLink.value );
      //window.clipboardData.setData("Text", oViewLink);
      //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
      var trb = $.trim($('#sHtml').html());
      var IE = (document.all) ? true : false;
      if (IE) {
        if (confirm("이 소스코드를 복사하시겠습니까?")) {
          window.clipboardData.setData("Text", trb);
        }
      } else {
        temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
      }

    }
    $(function() {
      $(".popbutton").click(function() {
        $('.ad_layer_info').lightbox_me({
          centered: true,
          onLoad: function() {}
        });
      });
      $(".popup_holder").mouseover(function(event) {
        $("#popupbox").hide();
        $(this).children(".popupbox").css({
          "top": (event.pageY + 25) + "px",
          "left": (event.pageX - 600) + "px"
        });
        $(this).children(".popupbox").show();
      });
      $(".popup_holder").mouseout(function() {
        $(this).children(".popupbox").css("display", "none");
      });

      $(".popup_text").hover(function() {
        $(this).css("color", "#0f7bef");
        $(this).children(".popupbox").css("color", "black");
      }, function(e) {
        $(".popup_text").css("color", "black");
      });
      $('input[name="except_send"]').on('click', function() {
        var request_idx = $(this).val();
        var except_status = "";
        if ($(this).prop('checked')) {
          except_status = "Y";
        } else {
          except_status = "N";
        }
        $.ajax({
          type: "POST",
          url: "mypage.proc.php",
          data: {
            mode: "request_except_update",
            request_idx: request_idx,
            except_status : except_status
          },
          success: function(data) {
            
          }
        });
      });

    });

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
  <script language="javascript" src="./js/rlatjd_fun.js?m=1574414201"></script>
  <script language="javascript" src="./js/rlatjd.js?m=1574414201"></script>
  <style>
    .pop_right {
      position: relative;
      right: 2px;
      display: inline;
      margin-bottom: 6px;
      width: 5px;
    }

    .popup_holder {
      position: relative;
    }

    .popupbox {
      z-index: 1000;
      text-align: left;
      font-size: 12px;
      font-weight: normal;
      background: white;
      border-radius: 3px;
      padding: 10px;
      border: none;
      position: absolute;
      box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
    }
  </style>
  <div>
    <div class="m_body">
      <form name="pay_form" action="" method="post" class="my_pay">
        <input type="hidden" name="page" value="<?= $page ?>" />
        <input type="hidden" name="page2" value="<?= $page2 ?>" />
        <div class="a1">
          <li style="margin: 10px;float:left;"><?= $data['event_title'] ?></li>
          <li style="float:right;"></li>
          <p style="clear:both"></p>
        </div>
        <div>
          <div class="p1">
            <select name="search_key" class="select">
              <option value="">전체</option>
            </select>
            <input type="text" name="search_text" placeholder="" id="search_text" value="<?= $_REQUEST['search_text'] ?>" />
            <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
            <div style="float:right;">
            </div>
          </div>

          <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:6%;">No</td>
                <td style="width:15%;">고객명</td>
                <td style="width:15%;">휴대폰번호</td>
                <td style="width:15%">신청일짜</td>
                <td style="width:10%">보기</td>
                <td style="width:10%">그룹제외</td>
                <td style="width:10%">발송제외</td>
              </tr>
              <?

              //$sql_serch = " m_id ='{$_SESSION['one_member_id']}' and event_idx='{$data['event_idx']}'";
              $sql_serch = " event_idx='{$data['event_idx']}'";
              if (isset($_REQUEST['search_text']))
                $sql_serch .= " AND (name like '%{$_REQUEST['search_text']}%' OR mobile like '%{$_REQUEST['search_text']}%')";
              $sql = "select count(*) as cnt from Gn_event_request where $sql_serch ";
              $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
              $row = mysqli_fetch_array($result);
              $intRowCount = $row['cnt'];
              if ($intRowCount) {
                if (!$_POST['lno'])
                  $intPageSize = 15;
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
                  $order_name = "request_idx";
                $intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
                $sql = "select * from Gn_event_request where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
                $excel_sql = base64_encode("select * from Gn_event_request where $sql_serch order by $order_name $order_status");
                $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
              ?>
                <?
                while ($row = mysqli_fetch_array($result)) {
                ?>
                  <tr>
                    <td><?= $sort_no ?></td>
                    <td style="font-size:12px;"><?= $row['name'] ?></td>
                    <td style="font-size:12px;"><?= $row['mobile'] ?></td>
                    <td><?= $row['regdate'] ?></td>
                    <td>
                      <div class="popup_holder popup_text"> 보기
                        <div class="popupbox" style="position:fixed;display:none; height: 75px;width: 570px;color: black;left: 160px;top: -37px;">아이디 | 성명 | 휴대폰 | 일반폰 | 이메일 | 생년월일 | 업종 | 업체명 | 직책 | 업체주소 | 자택주소 | 등록일시 <br>
                          <?
                          $sql_mem_reg = "select * from Gn_Member where mem_phone='{$row['mobile']}'";
                          $res_mem_reg = mysqli_query($self_con, $sql_mem_reg);
                          $row_mem_reg = mysqli_fetch_array($res_mem_reg);
                          $mem_name = $row_mem_reg['mem_name'] ? $row_mem_reg['mem_name'] : '성명';
                          $mem_phone = $row_mem_reg['mem_phone'] ? $row_mem_reg['mem_phone'] : '휴대폰';
                          $mem_phone1 = $row_mem_reg['mem_phone1'] ? $row_mem_reg['mem_phone1'] : '일반폰';
                          $mem_email = $row_mem_reg['mem_email'] ? $row_mem_reg['mem_email'] : '이메일';
                          $mem_birth = $row_mem_reg['mem_birth'] ? $row_mem_reg['mem_birth'] : '생년월일';
                          $com_type = $row_mem_reg['com_type'] ? $row_mem_reg['com_type'] : '업종';
                          $zy = $row_mem_reg['zy'] ? $row_mem_reg['zy'] : '업체명';
                          $mem_leb = $row_mem_reg['mem_job'] ? $row_mem_reg['mem_job'] : '직책';
                          $com_add1 = $row_mem_reg['com_add1'] ? $row_mem_reg['com_add1'] : '업체주소';
                          $mem_add1 = $row_mem_reg['mem_add1'] ? $row_mem_reg['mem_add1'] : '자택주소';
                          // $mem_memo = $row_mem_reg['mem_memo']?$row_mem_reg['mem_memo']:'메모';
                          echo $row_mem_reg['mem_id'] . " | " . $mem_name . " | " . $mem_phone . " | " . $mem_phone1 . " | " . $mem_email . " | "
                            . $mem_birth . " | " .  $com_type . " | " .  $zy . " | "
                            . $mem_leb . " | " . $com_add1 . " | " . $mem_add1 . " | " . $row_mem_reg['first_regist'];
                          ?>
                        </div>
                      </div>
                    </td>
                    <td>
                      <?
                      $stop_status = "";
                      $sql = "select count(*) from Gn_event_sms_info where event_idx='{$data['event_idx']}' and mobile='{$row['mobile']}'";
                      $stop_res = mysqli_query($self_con, $sql);
                      $stop_row = mysqli_fetch_array($stop_res);
                      if ($stop_row[0] > 0)
                        $stop_status = "checked";
                      ?>
                      <input type="checkbox" id="exclude_group" name="exclude_group" disabled <?= $stop_status ?> value="">
                    </td>
                    <td>
                      <?
                      $except_status = "";
                      if ($row['target'] >= 100)
                        $except_status = "checked";
                      ?>
                      <input type="checkbox" id="except_send" name="except_send" <?= $except_status ?> value="<?= $row['request_idx'] ?>">
                    </td>
                  </tr>
                <?
                  $sort_no--;
                }
                ?>
                <tr>
                  <td colspan="7">
                    <?
                    page_f($page, $page2, $intPageCount, "pay_form");
                    ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="6">
                    <div class="popup_holder" style="display:inline-block">
                      <input type="button" value="엑셀 다운받기" class="button" onclick="excel_down('/excel_down/excel_mypage_pop_member_list.php');return false;" style="cursor: pointer">
                    </div>
                  </td>
                </tr>
              <?
              } else {
              ?>
                <tr>
                  <td colspan="10">
                    검색된 내용이 없습니다.
                  </td>
                </tr>
              <?
              }
              ?>
            </table>
            <!--
            <input type="button" value="예약 문자 보내기" class="button">
            -->
          </div>
        </div>
      </form>
      <form id="excel_down_form" name="excel_down_form" target="excel_iframe" method="post">
        <input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
        <? if (isset($_GET['eventid'])) { ?>
          <input type="hidden" name="eventid" value="<?= $_GET['eventid'] ?>" />
        <? } ?>
      </form>
      <iframe name="excel_iframe" style="display:none;"></iframe>
    </div>
  </div>
</body>

</html>