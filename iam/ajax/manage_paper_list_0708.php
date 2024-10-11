
<?
include_once "../../lib/rlatjd_fun.php";
extract($_POST);
$seq = $_POST['seq'];
if($_POST['del'] == "Y"){
    $sql_del = "delete from Gn_Member_card where seq='{$seq}'";
    $res = mysqli_query($self_con,$sql_del);
}
else if($_POST[comment] == "Y"){
    $sql_comment = "select comment from Gn_Member_card where seq='{$seq}'";
    $res_comment = mysqli_query($self_con,$sql_comment);
    $row_comment = mysqli_fetch_array($res_comment);

    $res = str_replace("\n", "<br>", $row_comment[0]);
}
else if($_POST[get_data] == "Y"){
    $sql_data = "select * from Gn_Member_card where seq='{$seq}'";
    $res_data = mysqli_query($self_con,$sql_data);
    $row_data = mysqli_fetch_array($res_data);

    if(!$row_data[name] && !$row_data[job] && !$row_data[org_name] && !$row_data[address] && !$row_data[mobile] && !$row_data[email1] && $row_data[comment]){
        $arr = explode("\n", $row_data[comment]);
        for($i = 0; $i < count($arr); $i++){
            $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
            $phonePattern = '/[0-9]{3}[\-][0-9]{4}[\-][0-9]{4}|[0-9]{3}[\.][0-9]{4}[\.][0-9]{4}|[0-9]{3}[\s][0-9]{4}[\s][0-9]{4}|[0-9]{3}[\-][\s][0-9]{4}[\-][\s][0-9]{4}|[0-9]{3}[\.][\s][0-9]{4}[\.][\s][0-9]{4}/';
            $faxPatter = '/[0-9]{2}[\-][0-9]{4}[\-][0-9]{4}|[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}|[0-9]{2}[\s][0-9]{4}[\s][0-9]{4}|[0-9]{2}[\.][0-9]{4}[\.][0-9]{4}|[0-9]{3}[\s][0-9]{3}[\s][0-9]{4}|[0-9]{3}[\.][0-9]{3}[\.][0-9]{4}|[0-9]{4}[\-][0-9]{3}[\-][0-9]{4}|[0-9]{2}[\-][0-9]{3}[\-][0-9]{4}/';
            preg_match_all($phonePattern, $arr[$i], $matches1);
            // $matches1 = $matches1[0];
            if($matches1[0] != NULL){
                if(strstr($matches1[0][0], "010")){
                    $mobile = $matches1[0][0];
                    $mobile = str_replace(" ", "", $mobile);
                    $mobile = str_replace("-", "", $mobile);
                    $row_data[mobile] = str_replace(".", "", $mobile);
                }
                else{
                    $row_data[phone1] = $matches1[0][0];
                }
                // echo $mobile.">>".$phone;
            }

            if(strstr($arr[$i], "F")){
                preg_match_all($faxPatter, $arr[$i], $matches_fax);
                if($matches_fax[0] != NULL){
                    $row_data[fax] = $matches_fax[0][0];
                    // echo "fax".$fax;
                }
            }

            preg_match_all($pattern, $arr[$i], $matches);
            if($matches[1] != NULL){
                $row_data[email1] = $matches[0][0];
                // echo $email;
            }

            $name = str_replace(" ", "", trim($arr[$i]));
            if(mb_strlen($name, 'utf-8') == 3){
                $row_data[name] = trim($arr[$i]);
                // echo $name;
            }

            $addr_c = trim($arr[$i]);
            if((strstr($addr_c, "시") && strstr($addr_c, "구") && strstr($addr_c, "로")) || (strstr($addr_c, "시") && strstr($addr_c, "구")) || (strstr($addr_c, "시") && strstr($addr_c, "로")) || (strstr($addr_c, "구") && strstr($addr_c, "로"))){
                $row_data[address] = trim($arr[$i]);
                // echo $address;
            }
        }
    }

    $res = "{\"name\":\"$row_data[name]\",\"job\":\"$row_data[job]\",\"org_name\":\"$row_data[org_name]\",\"address\":\"$row_data[address]\",\"phone1\":\"$row_data[phone1]\",\"phone2\":\"$row_data[phone2]\",\"mobile\":\"$row_data[mobile]\",\"fax\":\"$row_data[fax]\",\"email1\":\"$row_data[email1]\",\"email2\":\"$row_data[email2]\",\"memo\":\"$row_data[memo]\"}";
}
else if($_POST[save_data] == "Y"){
    $paper_phone1 = str_replace('-', '', $paper_phone1);
    $paper_phone2 = str_replace('-', '', $paper_phone2);
    $paper_mobile = str_replace('-', '', $paper_mobile);
    $sql_update = "update Gn_Member_card set name='{$paper_name}', job='{$paper_job}', org_name='{$paper_org_name}', address='{$paper_address}', phone1='{$paper_phone1}', phone2='{$paper_phone2}', mobile='{$paper_mobile}', fax='{$paper_fax}', email1='{$paper_email1}', email2='{$paper_email2}', memo='{$paper_memo}' where seq='{$paper_seq}'";
    $res = mysqli_query($self_con,$sql_update);

    $mem_phone = str_replace('-', '', $member_iam[mem_phone]);

    $sql_chk = "select idx from Gn_MMS_Receive_Iam where paper_yn=1 and paper_seq='{$paper_seq}'";
    $res_chk = mysqli_query($self_con,$sql_chk);
    $cnt_chk = mysqli_num_rows($res_chk);

    if($cnt_chk){
        $row_chk = mysqli_fetch_array($res_chk);
        $sql_update_mms = "update Gn_MMS_Receive_Iam set send_num='{$mem_phone}', recv_num='{$paper_mobile}', name='{$paper_name}', reg_date=now() where idx='{$row_chk[idx]}'";
        $res = mysqli_query($self_con,$sql_update_mms);
    }
    else{
        $sql_grp_id = "select idx from Gn_MMS_Group where mem_id='{$_SESSION[iam_member_id]}' and grp='아이엠'";
        $res_grp = mysqli_query($self_con,$sql_grp_id);
        $row_grp = mysqli_fetch_array($res_grp);

        if($row_grp[idx]){
            $sql_insert = "insert into Gn_MMS_Receive_Iam set grp_id='{$row_grp[idx]}', mem_id='{$_SESSION[iam_member_id]}', grp='아이엠', grp_2='아이엠', send_num='{$mem_phone}', recv_num='{$paper_mobile}', name='{$paper_name}', reg_date=now(), iam=1, paper_yn=1, paper_seq='{$paper_seq}'";
            $res = mysqli_query($self_con,$sql_insert);
        }
    }
}
echo $res;
?>