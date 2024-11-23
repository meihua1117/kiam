<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
if($edit_type == "member_reg_edit" || $edit_type == "member_req_edit" || $edit_type == "member_get_edit"){
  $query_data = "select * from Gn_Member where mem_code='{$mem_code}'";
  $res_data = mysqli_query($self_con,$query_data);
  $data = mysqli_fetch_array($res_data);

  $list_idx = $data['mem_code'];
  $user_id = $data['mem_id']?$data['mem_id']:'';
  $name = $data['mem_name']?$data['mem_name']:'';
  $phone1 = $data['mem_phone']?$data['mem_phone']:'';
  $phone2 = $data['mem_phone1']?$data['mem_phone1']:'';
  $email = $data['mem_email']?$data['mem_email']:'';
  $birthday = $data['mem_birth']?$data['mem_birth']:'';
  $work_type = $data['com_type']?$data['com_type']:'';
  $company_name = $data['zy']?$data['zy']:'';
  $job = $data['mem_job']?$data['mem_job']:'';
  $company_addr = $data['com_add1']?$data['com_add1']:'';
  $home_addr = $data['mem_add1']?$data['mem_add1']:'';
  $memo = $data['mem_memo']?$data['mem_memo']:'';
  $reg_date = $data['first_regist']?$data['first_regist']:'';

  $query = "select card_short_url from Gn_Iam_Name_Card where group_id = 0 and mem_id = '{$data['mem_id']}' order by req_data asc";
  $cres = mysqli_query($self_con,$query);
  $crow = mysqli_fetch_array($cres);
  $card_url = $crow[0];

  $link = "card_list.php?mem_id=".$data['mem_id'];
}
else if($edit_type == "reg_cust_edit"){
  $query_data = "select * from gn_reg_customer where id='{$idx}'";
  $res_data = mysqli_query($self_con,$query_data);
  $data = mysqli_fetch_array($res_data);

  $idx = $data['id'];
  $user_id = $data['mem_id']?$data['mem_id']:'';
  $name = $data['name']?$data['name']:'';
  $phone1 = $data['phone1']?$data['phone1']:'';
  $phone2 = $data['phone2']?$data['phone2']:'';
  $email = $data['email']?$data['email']:'';
  $birthday = $data['birthday']?$data['birthday']:'';
  $work_type = $data['work_type']?$data['work_type']:'';
  $company_name = $data['company_name']?$data['company_name']:'';
  $job = $data['job']?$data['job']:'';
  $company_addr = $data['company_addr']?$data['company_addr']:'';
  $home_addr = $data['home_addr']?$data['home_addr']:'';
  $link = $card_url = $data['link']?$data['link']:'';
  $memo = $data['memo']?$data['memo']:'';
  $reg_date = $data['reg_date']?$data['reg_date']:'';
}
else if($edit_type == "req_cust_edit"){
  $query_data = "select * from Gn_event_request a inner join Gn_event b on a.event_idx=b.event_idx where request_idx='{$idx}'";
  $res_data = mysqli_query($self_con,$query_data);
  $data = mysqli_fetch_array($res_data);

  $idx = $data['request_idx'];
  $user_id = $data['m_id']?$data['m_id']:'';
  $name = $data['name']?$data['name']:'';
  $phone1 = $data['mobile']?$data['mobile']:'';
  $phone2 = $data['mobile1']?$data['mobile1']:'';
  $email = $data['email']?$data['email']:'';
  $birthday = $data['birthday']?$data['birthday']:'';
  $work_type = $data['work_type']?$data['work_type']:'';
  $company_name = $data['company_name']?$data['company_name']:'';
  $job = $data['job']?$data['job']:'';
  $company_addr = $data['addr']?$data['addr']:'';
  $home_addr = $data['addr1']?$data['addr1']:'';
  $link = $card_url = $data['short_url']?$data['short_url']:'';
  $memo = $data['memo']?$data['memo']:'';
  $reg_date = $data['regdate']?$data['regdate']:'';
}
else if($edit_type == "get_cust_edit"){
  $query_data = "select * from crawler_data where seq='{$idx}'";
  $res_data = mysqli_query($self_con,$query_data);
  $data = mysqli_fetch_array($res_data);

  $idx = $data['seq'];
  $user_id = $data['user_id']?$data['user_id']:'';
  $name = $data['ceo']?$data['ceo']:'';
  $phone1 = $data['cell']?$data['cell']:'';
  $phone2 = $data['cell1']?$data['cell1']:'';
  $email = $data['email']?$data['email']:'';
  $birthday = $data['birthday']?$data['birthday']:'';
  $work_type = $data['company_type']?$data['company_type']:'';
  $company_name = $data['company_name']?$data['company_name']:'';
  $job = $data['data_type']?$data['data_type']:'';
  $company_addr = $data['address']?$data['address']:'';
  $home_addr = $data['address1']?$data['address1']:'';
  $link = $card_url = $data['url']?$data['url']:'';
  $memo = $data['memo']?$data['memo']:'';
  $reg_date = $data['regdate']?$data['regdate']:'';
}

