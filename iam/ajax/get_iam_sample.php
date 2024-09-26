
<?
include_once "../../lib/rlatjd_fun.php";
$limit = (intval($_GET['limit']) != 0 ) ? $_GET['limit'] : 4;
$offset = (intval($_GET['offset']) != 0 ) ? $_GET['offset'] : 0;

$contents_count_per_page = $limit;
$w_offset = $offset;
if($w_offset < 0 ){
    $w_offset = 0;
}
if($_GET['sort'] == 11){//게시일짜
    $order_sql = " order by idx desc";
}elseif($_GET['sort'] == 12){//조회수
    $order_sql = "order by contents_temp desc";
}elseif($_GET['sort'] == 13){//좋아요
    $order_sql = " order by contents_like desc";
}

if($_GET['sort'] == 1){//1시간전
    $search_sql = " and req_data >= ADDTIME(now(), '-1:0:0') ";
}else if($_GET['sort'] == 2){//오늘
    $search_sql = " and req_data >= DATE(now()) ";
}else if($_GET['sort'] == 3){//이번주
    $search_sql = " and WEEKOFYEAR(req_data) = WEEKOFYEAR(now()) and YEAR(req_data) = YEAR(now()) ";
}else if($_GET['sort'] == 4){//이번달
    $search_sql = " and MONTH(req_data) = MONTH(now()) and YEAR(req_data) = YEAR(now()) ";
}else if($_GET['sort'] == 5){//올해
    $search_sql = " and YEAR(req_data) = YEAR(now()) ";
}
$logs = new Logs("../../iamlog.txt", false);
$logs->add_log("sample start");

if($_GET['sample_type'] == "best_sample"){
    $key_sql = "select * from Gn_Search_Key where key_id = 'sample_search_keyword'";
    $key_res = mysqli_query($self_con, $key_sql);
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
    $sql8 .= $search_sql;
    /*$sql9 = str_ireplace("SELECT * FROM", "SELECT count(*) FROM", $sql8);
    $res9=mysqli_query($self_con, $sql9);
    $row9 = mysqli_fetch_array($res9);
    $cont_count = $row9[0];*/
    $sql8 .=" order by sample_order desc ,req_data limit $contents_count_per_page offset ".$w_offset;
}else if($_GET['sample_type'] == "sample"){
    $sql8 = "SELECT * FROM Gn_Iam_Name_Card WHERE group_id is NULL and sample_click='Y'";
    if($_GET['search_key'])    
        $sql8 .= " AND (card_name LIKE '%" . $_GET['search_key']."%' OR card_company LIKE '%" . $_GET['search_key'] . "%' OR card_position LIKE '%" . $_GET['search_key']."%')"; 
    $sample_key = $_GET['sample_key'];
    if($sample_key)
        $sql8 .= " AND (card_name LIKE '%" . $sample_key."%' OR card_company LIKE '%" . $sample_key . "%' OR card_position LIKE '%" . $sample_key."%')"; 
    $sql8 .= $search_sql;
    /*$sql9 = str_ireplace("SELECT * FROM", "SELECT count(*) FROM", $sql8);
    $res9=mysqli_query($self_con, $sql9);
    $row9 = mysqli_fetch_array($res9);
    $cont_count = $row9[0];*/
    $sql8 .=" order by sample_order desc ,req_data limit $contents_count_per_page offset ".$w_offset;
}else if($_GET['sample_type'] == "recent_sample"){
    $recent_date =  date("Y-m-d", strtotime("-7 days"));
    $sql8 = "SELECT * FROM Gn_Iam_Name_Card WHERE group_id is NULL and sample_click='Y' AND up_data >= '".$recent_date."'";
    if($_GET['search_key'])    
        $sql8 .= " AND (card_name LIKE '%" . $_GET['search_key'] . "%' OR card_company LIKE '%" . $_GET['search_key'] ."%' OR card_position LIKE '%" . $_GET['search_key'] . "%')";       
    /*$sql9 = str_ireplace("SELECT * FROM", "SELECT count(*) FROM", $sql8);
    $res9=mysqli_query($self_con, $sql9);
    $row9 = mysqli_fetch_array($res9);
    $cont_count = $row9[0];*/
    $sql8 .=" order by sample_order desc ,req_data limit $contents_count_per_page offset ".$w_offset;
}
$cont_array = array();
if(!$global_is_local){
    $redisCache = new RedisCache();
    $sample_list = $redisCache -> get_query_to_array($sql8);
    for($i=0 ; $i < count($sample_list); $i++){
        array_push($cont_array,$sample_list[$i]);
    }
}else{
    $result8 = mysqli_query($self_con, $sql8) or die(mysqli_error($self_con));
    while( $contents_row = mysqli_fetch_array($result8)){
        array_push($cont_array,$contents_row);
    }
}
$body = '';
foreach($cont_array as $contents_row){
    $sample_sql = "select mem_code from Gn_Member where mem_id='{$contents_row['mem_id']}'";
    $sample_res = mysqli_query($self_con, $sample_sql);
    $sample_row = mysqli_fetch_array($sample_res);
    $desc = $contents_row['card_name'] . '/'. $contents_row['card_company']. '/'. $contents_row['card_position'];
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