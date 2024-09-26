<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<table width=600 border=1>
  <tr>
     <td> idx 
     <td> mem_id
     <td> card_name
     <td> card_phone
  </tr>
  
<?
$mysql_host = 'localhost';
$mysql_user = 'ilearning3';
$mysql_password = 'happy2022days';
$mysql_db = 'ilearning3';
$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysql_error());
mysql_select_db($mysql_db) or die(mysql_error());
mysql_query("set names utf8");

$domain_url = "http://www.obmms.net";
 
 // 입력하기 $query = "insert into Gn_Iam_Name_Card(mem_id,mem_name,) value(1,2,)";
 //불러오기 $query = "select * from Gn_Iam_Name_Card"; 
     // 쿼리를 변수에 대입
       $query = "select * from Gn_Iam_Name_Card";
     //쿼리를 DB에 전송한 후 결과값을 result 변수로 가져옴
      $result = mysql_query($query);
       
    while($data = mysql_fetch_array($result)){

?>
   <tr>
      <td> <?=$data[idx]?>
      <td> <?=$data[mem_id]?>
      <td> <?=$data[card_name]?>
      <td> <?=$data[card_phone]?>
   </tr>
<?
}
?>
</table>
<?
mysql_close
?>
