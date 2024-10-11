	<div class="big_1">
    	<div class="m_div">
  <!--      	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="mypage.php">마이페이지</a>
            </div> -->
            <div class="right_sub_menu">&nbsp;
		       <button type="button" style="background:#D8D8D8">
                <a href="/mypage.php">회원정보수정</a> </button>	
		       <button type="button" style="background:#D8D8D8">
                <a href="/mypage_payment.php">결제정보</a></button> 
                <!--<a href="mypage_payment.php">정산정보</a> | -->
		       <button type="button" style="background:#D8D8D8">
                <a href="/sub_7.php">추천정보</a> </button>
		       <button type="button" style="background:#D8D8D8">
                <?if($data[mem_leb] == 21 || $data[mem_leb] == 60 || $data['mem_id'] == "obmms02") {?>
                <a href="/mypage_lecture_list.php">강연관리</a></button>
                <?}?>
            </div>
            <p style="clear:both;"></p>
    	</div>
   </div>
   