?>
<style>
.box-body th {background:#ddd;}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script> 
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
            고객 정보관리
            <small>고객 정보를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">고객 정보관리</li>
          </ol>
        </section>

        <form method="post" id="dForm" name="dForm">
        <input type="hidden" name="idx" value="<?=$idx?>" />
        <input type="hidden" name="list_idx" value="<?=$list_idx?>" />
        <input type="hidden" name="mode" value="edit" />
        <input type="hidden" name="type" value="<?=$edit_type?>" />
        <!-- Main content -->
        <section class="content">
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">고객 상세정보</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="20%">
                      <col width="70%">
                     </colgroup>
                    <tbody>
                      <tr>
                        <th>유저아이디</th>
                        <td>
                            <input type="text" style="width:400px;" name="user_id" id="user_id" value="<?=$user_id?>" readonly> 
                        </td>
                      </tr>
                      <tr>                      
                        <th>이름</th>
                        <td>
                          <input type="text" style="width:400px;" name="name" id="name" value="<?=$name?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>휴대폰</th>
                        <td>
                            <input type="text" style="width:400px;" name="phone1" id="phone1" value='<?=$phone1?>' > 
                        </td>
                      </tr>    
                      <tr>
                        <th>일반폰</th>
                        <td>
                            <input type="text" style="width:500px;" name="phone2" id="phone2" value="<?=$phone2?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>이메일</th>
                        <td>
                            <input type="text" style="width:400px;" name="email" id="email" value="<?=$email?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>생년월일</th>
                        <td>
                            <input type="text" style="width:400px;" name="birthday" id="birthday" value="<?=$birthday?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>업종</th>
                        <td>
                            <input type="text" style="width:400px;" name="work_type" id="work_type" value="<?=$work_type?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>업체명</th>
                        <td>
                            <input type="text" style="width:400px;" name="company_name" id="company_name" value="<?=$company_name?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>직책</th>
                        <td>
                            <input type="text" style="width:400px;" name="job" id="job" value="<?=$job?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>업체주소</th>
                        <td>
                            <input type="text" style="width:400px;" name="company_addr" id="company_addr" value="<?=$company_addr?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>자택주소</th>
                        <td>
                            <input type="text" style="width:400px;" name="home_addr" id="home_addr" value="<?=$home_addr?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>링크</th>
                        <td>
                            <input type="text" style="width:400px;" name="link" id="link" value="<?=$link?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>메모</th>
                        <td>
                          <textarea name="memo" id="memo"  style="width:550px;height:100px" ><?php echo $memo?></textarea>
                        </td>
                      </tr>
                      <tr>
                        <th>등록일시</th>
                        <td>
                            <input type="text" style="width:400px;" name="reg_date" id="reg_date" value="<?=$reg_date?>" > 
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                <?if($pre_page == "reg_list"){
                  $location = "customer_reg_list.php";
                }
                else if($pre_page == "req_list"){
                  $location = "customer_req_list.php";
                }
                else if($pre_page == "get_list"){
                  $location = "customer_get_list.php";
                }
                ?>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='<?=$location?>';return false;"><i class="fa fa-list"></i> 목록</button>
            </div>            
          </div><!-- /.row -->          
        </section><!-- /.content -->
        </form>
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
      </div><!-- /content-wrapper -->
<script language="javascript">
function form_save() {
    $.ajax({
        type:"POST",
        url:"ajax/manage_customers.php",
        data:$('#dForm').serialize(),
        dataType: "json",
        success:function(data){
          alert("저장되었습니다.");
          location.reload();
        },
        error: function(){
            alert('고객조작 실패');
        }
    });
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 250;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
</script>      