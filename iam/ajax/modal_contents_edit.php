
<?
include_once "../../lib/rlatjd_fun.php";

$cur_win = $_GET['cur_win'];
$short_url = $_GET['short_url'];
$group_card_url = $_GET['group_card_url'];

$body = "";

if($cur_win=="my_info" && $short_url){
    //$cont_order_sql = "select idx,contents_img from Gn_Iam_Contents WHERE card_short_url like '%$short_url%' ORDER BY contents_order desc";
    $cont_order_sql = "select idx,contents_img from Gn_Iam_Contents WHERE westory_card_url = '$short_url' ORDER BY contents_order desc";
}else if($cur_win == "group-con" && $group_card_url){
    //$cont_order_sql = "select idx,contents_img from Gn_Iam_Contents WHERE card_short_url like '%$group_card_url%' ORDER BY contents_order desc";    
    $cont_order_sql = "select idx,contents_img from Gn_Iam_Contents WHERE westory_card_url = '$group_card_url' ORDER BY contents_order desc";
}
if($cont_order_sql){
    $cont_order_res = mysql_query($cont_order_sql);
    while($cont_order_row = mysql_fetch_array($cont_order_res)){
        $cont_images = explode(",",$cont_order_row['contents_img']);
        $cont_img = $cont_images[0];

        $body .= '<div style = "width:80px;height:60px;margin-top:10px;margin-left:10px;padding:1px;position:relative" data-num="'. $cont_order_row['idx'] .'" ondrop="orderDrop(event)" ondragover="allowOrderDrop(event)">';
        $body .= '  <img style="width:80px;height:60px" id="order' .$cont_order_row['idx'] .'" src="'. cross_image($cont_img).'" ontouchstart="orderTouchStart(event)" ontouchmove="orderTouchMove(event)" ontouchend = "orderTouchEnd(event)" draggable="true" ondragstart="orderDrag(event)">';
        $body .= '  <img src="/iam/img/menu/icon_main_close.png" onclick="del_cont_from_order(\''. $cont_order_row['idx']. '\')" style="position: absolute;left:65px;top:-10px;">';
          
        if(count($cont_images) > 1)
            $body .= '<button  style="position: absolute;right:0px;bottom:0px;font-size: 15px;opacity: 60%;background: black;color: white;">+'.(count($cont_images)-1).'</button>';
        $body .= '</div>';

    }
}
echo $body;
?>