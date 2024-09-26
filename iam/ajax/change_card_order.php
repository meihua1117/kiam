<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";?>
<?
    extract($_POST);
    $src_card_url = substr($_POST["src"],0,10);
    $dst_card_url = substr($_POST["dst"],0,10);
    $query = "select * from Gn_Iam_Name_Card where card_short_url ='$dst_card_url'";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);
    $date = $row['req_data'];
    $src_idx = $row['idx'];
    $src_mem_id = $row['mem_id'];
    $query = "select * from Gn_Iam_Name_Card where card_short_url ='$src_card_url'";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);
    $new_date = $row['req_data'];
    $dst_idx = $row['idx'];
    $dst_mem_id = $row['mem_id'];
    if($src_mem_id != $dst_mem_id)
        exit;
    $query="update Gn_Iam_Name_Card set req_data = '$new_date' where card_short_url = '$dst_card_url'";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));
    $query="update Gn_Iam_Name_Card set req_data = '$date' where card_short_url = '$src_card_url'";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));
    $query = "select count(*) from Gn_Iam_Service where profile_idx ='$src_idx'";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);
    if($row[0] == 1 )
    {
        $query="update Gn_Iam_Service set profile_idx = '$dst_idx' where profile_idx = '$src_idx'";
        mysqli_query($self_con, $query) or die(mysqli_error($self_con));
    }
    else{
        $query = "select count(*) from Gn_Iam_Service where profile_idx ='$dst_idx'";
        $res = mysqli_query($self_con, $query);
        $row = mysqli_fetch_array($res);
        if($row[0] == 1)
        {
            $query="update Gn_Iam_Service set profile_idx = '$src_idx' where profile_idx = '$dst_idx'";
            mysqli_query($self_con, $query) or die(mysqli_error($self_con));
        }
    }
    echo "카드순서가 변경되었습니다";
?>
