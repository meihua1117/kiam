<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");

if($idx) {
    // 가입 회원 상세 정보
    $query = "select *
                from Gn_Iam_Contents_Gwc where idx='$idx'";
    $res = mysqli_query($self_con,$query);
    $data = mysqli_fetch_array($res);

    $sql_mem = "select gwc_provider_name, gwc_worker_no, gwc_worker_img, gwc_worker_state, mem_id, mem_code, mem_name, mem_phone, mem_email, mem_add1, bank_name, bank_owner, bank_account from Gn_Member where mem_id='{$data['mem_id']}'";
    $res_mem = mysqli_query($self_con,$sql_mem);
    $row_mem = mysqli_fetch_array($res_mem);

    $sql_provider_id = "select mem_id, mem_code, mem_name, mem_phone, mem_email, mem_add1, bank_name, bank_owner, bank_account from Gn_Member where mem_code='{$data['delivery_id_code']}'";
    $res_provider_id = mysqli_query($self_con,$sql_provider_id);
    $row_provider_id = mysqli_fetch_array($res_provider_id);
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
            굿마켓 상품 관리
            <small>굿마켓 상품 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">굿마켓 상품 관리</li>
          </ol>
        </section>
        <?if($member != "Y"){?>
        <form method="post" id="req_provider_form" name="req_provider_form" action="/iam/ajax/product_mng.php"  enctype="multipart/form-data">
        <section class="content">
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">굿마켓 상품 상세정보</h3>
                </div><!-- /.box-header -->                
                <div style="padding:20px;">
                  <input type="hidden" id="mode" name="mode" value="req_provider">
                  <input type="hidden" id="gongup_id" name="gongup_id" value="<?=$row_mem['mem_id']?>">
                  <input type="hidden" id="gwc_worker_state" name="gwc_worker_state" value="<?=$row_mem[gwc_worker_state]?'1':'0'?>">
                  <div style="display:flex;margin-top:10px;">
                      공급사명:<input type="text" name="provider_name" id="provider_name" value="<?=$row_mem[gwc_provider_name]?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 45px;">
                  </div>
                  <div style="display:<?=$row_mem[gwc_worker_state]?'flex':'none'?>;margin-top:10px;" id="worker_no_side">
                      사업자등록번호:<input type="text" name="worker_no" id="worker_no" value="<?=$row_mem[gwc_worker_no]?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 6px;">
                  </div>
                  <div style="display:<?=$row_mem[gwc_worker_state]?'flex':'none'?>;margin-top:10px;" id="worker_img_side">
                      사업자등록증:<input type="file" name="worker_img" id="worker_img" value="<?=$row_mem[gwc_worker_img]?>" style="width: 200px;margin-left: 20px;">
                  </div>
                  <?if($row_mem[gwc_worker_img]){?>
                  <img src="<?=$row_mem[gwc_worker_img]?>" style="width:80px;margin-left:100px;">
                  <?}?>
                  <div style="margin-top: 10px;width: 300px;text-align: left;height: 25px;">
                      <input type="checkbox" name="gwc_worker_state_" id="gwc_worker_state_" <?=$row_mem[gwc_worker_state]?'checked':''?> style="vertical-align: text-top;margin-left:100px;" onclick="gwc_worker()"><span style="margin-left:7px;">사업자</span>
                      <a href="javascript:save_req_provider();" id="save_req_provider_side" style="background-color: black;color: white;padding: 5px;border-radius: 7px;margin: 5px;cursor: pointer;float:right;">저장</a>
                  </div>
                </div>
              </div>
          </div>
        </section>
        </form>
        <?}?>
        <form method="post" id="dForm" name="dForm" action="/iam/ajax/contents.proc.php"  enctype="multipart/form-data">
        <input type="hidden" name="contents_idx" value="<?=$data['idx']?>" />
        <input type="hidden" name="post_type" value="edit" />
        <input type="hidden" name="contents_type" value="3" />
        <input type="hidden" name="mem_id" value="<?=$data['mem_id']?>" />
        <input type="hidden" name="admin" value="1" />
        <input type="hidden" name="provider" value="<?=$_GET[provider]?>" />
        <input type="hidden" name="member" value="<?=$_GET[member]?>" />
        <input type="hidden" name="card_short_url" value="<?=$data['card_short_url']?>" />
        <input type="hidden" name="westory_card_url" value="<?=$data['westory_card_url']?>" />
        <input type="hidden" name="contents_url_title" value="<?=$data['contents_url_title']?>" />
        <input type="hidden" name="except_keyword" value="<?=$data['except_keyword']?>" />
        <input type="hidden" name="contents_display" value="<?=$data['contents_display']?>" />
        <input type="hidden" name="contents_westory_display" value="<?=$data['contents_westory_display']?>" />
        <input type="hidden" name="contents_type_display" value="<?=$data['contents_type_display']?>" />
        <input type="hidden" name="contents_user_display" value="<?=$data['contents_user_display']?>" />
        <input type="hidden" name="contents_footer_display" value="<?=$data['contents_footer_display']?>" />
        <input type="hidden" name="contents_share_text" value="<?=$data['contents_share_text']?>" />
        <input type="hidden" name="card_idx" value="<?=$data['card_idx']?>" />
        <input type="hidden" name="init_reduce_val" value="<?=$data['init_reduce_val']?>" />
        <input type="hidden" name="reduce_val" value="<?=$data['reduce_val']?>" />
        <input type="hidden" name="landing_mode" value="<?=$data['landing_mode']?>" />
        <input type="hidden" name="contents_iframe" value="<?=$data['contents_iframe']?>" />
        <input type="hidden" name="source_iframe" value="<?=$data['source_iframe']?>" />
        <!-- Main content -->
        <section class="content">
 
         
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">굿마켓 상품 상세정보</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="30%">
                      <col width="60%">
                     </colgroup>
                    <tbody>
                      <tr>
                        <th>콘텐츠 제목</th>
                        <td>
                            <input type="text" style="width:400px;" name="contents_title" id="contents_title" value="<?=$data['contents_title']?>" > 
                        </td>
                      </tr>
                      <tr>                      
                        <th>콘텐츠 내용</th>
                        <td>
                            <textarea name="contents_desc" id="contents_desc"  style="width:550px;height:100px" ><?php echo $data['contents_desc'];?></textarea>
                        </td>
                      </tr>
                      <tr>                      
                        <th>상품구분</th>
                        <td>
                          <select name="gwc_con_state" class="select">
                            <option value="1" <?php echo $data['gwc_con_state'] == 1?"selected":"";?>>굿마켓용</option>
                            <option value="2" <?php echo $data['gwc_con_state'] == 2?"selected":"";?>>공동구매용</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <th>이미지</th>
                        <td>
                          <input type="text" style="width:500px;" name="contents_img_url" id="contents_img_url" value="<?=$data['contents_img']?>" >
                        </td>
                      </tr>      
                      <tr>
                        <th>링크주소</th>
                        <td>
                            <input type="text" style="width:500px;" name="contents_url" id="contents_url" value="<?=$data['contents_url']?>" >
                        </td>
                      </tr>                  
                      <tr>                      
                        <th>콘텐츠열기</th>
                        <td>
                          <select name="open_type" class="select">
                            <option value="1" <?php echo $data['open_type'] == 1?"selected":"";?>>내부열기</option>
                            <option value="2" <?php echo $data['open_type'] == 2?"selected":"";?>>새창열기</option>
                          </select>
                        </td>
                      </tr>
                      <tr>                      
                        <th>상품코드</th>
                        <td>
                          <input type="text" style="width:500px;" name="product_code" id="product_code" value="<?=$data['product_code']?>" >
                        </td>
                      </tr>
                      <tr>                      
                        <th>상품모델명</th>
                        <td>
                          <input type="text" style="width:500px;" name="product_model_name" id="product_model_name" value="<?=$data['product_model_name']?>" >
                        </td>
                      </tr>
                      <tr>                      
                        <th>상품분류정보</th>
                        <td>
                          <input type="text" style="width:500px;" name="product_seperate" id="product_seperate" value="<?=$data['product_seperate']?>" >
                        </td>
                      </tr>
                      <tr>                      
                        <th>시중가</th>
                        <td>
                          <input type="text" style="width:500px;" name="contents_price" id="contents_price" value="<?=$data['contents_price']?>" >
                        </td>
                      </tr>
                      <tr>                      
                        <th>할인가</th>
                        <td>
                          <input type="text" style="width:500px;" name="contents_sell_price" id="contents_sell_price" value="<?=$data['contents_sell_price']?>" >
                        </td>
                      </tr>
                      <tr>                      
                        <th>공급가</th>
                        <td>
                          <input type="text" style="width:500px;" name="send_provide_price" id="send_provide_price" value="<?=$data['send_provide_price']?>" >
                        </td>
                      </tr>
                      <tr>                      
                        <th>생산가</th>
                        <td>
                          <input type="text" style="width:500px;" name="prod_manufact_price" id="prod_manufact_price" value="<?=$data['prod_manufact_price']?>" >
                        </td>
                      </tr>
                      <tr>                      
                        <th>배송료</th>
                        <td>
                          <input type="text" style="width:500px;" name="send_salary_price" id="send_salary_price" value="<?=$data['send_salary_price']?>" >
                        </td>
                      </tr>
                      <tr>
                        <th>카테고리</th>
                        <td>
                          <?
                          $sql5="select card_short_url,card_title from Gn_Iam_Name_Card where mem_id = 'iamstore' order by req_data asc";
                          $result5=mysqli_query($self_con,$sql5);
                          $i = 0;
                          while($row5=mysqli_fetch_array($result5)) {
                              ?>
                              <input type="radio" name="gwc_card_url"
                                      class="my_info_check"
                                      value="<?= $row5['card_short_url'] ?>" <?=$row5['card_short_url']==$data[westory_card_url]?'checked':''?>>
                              <?
                                  echo($i+1);
                              echo "(".$row5['card_title'].")";?>
                              <?$i++;
                          }
                          ?>
                        </td>
                      </tr>
                      <?if($member != "Y"){?>
                      <tr>                      
                        <th>배송정보</th>
                        <td>
                          <input type="hidden" id="check_deliver_id_state" name="check_deliver_id_state" value="N">
                          <input type="hidden" id="deliver_id_code" name="deliver_id_code" value="<?=$row_provider_id['mem_code']?>">
                          <input type="checkbox" id="same_gonggupsa" name="same_gonggupsa" onclick="self_deliver()" style="vertical-align: text-top;margin-right:5px;">공급사와 동일
                          <div style="display:flex;margin-top:10px;">
                              아이디:<input type="text" name="deliver_id" id="deliver_id" value="<?=$row_provider_id['mem_id']?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 20px;"><a href="javascript:check_deliver_id();" id="check_deliver_id" style="background-color: #82c836;color: white;padding: 2px 5px;margin: -1px 5px;cursor: pointer;">확인</a>
                          </div>
                          <div style="display:flex;margin-top:10px;">
                              이름:<input type="text" name="deliver_name" id="deliver_name" value="<?=$row_provider_id['mem_name']?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 33px;" readonly>
                          </div>
                          <div style="display:flex;margin-top:10px;">
                              핸드폰:<input type="text" name="deliver_phone" id="deliver_phone" value="<?=$row_provider_id['mem_phone']?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 20px;" readonly>
                          </div>
                          <div style="display:flex;margin-top:10px;">
                              주소:<input type="text" name="deliver_addr" id="deliver_addr" value="<?=$row_provider_id['mem_add1']?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 33px;" readonly>
                          </div>
                          <div style="display:flex;margin-top:10px;">
                              이메일:<input type="text" name="deliver_email" id="deliver_email" value="<?=$row_provider_id['mem_email']?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 20px;" readonly>
                          </div>
                          <div style="display:flex;margin-top:10px;">
                              입금계좌:<input type="text" name="deliver_bank" id="deliver_bank" value="<?=$row_provider_id['bank_name']?>" style="width: 70px;height: 15px;padding: 10px;margin-left: 8px;" readonly>
                              <input type="text" name="deliver_owner" id="deliver_owner" value="<?=$row_provider_id['bank_owner']?>" style="width: 70px;height: 15px;padding: 10px;" readonly>
                              <input type="text" name="deliver_account" id="deliver_account" value="<?=$row_provider_id['bank_account']?>" style="width: 70px;height: 15px;padding: 10px;" readonly>
                          </div>
                        </td>
                      </tr>
                      <?}?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          
            
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                <?if($_GET['provider'] == "Y"){?>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='card_gwc_contents_list_provider.php';return false;"><i class="fa fa-list"></i> 목록</button>
                <?}
                else{?>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='card_gwc_contents_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
                <?}?>
            </div>            
                        
          </div><!-- /.row -->          

        
          
          
        </section><!-- /.content -->
        </form>
      </div><!-- /.content-wrapper -->


      <!-- Footer -->
      
<script language="javascript">
$(document).ready(function(){
  if($("#deliver_id").val() != '' && $("#deliver_name").val() != '' && $("#deliver_phone").val() != '' && $("#deliver_addr").val() != '' && $("#deliver_email").val() != '' && $("#deliver_bank").val() != '' && $("#deliver_owner").val() != '' && $("#deliver_account").val() != ''){
    $("#check_deliver_id_state").val('Y');
  }
})
function form_save() {
    if($('#title').val() == "") {
        alert('메시지 타이틀을 입력해주세요.');
        return;
    }
    if($('#content').val() == "") {
        alert('메시지 콘텐츠를 입력해주세요.');
        return;
    }
    if($("#deliver_id").val() == ''){
        alert('배송정보를 확인해주세요.');
        return;
    }
    if($("#check_deliver_id_state").val() == "N"){
        alert('배송정보를 모두 채워야합니다. 배송자 아이디 회원정보를 수정해주세요.');
    }
    $('#dForm').submit();
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
function gwc_worker(){
    if($("#gwc_worker_state_").prop('checked')){
        $("#worker_no_side").css('display', 'flex');
        $("#worker_img_side").css('display', 'flex');
        // $("#save_req_provider_side").show();
        $("#gwc_worker_state").val(1);
    }
    else{
        $("#worker_no_side").css('display', 'none');
        $("#worker_img_side").css('display', 'none');
        // $("#save_req_provider_side").hide();
        $("#gwc_worker_state").val(0);
    }
}

function check_deliver_id(){
    var req_deliver_id = $("#deliver_id").val();
    if(req_deliver_id == ''){
        alert('아이디를 입력해 주세요.');
        $("#deliver_id").focus();
        $("#check_deliver_id_state").val('N');
        return;
    }
    else{
        $.ajax({
            type:"POST",
            url:"/ajax/get_mem_address.php",
            dataType:"json",
            data:{
                deliver_id:req_deliver_id,
                mode:"check_deliver_id"
            },
            success: function(data){
                if(data.result == "0"){
                    alert('정확한 아이디를 입력하세요.');
                    $("#check_deliver_id_state").val('N');
                    return;
                }
                else{
                    $("#deliver_id_code").val(data.mem_code);
                    $("#deliver_id").val(data.mem_id);
                    $("#deliver_name").val(data.mem_name);
                    $("#deliver_phone").val(data.mem_phone);
                    $("#deliver_addr").val(data.mem_add1);
                    $("#deliver_email").val(data.mem_email);
                    $("#deliver_bank").val(data.bank_name);
                    $("#deliver_owner").val(data.bank_owner);
                    $("#deliver_account").val(data.bank_account);
                    if($("#deliver_id").val() != '' && $("#deliver_name").val() != '' && $("#deliver_phone").val() != '' && $("#deliver_addr").val() != '' && $("#deliver_email").val() != '' && $("#deliver_bank").val() != '' && $("#deliver_owner").val() != '' && $("#deliver_account").val() != ''){
                        $("#check_deliver_id_state").val('Y');
                    }
                    else{
                        $("#check_deliver_id_state").val('N');
                    }
                }
            }
        });
    }
}
function self_deliver(){
    if($("#same_gonggupsa").prop('checked')){
        $("#deliver_id").val('<?=$row_mem['mem_id']?>');
        $("#deliver_name").val('<?=$row_mem['mem_name']?>');
        $("#deliver_phone").val('<?=$row_mem['mem_phone']?>');
        $("#deliver_addr").val('<?=$row_mem['mem_add1']?>');
        $("#deliver_email").val('<?=$row_mem['mem_email']?>');
        $("#deliver_bank").val('<?=$row_mem['bank_name']?>');
        $("#deliver_owner").val('<?=$row_mem['bank_owner']?>');
        $("#deliver_account").val('<?=$row_mem['bank_account']?>');
        $("#check_deliver_id").hide();
        $("#deliver_id_code").val('<?=$row_mem['mem_code']?>');
        if($("#deliver_id").val() != '' && $("#deliver_name").val() != '' && $("#deliver_phone").val() != '' && $("#deliver_addr").val() != '' && $("#deliver_email").val() != '' && $("#deliver_bank").val() != '' && $("#deliver_owner").val() != '' && $("#deliver_account").val() != ''){
            $("#check_deliver_id_state").val('Y');
        }
        else{
            $("#check_deliver_id_state").val('N');
        }
    }
    else{
        $("#deliver_id").val('');
        $("#deliver_name").val('');
        $("#deliver_phone").val('');
        $("#deliver_addr").val('');
        $("#deliver_email").val('');
        $("#deliver_bank").val('');
        $("#deliver_owner").val('');
        $("#deliver_account").val('');
        $("#deliver_id_code").val('');
        $("#check_deliver_id_state").val('N');
        $("#check_deliver_id").show();
    }
}
function save_req_provider(){
    $("#req_provider_form").submit();
}
</script>      
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      