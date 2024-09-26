<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
if($idx) {
    // 가입 회원 상세 정보
    $query = "select *
                from Gn_Iam_Service where idx='$idx'";
    $res = mysqli_query($self_con, $query);
    $data = mysqli_fetch_array($res);
	
	for($i = 1; $i <= 6; $i++)
	{
		$temp = $data['content'.$i.'_idx'];
		
		$query = "select * from Gn_Iam_Contents where idx='$temp'";
		$res2 = mysqli_query($self_con, $query);
		$data2 = mysqli_fetch_array($res2);

		$data['content'.$i.'_title'] = $data2['contents_title'];
		$data['content'.$i.'_img'] = $data2['contents_img'];
		$data['content'.$i.'_link'] = $data2[contents_url];
		$data['content'.$i.'_exp'] = $data2[contents_desc];
	}
	
	$query = "select * from Gn_Iam_Name_Card where idx='$data[profile_idx]'";
	$res2 = mysqli_query($self_con, $query);
	$data2 = mysqli_fetch_array($res2);
	
	$data['namecard_logo'] = $data2[profile_logo];
	$data['profile_name'] = $data2['card_name'];
	$data['profile_phone'] = $data2['card_phone'];
	$data['profile_add'] = $data2['card_addr'];
	$data['profile_email'] = $data2[card_email];
	$data['profile_rank'] = $data2['card_position'];
	$data['profile_info'] = $data2[story_myinfo];
	$data['profile_site1'] = $data2[story_online1];
	$data['profile_site2'] = $data2[story_online2];
}

