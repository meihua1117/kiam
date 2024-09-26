<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today = date("Y-m-d");
$date_month = date("Y-m");

// 가입 회원 상세 정보
$query = "select recommend_id, `mem_code`, `b2b_code`, `mem_leb`, `mem_group`, `mem_id`, `mem_tally`, `mem_pass`, `web_pwd`, 
                `mem_name`, `mem_nick`, `mem_post`, `mem_add1`, `mem_add2`, `mem_tel`, `mem_phone`, `mem_fax`, `mem_email`, 
                `mem_birth`, `mem_sex`, `mem_point`,`bank_name`, `bank_owner`, `bank_account`, `zy`, 
                `is_message`, `is_leave`, `leave_txt`, `c_all`, `mem_check`,`first_regist`, `last_modify`, `last_regist`, 
                `join_ip`, `login_ip`, `visited`, `site`, `id_type`, `level`, `fujia_date1`, `fujia_date2`, 
                `ext_school_sido`,`ext_num`, `ext_recm_id`, 
                `login_date`, `phone_cnt`, `total_pay_money`, `mem_type`,
                (select count(*) from Gn_MMS_Number where 1=1 and ( not (cnt1 = 10 and cnt2 = 20)) and mem_id =Gn_Member.mem_id) tcnt
            from Gn_Member where mem_code='$mem_code'";
$res = mysqli_query($self_con, $query);
$data = mysqli_fetch_array($res);

// 기부회원 상세 정보
$query = "select `idx`, `mem_id`, `sendnum`, `max_cnt`, `user_cnt`, `gl_cnt`, `month_cnt`, `today_cnt`, `over_cnt`, `memo`, `reg_date`, `up_date`, `max_over_cnt`, `memo2`, `device`, `memo3`, `usechk`, `cnt1`, `cnt2`, `format_date`, `end_status`, `end_date`, `donation_rate`, `daily_limit_cnt`, `use_order` 
            from Gn_MMS_Number where mem_id='$data[mem_id]' and sendnum='$sendnum'";
$res = mysqli_query($self_con, $query);
$donation_data = mysqli_fetch_array($res);

// =====================  유료결제건 시작 ===================== 
$sql = "select phone_cnt, add_phone from tjd_pay_result where buyer_id = '" . $data['mem_id'] . "' and end_date > '$date_today' and end_status='Y' order by end_date desc limit 1";
$res_result = mysqli_query($self_con, $sql);
$buyPhoneCnt = mysqli_fetch_row($res_result);
mysqli_free_result($res_result);


if ($buyPhoneCnt == 0) {
  $buyMMSCount = 0;
} else {
  $buyMMSCount = ($buyPhoneCnt[0] - 1) * 9000;
}
// ===================== 유료결제건 끝 ===================== 

// =====================  총결제금액 시작 ===================== 
$sql = "select sum(TotPrice) totPrice, date from tjd_pay_result where buyer_id = '" . $data['mem_id'] . "' and end_status='Y'";
$res_result = mysqli_query($self_con, $sql);
$totPriceRow = mysqli_fetch_row($res_result);


$totPrice = $totPriceRow[0];
$pay_date = $totPriceRow[1];
// ===================== 총결제금액 끝 =====================                     	

// =====================  마지막 결제정보 시작 ===================== 
/*
$sql = "select reg_date  from tjd_pay_result where buyer_id = '".$data['mem_id']."' order by end_date desc limit 1";
$res_result = mysqli_query($sql);
$totPriceRow = mysqli_fetch_row($res_result);
mysqli_free_result($res_result);

$totPrice = $totPriceRow[0];
*/
// ===================== 마지막 결제정보 끝 =====================                     	

// =====================  마지막 발송정보 시작 ===================== 
$sql = "select msg_text, reservation_time  from sm_data where dest = '" . str_replace("-", "", $data['mem_phone']) . "' order by reservation_time desc limit 1";
$res_result = mysqli_query($self_con, $sql);
$totPriceRow = mysqli_fetch_row($res_result);
mysqli_free_result($res_result);

//$totPrice = $totPriceRow[0];
// ===================== 마지막 발송정보 끝 =====================                     	


// 부가서비스 이용 여부 확인
// tjd_pay_result.fujia_status
if ($data['fujia_date2'] >= date("Y-m-d H:i:s")) {
  $add_opt = "사용";
} else {
  $add_opt = "미사용";
}

// 기부회원 상세정보

