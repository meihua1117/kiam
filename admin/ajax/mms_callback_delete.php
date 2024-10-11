<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$cam_id = $_POST["idx"]; 

    $query="delete from gn_mms_callback WHERE idx='$cam_id'
                                 ";
    mysqli_query($self_con,$query);	

    $sql_mms_update = "update gn_mms_callback set regdate=NOW() order by idx asc limit 1";
    mysqli_query($self_con,$sql_mms_update);

    $sql_mem_info = "select * from Gn_Member where (mem_callback_mun_state=1 and mun_callback={$cam_id}) or (mem_callback_phone_state=1 and phone_callback={$cam_id})";
    $res_mem_info = mysqli_query($self_con,$sql_mem_info);
    if(mysqli_num_rows($res_mem_info)){
        while($row_mem_info = mysqli_fetch_array($res_mem_info)){
            $sql_sel_first_main = "select * from gn_mms_callback where service_state=0 order by idx asc limit 1";
            $res_main = mysqli_query($self_con,$sql_sel_first_main);
            $row_main = mysqli_fetch_array($res_main);
            $main_idx = $row_main['idx'];
        
            if($row_mem_info['phone_callback'] == $cam_id && $row_mem_info['mun_callback'] == $cam_id){
                $sql_update = "update Gn_Member set phone_callback={$main_idx}, mun_callback={$main_idx} where mem_id='{$row_mem_info['mem_id']}'";
            }
            else if($row_mem_info['phone_callback'] == $cam_id){
                if($row_mem_info['mun_callback'] == $main_idx){
                    $sql_update = "update Gn_Member set phone_callback={$main_idx}, phone_callback_state=3, mun_callback_state=3 where mem_id='{$userid}'";
                }
                else{
                    $sql_update = "update Gn_Member set phone_callback={$main_idx} where mem_id='{$row_mem_info['mem_id']}'";
                }
            }
            else if($row_mem_info['mun_callback'] == $cam_id){
                if($row_mem_info['phone_callback'] == $main_idx){
                    $sql_update = "update Gn_Member set mun_callback={$main_idx}, phone_callback_state=3, mun_callback_state=3 where mem_id='{$row_mem_info['mem_id']}'";
                }
                else{
                    $sql_update = "update Gn_Member set mun_callback={$main_idx} where mem_id='{$row_mem_info['mem_id']}'";
                }
            }
            mysqli_query($self_con,$sql_update);
        }
    }

echo "<script>alert('저장되었습니다.');location='/admin/mms_callback_list.php';</script>";
exit;
?>