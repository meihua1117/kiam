<meta charset="utf-8">
<?php
include_once "../lib/rlatjd_fun.php";
 $row = 1;
 $handle = fopen("get.csv", "r");
 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    //아이디	닉네임	앱비번	PC비번	성명	이메일	전화번호	주소	직업	생년월일	추천인	결제금액	결제유형
    if($row > 1) {
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";
        }
        $mem_id = $data[0];
        $nick = $data[1];
        $passwd = $data[2];
        $web_passwd = $data[3];
        $name = $data[4];
        $email = $data[5];
        $mobile = $data[6];
        $address = $data[7];
        $job = $data[8];
        $birthday = $data[9];
        $recommend_id = $data[10];
        $money = $data[11];
        $type = $data[12];
        $sex = "여";
        
          $query = "insert into Gn_Member set mem_id='$mem_id',
                                              mem_leb='22',
                                              web_pwd=password('$web_passwd'),
                                              mem_pass=md5('$passwd'),
                                              mem_name='$name',
                                              mem_nick='$nick',
                                              mem_phone='$mobile',
                                              zy='$job',
                                              first_regist=now() ,
                                              mem_check=now(),
                                              mem_add1='$addr',
                                              mem_email='$email',
                                              mem_sex='$sex',
                                              join_ip='$_SERVER[REMOTE_ADDR]'
          ";
          echo $query;
          //mysql_query($query);
          //$mem_code = mysql_insert_id();
          if($money == "11000") {
                $share_per = 50;
                $member_type="기본문자";
          } else {
                $share_per = 40;
                $member_type="사업문자-정기";
          }
          
          
          $end_date = date("Y-m-d H:i:s", time()+(86400*365));
          $query = "insert into tjd_pay_result set phone_cnt='9000',
                                                   month_cnt='1',
                                                   end_date='$end_date',
                                                   end_status='Y',
                                                   buyertel='$mobile',
                                                   buyeremail='$email',
                                                   resultMsg='정기결제',
                                                   payMethod='MONTH',
                                                   TotPrice='$money',
                                                   VACT_InputName='$name',
                                                   buyer_id='$mem_id',
                                                   date=NOW(),
                                                   max_cnt=9000,
                                                   add_phone=1,
                                                   member_type='$member_type',
                                                   share_per='$share_per'
          ";      
          echo $query;
          mysql_query($query);
        
    }
    
    $row++;
 }
 fclose($handle);
?>