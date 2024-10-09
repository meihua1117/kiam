<div id='ajax_div'></div>
<div class="loading_div"><img src="images/ajax-loader.gif"></div>
<?
if(eregi("mypage",$_SERVER[PHP_SELF]))
{
?>
<div id='open_pay_extend' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_pay_extend,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_2_1">결제연장하기</li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_pay_extend)"><img src="images/div_pop_01.jpg" /></a></li>        
    </div>
	<div class="open_3 pay">
    	<form name="pay_extend_form" action="" method="post">
    	<input type="hidden" name="pay_ex_end_date" />
    	<input type="hidden" name="pay_ex_no" />        
                <div class="r2">
                    <div class="a2">
                            <table class="view_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:18%">상품</td>
                                    <td style="width:22%">갯수</td>
                                    <td style="width:18%">내용</td>
                                    <td style="width:22%">개별가격</td>
                                    <td style="width:20%">통합가격</td>
                                </tr>
                                <tr>
                                    <td>본인휴대폰</td>
                                    <td>기본1개</td>
                                    <td>폰별 월 9,000건</td>
                                    <td>무료</td>
                                    <td>무료</td>
                                </tr>
                                <tr>
                                    <td>추가휴대폰</td>
                                    <td><input type="text" style="width:55px;" name="add_phone" value="0" onfocus="pay_total('add_phone')" onkeyup="pay_total('add_phone')"/> 개</td>
                                    <td><input type="button" value="추가예시" onClick="open_div(open_pay_ex,160,150,true)" /></td>
                                    <td>개당2,000원</td>
                                    <td><span class='add_money_span'>0</span>원<input type="hidden" name="add_money" value="0" /></td>
                                </tr>
                                <tr>
                                    <td>부가서비스</td>
                                    <td><input type="hidden" name="show_fujia" value="이미결제됨 <br><br> 사용만료시간 :<?=$member_1[fujia_date2]?>"/>
										<label <?=$fujia_pay?"onmouseover=\"show_recv('show_fujia','0','부가서비스',true)\"":""?>><input type="checkbox" name="add_service" onclick="pay_total('add_service')" <?=$fujia_pay?"disabled":""?> />사용시 선택</label>
                                    </td>
                                    <td>
										<?
                                        foreach($fujia_type as $key=>$v)
                                        {
											$br=$key==count($fujia_type)-1?"":"<br>";
										 	echo $v.$br;
                                        }
                                        ?>
                                    </td>
                                    <td>통합20,000원</td>
                                    <td><span class="add_money_span">0</span>원<input type="hidden" name="add_money" value="0" /></td>
                                </tr>
                            </table>
                    </div>
                    <div class="a5">
                            <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td style="width:30%;">직접결제</td>
                                <td style="width:70%;"><label><input type="radio" name="money_type" value="1" onclick="pay_total()" checked />1개월</label></td>
                              </tr>
                              <tr>
                                <td rowspan="3">자동결제</td>
                                <td ><label><input type="radio" name="money_type" value="3" <?=$is_chrome?> onclick="pay_total()" />3개월 기간</label></td>
                              </tr>
                              <tr>
                                <td style="background-color:#FFF"><label><input type="radio" name="money_type" value="6" <?=$is_chrome?>  onclick="pay_total()"/>6개월 기간</label></td>
                              </tr>
                              <tr>
                                <td style="background-color:#FFF"><label><input type="radio" name="money_type" value="12" <?=$is_chrome?> onclick="pay_total()" />12개월 기간</label></td>
                              </tr>
                            </table>
                	</div>
                    <div class="a6" style="background-color:#fed700;"> 
                        결제금액: <span class="add_money_span">0</span> 원<input type="hidden" name="price" />                                                                      
                    </div>                    
                     <input type="hidden" name="gopaymethod" />
                     <input type="hidden" name="goodname" />
                     <input type="hidden" name="month_cnt" />
                     <input type="hidden" name="fujia_status" />
                     <input type="hidden" name="mid" value="obmms20151" />                                    
                    <div class="a8"><a href="javascript:void(0)" onclick="pay_go(pay_extend_form)"><img src="images/sub_02_btn_23.jpg" /></a></div>            
              </div>        
    	</form>
         <iframe name="pay_iframe" style="display:none;"></iframe>
    </div>
</div>
<?
}
?>
<div id='open_group_create' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_group_create,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_2_1">그룹 생성하기</li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_group_create)"><img src="images/div_pop_01.jpg" /></a></li>        
    </div>
	<div class="open_3">
    	<form name="group_create_form" action="" method="post">
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>휴대폰번호</td>
                    <td id='sendnum_span'>&nbsp;</td>
                </tr>
                <tr>
                    <td>그룹명</td>
                    <td>
                    <input type="hidden" name="seq" id='sendnum_hid' />
                    <input type="text" name="g_name" itemname='그룹명' required />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="button" value="생성하기" onClick="group_create_ok(group_create_form)" />                
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<div id='open_pop_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_pop_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_2_1 group_title_open">그룹 전화번호</li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_pop_div)"><img src="images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_3" style="width:500px;">
    <?php
    $link = $_SERVER['PHP_SELF'];
    if($link == "/sub_6.php"){
	$height = 650;
	}
    else if($link == "/sub_6_elc.php"){
	$height = 800;
    }
    ?>
		<iframe id="pop_iframe" src="" width="100%" height="<?=$height;?>" frameborder="0" scrolling="auto"></iframe>
    </div>
</div>
<div id="open_pay_ex" class="open_1">
	<div class="open_2" onmousedown="down_notice(open_pay_ex,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_2_1">추가선택</li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_pay_ex)"><img src="images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_3">
    	<div class="pay_ex">
            <div style="float:left;width:90px;text-align:center;line-height:300px;height:300px;">
                추가선택
            </div>
            <div style="float:left;">
                <table class="yiban_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <?
                    for($i=1; $i<11; $i++)
                    {
                        $cnt=9000*$i;					
                        $money=2000*$i;
						$border_bottom=$i==10?"":"1px solid #CCC;"
                        ?>
                        <tr>
                            <td style="border-bottom:<?=$border_bottom?>"><label><input type="radio" name="add_cnt_radio" onclick="document.getElementsByName('add_phone')[0].value='<?=$i?>';document.getElementsByName('add_phone')[0].focus();close_div(open_pay_ex)" /><?=$i?>개추가</label></td>
                            <td style="border-bottom:<?=$border_bottom?>">월 <?=number_format($cnt)?>건</td>
                            <td style="border-bottom:<?=$border_bottom?>"><?=number_format($money)?>원</td>
                        </tr>
                    <?
                    }
                    ?>
                </table>
            </div>
            <p style="clear:both;"></p>
        </div>
    </div>
</div>
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_recv_title open_2_1"></li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
