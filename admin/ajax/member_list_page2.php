<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

// 가입 회원 상세 정보
$query = "select `mem_code`, `b2b_code`, `mem_leb`, `mem_group`, `mem_id`, `mem_tally`, `mem_pass`, `web_pwd`, `mem_name`, `mem_nick`, `mem_sch`, `mem_ssn1`, `mem_ssn2`, `mem_post`, `mem_add1`, `mem_add2`, `mem_tel`, `mem_phone`, `mem_fax`, `mem_email`, `mem_home`, `mem_birth`, `mem_btype`, `mem_sex`, `mem_remail`, `mem_sms`, `mem_content`, `mem_point`, `mem_cash`, `mshop_total`, `mshop_count`, `bank_name`, `bank_owner`, `bank_account`, `com_num`, `com_ceo`, `com_charge`, `com_cphone`, `com_post`, `com_add1`, `com_add2`, `zy`, `is_message`, `is_leave`, `leave_txt`, `c_all`, `c_month`, `c_duty`, `school`, `history`, `exe_1`, `exe_2`, `exe_3`, `exe_4`, `exe_5`, `exe_6`, `mem_check`, `mem_chu`, `first_regist`, `last_modify`, `last_regist`, `join_ip`, `login_ip`, `visited`, `site`, `id_type`, `limit_sms`, `level`, `fujia_date1`, `fujia_date2`, `ext_grp`, `ext_school_sido`, `ext_school`, `ext_year`, `ext_class`, `ext_num`, `ext_recm_id`, `ext_charge`, `ext_division`, `ext_rschool_sido`, `ext_rschool`, `login_date`, `phone_cnt`, `total_pay_money`, `mem_type`,(select count(*) from Gn_MMS_Number where 1=1 and ( not (cnt1 = 10 and cnt2 = 20)) and mem_id =Gn_Member.mem_id) tcnt
            from Gn_Member where mem_code='$mem_code'";
$res = mysqli_query($self_con,$query);
$data = mysqli_fetch_array($res);

// 기부회원 상세 정보
$query = "select `idx`, `mem_id`, `sendnum`, `max_cnt`, `user_cnt`, `gl_cnt`, `month_cnt`, `today_cnt`, `over_cnt`, `memo`, `reg_date`, `up_date`, `max_over_cnt`, `memo2`, `device`, `memo3`, `usechk`, `cnt1`, `cnt2`, `format_date`, `end_status`, `end_date`, `donation_rate`, `daily_limit_cnt`, `use_order` 
            from Gn_Mms_Number where mem_id='$data[mem_id]' and sendnum='$sendnum'";
$res = mysqli_query($self_con,$query);
$donation_data = mysqli_fetch_array($res);

// =====================  유료결제건 시작 ===================== 
$sql = "select phone_cnt from tjd_pay_result where buyer_id = '".$data['mem_id']."' and end_date > '$date_today' order by end_date desc limit 1";
$res_result = mysqli_query($self_con,$sql);
$buyPhoneCnt = mysqli_fetch_row($res_result);
mysqli_free_result($res_result);

if($buyPhoneCnt == 0){	
	$buyMMSCount = 0;
}else{
	$buyMMSCount = ($buyPhoneCnt[0] -1) * 9000;
}                    	
// ===================== 유료결제건 끝 ===================== 

// =====================  총결제금액 시작 ===================== 
$sql = "select sum(TotPrice) totPrice, date from tjd_pay_result where buyer_id = '".$data['mem_id']."'";
$res_result = mysqli_query($self_con,$sql);
$totPriceRow = mysqli_fetch_row($res_result);
mysqli_free_result($res_result);

$totPrice = $totPriceRow[0];
$pay_date = $totPriceRow[1];
// ===================== 총결제금액 끝 =====================                     	

// =====================  마지막 결제정보 시작 ===================== 
/*
$sql = "select reg_date  from tjd_pay_result where buyer_id = '".$data['mem_id']."' order by end_date desc limit 1";
$res_result = mysqli_query($self_con,$sql);
$totPriceRow = mysqli_fetch_row($res_result);
mysqli_free_result($res_result);

$totPrice = $totPriceRow[0];
*/
// ===================== 마지막 결제정보 끝 =====================                     	

// =====================  마지막 발송정보 시작 ===================== 
$sql = "select msg_text, reservation_time  from sm_data where dest = '".str_replace("-", "", $data['mem_phone'])."' order by reservation_time desc limit 1";
$res_result = mysqli_query($self_con,$sql);
$totPriceRow = mysqli_fetch_row($res_result);
mysqli_free_result($res_result);

$totPrice = $totPriceRow[0];
// ===================== 마지막 발송정보 끝 =====================                     	


// 부가서비스 이용 여부 확인
// tjd_pay_result.fujia_status
if($row['fujia_date2'] >= date("Y-m-d H:i:s")) {
    $add_opt = "사용";
} else {
    $add_opt = "미사용";
}

// 기부회원 상세정보

?> 
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">기부회원 상세정보</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail2" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="16%">
                      <col width="16%">
                      <col width="16%">
                      <col width="16%">
                      <col width="16%">
                      <col width="20%">
                     </colgroup>
                    <tbody>
                      <tr>
                        <th>기부폰 번호</th>
                        <td><?=$donation_data['sendnum']?></td>
                        <th>앱설치일자</th>
                        <td><?=$donation_data['reg_date']?></td>
                        <th>가입자 아이디</th>
                        <td><?=$data['mem_id']?></td>
                      </tr>
                      <tr>
                        <th>최근발송건</th>
                        <td><?=$donation_data['reservation_time']?></td>
                        <th>기부비율</th>
                        <td>
                            <input type="text" style="width:100px;" name="donation_rate" value="<?=$donation_data['donation_rate']?>"> %
                            <div><span style="font-size:10px">※ 설치 기록이 없으면 비율이 변경되지 않음.</span></div>
                        </td>
                        <th>가입자 이름</th>
                        <td><?=$data['mem_name']?></td>                       
                      </tr>
                      <tr>
                        <th>최근발송일</th>
                        <td><?=$donation_data['reservation_time']?></td>
                        <th>기부문자</th>
                        <td>
                            <?=number_format($donation_data['daily_limit_cnt'] * ($donation_data['donation_rate'] * 0.01))?> / <?=number_format($donation_data['daily_limit_cnt'])?>
                        </td>
                        <th>가입자 전화번호</th>
                        <td><?=$data['mem_phone']?></td>                     
                      </tr>                                                                                                                                                                
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->