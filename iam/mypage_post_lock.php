<?php 
include "inc/header.inc.php";
if($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/iam/';</script>";
}
$mem_id = $_SESSION['iam_member_id'];
$sql_serch=" mem_id ='{$_SESSION['iam_member_id']}' ";
$sql="select count(*) from Gn_Iam_Contents cont inner join Gn_Iam_Post p on p.content_idx = cont.idx where cont.mem_id  = '$mem_id' and lock_status='Y' order by p.reg_date";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row = mysqli_fetch_array($result);
$post_count	=  $row[0];
if (!$_POST['lno'])
    $intPageSize =20;
else
    $intPageSize = $_POST['lno'];
if($_POST['page'])
{
    $page=(int)$_POST['page'];
    $sort_no=$post_count-($intPageSize*$page-$intPageSize);
}
else
{
    $page=1;
}
if($_POST['page2'])
    $page2=(int)$_POST['page2'];
else
    $page2=1;
$startIndex = ($page - 1) * $intPageSize;
$intPageCount=(int)(($post_count + $intPageSize - 1)/$intPageSize);
$sql="select p.*,m.mem_name,m.profile,cont.westory_card_url from Gn_Iam_Contents cont inner join Gn_Iam_Post p on p.content_idx = cont.idx inner join Gn_Member m on m.mem_id = p.mem_id where cont.mem_id  = '$mem_id' and lock_status='Y' order by p.reg_date limit $startIndex,$intPageSize";
$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
?>
<style>
.container {
    background-color: #fff;
    -webkit-box-shadow: 0 0 0 1px rgba(0,0,0,0.05), 0 0 5px 0 rgba(0,0,0,0.1);
    padding: 0 !important;
}
td {
    font-size: 11px !important;
    vertical-align: middle;
}
</style>
<link href='/css/main.css' rel='stylesheet' type='text/css'/>
<link href='/css/responsive.css' rel='stylesheet' type='text/css'/>
<main id="register" class="common-wrap" style=""><!-- 컨텐츠 영역 시작 -->
    <div class="container">
        <div class="inner-wrap">
                    <h2 class="title"></h2>
                    <div class="mypage_menu">
                        <div style="display:flex;float: right">
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_receive&modal=Y')" title = "<?=$MENU['IAM_MENU']['M7_TITLE'];?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">콘수신</p>
                                <label class="label label-sm" id = "share_recv_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_send&modal=Y')" title = "<?=$MENU['IAM_MENU']['M8_TITLE'];?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">콘전송</p>
                                <label class="label label-sm" id = "share_send_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=unread_post')" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">댓글수신</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="/iam/mypage_post_lock.php" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:#99cc00">댓글차단해지</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=request_list')" title = "<?='신청알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">이벤트신청</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                        </div>
                        <div style="display:flex;float: right;">
                            <?if($_SESSION['iam_member_subadmin_id'] == $_SESSION['iam_member_id']){?>
                            <a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&box=send&modal=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공지전송</p>
                                <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&modal=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공지수신</p>
                                <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <?}else{?>
                            <a class="btn  btn-link" title = "<?='공지알림';?>" href="javascript:iam_mystory('cur_win=unread_notice')" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공지</p>
                                <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <?}?>
                            <?if($is_pay_version){?>
                            <a class="btn  btn-link" title = "" href="/iam/mypage_refer.php" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">추천</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "" href="/iam/mypage_payment.php" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">결제</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "" href="/iam/mypage_payment_item.php" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">판매</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <?}?>
                            <?if($member_iam[service_type] < 2){
                                $report_link = "/iam/mypage_report_list.php";
                            }else{
                                $report_link = "/iam/mypage_report.php";
                            }
                            ?>
                            <a class="btn  btn-link" title = "" href="<?=$report_link?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">리포트</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "" href="/?cur_win=unread_notice&req_provide=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공급사신청</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                        </div>
                    </div>
                    <br>
                    <form name="pay_form" action="" method="post" class="my_pay" enctype="multipart/form-data">
                        <input type="hidden" name="page" value="<?=$page?>" />
                        <input type="hidden" name="page2" value="<?=$page2?>" />
                        <div style="text-align: center;margin-top: 70px;">
                            <h2 class="title">댓글 차단 해제</h2>
                        </div>
                        <br>
                        <div>
                            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:10%;">콘텐츠링크</td>
                                    <td style="width:5%;">프로필</td>
                                    <td style="width:10%;">이름</td>
                                    <td style="width:10%;">아이디</td>
                                    <td style="width:45%;">댓글내용</td>
                                    <td style="width:10%;">등록일</td>
                                    <td style="width:10%;">
                                        <button type="button" class="btn btn-primary" style="position: relative; right: 1px; padding: 9px 12px" onclick="unlock_post(0);">전체해제</button>
                                    </td>
                                </tr>
                                <?
                                if($post_count)
                                {
                                    while($row=mysqli_fetch_array($result))
                                    {
                                        ?>
                                        <tr >
                                            <td style=""><?=$row[westory_card_url]?></td>
                                            <td style="text-align: center">
                                                <div style="margin: 5px;width: 38px;height: 38px;border-radius: 50%;overflow: hidden;">
                                                    <img src='<?=$row[profile]?>'>
                                                </div>
                                            </td>
                                            <td style=""><?=$row['mem_name']?></td>
                                            <td style=""><?=$row['mem_id']?></td>
                                            <td style=""><?=$row['content']?></td>
                                            <td style="font-size:11px;"><?=$row[reg_date]?></td>
                                            <td style="font-size:11px;">
                                                <button type="button" class="btn btn-primary" style="position: relative; right: 1px; padding: 9px 12px" onclick="unlock_post('<?=$row['id']?>');">해제</button>
                                            </td>
                                        </tr>
                                        <?
                                        $sort_no--;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="11">
                                            <?
                                            page_f($page,$page2,$intPageCount,"pay_form");
                                            ?>
                                        </td>
                                    </tr>
                                <?
                                }
                                else
                                {
                                    ?>
                                    <tr>
                                        <td colspan="11">
                                            검색된 내용이 없습니다.
                                        </td>
                                    </tr>
                                <?
                                }
                                ?>
                            </table>
                        </div>
                    </form>
                </div>
    </div>
</main><!-- // 컨텐츠 영역 끝 -->
<div id="ajax_div" style="display:none"></div>
<script language="javascript">
    function unlock_post(post_id) {
        if(confirm('댓글차단을 해제하시겠습니까?')) {
            $.ajax({
                type:"POST",
                url:"ajax/add_post.php",
                data:{
                    post_idx : post_id,
                    mode : 'unlock',
                    mem_id : '<?=$_SESSION['iam_member_id']?>'
                },
                success:function(data){
                    alert('댓글차단이 해제되었습니다.');
                    location.reload();
                }
            })

        }
    }
    $(function(){
        $(document).ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1);
        });
    });
</script>
