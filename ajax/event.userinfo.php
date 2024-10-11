<?
header("Content-Type: text/html; charset=UTF-8");
include_once "../lib/rlatjd_fun.php";

if($_SESSION[one_member_id]){


    $sql="SELECT *  FROM Gn_Member WHERE mem_id ='$_SESSION[one_member_id]'";

	$result=mysqli_query($self_con,$sql);
	$row = mysqli_fetch_array($result);

	if($row){

    $card_name = $row[mem_name];
	$card_company = $row[zy];
	$card_phone = $row[mem_phone];
	$card_email = $row[mem_email];
    $card_addr = $row[mem_add1];
    $card_birth = $row[mem_birth];

    $card_phone = str_replace("-", "", $card_phone);

	// $profile_logo = $row[profile_logo];

	$card_phone_ = explode('-',$card_phone);
            echo "{";
            echo "\"result\" : \"success\"";
            echo ",\"card_name\" : \"$card_name\"";
            echo ",\"card_company\" : \"$card_company\"";
            echo ",\"card_phone\" : \"$card_phone\"";
            echo ",\"card_email\" : \"$card_email\"";
            echo ",\"card_addr\" : \"$card_addr\"";
            echo ",\"card_birth\" : \"$card_birth\"";
            echo ",\"card_company\" : \"$card_company\"";
            echo "}";
            exit;

    }


}
mysqli_close($self_con);

echo "{";
echo "\"result\" : \"fail\"";
echo "}";


?>