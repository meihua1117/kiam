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
$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysqli_error($self_con));
mysql_select_db($mysql_db) or die(mysqli_error($self_con));
mysqli_query($self_con,"set names utf8");

$domain_url = "http://www.obmms.net";
 
 // �Է��ϱ� $query = "insert into Gn_Iam_Name_Card(mem_id,mem_name,) value(1,2,)";
 //�ҷ����� $query = "select * from Gn_Iam_Name_Card"; 
     // ������ ������ ����
       $query = "select * from Gn_Iam_Name_Card";
     //������ DB�� ������ �� ������� result ������ ������
      $result = mysqli_query($self_con,$query);
       
    while($data = mysqli_fetch_array($result)){

?>
   <tr>
      <td> <?=$data['idx']?>
      <td> <?=$data['mem_id']?>
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