?>
<style>
    .box-body th {background:#ddd;width:150px;}
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script> 

<link rel='stylesheet' id='jquery-ui-css'  href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2' type='text/css' media='all' />


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
            아이엠 분양사 유저정보관리
            <small>아이엠분양사 유저정보를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">분양사 유저정보</li>
          </ol>
        </section>
 

        <form method="post" id="dForm" name="dForm" action="/admin/ajax/service_Iam_save.php"  enctype="multipart/form-data">
        <input type="hidden" name="ct1" value="<?=$data['content1_idx']?>" />
		<input type="hidden" name="ct2" value="<?=$data['content2_idx']?>" />
		<input type="hidden" name="ct3" value="<?=$data['content3_idx']?>" />
		<input type="hidden" name="ct4" value="<?=$data['content4_idx']?>" />
		<input type="hidden" name="ct5" value="<?=$data['content5_idx']?>" />
		<input type="hidden" name="ct6" value="<?=$data['content6_idx']?>" />
		<input type="hidden" name="profile_idx" value="<?=$data['profile_idx']?>" />
		
		<input type="hidden" name="idx" value="<?=$data['idx']?>" />
        <input type="hidden" name="mode" value="<?=$data['idx']?"update":"insert"?>" />
        <!-- Main content -->
        <section class="content">
 
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
              </div>            
          </div>
          
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">아이엠유저정보</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
                    <tbody>

                      <tr>
                      <th> 홈페이지제목</th>
                        <td>
                            <input type="text" style="width:300px;" name="web_theme" id="web_theme" value="<?=$data['web_theme']?>" > 
                            </td>            
                      </tr>                  

                      <tr>
                      <th >아이엠로고</th>
                        <td >
                            <input type="file" name="head_logo">
                            <?php if($data['head_logo'] != "") {?>
                            <img src="<?=$data['head_logo']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>                  
                        <tr>
                        <th>홈아이콘링크</th>
                        <td>
                            <input type="text" style="width:300px;" name="home_link" id="home_link" value="<?=$data['home_link']?>" > 
                            </td>            
                        </tr>                  


                      <tr>
                        <th >대표디폴트이미지1</th>
                        <td >
                            <input type="file" name="main_img1">
                            <?php if($data['main_img1'] != "") {?>
                            <img src="<?=$data['main_img1']?>" style="width:120px">
                            <?php }?>
                        </td>
                      </tr>     

                      <tr>
                        <th >대표디폴트이미지2</th>
                        <td >
                            <input type="file" name="main_img2">
                            <?php if($data['main_img2'] != "") {?>
                            <img src="<?=$data['main_img2']?>" style="width:120px">
                            <?php }?>
                        </td>   
                      </tr>  

                      <tr>
                        <th >대표디폴트이미지3</th>
                        <td >
                            <input type="file" name="main_img3">
                            <?php if($data['main_img3'] != "") {?>
                            <img src="<?=$data['main_img3']?>" style="width:120px">
                            <?php }?>
                        </td>                           
                     </tr>  
                     <tr>
                        <th>명함스토리1번</th>
                        <td colspan="3">
                            <input type="text" style="width:300px;height:100px;" name="story_data" id="story_data" value="<?=$data['story_data']?>" > 
                        </td>
                      </tr> 
                      <tr>
                        <th>명함스토리2번</th>
                        <td colspan="3">
                            <input type="text" style="width:300px;height:100px;" name="story_data" id="story_data" value="<?=$data['story_data']?>" > 
                        </td>
                      </tr> 
                      <tr>
                        <th>명함스토리3번</th>
                        <td colspan="3">
                            <input type="text" style="width:300px;height:100px;" name="story_data" id="story_data" value="<?=$data['story_data']?>" > 
                        </td>
                      </tr> 

                     <tr>       
                      <th >명함로고</th>
                        <td >							
                            <input type="file" name="namecard_logo">
                            <?php if($data['namecard_logo'] != "") {?>
                            <img src="<?=$data['namecard_logo']?>" style="width:120px">
                            <?php }?>
                        </td>
                      </tr>  

                      <tr>
                      <th>프로필이름</th>
                        <td>
                            <input type="text" style="width:280px;" name="profile_name" id="profile_name" value="<?=$data['profile_name']?>" > 
                        </td>
                        </tr>  
                        <tr>
                        <th>프로필소속직책</th>
                        <td>
                            <input type="text" style="width:280px;" name="profile_rank" id="profile_rank" value="<?=$data['profile_rank']?>" > 
                        </td>
                        <tr>
                        <th>프로필자기소개</th>
                        <td>
                            <input type="text" style="width:280px;" name="profile_info" id="profile_info" value="<?=$data['profile_info']?>" > 
                        </td>
                        </tr>


                        <th>프로필휴대폰</th>
                        <td>
                            <input type="text" style="width:280px;" name="profile_phone" id="profile_phone" value="<?=$data['profile_phone']?>" > 
                        </td>
                        </tr>
                        <tr>
                        <th>프로필주소</th>
                        <td>
                            <input type="text" style="width:280px;" name="profile_add" id="profile_add" value="<?=$data['profile_add']?>" > 
                        </td>
                        </tr>

                         
                        <tr>
                        <th>프로필이메일</th>
                        <td>
                            <input type="text" style="width:280px;" name="profile_email" id="profile_email" value="<?=$data['profile_email']?>" > 
                        </td>
                         
                        <tr>
                        <th>프로필홈피1</th>
                        <td>
                            <input type="text" style="width:280px;" name="profile_site1" id="profile_site1" value="<?=$data['profile_site1']?>" > 
                        <tr>
                        </td>
                        <th>프로필홈피2</th>
                        <td>
                            <input type="text" style="width:280px;" name="profile_site2" id="profile_site2" value="<?=$data['profile_site2']?>" > 
                        </td>
                        </tr>

                      <tr>
                        <th>keywords</th>
                        <td colspan="3">
                            <input type="text" style="width:300px; height:100px;" name="keywords" id="keywords" value="<?=$data['keywords']?>" > 
                        </td>
                      </tr>


                      <tr>
                      <th>컨텐츠1번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="content1_title" id="content1_title" value="<?=$data['content1_title']?>" > 
                        </td>

                        <tr>
                        <th>컨텐츠1번 이미지</th>
                        <td>
                        <input type="file" name="content1_img">
                            <?php if($data['content1_img'] != "") {?>
                            <img src="<?=$data['content1_img']?>" style="width:120px">
                            <?php }?>
                        </td>
                        <tr>
                        <th>컨텐츠1번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="content1_link" id="content1_link" value="<?=$data['content1_link']?>" > 
                        </td>
                        </tr>
                        <tr>
                        <th>컨텐츠1번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="content1_exp" id="content1_exp" value="<?=$data['content1_exp']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠2번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="content2_title" id="content2_title" value="<?=$data['content2_title']?>" > 
                        </td>
                        </tr>
                        <tr>
                        <th>컨텐츠2번 이미지</th>
                        <td>
                        <input type="file" name="content2_img">
                            <?php if($data['content2_img'] != "") {?>
                            <img src="<?=$data['content2_img']?>" style="width:120px">
                            <?php }?>
                        </td>

                        </tr>

                        <tr>
                        <th>컨텐츠2번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="content2_link" id="content2_link" value="<?=$data['content2_link']?>" > 
                        </td>

                        </tr>

                        <tr>
                        <th>컨텐츠2번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="content2_exp" id="content2_exp" value="<?=$data['content2_exp']?>" > 
                        </td>
                        </tr>    

                        <tr>
                        <th>컨텐츠3번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="content3_title" id="content3_title" value="<?=$data['content3_title']?>" > 
                        </td>
                        </tr>    

                        <tr>
                        <th>컨텐츠3번 이미지</th>
                        <td>
                        <input type="file" name="content3_img">
                            <?php if($data['content3_img'] != "") {?>
                            <img src="<?=$data['content3_img']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>    

                        <tr>
                        <th>컨텐츠3번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="content3_link" id="content3_link" value="<?=$data['content3_link']?>" >
                        </tr>    
                        <tr>
                        <th>컨텐츠3번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="content3_exp" id="content3_exp" value="<?=$data['content3_exp']?>" > 
                        </td>
                        </tr>    


                        <tr>
                        <th>컨텐츠4번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="content4_title" id="content4_title" value="<?=$data['content4_title']?>" > 
                        </td>
                        </tr>
                        <tr>
                        <th>컨텐츠4번 이미지</th>
                        <td>
                        <input type="file" name="content4_img">
                            <?php if($data['content4_img'] != "") {?>
                            <img src="<?=$data['content4_img']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>
                        <tr>     
                        <th>컨텐츠4번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="content4_link" id="content4_link" value="<?=$data['content4_link']?>" > 
                        </td>
                        </tr>
                        <tr>
                        <th>컨텐츠4번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="content4_exp" id="content4_exp" value="<?=$data['content4_exp']?>" > 
                        </td>
                        </tr>    

                        <tr>
                        <th>컨텐츠5번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="content5_title" id="content5_title" value="<?=$data['content5_title']?>" > 
                        </td>
                        </tr>    

                        <tr>
                        <th>컨텐츠5번 이미지</th>
                        <td>
                        <input type="file" name="content5_img">
                            <?php if($data['content5_img'] != "") {?>
                            <img src="<?=$data['content5_img']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>    

                        <tr>
                        <th>컨텐츠5번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="content5_link" id="content5_link" value="<?=$data['content5_link']?>" > 
                        </td>
                        </tr>    

                        <tr>
                        <th>컨텐츠5번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="content5_exp" id="content5_exp" value="<?=$data['content5_exp']?>" > 
                        </td>
                        </tr>    

                        <tr>
                        <th>컨텐츠6번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="content6_title" id="content6_title" value="<?=$data['content6_title']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠6번 이미지</th>
                        <td>
                        <input type="file" name="content6_img">
                            <?php if($data['content6_img'] != "") {?>
                            <img src="<?=$data['content6_img']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>
                        <tr>
                        <th>컨텐츠6번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="content6_link" id="content6_link" value="<?=$data['content6_link']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠6번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="content6_exp" id="content6_exp" value="<?=$data['content6_exp']?>" > 
                        </td>
                        </tr>    
                        <tr>
                        <th >푸터로고</th>
                        <td >
                            <input type="file" name="footer_logo">
                            <?php if($data['footer_logo'] != "") {?>
                            <img src="<?=$data['footer_logo']?>" style="width:120px">
                            <?php }?>
                        </td>
                       </tr>
                      
                        <tr>
                        <th>푸터링크</th>
                        <td>
                            <input type="text" style="width:110px;" name="footer_link" id="footer_link" value="<?=$data['footer_link']?>" > 
                            </td>            

                       </tr>
                                                 
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          
            
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='service_Iam_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
            </div>            
                        
          </div><!-- /.row -->          

        
          
          
        </section><!-- /.content -->
        </form>
      </div><!-- /content-wrapper -->


      <!-- Footer -->
      
<script language="javascript">
function form_save() {
    if($('#domain').val() == "") {
        alert('도메인을 입력해주세요.');
        return;
    }    
    $('#dForm').submit();
}    
 

</script>      
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
<script language="javascript">
jQuery(function($){
 $.datepicker.regional['ko'] = {
  closeText: '닫기',
  prevText: '이전달',
  nextText: '다음달',
  currentText: 'X',
  monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
  '7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
  monthNamesShort: ['1월','2월','3월','4월','5월','6월',
  '7월','8월','9월','10월','11월','12월'],
  dayNames: ['일','월','화','수','목','금','토'],
  dayNamesShort: ['일','월','화','수','목','금','토'],
  dayNamesMin: ['일','월','화','수','목','금','토'],
  weekHeader: 'Wk',
  dateFormat: 'yy-mm-dd',
  firstDay: 0,
  isRTL: false,
  showMonthAfterYear: true,
  yearSuffix: ''};
 $.datepicker.setDefaults($.datepicker.regional['ko']);

    $('#contract_start_date').datepicker({
        showOn: 'button',
        buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
        buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });
    $('#contract_end_date').datepicker({
        showOn: 'button',
        buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
        buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });	
});
</script>      
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      