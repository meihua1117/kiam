<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?
extract($_POST);
if(!$method) {
    echo '잘못된 접근입니다. 다시 시도 해주세요.';
    exit;
}else if($method == "check_group"){
    $group_sql = "select card_short_url,group_id,mem_code from gn_group_info info 
                        inner join Gn_Iam_Name_Card card on card.idx = info.card_idx 
                        inner join Gn_Member mem on mem.mem_id = info.manager where manager = '{$_SESSION['iam_member_id']}'";
    $group_res = mysqli_query($self_con,$group_sql);
    $group_count = mysqli_num_rows($group_res);
    $group_row = mysqli_fetch_array($group_res);
    $group = "location.href = '?".$group_row['card_short_url'].$group_row['mem_code']."&cur_win=group-con&gkind=".$group_row['group_id']."'";
    if($group_count > 0){
        echo json_encode(array("group"=>$group));
    }else{
        echo json_encode(array("group"=>null));
    }
}else if($method == "create_group"){
    $sql = "insert into gn_group_info set name = '$name',
                                            manager = '{$_SESSION['iam_member_id']}',
                                            public_status = '$public',
                                            upload_status = '$upload',
                                            description = '$desc',
                                            req_date = now()";
    mysqli_query($self_con,$sql);
    $g_idx = mysqli_insert_id($self_con);
    $sql = "select * from Gn_Iam_Name_Card where mem_id='{$_SESSION['iam_member_id']}' and group_id is NULL order by req_data";//이미지 꺼내기
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    $gTitle = $name."소개";
    $short_url = generateRandomString();
    $sql = "insert into Gn_Iam_Name_Card set group_id = '$g_idx',
                                            mem_id = '{$_SESSION['iam_member_id']}',
                                            req_data = now(),
                                            up_Data=now(),
                                            card_title = '$gTitle',
                                            card_name = '$gTitle',
                                            main_img1 = '{$row['main_img1']}',
                                            main_img2 = '{$row['main_img2']}',
                                            main_img3 = '{$row['main_img3']}',
                                            card_short_url = '$short_url'
                                            ";
    mysqli_query($self_con,$sql);
    $card_idx = mysqli_insert_id($self_con);
    $sql = "update gn_group_info set card_idx = '$card_idx' where idx='$g_idx'";
    mysqli_query($self_con,$sql);
    $sql = "select count(*) from gn_group_member where mem_id='{$_SESSION['iam_member_id']}' and group_id='$g_idx'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    if($row[0] == 0){
        $sql = "insert into gn_group_member set mem_id='{$_SESSION['iam_member_id']}',group_id='$g_idx',req_date=now(),visit_date=now()";
        mysqli_query($self_con,$sql);
    }
    $sql = "select mem_code from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    $link = "/?".$short_url.$row['mem_code']."&cur_win=group-con&gkind=".$g_idx;
    echo json_encode(array("result"=>$link));
}else if($method == "edit_group"){
    $sql = "update gn_group_info set name = '$name',
                                      manager = '{$_SESSION['iam_member_id']}',
                                      public_status = '$public',
                                      upload_status = '$upload',
                                      description = '$desc',
                                      req_date = now() where idx = '$id'";
    mysqli_query($self_con,$sql);
    $sql = "update Gn_Iam_Contents set group_display = '$public' where group_id = '$id'";
    mysqli_query($self_con,$sql);
    echo json_encode(array("result"=>"success"));
}else if($method == "get_group_info"){
    $result = array();
    $sql = "select mem_code from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    $mem_code = $row['mem_code'];
    $sql = "select group_id from gn_group_member where mem_id='{$_SESSION['iam_member_id']}'";
    $res = mysqli_query($self_con,$sql);
    while($row = mysqli_fetch_array($res)){
        $group_id = $row['group_id'];
        $g_card_sql = "select main_img1,g.name,card_short_url from Gn_Iam_Name_Card c inner join gn_group_info g on c.idx=g.card_idx where g.idx='$group_id'";
        $g_card_res = mysqli_query($self_con,$g_card_sql);
        $g_card_row = mysqli_fetch_array($g_card_res);
        $main_link = $g_card_row['card_short_url'].$mem_code;
        $img = $g_card_row['main_img1'];
        $title = $g_card_row['name'];
        $card_array = array();
        $g_card_sql = "select card_short_url,mem_code,card_title from Gn_Iam_Name_Card c inner join Gn_Member m on c.mem_id=m.mem_id where c.group_id='$group_id' and c.phone_display = 'Y'";
        $g_card_res = mysqli_query($self_con,$g_card_sql);
        $i = 1;
        while($g_card_row = mysqli_fetch_array($g_card_res)){
            $card_title = $g_card_row['card_title'];
            if($card_title == "")
               $card_title = $i."번 카드";
            $card = array("card_title"=>$card_title,"card_link"=>$g_card_row['card_short_url'].$g_card_row['mem_code']);
            array_push($card_array,$card);
            $i++;
        }
        $group = array("img"=>$img,"title"=>$title,"card"=>$card_array,"group"=>$group_id,"link"=>$main_link);
        array_push($result,$group);
    }
    echo json_encode(array("result"=>$result));
}else if($method == "create_group_card"){
    $new_card_short_url = generateRandomString();
    $sql = "insert into Gn_Iam_Name_Card set mem_id = '{$_SESSION['iam_member_id']}',
                                                group_id='$group_id',
                                                card_title='$card_title',
                                                card_name='$card_title',
                                                card_short_url='$new_card_short_url',
                                                req_data=now(),
                                                up_data=now()";
    mysqli_query($self_con,$sql);
    $mem_sql="select * from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
    $mem_res=mysqli_query($self_con,$mem_sql);
    $mem_row=mysqli_fetch_array($mem_res);
    $site_iam = $mem_row['site_iam'];
    $site = $mem_row['site'];
    if($site_iam == "kiam")
        $service = "http://www.kiam.kr";
    else
        $service = "http://".$site_iam.".kiam.kr";
    $buyer_name = $mem_row['mem_name'];
    $pay_method = $_SESSION['iam_member_id']."/".$buyer_name;
    $buyer_phone = $mem_row['mem_phone'];
    $cur_point = $mem_row['mem_point'] * 1 - $price * 1;
    $service = $service."?".$new_card_short_url.$mem_row['mem_code']."&cur_win=group-con&gkind=".$group_id;
    $item_name = "그룹 카드/".$new_card_short_url;
    $point = 1;
    $sql_buyer = "insert into Gn_Item_Pay_Result set buyer_id='{$_SESSION['iam_member_id']}',
                                                    buyer_tel='$buyer_phone',
                                                    site='$service',
                                                    pay_method='$pay_method',
                                                    item_name = '$item_name',
                                                    item_price=$price,
                                                    seller_id='',
                                                    pay_date=NOW(),
                                                    pay_status='Y',
                                                    pay_percent='$pay_percent',
                                                    order_number = '$allat_order_no',
                                                    VACT_InputName='$buyer_name',
                                                    point_val=$point,
                                                    type='group_card',
                                                    current_point=$cur_point,
                                                    current_cash={$mem_row['mem_cash']}";
    mysqli_query($self_con,$sql_buyer);

    $sql_update = "update Gn_Member set mem_point={$cur_point} where mem_id='{$_SESSION['iam_member_id']}'";
    mysqli_query($self_con,$sql_update);
    echo json_encode(array("result"=>"success"));
}else if($method == "send_invite"){
    $mem_array = explode(",",$members);
    for($i = 0; $i < count($mem_array);$i++){
        $invite_mem = $mem_array[$i];
        $sql = "insert into gn_group_invite set mem_id = '{$_SESSION['iam_member_id']}',group_id = '$group_id',invite_id='$invite_mem'";
        mysqli_query($self_con,$sql);
    }
    echo json_encode(array("result"=>"success"));
}else if($method == "del_invite"){
    $sql = "delete from gn_group_invite where invite_id = '{$_SESSION['iam_member_id']}' and group_id = '$group_id'";
    mysqli_query($self_con,$sql);
    echo json_encode(array("result"=>"success"));
}else if($method == "accept_invite"){
    $sql = "delete from gn_group_invite where invite_id = '{$_SESSION['iam_member_id']}' and group_id = '$group_id'";
    mysqli_query($self_con,$sql);
    $sql = "select count(*) from gn_group_member where mem_id='{$_SESSION['iam_member_id']}' and group_id='$group_id'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    if($row[0] == 0){
        $sql = "insert into gn_group_member set mem_id='{$_SESSION['iam_member_id']}',group_id='$group_id',req_date=now(),visit_date=now()";
        mysqli_query($self_con,$sql);
        echo json_encode(array("result"=>"success"));
    }else{
        echo json_encode(array("result"=>"이미 가입되어있는 그룹입니다."));
    }
}else if($method == "pin"){
    $sql = "select * from gn_group_member where mem_id='{$_SESSION['iam_member_id']}' and group_id='$group_id'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    if($row[fix_status] == "Y"){
        $sql = "update gn_group_member set fix_status = 'N' where mem_id='{$_SESSION['iam_member_id']}' and group_id='$group_id'";
        mysqli_query($self_con,$sql);
        echo json_encode(array("result"=>"N"));
    }else{
        $sql = "update gn_group_member set fix_status = 'Y' where mem_id='{$_SESSION['iam_member_id']}' and group_id='$group_id'";
        mysqli_query($self_con,$sql);
        echo json_encode(array("result"=>"Y"));
    }
}else if($method == "del_members"){
    $members = explode(",",$members);
    for($i = 0; $i < count($members);$i++){
        $mem_id = $members[$i];
        $sql = "delete from gn_group_member where mem_id = '$mem_id' and group_id = '$group_id'";
        mysqli_query($self_con,$sql);

        $sql = "delete from Gn_Iam_Name_Card where mem_id = '$mem_id' and group_id = '$group_id'";
        mysqli_query($self_con,$sql);

        $sql = "delete from Gn_Iam_Contents where mem_id = '$mem_id' and group_id = '$group_id'";
        mysqli_query($self_con,$sql);
    }
    echo json_encode(array("result"=>"success"));
}else if($method == "get_contents_list"){
    $result = "";
    $sql = "select cont.*,mem.mem_name from Gn_Iam_Contents cont inner join Gn_Member mem on mem.mem_id=cont.mem_id where group_id = '$group_id'";
    $res = mysqli_query($self_con,$sql);
    while($row = mysqli_fetch_array($res)){
        $result .= "<tr>".
                        "<td class=\"iam_table\">".$row["req_data"]."</td>".
                        "<td class=\"iam_table\">".$row["mem_name"]."</td>".
                        "<td class=\"iam_table\">".$row["mem_id"]."</td>".
                        "<td class=\"iam_table\">".
                            "<a href='/iam/contents.php?contents_idx={$row['idx']}' target='_blank'>".
                                "<img src='{$row['contents_img']}'>".
                            "</a>".
                        "</td>".
                        "<td class=\"iam_table\">".
                            "<label class=\"switch\" onchange='change_group_content_display(".$row['idx'].")'>".
                                "<input type='checkbox' name='status' value='".$row['idx']."'". ($row['group_display']=="Y"?"checked":"").">".
                                "<span class='slider round' name='status_round'></span>".
                            "</label>".
                        "</td>".
                        "<td class=\"iam_table\"><a href=\"javascript:contents_del(".$row['idx'].",0,".$group_id.")\">삭제</a></td>".
                    "</tr>";
    }
    echo $result;
}else if($method == "change_content_display"){
    $group_display = "Y";
    $sql = "select group_display from Gn_Iam_Contents where idx = '$group_id'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    if($row[0] == "Y")
        $group_display = "N";
    $sql = "update Gn_Iam_Contents set group_display = '$group_display' where idx = '$group_id'";
    mysqli_query($self_con,$sql);
    echo json_encode(array("result"=>"success"));
}else if($method == "group_content_fix"){
    $sql = "select group_id,group_fix,card_idx from Gn_Iam_Contents where idx = '$group_id'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    if($row[group_fix] == 0) {
        $sql = "select max(group_fix) from Gn_Iam_Contents where card_idx = '{$row['card_idx']}'";
        $res = mysqli_query($self_con,$sql);
        $max_row = mysqli_fetch_array($res);
        $group_fix = $max_row[0] + 1;

        $sql = "update Gn_Iam_Contents set group_fix = $group_fix where idx = $group_id";
        mysqli_query($self_con,$sql);
        echo json_encode(array("result"=>"성공적으로 고정되었습니다."));
    }else{
        $sql = "update Gn_Iam_Contents set group_fix = group_fix - 1 where group_id = {$row['group_id']} and group_fix > {$row['group_fix']}";
        mysqli_query($self_con,$sql);

        $sql = "update Gn_Iam_Contents set group_fix = 0 where idx = $group_id";
        mysqli_query($self_con,$sql);
        echo json_encode(array("result"=>"성공적으로 해지되었습니다."));
    }
}else if($method == "edit_group_card"){
    $sql = "update Gn_Iam_Name_Card set card_title = '$card_title' where card_short_url = '$url'";
    mysqli_query($self_con,$sql);
    echo json_encode(array("result"=>"success"));
}
?>
