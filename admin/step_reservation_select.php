<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";

$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
if($search != '') {
    $where = "a.m_id like '%{$search}%' OR a.reservation_title like '%{$search}%' OR a.reservation_desc like '%{$search}%'";
    $query = "select * from Gn_event_sms_info a WHERE {$where} order by sms_idx desc";
}else {
    $query = "select * from Gn_event_sms_info a order by sms_idx desc";
}
$res = mysqli_query($self_con,$query);
?>

<div class="row">
    <div class="col-md-12" style="padding:30px;">
        <div class="row">
            <div class="col-md-12">
                <button type="button" onclick="onSelectMessage()" class="btn btn-success btn-sm" style="float:right;margin-left:20px;">선택</button>
                <form method="get" action="step_reservation_select.php">
                    <div class="input-group" style="width: 250px;float:right;">
                    <input type="text" name="search" id="search_key" class="form-control input-sm pull-right" placeholder="ID/제목/설명" value="<?=$search?>">
                    <div class="input-group-btn">
                        <button class="btn btn-sm btn-primary">조회하기<i class="fa fa-search"></i></button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
        <section class="">
            <p><h3 class="section-title" style="margin-top:10px;">스텝예약 메시지 리스트</h3></p>
        </section>
        <table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width:2%;"></td>
                <td style="width:6%;">No</td>
                <td style="width:6%;">ID</td>
                <td style="width:15%;">메시지세트제목</td>
                <td style="width:15%;">메시지세트설명</td>
                <td style="width:6%;">단계</td>
                <td style="width:9%;">등록일</td>
                <td style="width:9%;">관리</td>
              </tr>
              <?php $i = 0; while($row = mysqli_fetch_array($res)) {
                  $sql="select count(*) as cnt from Gn_event_sms_step_info where sms_idx='$row[sms_idx]'";
                  $sresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				                    
                  $srow = mysqli_fetch_array($sresult);
              ?>
                <tr>
                    <td><input type="checkbox" class="check_step" key = "<?=$row[sms_idx]?>" title="<?=$row[reservation_title]?>"></td>
                    <td><?=$i?></td>
                    <td><?=$row['m_id']?></td>
                    <td style="font-size:12px;"><?=$row[reservation_title]?></td>
                    <td><?=$row[reservation_desc]?></td>
                    <td><?=$srow['cnt']?></td>
                    <td><?=$row[regdate]?></td>
                    <td>
                        <a href="/mypage_reservation_create.php?sms_idx=<?=$row['sms_idx']?>">수정</a>/<a href="javascript:;;" onclick="deleteRow('<?=$row['sms_idx']?>')">삭제</a>
                    </td>
                </tr>
              <?php $i++; } ?>
        </table>
        <div class="row">
            <div class="col-md-12">
                <button type="button" onclick="onSelectMessage()" class="btn btn-success btn-sm" style="float:right;">선택</button>
            </div>
        </div>
    </div>
</div>
<?
include_once "_foot.php";
?>

<script>
    var checked_key = "";
    var checked_title = "";
    $(".check_step").on('click', function(){
        if($(this).prop('checked')) {
            $(".check_step").prop('checked', false);
            $(this).prop('checked', true);
            checked_key = $(this).attr('key');
            checked_title = $(this).attr('title');
        }else {
            checked_key = "";
            checked_title = "";
        }
    });
    
    function onSelectMessage() {
        if(checked_key == "") {
            alert('발송할 메시지를 선택해주세요.');
            return;
        }
        opener.step_idx.value = checked_key;
        opener.step_title.value = checked_title;
        $(opener.btnSendText).css('display', 'none');
        $(opener.btnSendStep).css('display', 'block');
        window.close();
    }
    function deleteRow(sms_idx) {
        if(confirm('삭제하시겠습니까?')) {
            $.ajax({
                type:"POST",
                url:"/mypage.proc.php",
                data:{
                    mode : "reservation_del",
                    sms_idx: sms_idx
                    },
                success:function(data){
                    $("#ajax_div").html(data);
                    alert('삭제되었습니다.');
                    location.reload();
                    }
                });
                return false;
        }
    }
</script>