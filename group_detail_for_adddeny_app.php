<?
$path="./";
include_once $path."lib/rlatjd_fun.php";
$type = $_REQUEST['type'];
if($type == "deny"){
    if(!$_REQUEST[phone]) $sql_serch=" 1=0 ";
    else $sql_serch=" dest='$_REQUEST[phone]' ";
    if($_REQUEST[grp_id])
        $sql_serch.=" and grp_id = '$_REQUEST[grp_id]' ";
    if($_REQUEST[grp_2])
        $sql_serch.=" and grp_2 = '$_REQUEST[grp_2]' ";
    if($_REQUEST[deta_select] && $_REQUEST[deta_text])
        $sql_serch.=" and $_REQUEST[deta_select] like '%$_REQUEST[deta_text]%' ";
    $sql="select count(seq) as cnt from sm_data where $sql_serch ";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row=mysqli_fetch_array($result);
    $intRowCount=$row[cnt];
    if($_POST[page])
        $page=(int)$_POST[page];
    else
        $page=1;
    if($_POST[page2])
        $page2=(int)$_POST[page2];
    else
        $page2=1;
    if (!$_REQUEST[contact_count]) 
        $intPageSize =50;
    else 
        $intPageSize = $_REQUEST[contact_count];
    $int=($page-1)*$intPageSize;
    if($_REQUEST[order_status])
        $order_status=$_REQUEST[order_status];
    else
        $order_status="asc";
    if($_REQUEST[order_name])
        $order_name=$_REQUEST[order_name];
    else
        $order_name="seq";

    $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
    $sql="select * from sm_data where $sql_serch group by msg_url order by $order_name $order_status limit $int,$intPageSize";
    $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
}
else{
    if(!$_REQUEST[phone]) $sql_serch=" 1=0 ";
    else $sql_serch=" b.mem_id='$_REQUEST[mem_id]' and b.chanel_type=9 and a.dest='$_REQUEST[phone]'";
    if($_REQUEST[grp_id])
        $sql_serch.=" and grp_id = '$_REQUEST[grp_id]' ";
    if($_REQUEST[grp_2])
        $sql_serch.=" and grp_2 = '$_REQUEST[grp_2]' ";
    if($_REQUEST[deta_select] && $_REQUEST[deta_text])
        $sql_serch.=" and a.$_REQUEST[deta_select] like '%$_REQUEST[deta_text]%' ";
    $sql="select count(a.seq) as cnt from sm_data a inner join Gn_MMS_Deny b on a.msg_url=b.recv_num where $sql_serch ";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row=mysqli_fetch_array($result);
    $intRowCount=$row[cnt];
    if($_POST[page])
        $page=(int)$_POST[page];
    else
        $page=1;
    if($_POST[page2])
        $page2=(int)$_POST[page2];
    else
        $page2=1;
    if (!$_REQUEST[contact_count]) 
        $intPageSize =50;
    else 
        $intPageSize = $_REQUEST[contact_count];
    $int=($page-1)*$intPageSize;
    if($_REQUEST[order_status])
        $order_status=$_REQUEST[order_status];
    else
        $order_status="asc";
    if($_REQUEST[order_name])
        $order_name=$_REQUEST[order_name];
    else
        $order_name="a.seq";

    $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
    $sql="select a.* from sm_data a inner join Gn_MMS_Deny b on a.msg_url=b.recv_num where $sql_serch group by a.msg_url order by $order_name $order_status limit $int,$intPageSize";
    $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
        <title>무제 문서</title>
        <link href='<?=$path?>css/nanumgothic.css' rel='stylesheet' type='text/css'/>
        <link href='<?=$path?>css/main.css' rel='stylesheet' type='text/css'/>
        <script language="javascript" src="<?=$path?>js/jquery-1.7.1.min.js"></script>
        <script language="javascript" src="<?=$path?>js/rlatjd_fun.js"></script>
        <script language="javascript" src="<?=$path?>js/rlatjd.js"></script>
        <script language="javascript" src="<?=$path?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
        <style>
            ul,li { list-style:none; font-size:13px ; }
            .btn_adddeny{
                background-color: green;
                color: white;
                padding: 7px;
                font-size: 15px;
                background-color: #7be278;
                font-weight: bold;
                color: white;
                width: 40%;
                border: none;
                margin-top: 20px;
                padding: 9px;
                border-radius: 10px;
            }
            .pagenum{
                font-size: 17px;
                font-weight: 700;
            }
        </style>
    </head>
    <body>
        <?include_once $path."open_div.php";?>
        
        <div style="text-align:center;margin-bottom: 15px;">
        <?
        if($type == "deny"){
        ?>
            <h2 id="msg_title" style="text-align:center; background-color:rgb(130,199,54);color: white;padding: 10px;margin-top: 0px;">자동콜백 제외대상 추가하기</h2>
            <span style="font-size: 14px;">선택하면 콜백 제외대상으로 등록됩니다.</span>
        <?}
        else{?>
            <h2 id="msg_title" style="text-align:center; background-color:rgb(130,199,54);color: white;padding: 10px;margin-top: 0px;">자동콜백 제외리스트 보기/해제</h2>
            <span style="font-size: 14px;">선택하면 제외리스트에서 해제됩니다.</span>
        <?}?>
        </div>
        
        <form name="group_detail_form" action="" method="post" >
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />
            <input type="hidden" name="phone" value="<?=$_REQUEST[phone]?>" />
            <input type="hidden" name="grp_2" value="<?=$_REQUEST[grp_2]?>" />
            <div class="grp_detail">
                <select name="deta_select">
                <?
                $select_deta_arr=array("msg_text"=>"이름","msg_url"=>"전화번호");
                foreach($select_deta_arr as $key=>$v)
                {
                    $selected=$_REQUEST[deta_select]==$key?"selected":"";
                    ?>
                    <option value="<?=$key?>" <?=$selected?>><?=$v?></option>
                    <?
                }
                ?>
                </select>
                <input type="text" name="deta_text" value="<?=$_REQUEST[deta_text]?>" />
                <input type="image" src="images/sub_button_703.jpg" />
                <select name="contact_count" onchange="group_detail_form.submit()" style="float:right;">
                    <option value="50" <?=$_REQUEST[contact_count]==50?'selected':''?>>50개씩 보기</option>
                    <option value="100" <?=$_REQUEST[contact_count]==100?'selected':''?>>100개씩 보기</option>
                </select>
            </div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:25%;">
                        <li><input type="checkbox" id="all_id" onclick="check_all(this,'name_box');box_ed_deny(-1,'<?=$_REQUEST[grp_id]?>')" /></li>
                        <li style="font-size:13px;font-family:Nanum Gothic;">
                            <label for="all_id">선택</label>
                            <a href="javascript:order_sort(group_detail_form,'name',group_detail_form.order_status.value)">이름<? if($_REQUEST[order_name]=="name"){echo $_REQUEST[order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a>
                        </li>
                    </td>
                    <td style="width:25%;">전화번호</td>
                </tr>
            <?
            $i=0;
            if($intRowCount)
            {
                $i=0;
                while($row=mysqli_fetch_array($result))
                {
            ?>
                <tr>
                    <td class="g_dt_name_<?=$i?>" >
                        <li><input type="checkbox" name="name_box" value="<?=$row[msg_url]?>" onclick="box_ed_deny('<?=$i?>','<?=$row[msg_text]?>')" /></li>
                        <li><label name = "lbox_<?=$i?>" id="lbox_<?=$i?>" ><?=$row[msg_text]?></label></li>
                    </td>
                    <td class="g_dt_num_<?=$i?>">
                        <?=$row[msg_url]?>
                    </td>
                    <td class="g_dt_num_<?=$i?>" style="display:none;">
                        <input type="text" name="recv_num" value="<?=$row[msg_url]?>" />
                    </td>
                </tr>
            <?
                    $i++;
                }
            ?>
                <tr>
                    <td colspan="4">
                    <?
                    page_f_deny($page,$page2,$intPageCount,"group_detail_form");
                    ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div><span class="pagenum" style="color: #7be278;"><?=$page?></span>/<span class="pagenum"><?=$intPageCount?></span></div>
                    </td>
                </tr>
              <?
            }else{
            ?>
                <tr>
                    <td colspan="4">
                        내용이 없습니다.
                    </td>
                </tr>
            <?
            }
            ?>
                <tr>
                </tr>
            </table>
        </form>
        <div style="text-align: center;">
            <!-- <button onclick="window.close()" class="btn_adddeny">취소</button> -->
            <button onclick="close_window()" class="btn_adddeny">확인</button>
        </div>
        <script>
            var name_arr = document.getElementsByName('name_box');
            var group_arr = [];
            function box_ed_deny(g,name){
                if (g  >= 0) {
                    if (name_arr[g].checked) {
                        if( name == '') {
                            window.parent.parent_alert("엑셀디비에 이름이 기입되어 있어야 치환기능이 됩니다.");
                        }
                        if (jQuery.inArray(name_arr[g].value, group_arr) == -1)
                            group_arr.push(name_arr[g].value);
                    } else {
                        for (var a = 0; a < group_arr.length; a++) {
                            if (name_arr[g].value == group_arr[a])
                                group_arr.splice(a, 1);
                        }
                    }
                } else {
                    for (var i = 0; i < name_arr.length; i++) {
                        if (name_arr[i].checked) {
                            if (jQuery.inArray(name_arr[i].value, group_arr) == -1)
                                group_arr.push(name_arr[i].value);
                        } else {
                            for (var a = 0; a < group_arr.length; a++) {
                                if (name_arr[i].value == group_arr[a])
                                    group_arr.splice(a, 1);
                            }
                        }
                    }
                    if(name_arr[0].checked) {
                        $.ajax({
                            type: "POST",
                            url: "/ajax/sendmmsPrc.2020.php",
                            data: {
                                method: "check_recv_name",
                                group_arr: name
                            },
                            dataType: "json",
                            success: function (data) {
                                if (data.result != "success") {
                                    window.parent.alert(data.result + "그룹에 이름이 없는 회원이 있습니다.엑셀디비에 이름이 기입되어 있어야 치환기능이 됩니다");
                                }
                            },
                            error: function () {
                            }
                        });
                    }
                }
            }

            function close_window(){
                var nums_str = group_arr.join(",");
                location.href='<?=$_GET[pre_link]?>'+'&show_modal=Y&deny_nums='+nums_str+'&type='+'<?=$type?>';
            }
        </script>
    </body>
</html>

