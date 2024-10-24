<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*$sql = "select mem_code,site,site_iam from Gn_Member where site like 'iam_%' or site_iam like 'iam_%'";
    $result  = mysqli_query($self_con,$sql);
    while($row = mysqli_fetch_array($result)) {
        $mem_code = $row['mem_code'];
        $site = $row['site'];
        $site_iam = $row['site_iam'];
        $site = str_replace('iam_','',$site);
        $site_iam = str_replace('iam_','',$site_iam);
        $query = "update Gn_Member set site = '$site', site_iam = '$site_iam' where mem_code = '$mem_code'";
        mysqli_query($self_con,$query);
}*/
/*$sql = "select card_short_url, phone_display from Gn_Iam_Name_Card where 1=1";
$result  = mysqli_query($self_con,$sql);
while($row = mysqli_fetch_array($result)) {
    echo $row['card_short_url']."|" .$row['phone_display'].'<br>';
    $query = "update Gn_Iam_Contents set public_display = '$row['phone_display']'  where westory_card_url = '{$row['card_short_url']}'";
    mysqli_query($self_con,$query);
}*/
/*$sql = "select idx,card_short_url,phone_display from Gn_Iam_Name_Card where 1=1";
$result  = mysqli_query($self_con,$sql);
while($row = mysqli_fetch_array($result)) {
    $query = "update Gn_Iam_Contents set card_idx = $row['idx'],public_display = '$row['phone_display']'  where westory_card_url = '{$row['card_short_url']}'";
    echo $query.'<br>';
    mysqli_query($self_con,$query);
}*/
$sql = "select count(idx) from Gn_Iam_Contents where idx < 14635646";
$result  = mysqli_query($self_con,$sql);
$row = mysqli_fetch_array($result);
$count = $row[0];
$i = $_GET["index"];
$step = $_GET["count"];
while($count >= $i){
    $sql = "select idx,card_short_url,westory_card_url,req_data,up_data from Gn_Iam_Contents use index(idx) where idx < 14635646 order by idx limit $i,$step";
    $result  = mysqli_query($self_con,$sql);
    while($row = mysqli_fetch_array($result)) {
        $main_card_sql = "select idx from Gn_Iam_Name_Card where card_short_url = '{$row['westory_card_url']}'";
        $main_card_res = mysqli_query($self_con,$main_card_sql);
        $main_card_row = mysqli_fetch_array($main_card_res);
        $card_array = explode(",",$row['card_short_url']);
        foreach($card_array as $card_link ){
            $card_sql = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
            $card_res = mysqli_query($self_con,$card_sql);
            $card_row = mysqli_fetch_array($card_res);
            $query = "insert into Gn_Iam_Con_Card set cont_idx={$row['idx']},card_idx = {$card_row['idx']},main_card={$main_card_row['idx']}";
            echo $i."=>".$query.'<br>';
            mysqli_query($self_con,$query);
        }
    }
    $i += $step;
}
echo "success";
?>