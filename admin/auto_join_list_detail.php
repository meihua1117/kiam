<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

if($idx) {
    // 가입 회원 상세 정보
    $query = "select *
                from Gn_event where event_idx='$idx'";
    $res = mysql_query($query);
    $data = mysql_fetch_array($res);
}

?>
<style>
    .box-body th {background:#ddd;}
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script> 

<link rel='stylesheet' id='jquery-ui-css'  href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2' type='text/css' media='all' />
<script
  src="http://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.min.js"
  integrity="sha256-55Jz3pBCF8z9jBO1qQ7cIf0L+neuPTD1u7Ytzrp2dqo="
  crossorigin="anonymous"></script>

    <div class="wrapper">

      <!-- Top 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>      
      
      <!-- Left 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            오토회원 가입 메시지관리
            <small>오토회원 가입 메시지를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">오토회원 가입 메시지관리</li>
          </ol>
        </section>

        <input type="hidden" name="event_idx" id="event_idx" value="<?=$data['event_idx']?>" />
        <!-- Main content -->
        <section class="content">
 
         
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">오토회원 가입 메시지 상세정보</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="30%">
                      <col width="60%">
                     </colgroup>
                    <tbody>
                      <tr>
                        <th>이벤트타이틀</th>
                        <td>
                            <input type="text" style="width:400px;" name="event_title" id="event_title" value="<?=$data['event_title']?>" > 
                        </td>
                      </tr>
                      <tr>                      
                        <th>이벤트메시지</th>
                        <td>
                            <textarea name="event_desc" id="event_desc"  style="width:550px;height:100px" ><?php echo $data['event_desc'];?></textarea>
                        </td>
                      </tr>
                      <tr>
                        <th>카드링크</th>
                        <td>
                          <input type="text" id="card_short_url" value="<?=$data[event_info]?>" hidden>
                          <div id="cardsel1" onclick="limit_selcard1()" style="margin-top:15px;">
                            <?
                            $sql5="select card_short_url,phone_display, card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '$data[m_id]' order by req_data asc";
                            $result5=mysql_query($sql5);
                            $i = 0;
                            while($row5=mysql_fetch_array($result5)) {
                                $order = $i+1;
                                $card_arr = explode(",", $data['event_info']);
                                for($k = 0; $k < count($card_arr); $k++){
                                  if($card_arr[$k] == $order){
                                    $checked = "checked";
                                  break;
                                  }
                                  else{
                                    $checked = "";
                                  }
                                }

                                if($i == 0){
                                    $hidden = "hidden";
                                }
                                else{
                                    $hidden = "";
                                }
                                ?>
                                <input type="checkbox" id="multi_westory_card_url1_<?= $order ?>" name="multi_westory_card_url1"
                                    class="we_story_radio1" <?=$checked?>
                                    value="<?= $order ?>" <? if($row5['phone_display']=="N"
                                ){echo "onclick='locked_card_click();'" ;} ?> <?=$hidden?>
                                    >
                                <span <? if($row5['phone_display']=="N" ){echo "class='locked' title='비공개카드'" ;} ?> <?=$hidden?>>
                                    <?=$order?>번(<?=$row5['card_title']?>)
                                </span>
                                <?
                                $i++;
                            }
                            ?>
                          </div>
                        </td>
                      </tr>      
                      <tr>
                        <th>버튼타이틀</th>
                        <td>
                            <input type="text" style="width:500px;" name="btn_title" id="btn_title" value="<?=$data['event_type']?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>버튼링크</th>
                        <td>
                            <input type="text" style="width:500px;" name="btn_link" id="btn_link" value="<?=$data['event_sms_desc']?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>단축주소</th>
                        <td>
                            <input type="text" style="width:500px;" name="short_url" id="short_url" value="<?=$data['short_url']?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>이벤트신청주소</th>
                        <td>
                            <input type="text" style="width:500px;" name="event_req_link" id="event_req_link" value="<?=$data['event_req_link']?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>조회수</th>
                        <td>
                            <input type="text" style="width:500px;" name="read_cnt" id="read_cnt" value="<?=$data['read_cnt']?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>대상</th>
                        <td>
                            <input type="text" style="width:500px;" name="object" id="object" value="<?=$data['object']?>" > 
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          
            
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="save_edit_ev();"><i class="fa fa-save"></i> 저장</button>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='auto_join_list_member.php';return false;"><i class="fa fa-list"></i> 목록</button>
            </div>            
                        
          </div><!-- /.row -->          

        
          
          
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->


      <!-- Footer -->
      
<script language="javascript">
function save_edit_ev(){
    id = $("#event_idx").val();
    title = $("#event_title").val();
    desc = $("#event_desc").val();
    card_short_url = $("#card_short_url").val();
    btn_title = $("#btn_title").val();
    btn_link = $("#btn_link").val();
    short_url = $("#short_url").val();
    event_link = $("#event_req_link").val();
    read_cnt = $("#read_cnt").val();
    object = $("#object").val();

    $.ajax({
        type:"POST",
        url:"/ajax/edit_event.php",
        dataType:"json",
        data:{save:true, id:id, title:title, desc:desc, card_short_url:card_short_url, btn_title:btn_title, btn_link:btn_link, short_url:short_url, read_cnt:read_cnt, object:object, event_link:event_link},
        success: function(data){
            console.log(data);
            if(data == 1){
                alert('수정 되었습니다.');
                window.location.reload();
            }
        }
    })
} 

$( ".date" ).datepicker({
 dateFormat: "yy-mm-dd",
 defaultDate: "+1w"
});

//시작일. 끝일보다는 적어야 되게끔
$( "#send_start_date" ).datepicker({
 dateFormat: "yy-mm-dd",
 defaultDate: "+1w",
 onClose: function( selectedDate ) {
  $("#send_start_date").datepicker( "option", "minDate", selectedDate );
 }
});
 
//끝일. 시작일보다는 길어야 되게끔
$( "#send_end_date" ).datepicker({
 dateFormat: "yy-mm-dd",
 defaultDate: "+1w",
 onClose: function( selectedDate ) {
  $("#send_end_date").datepicker( "option", "maxDate", selectedDate );
 }
});

function limit_selcard1(){
    var sel_card1 = new Array();
    var cnt1;
    $('input[class=we_story_radio1]:checked').each(function() {
        var idVal1 = $(this).attr("value");
        // console.log(idVal);
        cnt1 = sel_card1.push(idVal1);
        if(cnt1 > 4){
            alert('최대 4개까지 선택할수 있습니다.');
            $('input[id=multi_westory_card_url1_'+idVal1+']').prop("checked", false);
            return;
        }
        $("#card_short_url").val(sel_card1.join(","));
    });
}
</script>      
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      