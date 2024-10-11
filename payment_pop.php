<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>온리원문자</title>
  <? include_once $SERVER['DOCUMENT_ROOT'] . "lib/db_config.php";
  extract($_GET);
  $sql = "select msg from tjd_pay_result_month where pay_idx='$index' order by idx desc";
  $result = mysqli_query($self_con,$sql);
  $row = mysqli_fetch_array($result);
  $msg = $row['msg'];
  ?>
  <style>
    .pop-layer .pop-container {
      padding: 20px 25px;
    }

    .pop-layer p.title {
      width: 100%;
      margin: 10px 0 20px;
      padding-top: 10px;
      border-bottom: 1px solid #DDD;
      text-align: left;
      font-size: 18px;
      color: #404040;
      font-weight: bold;
    }

    .pop-layer p.ctxt {
      color: #666;
      line-height: 25px;
    }

    .pop-layer .btn-r {
      width: 100%;
      margin: 10px 0 20px;
      padding-top: 10px;
      border-top: 1px solid #DDD;
      text-align: right;
    }

    a.btn-layerClose {
      display: inline-block;
      height: 25px;
      padding: 0 14px 0;
      border: 1px solid #304a8a;
      background-color: #3f5a9d;
      font-size: 13px;
      color: #fff;
      line-height: 25px;
    }

    a.btn-layerClose:hover {
      border: 1px solid #091940;
      background-color: #1f326a;
      color: #fff;
    }
  </style>
  <link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
  <link href='/css/main.css' rel='stylesheet' type='text/css' />
  <Script>
    function Memo_setCookie(name, value, expiredays) {
      var todayDate = new Date();
      //var d = new Date();
      var expireTime = (expiredays * 1 * 60 * 60 * 1000);
      todayDate.setTime(todayDate.getTime() + expireTime);
      document.cookie = name + '=' + escape(value) + '; path=/; expires=' + todayDate.toGMTString() + ';'
    }

    function Memo_closeWin() {
      Memo_setCookie('Memo1', 'done', 1); // 1=하룻동안 전체 공지창 열지 않음  -> 1=1시간
      self.close();
    }
  </Script>
</head>

<body style="background-color: #fff;  border: 5px solid #3571B5;">
  <div id="layer1" class="pop-layer">
    <div class="pop-container">
      <div class="pop-conts">
        <!--content //-->

        <p class="title">[정기결제 미출금으로 인한 이용 정지 안내]<br></p>
        <br />
        <? if ($_GET['type'] == "user") { ?>
          <p>
            회원님은 결제회원인데 미출금으로 계정에 로그인할 수 없습니다. <br>
            본 계정은 유료 기능으로 세팅되어 있어 다시 무료로 전환되지 않습니다.<br> 
            다시 이용하려면 결제한 이후에 사용하시거나 무료계정으로 새 계정을 만들어 사용하시면 됩니다. <br>
            SC은행 617-20-109431 온리원연구소 송조은<br />
          </p>
        <? } else { ?>
          <p>
            정기결제 계좌에서 약정한 날짜에 출금이 되지 않았습니다. <br />
            아래 계좌로 입금 후 카카오상담이나 고객센터로 문의 주시면 이용 가능하십니다.<br /><br />
            결제오류 : <?= $msg ?><br><br>
            SC은행 617-20-109431 온리원연구소 송조은<br />
          </p>
        <? } ?>
        <div class="btn-r">
          <span style='font-size:10pt;color:#000000;font-weight:bold;'>
            <input type='checkbox' name='Memo1' onclick='javascript:Memo_closeWin();'>&nbsp;
            오늘은 그만보기 &nbsp;&nbsp;&nbsp;
            <a href='javascript:self.close()' class="btn-layerClose">창닫기</a></span>
        </div>
        <!--// content-->
      </div>
    </div>
  </div>




</body>

</html>