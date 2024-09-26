<?
$path = "./";
include_once "_head_open.php";
if (!$_SESSION['one_member_id']) {
?>
  <script language="javascript">
    location.replace('/ma.php');
  </script>
<?
  exit;
}
$sql = "select * from Gn_Member  where mem_id='" . $_SESSION['one_member_id'] . "'";
$sresul_num = mysqli_query($self_con, $sql);
$data = mysqli_fetch_array($sresul_num);


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
    })

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

<!DOCTYPE html>
<html lang="ko">

<head>


  <style>
    .pop_right {
      position: relative;
      right: 2px;
      display: inline;
      margin-bottom: 6px;
      width: 5px;
    }

    table {
      width: 100%;
      ;
      border: 3px solid #333333;
    }

    th {
      border: 1px solid black;
    }

    td {
      padding: 5px;
      border: 1px solid #333333;
    }

    .a {
      border-collapse: separate;
    }

    .b {
      border-collapse: collapse;
    }
  </style>
</head>

<body>
  <div class="bnt main-buttons" style="background-color: #FFFFFF2; width:60%;padding-top :10px;padding-left 10px;padding-right :10px;padding-bottom :10px;">
    <h3>온리원셀링 자동솔루션에 대해서 코칭을 받고 싶으신가요? <br>배우는 과정에 대해 자세히 보시려면 아래에서 확인하세요.</h3>
    <table class="b">
      <tbody>
        <tr>
          <th>유효<br>기간</th>
          <th>코칭<br>시간</th>
          <th>코칭<br>비용</th>
          <th>신청<br>하기</th>
        </tr>
        <tr>
          <td>3일</td>
          <td>2시간</td>
          <td>5만원</td>
          <td><input type="radio" name="coaching_type" value="1"></td>
        </tr>
        <tr>
          <td>7일</td>
          <td>4시간</td>
          <td>9만원</td>
          <td><input type="radio" name="coaching_type" value="2"></td>
        </tr>
        <tr>
          <td>11일</td>
          <td>6시간</td>
          <td>13만원</td>
          <td><input type="radio" name="coaching_type" value="3"></td>
        </tr>
        <tr>
          <td>15일</td>
          <td>8시간</td>
          <td>17만원</td>
          <td><input type="radio" name="coaching_type" value="4"></td>
        </tr>
        <tr>
          <td>19일</td>
          <td>10시간</td>
          <td>21만원</td>
          <td><input type="radio" name="coaching_type" value="5"></td>
        </tr>
        <tr>
          <td>25일</td>
          <td>12시간</td>
          <td>25만원</td>
          <td><input type="radio" name="coaching_type" value="6"></td>
        </tr>
        <tr>
          <td>30일</td>
          <td>14시간</td>
          <td>28만원</td>
          <td><input type="radio" name="coaching_type" value="7"></td>
        </tr>
        <tr>
          <td>60일</td>
          <td>30시간</td>
          <td>50만원</td>
          <td><input type="radio" name="coaching_type" value="8"></td>
        </tr>
      </tbody>
    </table>
    <div class="p1" style="text-align:center;margin-top:20px;">
      <input type="button" value="코칭과정보기" class="button">
      <input type="button" value="코칭신청하기" class="button" id="applyCoaching">
      <input type="button" value="다음에보기" class="button">
    </div>
    <script type="text/javascript">
      $("#applyCoaching").click(function() {

        var coaching_type = $('input[name="coaching_type"]:checked').val();
        var cont_term = 0;
        var cont_time = 0;
        var coaching_price = 0;
        switch (coaching_type) {
          case "1":
            cont_term = 3;
            cont_time = 2;
            coaching_price = 50000;
            break;
          case "2":
            cont_term = 7;
            cont_time = 4;
            coaching_price = 90000;
            break;
          case "3":
            cont_term = 11;
            cont_time = 6;
            coaching_price = 130000;
            break;
          case "4":
            cont_term = 15;
            cont_time = 8;
            coaching_price = 170000;
            break;
          case "5":
            cont_term = 19;
            cont_time = 10;
            coaching_price = 210000;
            break;
          case "6":
            cont_term = 25;
            cont_time = 12;
            coaching_price = 250000;
            break;
          case "7":
            cont_term = 30;
            cont_time = 14;
            coaching_price = 280000;
            break;
          case "8":
            cont_term = 60;
            cont_time = 30;
            coaching_price = 500000;
            break;
          default:
            alert('코칭형태 정확히 선택하세요.');
            return;
            break;
        }

        $.ajax({
          type: "POST",
          url: "/mypage.proc.php",
          data: {
            mode: "req_coaching",
            cont_time: cont_time,
            cont_term: cont_term,
            coaching_price: coaching_price
          },
          dataType: 'html',
          success: function(data) {
            alert("교육과정 신청되었습니다.담당자와 상담후에 진행하세요. 감사합니다.");

          },
          error: function() {
            alert('정상적으로 신청되지 않았습니다.');
            console.log("Error is dected");
          }
        });

      });
    </script>
</body>

</html>