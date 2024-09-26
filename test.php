<?
$path="./";
include_once "_head.php";
//$code = whois_ascc($whois_api_key, $_SERVER['REMOTE_ADDR']);
?>
<script>
</script>
<style>
.pop_right {
    position: relative;
    right: 2px;
    display: inline;
    margin-bottom: 6px;
    width: 5px;
}    
.gwc_chk{
    height:14px !important;
}
</style>
 
<div class="big_sub">
   <div class="m_div" id="m_div_mp">
        <div class="join">
			<div class="a1">
				<li style="float:left;">폰문자인증 테스트</li>
				<li style="float:right;"></li>
				<p style="clear:both"></p>
			</div>
			<table class="write_table" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px">
				<tr>
					<td>API_KEY</td>
					<td  colspan="3"><input type="text" id="api_key"/></td>
				</tr>
				<tr>
					<td>폰번호</td>
					<td><input type="tel" id="phone_number"/></td>
					<td colspan="2"><input type="button" value="문자발송" onclick="send()"></td>
				</tr>
				<tr>
					<td>인증번호</td>
					<td><input type="text" id="check"/></td>
					<td colspan="2"><input type="button" value="문자인증" onclick="check()"></td>
				</tr>
			</table>
        </div>
   </div>
</div><!--end big_sub-->
<?include_once "_foot.php";?>

<script>
/*.ajaxStop(function() {
    $("#ajax-loading").delay(10).hide(1);
});*/
function send(){
	var mode = "munja_send";
	var api = $("#api_key").val();
	var phone = $("#phone_number").val();
	$.ajax({
          type:"POST",
          url:"/ajax/phone_check.php",
          dataType:"json",
          data:{
              mode:mode,
              api_key:api,
              phone_number:phone
          },
          success:function(data){
			  alert(data.result);
          },
          error: function(){
              alert('변경 실패');
          }
	});
}

function check() {
    var mode = "munja_check";
	var api = $("#api_key").val();
	var num = $("#check").val();
    var phone = $("#phone_number").val();
	$.ajax({
          type:"POST",
          url:"/ajax/phone_check.php",
          dataType:"json",
          data:{
              mode:mode,
              api_key:api,
              check:num,
              phone_number:phone
          },
          success:function(data){
			  alert(data.result);
          },
          error: function(){
              alert('변경 실패');
          }
	});
}
</script>