<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$keyword_list = array();
$type = $_POST['type'];
if($type == 'special') {
    $value = $_POST['value'];
    $query = "SELECT card_keyword FROM Gn_Iam_Name_Card WHERE group_id is NULL and card_keyword like '%{$value}%'";
}else {
    $query = "SELECT card_keyword FROM Gn_Iam_Name_Card WHERE group_id is NULL";
}
$res = mysqli_query($self_con, $query);
while($row = mysqli_fetch_array($res)) {
    $card_keywords = $row['card_keyword'];
    $card_keywords = str_replace(" ", ",", $card_keywords);
    $card_keywords = str_replace("#", ",", $card_keywords);
    $card_keywords = str_replace("\t", ",", $card_keywords);
    $card_keywords = str_replace(".", ",", $card_keywords);
    $card_keywords = str_replace("&", ",", $card_keywords);
    $keywords = explode(",", $card_keywords);
    foreach($keywords as $keyword) {
        if(!in_array($keyword, $keyword_list)) {
            if(trim($keyword) != '') {
                if($type == 'special') {
                    if(strpos($keyword, $value) !== false) {
                        $keyword_list[] = $keyword;
                    }
                }else {
                    $keyword_list[] = $keyword;
                }
            }
        }
    }
}
sort($keyword_list);
echo json_encode(array('status'=>'1', 'keywords'=>$keyword_list));
?>