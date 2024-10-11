	<div class="big_1 top_menu">
    	<div class="m_div">
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="mypage.php">마이페이지</a>
            </div>
            <div class="right_sub_menu">&nbsp;
                <a href="/mypage.php">회원정보수정</a> | 

                <a href="/mypage_payment.php">결제정보</a> | 

                 <?if($data[service_type] == 3 || $data[service_type] == 2 || $data['mem_id'] == "obmms02") {?>
                <a href="/mypage_payment_reseller.php">정산정보</a> |
                <a href="/sub_7.php">추천정보</a> | 
                <?}?>


               <?
               //mem_leb 21:강사, 22: 일반, 50: 사업자
               //service_type: 0: 이용자, 1: 리셀러, 3: 분양자

               ?>
                <?if($data[mem_leb] == 21 || $data[mem_leb] == 60 || $data['mem_id'] == "obmms02") {?>
                <a href="/mypage_lecture_list.php">강연관리</a> | 
                
                <?}?>



               <a href="/mypage_coaching_list.php">코칭관리</a>
            </div>
            <p style="clear:both;"></p>
    	</div>
   </div>