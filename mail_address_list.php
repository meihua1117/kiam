<?
$path="./";
include_once $path."lib/rlatjd_fun.php";
if($_SESSION[one_member_id] == "")
   exit;
$sql_serch=" mem_id ='$_SESSION[one_member_id]' ";
$sql="select count(idx) as cnt from gn_member_emails where $sql_serch ";
$result = mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_array($result);
$intRowCount=$row[cnt];
if($_POST[page])
    $page=(int)$_POST[page];
else
    $page=1;
if($_POST[page2])
    $page2=(int)$_POST[page2];
else
    $page2=1;
if (!$_POST[lno]) 
    $intPageSize =10;
else 
    $intPageSize = $_POST[lno];
$int=($page-1)*$intPageSize;
if($_REQUEST[order_status])
    $order_status=$_REQUEST[order_status];
else
    $order_status="asc";
if($_REQUEST[order_name])
    $order_name=$_REQUEST[order_name];
else
    $order_name="idx";
$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
$sql="select * from gn_member_emails where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
$result=mysql_query($sql) or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>메일 리스트</title>

    </head>
    <body>
        <link href='<?=$path?>css/nanumgothic.css' rel='stylesheet' type='text/css'/>
        <link href='<?=$path?>css/main.css' rel='stylesheet' type='text/css'/>
        <script language="javascript" src="<?=$path?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
        <style>
            ul,li { list-style:none; font-size:13px ; }
        </style>
        <script language="javascript" src="<?=$path?>js/jquery-1.7.1.min.js"></script>
        <script language="javascript" src="<?=$path?>js/rlatjd_fun.js"></script>
        <script language="javascript" src="<?=$path?>js/rlatjd.js"></script>
        <?include_once $path."open_div.php";?>
        <script>
            //디테일 수정 삭제 추가
function mail_func(status,idx)
{
    if(status == "del")
    {
		var msg="삭제하시겠습니까?";	
        if(confirm(msg))
        {
            $.ajax({
                type:"POST",
                url:"ajax/mail_address_func.php",
                data:{
                    mode : "del",
                    idx : idx
                },
                success:function(data){
                    location.reload();
                }
            });	
        }
    }
    else if(status == "use")
    {
        $.ajax({
                type:"POST",
                url:"ajax/mail_address_func.php",
                dataType:"json",
                data:{
                    mode : "use",
                    idx : idx
                },
                success:function(data){
                    if(data.status == "success")
                    {  
                        window.opener.document.getElementsByName("txt_mail_address")[0].value = data.msg;
                        window.close();
                    }
                }
            });	
    }

}
        </script>
        <form name="group_detail_form" action="" method="post" >
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>

                    <td style="width:50%;">메일주소</td>
                    <td style="width:25%;">삭제</td>
                    <td style="width:25%;">사용</td>
                </tr>
            <?
            $i=0;
            if($intRowCount)
            {
                $i=0;
                while($row=mysql_fetch_array($result))
                {
            ?>
                <tr>
                    <td class="g_dt_num_<?=$i?>">
                        <?=$row[email]?>
                    </td>
                    <td>
                        <a href="javascript:mail_func('del','<?=$row[idx]?>')">삭제</a>
                    </td>
                    <td>
                        <a href="javascript:mail_func('use','<?=$row[idx]?>')">사용</a>
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
            </table>
        </form>
    </body>
</html>