?>
<style>
  .box-body th {
    background: #ddd;
  }
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script>
<script language="javascript">
  //수정
  function form_save() {

    var recommender = $("#recommend_id").val();
    var userid = $("#divID").text();
    if (recommender == userid) {
      alert('자신의 아이디는 추천에 입력되지 않습니다.');
      return;
    }
    if ($('#is_exist_recommender').val() == "N") {
      alert('추천인을 정확히 입력해 주세요.');
      return;
    }
    if (recommender == '') {
      alert('추천인을 입력해주세요');
      return;
    }

    var msg = confirm('저장하시겠습니까?');

    if (msg) {
      var data = $('#dForm').serialize();
      $.ajax({
        type: "POST",
        url: "/admin/ajax/user_change.php",
        data: data,
        success: function() {
          alert('저장되었습니다.');
          //location.reload();
        },
        error: function() {
          alert('저장 실패');
        }
      });

    } else {
      return false;
    }
  }

  function check_recommender() {
    if ($('#recommend_id').val() != '') {
      $.ajax({
        type: "POST",
        url: "/ajax/join.proc.php",
        cache: false,
        dataType: "json",
        data: {
          mode: "check_recommender",
          rphone: $('#recommend_id').val()
        },
        success: function(data) {
          if (data.result == "success") {
            $('#is_exist_recommender').val("Y");
          } else {
            $('#is_exist_recommender').val("N");
            alert('아이디가 없습니다.');
          }

        }
      })
    }
  }

  //계정 삭제
  function passwd_reset(mem_code, opt) {

    var msg = confirm('정말로 초기화하시겠습니까?');

    if (msg) {

      $.ajax({
        type: "POST",
        url: "/admin/ajax/user_passwd.php",
        data: {
          mem_code: mem_code,
          opt: opt
        },
        success: function() {
          alert('초기화되었습니다.');
          location.reload();
        },
        error: function() {
          alert('초기화 실패');
        }
      });

    } else {
      return false;
    }
  }

  function gotoLogin() {

    $.ajax({
      type: "POST",
      url: "/admin/ajax/login_user.php",
      data: $('form[name=login_form]').serialize(),
      success: function() {
        location = "/";
        //location.reload();
      },
      error: function() {
        alert('초기화 실패');
      }
    });


    //alert($('form[name=login_form]').attr("action"));
    //$('form[name=login_form]').submit(); 
    //alert('');
    return false;
  }
