
<?
include_once "../../lib/rlatjd_fun.php";
extract($_POST);

if($method == "show_req_list"){
    $str = "";
    $sql_sel = "select * from Gn_Gpt_Req_List where mem_id='{$mem_id}' order by reg_date desc";
    $res_sel = mysqli_query($self_con,$sql_sel);
    $cnt = mysqli_num_rows($res_sel);
    if(!$cnt){
        $str .= '<li class="article-title" style="text-align: center;"><span class="chat_title">자료가 없습니다.</span></li>';
    }
    else{
        while($row_sel = mysqli_fetch_array($res_sel)){
            $qu = htmlspecialchars_decode($row_sel['gpt_question']);
            $aw = htmlspecialchars_decode($row_sel['gpt_answer']);
            $str .= '<li class="article-title" id="q'.$row_sel[id].'"><img src="/iam/img/chat_Q.png" onclick="show(`'.$row_sel[id].'`)" style="width:30px;margin-right: 10px;"><span class="chat_title" onclick="show(`'.$row_sel[id].'`)">'.$qu.'</span><span onclick="del_list(`'.$row_sel[id].'`)" style="margin-left:20px;">[삭제]</span></li>
            <li class="article-content hided" id="a'.$row_sel[id].'"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;">'.$aw.'</span></li>';
        }
    }   
    echo $str;
}
else if($method == "del_req_list"){
    $sql_del = "delete from Gn_Gpt_Req_List where id='{$id}'";
    mysqli_query($self_con,$sql_del);

    echo '{"result":1}';
}
?>