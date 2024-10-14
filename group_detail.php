<?
$path="./";
include_once $path."lib/rlatjd_fun.php";
$sql_serch=" mem_id ='{$_SESSION['one_member_id']}' ";
if($_REQUEST['grp_id'])
    $sql_serch.=" and grp_id = '{$_REQUEST['grp_id']}' ";
if($_REQUEST['grp_2'])
    $sql_serch.=" and grp_2 = '{$_REQUEST['grp_2']}' ";
if($_REQUEST['deta_select'] && $_REQUEST['deta_text'])
    $sql_serch.=" and {$_REQUEST['deta_select']} like '{$_REQUEST['deta_text']}%' ";
$sql="select count(idx) as cnt from Gn_MMS_Receive where $sql_serch ";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($result);
$intRowCount=$row['cnt'];
if($_POST['page'])
    $page=(int)$_POST['page'];
else
    $page=1;
if($_POST['page2'])
    $page2=(int)$_POST['page2'];
else
    $page2=1;
if (!$_POST['lno']) 
    $intPageSize =10;
else 
    $intPageSize = $_POST['lno'];
$int=($page-1)*$intPageSize;
if($_REQUEST['order_status'])
    $order_status=$_REQUEST['order_status'];
else
    $order_status="asc";
if($_REQUEST['order_name'])
    $order_name=$_REQUEST['order_name'];
else
    $order_name="idx";
$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
$sql="select * from Gn_MMS_Receive where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>무제 문서</title>
        <link href='<?=$path?>css/nanumgothic.css' rel='stylesheet' type='text/css'/>
        <link href='<?=$path?>css/main.css' rel='stylesheet' type='text/css'/>
        <!--script language="javascript" src="<?=$path?>js/jquery.carouFredSel-6.2.1-packed.js"></script-->
        <style>
            ul,li { list-style:none; font-size:13px ; }
        </style>
    </head>
    <body>
        <script language="javascript" src="<?=$path?>js/jquery-1.7.1.min.js"></script>
        <script language="javascript" src="<?=$path?>js/rlatjd_fun.js"></script>
        <script language="javascript" src="<?=$path?>js/rlatjd.js"></script>
        <?include_once $path."open_div.php";?>
        <form name="group_detail_form" action="" method="post" >
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />
            <input type="hidden" name="grp_id" value="<?=$_REQUEST['grp_id']?>" />
            <input type="hidden" name="grp_2" value="<?=$_REQUEST['grp_2']?>" />
            <div class="grp_detail">
                <a href="group_detail.php?grp_id=<?=$_REQUEST['grp_id']?>" class="a_btn_2">전체</a>
                <select name="deta_select">
                <?
                $select_deta_arr=array("recv_num"=>"전화번호","grp"=>"소그룹명","name"=>"이름", "email"=>"이메일");
                foreach($select_deta_arr as $key=>$v)
                {
                    $selected=$_REQUEST['deta_select']==$key?"selected":"";
                    ?>
                    <option value="<?=$key?>" <?=$selected?>><?=$v?></option>
                    <?
                }
                ?>
                </select>
                <input type="text" name="deta_text" value="<?=$_REQUEST['deta_text']?>" />
                <input type="image" src="images/sub_button_703.jpg" />
                <a href="javascript:deleteGroupMember('<?=$_REQUEST['grp_id']?>')"><img src="/images/sub_button_16.jpg" /></a>
            </div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:20%;"><a href="javascript:void(0)" onclick="order_sort(group_detail_form,'grp_2',group_detail_form.order_status.value)">소그룹명<? if($_REQUEST['order_name']=="grp_2"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                    <td style="width:20%;">
                        <li><input type="checkbox" id="all_id" onclick="check_all(this,'name_box');box_ed(-1,'<?=$_REQUEST['grp_id']?>')" /></li>
                        <li style="font-size:13px;font-family:Nanum Gothic;">
                            <label for="all_id">선택</label>
                            <a href="javascript:order_sort(group_detail_form,'name',group_detail_form.order_status.value)">이름<? if($_REQUEST['order_name']=="name"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a>
                        </li>
                    </td>
                    <td style="width:25%;">전화번호</td>
                    <td style="width:25%;">이메일</td>
                    <td style="width:10%;">편집</td>
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
                    <td class="g_dt_grp_<?=$i?>">
                        <a href="javascript:group_detail_form.grp_2.value='<?=$row['grp_2']?>';group_detail_form.submit();"><?=$row['grp_2']?></a>
                    </td>
                    <td class="g_dt_grp_<?=$i?>" style="display:none;">
                        <input type="text" name="xiao_grp" value="<?=$row['grp_2']?>"/>
                    </td>
                    <td class="g_dt_name_<?=$i?>" >
                        <li><input type="checkbox" name="name_box" value="<?=$row['recv_num']?>" onclick="box_ed('<?=$i?>','<?=$row['name']?>')" /></li>
                        <li><label name = "lbox_<?=$i?>" id="lbox_<?=$i?>" ><?=$row['name']?></label></li>
                    </td>
                    <td class="g_dt_name_<?=$i?>" style="display:none;">
                        <input type="text" name="num_name" value="<?=$row['name']?>" />
                    </td>
                    <td class="g_dt_num_<?=$i?>">
                        <?=$row['recv_num']?>
                    </td>
                    <td class="g_dt_num_<?=$i?>" style="display:none;">
                        <input type="text" name="recv_num" value="<?=$row['recv_num']?>" />
                    </td>
                    <td class="g_dt_mail_<?=$i?>">
                        <?=$row['email']?>
                    </td>
                    <td class="g_dt_mail_<?=$i?>" style="display:none;">
                        <input type="text" name="email" value="<?=$row['email']?>" />
                    </td>               
                    <td>
                        <a href="javascript:g_dt_show_cencle('g_dt_name_','g_dt_num_','modify_btn_','<?=$i?>','g_dt_grp_', 'g_dt_mail_')" class="modify_btn_<?=$i?>" >수정</a>
                        <a href="javascript:g_dt_fun('modify','<?=$_REQUEST['grp_id']?>','<?=$row['idx']?>','<?=$i?>')"class="modify_btn_<?=$i?>" style="display:none;" >수정</a>
                        <a href="javascript:g_dt_fun('del','<?=$_REQUEST['grp_id']?>','<?=$row['idx']?>','<?=$i?>')">삭제</a>
                    </td>
                </tr>
            <?
                    $i++;
                }
            ?>
                <tr>
                    <td colspan="4">
                    <?
                    page_f($page,$page2,$intPageCount,"group_detail_form");
                    ?>
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
                    <td colspan="4" style="text-align:center;">
                        새로 추가:
                        <input type="text" name="xiao_grp" placeholder="소그룹명" style="width:90px;" />
                        <input type="text" name="num_name" placeholder="이름" style="width:90px;" />
                        <input type="text" name="recv_num" placeholder="전화번호" style="width:90px;" />
                        <input type="text" name="email" placeholder="이메일" style="width:90px;" />
                        <input type="button" value="저장" onclick="g_dt_fun('add','<?=$_REQUEST['grp_id']?>','','<?=$i?>')"  />
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>

