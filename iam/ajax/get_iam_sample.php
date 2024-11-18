
<?
include_once "../../lib/rlatjd_fun.php";
$limit = (intval($_GET['limit']) != 0 ) ? $_GET['limit'] : 4;
$offset = (intval($_GET['offset']) != 0 ) ? $_GET['offset'] : 0;

$contents_count_per_page = $limit;
$w_offset = $offset;
if($w_offset < 0 ){
    $w_offset = 0;
}

$logs = new Logs("iamlog.txt", true);
$logs->add_log("sample start");

if($_GET['sample_type'] == "best_sample")
{
    $key_sql = "select * from Gn_Search_Key where key_id = 'sample_search_keyword'";
    $key_res = mysqli_query($self_con,$key_sql);
    $key_row = mysqli_fetch_array($key_res);
    $key = $key_row['key_content'];
    $keys = explode(",",$key);
    $sql8 = "SELECT * FROM Gn_Iam_Name_Card WHERE group_id is NULL and sample_click='Y' AND (";
    if($_GET['search_key'])    
        $sql8 .= "card_name LIKE '%" . $_GET['search_key']. "%' OR card_company LIKE '%" . $_GET['search_key'] . "%' OR card_position LIKE '%" . $_GET['search_key'] . "%') AND (";      
    foreach($keys as $k){
        if($k != "")
            $sql8 .= " card_name LIKE '%" . $k . "%' OR card_company LIKE '%" . $k . "%' OR card_position LIKE '%" . $k . "%' OR card_title LIKE '%" . $k . "%' OR card_keyword LIKE '%" . $k . "%' OR ";
    }
    $sql8 = substr($sql8,0,strlen($sql8) - 3);
    $sql8 .=")";
    /*$sql9 = str_ireplace("SELECT * FROM", "SELECT count(*) FROM", $sql8);
    $res9=mysqli_query($self_con,$sql9);
    $row9 = mysqli_fetch_array($res9);
    $cont_count = $row9[0];
*/
    $sql8 .=" order by sample_order desc ,req_data limit $contents_count_per_page , ".$w_offset;
}
else if($_GET['sample_type'] == "sample")
{
    $sql8 = "SELECT * FROM Gn_Iam_Name_Card WHERE group_id is NULL and sample_click='Y'";
    if($_GET['search_key'])    
        $sql8 .= " AND (card_name LIKE '%" . $_GET['search_key']."%' OR card_company LIKE '%" . $_GET['search_key'] . "%' OR card_position LIKE '%" . $_GET['search_key']."%')"; 
    /*$sql9 = str_ireplace("SELECT * FROM", "SELECT count(*) FROM", $sql8);
    $res9=mymysqli_fetch_ql9);
    $row9 = mysqli_fetch_array($res9);
    $cont_count = $row9[0];
*/
    $sql8 .=" order by sample_order desc ,req_data limit $contents_count_per_page , ".$w_offset;
}
else if($_GET['sample_type'] == "recent_sample")
{
    $recent_date =  date("Y-m-d", strtotime("-7 days"));
    $sql8 = "SELECT * FROM Gn_Iam_Name_Card WHERE group_id is NULL and sample_click='Y' AND up_data >= '".$recent_date."'";
    if($_GET['search_key'])    
        $sql8 .= " AND (card_name LIKE '%" . $_GET['search_key'] . "%' OR card_company LIKE '%" . $_GET['search_key'] .
        "%' OR card_position LIKE '%" . $_GET['search_key'] . "%')";       
    /*$sql9 mysqli_fetch_ce("SELECT * FROM", "SELECT count(*) FROM", $sql8);
    $res9=mysqli_query($self_con,$sql9);
    $row9 = mysqli_fetch_array($res9);
    $cont_count = $row9[0];
*/
    $sql8 .=" order by sample_order desc ,req_data limit $contents_count_per_page , ".$w_offset;
}

$redisCache = new RedisCache();
//$redisCache->set_debug(true);
$sample_list = $redisCache -> get_query_to_array($sql8);
//$logs->add_log( $redisCache ->get_debug_string(), false);
$body = '';
echo "sample=".count($sample_list);
for($i=0 ; $i < count($sample_list); $i++)
{
    $contents_row = $sample_list[$i];
    $sample_sql = "select mem_code from Gn_Member where mem_id='{$contents_row['mem_id']}'";
    $sample_res = mysqli_query($self_con,$sample_sql);
    $sample_row = mysqli_fetch_array($sample_res);
    $desc = $contents_row['card_name'] . '/'. $contents_row['card_company']. '/'. $contents_row['card_position'];
    //$desc = mb_substr($desc,0,25,"UTF-8");
    $body .= '<div class="col-sm-6 col-xs-6 sample_card">';
    $body .= '<div class="square" onclick="window.open(\'/?' . $contents_row['card_short_url'].$sample_row["mem_code"]. '\')">';
    $body .= '<div class="content" style="background-image:url(\'' .  $contents_row['main_img1'] . '\'); background-size: cover;background-position: center;border-top-right-radius:10px;border-top-left-radius:10px;">';
    $body .= '</div>';
    $body .= '<div class="content2">';
    $body .= '<div class="card-data"><p class="card-text sample-text">'. $desc.'</p>';//'<strong style="font-size: x-large"> +</strong></p>';
    $body .= '</div>';
    $body .= '</div>';
    $body .= '</div>';
    $body .= '</div>';       
}

$logs->add_log("end sample");
$logs->write_to_file();

echo $body;
?>