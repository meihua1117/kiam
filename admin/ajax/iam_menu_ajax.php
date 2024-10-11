<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$date = date("Y-m-d H:i:s");
$file_name = $_FILES['menu_icon']['name'];
$temp_name = $_FILES['menu_icon']['tmp_name'];
$file_size = $_FILES['menu_icon']['size'];
if($page_type == "")
    $page_type = "all";
if($mode == "save"){
    if($idx == "") {    
        if($file_name) {
            $info = explode(".",$file_name);
            $ext = $info[count($info)-1];
            $filename = time().".".$ext;
            $img_path = "http://www.kiam.kr".gcUpload($file_name, $temp_name, $file_size, "ad", $filename);
        }
        $query="insert into Gn_Iam_Menu set `title`     ='$menu_title',
                                        `menu_desc`     ='$menu_desc', 
                                        `move_url`      ='$menu_move_url', 
                                        `img_url`       ='$img_path', 
                                        `menu_type`     ='$menu_type', 
                                        `page_type`     ='$page_type', 
                                        `site_iam`      ='$site_iam',
                                        `display_order` ='$menu_display_order',
                                        `reg_date`      ='$date'";
        mysqli_query($self_con,$query);
        echo json_encode(array("result"=>"success","mode"=>$mode,"idx"=>$idx,"msg"=>"성공적으로 추가되었습니다."));	
    }else{
        if($file_name) {
            $info = explode(".",$file_name);
            $ext = $info[count($info)-1];
            $filename = time().".".$ext;
            $img_path = "http://www.kiam.kr".gcUpload($file_name, $temp_name, $file_size, "ad", $filename);
        }
        if($img_path)
            $addQuery = " `img_url`        ='$img_path', ";
    
        $query="insert into Gn_Iam_Menu set `title` ='$menu_title', 
                                            `menu_desc`  ='$menu_desc',
                                            `move_url` ='$menu_move_url', 
                                            `img_url` ='$img_path', 
                                            `menu_type` ='$menu_type',
                                            `page_type`     ='$page_type',  
                                            `site_iam` = '$site_iam',
                                            `display_order` ='$menu_display_order',
                                            `reg_date` = '$date'";
        mysqli_query($self_con,$query) or die(mysqli_error($self_con));	
        echo json_encode(array("result"=>"success","mode"=>$mode,"idx"=>$idx,"msg"=>"성공적으로 추가되었습니다."));
    }
}else if($mode == "updat"){
    if($file_name) {
        $info = explode(".",$file_name);
        $ext = $info[count($info)-1];
        $filename = time().".".$ext;
        $img_path = "http://www.kiam.kr".gcUpload($file_name, $temp_name, $file_size, "ad", $filename);
    }
    if($img_path)
        $addQuery = " `img_url`        ='$img_path', ";
    $query="update  Gn_Iam_Menu set `title`  ='$menu_title', 
                                    `menu_desc`  ='$menu_desc',
                                    `move_url` ='$menu_move_url', 
                                    `menu_type` ='$menu_type', 
                                    `page_type`     ='$page_type', 
                                    `display_order` ='$menu_display_order', 
                                    $addQuery
                                    `site_iam` ='$site_iam',
                                    `up_date` = '$date'
                                    WHERE idx='$idx'";
    mysqli_query($self_con,$query);
    echo json_encode(array("result"=>"success","mode"=>$mode,"idx"=>$idx,"msg"=>"성공적으로 저장되었습니다."));	
}else if($mode == "del"){
    $query="delete from Gn_Iam_Menu WHERE idx='$idx'";
    mysqli_query($self_con,$query);
    echo json_encode(array("result"=>"success","mode"=>$mode,"idx"=>$idx,"msg"=>"성공적으로 삭제되었습니다."));	
}else if($mode == "set_show"){
    
    $sql_update = "update Gn_Iam_Service set admin_iam_menu='{$set_type}' where sub_domain like '%".$domain."%'";
    $res = mysqli_query($self_con,$sql_update);
    $domains = explode(".",$domain);
    $site = $domains[0];
    if($site == "www")
        $site = "kiam";
    if($set_type == 1){
        $sql = "select count(idx) from Gn_Iam_Menu where site_iam='{$site}'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        if($row[0] == 0){
            $menu_sql = "select * from Gn_Iam_Menu where site_iam='kiam'";
            $menu_res = mysqli_query($self_con,$menu_sql);
            while($menu_row = mysqli_fetch_assoc($menu_res)){
                $sql = "insert into Gn_Iam_Menu set ";
                foreach($menu_row as $key=>$v){
                    if($key != "reg_date" && $key != "idx"){
                        $v=$key=="site_iam"?$site:$v;
                        $sql.=" $key='".addslashes($v)."',";
                    }
                }
                $sql .= "reg_date="."'$date'";
                mysqli_query($self_con,$sql);
            }
        }
    }
}else if($mode == "status"){
    $sql_update = "update Gn_Iam_Menu set use_yn='{$status}' where idx='{$id}'";
    $res = mysqli_query($self_con,$sql_update);
}
exit;
?>