</script>
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
    <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post">
      <input type="hidden" name="one_id" value="<?= $data['mem_id'] ?>" />
      <input type="hidden" name="mem_pass" value="<?= $data['web_pwd'] ?>" />
      <input type="hidden" name="mem_code" value="<?= $data['mem_code'] ?>" />
    </form>

    <form method="post" id="dForm" name="dForm">
      <input type="hidden" name="mem_code" value="<?= $data['mem_code'] ?>" />
      <input type="hidden" name="sendnum" value="<?= $_GET['sendnum'] ?>" />
      <!-- Main content -->
      <section class="content">
        <?
        if (str_replace("-", "", $data['mem_phone']) == $_GET['sendnum'] || $_GET['sendnum'] == "") {
        ?>
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="gotoLogin()"><i class="fa fa-user"></i> 회원페이지 로그인</button>
              회원정보 수정
            </div>
          </div>



          <div class="row">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">가입회원상세정보</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                <table id="detail1" class="table table-bordered table-striped">
                  <colgroup>
                    <col width="16%">
                    <col width="16%">
                    <col width="16%">
                    <col width="16%">
                    <col width="16%">
                    <col width="20%">
                  </colgroup>
                  <tbody>
                    <tr>
                      <th>아이디</th>
                      <td>
                        <div id='divID'><?= $data['mem_id'] ?></div>
                      </td>
                      <th>기부폰승인</th>
                      <td><?= $data['phone_cnt'] ?></td>
                      <th>회원가입일</th>
                      <td><?= $data['first_regist'] ?></td>
                    </tr>
                    <tr>
                      <th>닉네임</th>
                      <td><?= $data['mem_nick'] ?></td>
                      <th>부가서비스</th>
                      <td>
                        <input type="radio" name="add_opt" value="Y" <?= $add_opt == "사용" ? "checked" : "" ?>>사용 /
                        <input type="radio" name="add_opt" value="N" <?= $add_opt == "미사용" ? "checked" : "" ?>>미사용
                        <BR>서비스종료일 : <input type="text" name="fujia_date2" id="fujia_date2" value="<?= $data['fujia_date2'] ?>" />
                      </td>
                      <th>최근접속일</th>
                      <td><?= $data['login_date'] ?></td>
                    </tr>
                    <tr>
                      <th>성명</th>
                      <td><?= $data['mem_name'] ?></td>
                      <th>총결제금</th>

                      <td>
                        <?= number_format($totPrice) ?><BR /><!--<?= $data['total_pay_money'] ?>-->
                      </td>
                      <th>정보수정일</th>
                      <td><?= $data['last_modify'] ?></td>
                    </tr>
                    <tr>
                      <th>스마트폰번호</th>
                      <td><?= $data['mem_phone'] ?></td>
                      </td>
                      <th>결제계좌</th>
                      <td>
                        <table>
                          <colgroup>
                            <col width="60">
                            <col>
                          </colgroup>
                          <tbody>
                            <tr>
                              <td>은행</td>
                              <td><input type="text" style="width:150px;" name="bank_name" id="bank_name" value="<?= $data['bank_name'] ?>"></td>
                            </tr>
                            <tr>
                              <td>계좌</td>
                              <td><input type="text" style="width:150px;" name="bank_account" id="bank_account" value="<?= $data['bank_account'] ?>"></td>
                            </tr>
                            <tr>
                              <td>예금주</td>
                              <td><input type="text" style="width:150px;" name="bank_owner" id="bank_owner" value="<?= $data['bank_owner'] ?>"></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                      <th>접속횟수</th>
                      <td><?= number_format($data['visited']) ?></td>
                    </tr>
                    <tr>
                      <th>이메일</th>
                      <td><?= $data['mem_email'] ?></td>
                      <th>마지막 결제정보</th>
                      <td><?= $pay_date ?></td>
                      <th>회원구분</th>
                      <td>
                        <input type="radio" name="mem_type" value="V" <?= $data['mem_type'] != 'D' ? "checked" : "" ?>>선거용 /
                        <input type="radio" name="mem_type" value="D" <?= $data['mem_type'] == 'D' ? "checked" : "" ?>>일반용
                      </td>
                    </tr>
                    <tr>
                      <th>앱비밀번호</th>
                      <td>
                        <input type="password" style="width:100px;" name="passwd" id="passwd">
                        <button class="btn btn-primary" style="margin-right: 5px;" onclick="void($('#passwd').val('111111'));return false;">초기화</button>
                      </td>
                      <th>소식받기</th>
                      <td>
                        <input type="radio" name="is_message" value="Y" <?= $data['is_message'] == 'Y' ? "checked" : "" ?>>받음 /
                        <input type="radio" name="is_message" value="N" <?= $data['is_message'] != 'Y' ? "checked" : "" ?>>받지않음
                      </td>
                      <th>등록된 기부폰</th>
                      <td><?= number_format($data['tcnt']) ?> 개</td>
                    </tr>
                    <tr>
                      <th>웹비밀번호</th>
                      <td>
                        <input type="password" style="width:100px;" name="web_passwd" id="web_passwd">
                        <button class="btn btn-primary" style="margin-right: 5px;" onclick="void($('#web_passwd').val('111111'));return false;">초기화</button>
                      </td>
                      <th>주소</th>
                      <td><?= $data['mem_add1'] ?> <?= $data['mem_add2'] ?> (<?= $data['mem_post'] ?>)</td>
                      <th>추천인아이디</th>
                      <td><?= $data['recommend_id'] ?></td>

                    </tr>
                    <tr>
                      <th>생년월일</th>
                      <td><?= $data['mem_birth'] ?></td>
                      <th>직업</th>
                      <td><?= $data['zy'] ?></td>
                      <th>소속</th>
                      <td colspan="3"><?= $data['site'] ?></td>
                    </tr>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div><!-- /.col -->
        <? } else { ?>
          <div class="row">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">기부회원 상세정보</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                <table id="detail2" class="table table-bordered table-striped">
                  <colgroup>
                    <col width="16%">
                    <col width="16%">
                    <col width="16%">
                    <col width="16%">
                    <col width="16%">
                    <col width="20%">
                  </colgroup>
                  <tbody>
                    <tr>
                      <th>기부폰 번호</th>
                      <td><?= $donation_data['sendnum'] ?></td>
                      <th>앱설치일자</th>
                      <td><?= $donation_data['reg_date'] ?></td>
                      <th>가입자 아이디</th>
                      <td><?= $data['mem_id'] ?></td>
                    </tr>
                    <tr>
                      <th>최근발송건</th>
                      <td><?= $donation_data['reservation_time'] ?></td>
                      <th>기부비율</th>
                      <td>
                        <input type="text" style="width:100px;" name="donation_rate" value="<?= $donation_data['donation_rate'] ?>"> %
                        <div><span style="font-size:10px">※ 설치 기록이 없으면 비율이 변경되지 않음.</span></div>
                      </td>
                      <th>가입자 이름</th>
                      <td><?= $data['mem_name'] ?></td>
                    </tr>
                    <tr>
                      <th>최근발송일</th>
                      <td><?= $donation_data['reservation_time'] ?></td>
                      <th>기부문자</th>
                      <td>
                        <?= number_format($donation_data['daily_limit_cnt'] * ($donation_data['donation_rate'] * 0.01)) ?> / <?= number_format($donation_data['daily_limit_cnt']) ?>
                      </td>
                      <th>가입자 전화번호</th>
                      <td><?= $data['mem_phone'] ?></td>
                    </tr>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div><!-- /.col -->
        <? } ?>

        <div class="box-footer" style="text-align:center">
          <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
          <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='member_list_con.php';return false;"><i class="fa fa-list"></i> 회원목록</button>
        </div>

  </div><!-- /.row -->




  </section><!-- /.content -->
  </form>
</div><!-- /.content-wrapper -->

<!-- Footer -->
<? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>