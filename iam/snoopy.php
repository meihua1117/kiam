<?
include_once "./snoopy/Snoopy.class.php";

$snoopy = new Snoopy;
$snoopy->agent = $_SERVER['HTTP_USER_AGENT'];
// $snoopy->referer = "https://book.naver.com/bookdb/book_detail.nhn?bid=6260986";
$url = "https://book.naver.com/bookdb/book_detail.nhn?bid=6260986";
$snoopy->fetch($url);
// print $snoopy->results;
$res = $snoopy->results;
// $res = iconv("UTF-8","euc-kr",$res); //한글출력 때문에 긁어온 소스를 UTF-8로 변환합니다.
// print $res;
// preg_match('/<title>(.*?)<\/title>/', $res, $html);
// preg_match('/<title>(.*?)<\/title>/is', $res, $html);
echo $res;
?>


<script>

// // 클립보드로 복사하는 기능을 생성
// function copyToClipboard(elementId) {
//   // 글을 쓸 수 있는 란을 만든다.
//   var aux = document.createElement("input");
//   // 지정된 요소의 값을 할당 한다.
//   aux.setAttribute("value", document.getElementById(elementId).innerHTML);
//   // bdy에 추가한다.
//   document.body.appendChild(aux);
//   // 지정된 내용을 강조한다.
//   aux.select();
//   // 텍스트를 카피 하는 변수를 생성
//   document.execCommand("copy");
//   // body 로 부터 다시 반환 한다.
//   document.body.removeChild(aux);
// }
// </script>


<!--복사할 텍스트 만들기-->
<!-- <p id="text1">텍스트 복사에 성공 하였습니다. </p> -->
  <!--// 버튼 만들기-->
<!-- <button onclick="copyToClipboard('text1')">텍스트 복사하기</button> -->
