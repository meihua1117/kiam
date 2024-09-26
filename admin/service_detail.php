<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today = date("Y-m-d");
if ($idx) {
  // 가입 회원 상세 정보
  $query = "select * from Gn_Service where idx='$idx'";
  $res = mysqli_query($self_con, $query);
  $data = mysqli_fetch_array($res);
}
?>
<style>
  .box-body th {
    background: #ddd;
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

  .switch_shoping_status {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 28px;
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
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;

  }
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script>
<link rel='stylesheet' id='jquery-ui-css' href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2' type='text/css' media='all' />

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
        분양관리
        <small>분양업체를 관리합니다.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">분양관리</li>
      </ol>
    </section>
    <form method="post" id="dForm" name="dForm" action="/admin/ajax/service_save.php" enctype="multipart/form-data">
      <input type="hidden" name="idx" value="<?= $data['idx'] ?>" />
      <input type="hidden" name="mode" value="<?= $data['idx'] ? "updat" : "inser" ?>" />
      <!-- Main content -->
      <section class="content">
        <div class="row" id="toolbox">
          <div class="col-xs-12" style="padding-bottom:20px">
          </div>
        </div>

        <div class="row">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">분양상세정보</h3>
            </div><!-- /.box-header -->
            <div class="box-body" style="overflow: auto !important">
              <table id="detail1" class="table table-bordered table-striped">
                <colgroup>
                  <col width="15%">
                  <col width="35%">
                  <col width="15%">
                  <col width="35%">
                </colgroup>
                <tbody>
                  <tr>
                    <th>업체이름</th>
                    <td>
                      <input type="text" style="width:250px;" name="company_name" id="company_name" value="<?= $data['company_name'] ?>">
                    </td>
                    <th>서비스명</th>
                    <td>
                      <input type="text" style="width:250px;" name="service_name" id="service_name" value="<?= $data['service_name'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>업체대표</th>
                    <td>
                      <input type="text" style="width:250px;" name="ceo_name" id="ceo_name" value="<?= $data['ceo_name'] ?>">
                    </td>
                    <th>사이트명</th>
                    <td>
                      <input type="text" style="width:250px;" name="site_name" id="site_name" value="<?= $data['site_name'] ?>">
                    </td>

                  </tr>
                  <tr>
                    <th>담당자명</th>
                    <td>
                      <input type="text" style="width:250px;" name="manage_name" id="manage_name" value="<?= $data['manage_name'] ?>">
                    </td>
                    <th>메인도메인</th>
                    <td>
                      <input type="text" style="width:250px;" name="domain" id="domain" value="<?= $data['domain'] ?>">

                    </td>

                  </tr>
                  <tr>
                    <th>전화번호</th>
                    <td>
                      <input type="text" style="width:250px;" name="manage_cell" id="manage_cell" value="<?= $data['manage_cell'] ?>">
                    </td>
                    <th>서브도메인</th>
                    <td>
                      <input type="text" style="width:250px;" name="sub_domain" id="sub_domain" value="<?= $data['sub_domain'] ?>">
                    </td>

                  </tr>
                  <tr>
                    <th>업체주소</th>
                    <td>
                      <input type="text" style="width:250px;" name="address" id="address" value="<?= $data['address'] ?>">
                    </td>
                    <th>통신판매번호</th>
                    <td>
                      <input type="text" style="width:250px;" name="communications_vendors" id="communications_vendors" value="<?= $data['communications_vendors'] ?>">

                    </td>

                  </tr>
                  <tr>
                    <th>팩스번호</th>
                    <td>
                      <input type="text" style="width:250px;" name="fax" id="fax" value="<?= $data['fax'] ?>">

                    </td>

                    <th>개인정보책임자</th>
                    <td>
                      <input type="text" style="width:250px;" name="privacy" id="privacy" value="<?= $data['privacy'] ?>">
                    </td>
                  </tr>

                  <tr>
                    <th>분양가격</th>
                    <td>
                      <input type="text" style="width:250px;" name="branch_type" id="branch_type" value="<?= $data['branch_type'] ?>">
                    </td>
                    <th rowspan="2">로고</th>
                    <td rowspan="2">
                      <input type="file" name="logo">
                      <?php if ($data['logo'] != "") { ?>
                        <img src="<?= $data['logo'] ?>" style="width:120px">
                      <?php } ?>
                    </td>
                  </tr>
                  <tr>
                    <th>발송폰수</th>
                    <td>
                      <input type="text" style="width:250px;" name="phone_cnt" id="phone_cnt" value="<?= $data['phone_cnt'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>분양비율</th>
                    <td>
                      <input type="text" style="width:250px;" name="branch_rate" id="branch_rate" value="<?= $data['branch_rate'] ?>">
                    </td>
                    <th rowspan="2">메인이미지</th>
                    <td rowspan="2">
                      <input type="radio" value="Y" name="main_default_yn" id="main_default_yn" <?php echo $data['main_default_yn'] == "Y" ? "checked" : "" ?>> 기본화면 이용<br>
                      <input type="radio" value="L" name="main_default_yn" id="main_default_yn" <?php echo $data['main_default_yn'] == "L" ? "checked" : "" ?>> 링크 이용
                      <input type="text" style="width:250px;" name="main_url" id="main_url" value="<?= $data['main_url'] ?>"><br>
                      <input type="radio" value="I" name="main_default_yn" id="main_default_yn" <?php echo $data['main_default_yn'] == "I" ? "checked" : "" ?>> 이미지 이용
                      <input type="file" name="main_image"><br>
                      <?php if ($data['main_image'] != "") { ?>
                        <img src="<?= $data['main_image'] ?>" style="width:120px"><br>
                      <?php } ?>
                    </td>
                  </tr>
                  <tr>
                    <th>월이용료</th>
                    <td>
                      <input type="text" style="width:250px;" name="price" id="price" value="<?= $data['price'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>사용여부</th>
                    <td>
                      <select name="status">

                        <option value="Y" <?= $data['status'] == "Y" ? "selected" : "" ?>>사용</option>
                        <option value="N" <?= $data['status'] == "N" ? "selected" : "" ?>>미사용</option>
                      </select>
                    </td>


                    <th>하단이미지</th>
                    <td>
                      <input type="file" name="footer_image">
                      <?php if ($data['footer_image'] != "") { ?>
                        <img src="<?= $data['footer_image'] ?>" style="width:120px">
                      <?php } ?>
                    </td>

                  </tr>

                  <tr>
                    <th>계약기간</th>
                    <td>
                      <input type="date" name="contract_start_date" id="contract_start_date" value="<?= $data['contract_start_date'] ?>" class="date" style="width:130px">
                      ~
                      <input type="date" name="contract_end_date" id="contract_end_date" value="<?= $data['contract_end_date'] ?>" class="date" style="width:130px">
                    </td>
                    <th>아이디</th>
                    <td>
                      <input type="text" style="width:250px;" name="mem_id" id="mem_id" value="<?= $data['mem_id'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>회원수</th>
                    <td>
                      <input type="text" style="width:250px;" name="member_cnt" id="member_cnt" value="<?= $data['member_cnt'] ?>">
                    </td>
                    <th>이름</th>
                    <td>
                      <input type="text" style="width:250px;" name="mem_name" id="mem_name" value="<?= $data['mem_name'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>굿마켓 명칭</th>
                    <td>
                      <input type="text" style="width:550px;" name="gwc_name" id="gwc_name" value="<?= $data['gwc_name'] ?>" placeholder="여기에 굿마켓 대신 이름을 넣으세요.">
                    </td>
                    <th>쇼핑몰 관리페이지</th>
                    <td>
                      <label class="switch_shoping_status" style="margin:0 25px;">
                        <input type="checkbox" name="status" id="stauts_shoping_<?= $data['idx']; ?>" value="<?= $data['idx']; ?>" <?= $data['shoping_manage'] == 1 ? "checked" : "" ?> style="display:none">
                        <span class="slider round" name="status_round" id="stauts_round_<?= $data['idx']; ?>"></span>
                      </label>
                    </td>
                    <!-- <th>소속상품만 보여주기</th>
                        <td>
                            <input type="checkbox" style="width:100px;" name="gwc_site_cons" id="gwc_site_cons" <?= $data['gwc_site_cons'] ? "checked" : "" ?> >
                        </td>   -->
                  </tr>
                  <tr>
                    <th>네이버 SITE-VERIFICATION</th>
                    <td colspan="3">
                      <input type="text" style="width:550px;" name="naver-site-verification" id="naver-site-verification" value="<?= $data['naver-site-verification'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>keywords</th>
                    <td colspan="3">
                      <input type="text" style="width:850px;" name="keywords" id="keywords" value="<?= $data['keywords'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>KaKao Ink</th>
                    <td colspan="3">
                      <input type="text" style="width:850px;" name="kakao" id="kakao" value="<?= $data['kakao'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>상담코칭 신청</th>
                    <td colspan="3">
                      <input type="text" style="width:850px;" name="consultation_request" id="consultation_request" value="<?= $data['consultation_request'] ?>">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        </div><!-- /.col -->
        <div class="box-footer" style="text-align:center">
          <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
          <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='service_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
        </div>
  </div><!-- /.row -->
  </section><!-- /.content -->
  </form>
  <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>
</div><!-- /content-wrapper -->

<script language="javascript">
  function form_save() {
    if ($('#service_name').val() == "") {
      alert('서비스명 입력해주세요.');
      return;
    }
    if ($('#domain').val() == "") {
      alert('도메인을 입력해주세요.');
      return;
    }
    $('#dForm').submit();
  }
  $(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if (navH != contHeaderH)
      contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top", contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 232;
    if (height < 375)
      height = 375;
    $(".box-body").css("height", height);
  });
  $('.switch_shoping_status').on("change", function() {
        var id = $(this).find("input[type=checkbox]").val();
        var status = $(this).find("input[type=checkbox]").is(":checked")==true?1:0;
        $.ajax({
            type:"POST",
            url:"/admin/ajax/service_save.php",
            data:{
                mode:'shoping_updat',
                index:id,
                status:status
            },
            success:function(data){
                //location.reload();
            }
        })
    });
</script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>