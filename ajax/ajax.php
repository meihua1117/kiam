<?
include_once "../lib/rlatjd_fun.php";
if($_POST['mode'] == "intro_message") {
   	$intro_message=htmlspecialchars($_POST[intro_message]);
    $sql="update Gn_Member set intro_message='$intro_message' where mem_id='".$_SESSION['one_member_id']."'";
    mysqli_query($self_con,$sql);   
    echo "success"; 
    exit;	
}
//아이디 유무 판단
if($_POST[id_che]){
    $solution_type = $_POST[solution_type];
    $solution_name = $_POST[solution_name];
    $id_che=trim($_POST[id_che]);
	if($solution_type && $solution_name){
		$search = " and ".$solution_type." = '$solution_name'";
	}
	else{
		$search = "";
	}
    $sql="select mem_id from Gn_Member where mem_id='$id_che'".$search;
    $resul=mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($resul);
    if($row['mem_id']){?>
    <script language="javascript">
		alert('이미 가입되어있는 아이디입니다.');
		<?=$_POST[id_che_form]?>.id_status.value=''
		<?=$_POST[id_che_form]?>.id.value='';
		<?=$_POST[id_che_form]?>.id.focus();
		$("#id_chk_str").hide();
		$("[id^='id_html']").html("&nbsp;&nbsp; ※ 아이디 중복확인을 클릭해주세요.");
	</script>
	<?
	}else{
		if(@in_array($id_che,$jinyu_arr)){
    ?>
			<script language="javascript">
                alert('본 홈페이지 금지단어입니다.');
                <?=$_POST[id_che_form]?>.id_status.value=''
                <?=$_POST[id_che_form]?>.id.value='';
                <?=$_POST[id_che_form]?>.id.focus();
                </script>
    <?  }else{
			$sql="select count(mem_id) from Gn_Member where mem_id='$id_che' and ".$solution_type." = ''";
			$result=mysqli_query($self_con,$sql);
			$row=mysqli_fetch_array($result);
			if($row[0] * 1 > 0 ) {
    ?>
				<script>
					var msg = confirm('이미 가입된 회원입니다. 현재 도메인으로 소속을 변경하시려면 확인을 클릭하고 변경할수 있습니다.');
					$("#id_chk_str").hide();
					if (msg) {
						window.open('/check_password.php?mem_id=<?=$id_che?>&solution_type=<?=$solution_type?>&solution_name=<?=$solution_name?>', 'popup', 'width=600, height=140, toolbar=no, menubar=no, scrollbars=no, resizable=no,scrolling=no');
					} else {
						alert('이미 가입되어있는 아이디입니다.');
						<?=$_POST[id_che_form]?>.id_status.value = ''
							<?=$_POST[id_che_form]?>.id.value = '';
						<?=$_POST[id_che_form]?>.id.focus();
						$("[id^='id_html']").html('&nbsp;&nbsp; ※ 아이디 중복확인을 클릭해주세요.');
					}
				</script>
    <?      }else {
				$sql = "select count(mem_id) from Gn_Member where mem_id='$id_che' and " . $solution_type . " != '$solution_name'";
				$result = mysqli_query($self_con,$sql);
				$row = mysqli_fetch_array($result);
				if ($row[0] == 0) {
    ?>
					<script language="javascript">
						<?=$_POST[id_che_form]?>.id_status.value = 'ok';
						$("#id_chk_str").hide();
						$("[id^='id_html']").html("<img src='/images/check.gif' /> 사용 가능하십니다.");
					</script>
    <?
				} else {
					if ($solution_type == 'site')
						$sql = "select count(*) from Gn_Service where mem_id = '$id_che'";
					else if ($solution_type == 'site_iam')
						$sql = "select count(*) from Gn_Iam_Service where mem_id = '$id_che'";
					else {
    ?>
						<script language="javascript">
							<?=$_POST[id_che_form]?>.id_status.value = 'ok';
							$("#id_chk_str").hide();
							$("[id^='id_html']").html("<img src='/images/check.gif' /> 사용 가능하십니다.");
						</script>
						<?exit;
					}
					$result = mysqli_query($self_con,$sql);
					$row = mysqli_fetch_array($result);
					if ($row[0] == 0) {
    ?>
						<script>
                            alert('이미 가입되어있는 아이디입니다.');
                            <?=$_POST[id_che_form]?>.id_status.value = '';
                            <?=$_POST[id_che_form]?>.id.value = '';
                            <?=$_POST[id_che_form]?>.id.focus();
							$("#id_chk_str").hide();
                            $("[id^='id_html']").html('&nbsp;&nbsp; ※ 아이디 중복확인을 클릭해주세요.');
						</script>
    <?
					} else {
						?>
						<script language="javascript">
							alert('이미 가입되어있는 아이디입니다.');
							<?=$_POST[id_che_form]?>.id_status.value = ''
                            <?=$_POST[id_che_form]?>.id.value = '';
							<?=$_POST[id_che_form]?>.id.focus();
							$("#id_chk_str").hide();
							$("[id^='id_html']").html("&nbsp;&nbsp; ※ 아이디 중복확인을 클릭해주세요.")
						</script>
    <?
					}
				}
			}
		}
	}
}
//닉네임 중복확인
if($_POST[nick_che]){
    $add_sql=$_SESSION['one_member_id']?" and mem_id<>'{$_SESSION['one_member_id']}' ":"";
    $nick_che=trim($_POST[nick_che]);
    $sql="select mem_id from Gn_Member where mem_nick='$nick_che' $add_sql";
    $resul=mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($resul);
    if($row['mem_id']){
?>
	<script language="javascript">
        document.getElementById('nick_html').innerHTML='이미 가입되어있는 닉네임입니다.'
        <?=$_POST[nick_che_form]?>.nick_status.value='';
        <?=$_POST[nick_che_form]?>.nick.value='';
        <?=$_POST[nick_che_form]?>.nick.focus();
	</script>
<?
	}else{
	    if(in_array($nick_che,$jinyu_arr)){
?>
			<script language="javascript">
                alert('본 홈페이지 금지단어입니다.');
                <?=$_POST[nick_che_form]?>.nick_status.value='';
                <?=$_POST[nick_che_form]?>.nick.value='';
                <?=$_POST[nick_che_form]?>.nick.focus();
			</script>
<?
		}else{
?>
			<script language="javascript">
                <?=$_POST[nick_che_form]?>.nick_status.value='ok';
                document.getElementById('nick_html').innerHTML="<img src='/images/check.gif' />";
			</script>
<?
		}
	}		
}
//회원가입 1단계
if($_POST[join_nick] && $_POST[join_is_message]){
	$_POST[join_id] = strtolower(trim($_POST[join_id]));
    if(!$_POST[join_modify]){
        $member_info['mem_id']=htmlspecialchars($_POST[join_id]);
		$member_info[mem_leb]=22;
		$member_info[id_type]="hp";
		$member_info[join_ip]=$ip;
		$member_info[mem_pass]=md5($_POST[join_pwd]);
		$member_info[web_pwd]=$_POST[join_pwd];
		$member_info[mem_phone]=$_POST[join_phone];
	} else {
        // 핸드폰 번호 중복 확인
        $sql="select * from Gn_Member where replace(mem_phone,'-','')=replace('$_POST[join_phone]','-','')";
        $resul=mysqli_query($self_con,$sql);
        $row=mysqli_fetch_array($resul);	
        if($row[0] != "") {
?>
			<script language="javascript">
			alert('가입자의 휴대폰입니다.');
			document.getElementById('mobile_1').value=''
			document.getElementById('mobile_2').value='';
			document.getElementById('mobile_3').value='';;
			document.getElementById('mobile_1').focus();
			</script>        
<?
        exit;
        }	    
	}
	$member_info[mem_nick]=htmlspecialchars($_POST[join_name]);
	$member_info['mem_name']=htmlspecialchars($_POST[join_name]);
	$member_info[mem_email]=$_POST[join_email];
	$member_info[mem_add1]=$_POST[join_add1];
	$member_info[zy]=$_POST[join_zy];
	$member_info[mem_birth]=$_POST[join_birth];
	$member_info[is_message]=$_POST[join_is_message];	
	if($_FILES[profile]) {
    	$tempFile = $_FILES[profile][tmp_name];
    	if($tempFile) {
    	    $file_arr=explode(".",$_FILES[profile]['name']);
    	    $tmp_file_arr=explode("/",$tempFile);
    	    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];
			$up_dir = make_folder_month(1);
			if($up_dir != ''){
				$uploaddir = '..'.$up_dir;
			}
			else{
				$uploaddir = '../upload/';
				$up_dir = "/upload/";
			}	
        	if(move_uploaded_file($_FILES['profile']['tmp_name'], $uploaddir.$file_name)){
        	    $member_info[profile] = $up_dir.$file_name;
        	}	    
    	}
	}
	if($_POST[solution_type]) {
		$member_info[site] = $_POST[solution_name];
		$sql_iam="select count(*) FROM Gn_Iam_Service WHERE sub_domain like '%http://".$HTTP_HOST."'";
		$res_iam=mysqli_query($self_con,$sql_iam);
		$row_iam = mysqli_fetch_array($res_iam);
		if($row_iam[0] != 0)
			$member_info[site_iam] = $_POST[solution_name];
		else
			$member_info[site_iam] = "kiam";
	}
    $site_info = explode(".",$HTTP_HOST);
    $site_name = $site_info[0];
    if($site_name == "www")
        $site_name = "kiam";
    $member_info[site] = $member_info[site_iam] = $site_name;
	if($_POST[recommend_id]){
		$member_info[recommend_id]=$_POST[recommend_id];
	}else{
		if($HTTP_HOST == 'kiam.kr')
			$member_info[recommend_id] = 'onlyone';
		else{
			$sql = "select mem_id from Gn_Service where sub_domain like '%http://".$HTTP_HOST."%'";
			$res = mysqli_query($self_con,$sql);
			$row = mysqli_fetch_array($res);
			$member_info[recommend_id] = $row['mem_id'];
		}
	}
	if($_POST[recommend_branch])
	    $member_info[recommend_branch]=$_POST[recommend_branch];	    
	    
	$sql="select * from Gn_Service where mem_id='$_POST[recommend_id]'";
	$result=mysqli_query($self_con,$sql);
	$sinfo=mysqli_fetch_array($result);	
	if($sinfo[0] != "") {
		$parse = parse_url($sinfo['sub_domain']);
		$sites = explode(".", $parse['host']);
		$member_info[ext_recm_id] = $sites[0];
		//$member_info[ext_recm_id] = str_replace(".kiam.kr", "", str_replace("http://", "", $sinfo['sub_domain']));
	}
	$member_info[bank_name]=$_POST[bank_name];
	$member_info[bank_account]=$_POST[bank_account];
	$member_info[bank_owner]=$_POST[bank_owner];
	if($_POST[join_modify] == "") {
	    if($_POST[rnum] == "" && $_POST[country_code] == "KR") {
	        ?>
	        <script language="javascript">
				alert('인증번호가 없습니다.');
			</script>
	        <?
	        exit;
	    }
		if($_POST['rnum'] != "" && $_POST[country_code] == "KR") {
			$sql="select * from Gn_Member_Check_Sms where mem_phone='$member_info[mem_phone]' and secret_key='$_POST[rnum]' and status='Y' order by idx desc";
			$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
			$data = $row=mysqli_fetch_array($result);

			$query = "select count(idx) from Gn_MMS where content like '%{$_POST['rnum']}%' and type='10' order by idx desc";
            $res = mysqli_query($self_con,$query);
			$row = mysqli_fetch_array($res);
			
			if($data[idx] == "" && $row[0] == 0) {
				?>
				<script language="javascript">
					alert('인증번호를 확인해주세요.');
				</script>
				<?
				exit;
			}
		}
	}

	$member_info[mem_pass]=md5($_POST[join_pwd]);
	$member_info[web_pwd]=$_POST[join_pwd];
			
	if($_POST[join_modify])
	    $sql=" update Gn_Member set ";
	else {
		$query = "select count(*) from Gn_Member where mem_id = '{$member_info['mem_id']}'";
		$result = mysqli_query($self_con,$query);
		$row = mysqli_fetch_array($result);
		if($row[0] == 0)
			$sql = " insert into Gn_Member set ";
		else
			$sql = "update Gn_Member set ".$_POST[site_type]." = '".$_POST[site_name]."' where mem_id = '".$member_info['mem_id']."'";
	}
	if($_POST[join_modify] || $row[0] == 0) {
		$i = 0;
		foreach ($member_info as $key => $v) {
			$bd = $i == (count($member_info) - 1) ? "" : ",";
			$v = $key == "web_pwd" ? "password('$v')" : "'$v'";
			$sql .= " $key=$v $bd ";
			$i++;
		}
	}
	if($_POST[join_modify])
		$sql.=" where mem_code='$_POST[join_modify]' ";
	else if($row[0] == 0)
		$sql.=" ,first_regist=now() , mem_check=now() ";
	if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con))){
		if($_POST[join_modify]){
?>
        <script language="javascript">
		alert('수정완료되었습니다.');
		location.reload();
		</script>
<?
		}else if($row[0] == 0){
			$sql="select * from Gn_MMS_Group where mem_id='{$member_info['mem_id']}' and grp='아이엠'";
			$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
			$data = mysqli_fetch_array($result);
			if($data[idx] == ""){
				$query = "insert into Gn_MMS_Group set mem_id='{$member_info['mem_id']}', grp='아이엠', reg_date=NOW()";
				mysqli_query($self_con,$query);
			}
			$_SESSION['one_member_id']=$_POST[join_id];
            $content=$_POST[join_name]."님 온리원문자 회원이 되신걸 환영합니다.";
            $subject="온리원문자 회원가입";
            sendemail("", $member_info[mem_email],"admin@kiam.kr",$subject,$content);

?>
			<script language="javascript">
				alert('회원가입 되었습니다.');
				//location.href='./';
				show_install_modal();
			</script>
<?
		}else{
		    echo "<script>	alert('회원가입되었습니다.');location.href='/ma.php';</script>";
		}
	}
}
//아이디 패스워드찾기
if($_POST[search_id_pw_mem_name] && $_POST[search_id_pw_type]){	
	$s = 0;
	$conf_sql = "select * from gn_conf";
	$conf_result = mysqli_query($self_con,$conf_sql);
	$conf_row = mysqli_fetch_array($conf_result);
	$mem_id = $conf_row['phone_id'];
	$send_num = $conf_row['phone_num'];
	if($_POST[search_id_pw_type]=="phone") {
	    $phone_num = str_replace("-","",$_POST[search_id_pw_phone]);
        $sql_serch = " (mem_phone='$_POST[search_id_pw_phone]' or mem_phone='$phone_num')";
    }
	else if($_POST[search_id_pw_type]=="email")
	    $sql_serch =" mem_email='$_POST[search_id_pw_email]' ";
	if($_POST[search_id_pw_mem_id])
		$sql_serch.=" and mem_id=trim('$_POST[search_id_pw_mem_id]') and mem_name=trim('$_POST[search_id_pw_mem_name]') ";
	else
		$sql_serch.=" and mem_name=trim('$_POST[search_id_pw_mem_name]') ";
    $sql="select * from Gn_Member where $sql_serch ";
    $resul=mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($resul);
    if($row['mem_code'])
    {
        // 수정 시작
        if($row[is_leave] == 'Y'){
?>
        <script language="javascript">
              alert('탈퇴한 아이디 입니다.');
              //location.reload();
        </script>
<?
        }else if($_POST[search_id_pw_mem_id]){
            $new_pwd=substr(md5(time()),0,10);
            $sql_u="update Gn_Member set web_pwd=password('$new_pwd') where mem_code='{$row['mem_code']}' ";
            mysqli_query($self_con,$sql_u);

			if($row[site_iam] == "kiam" || $row[site_iam] == ""){
				$site_iam = "";
			}
			else{
				$site_iam = $row[site_iam].".";
			}
			$content=$row['mem_name']."님 온리원문자 비밀번호가[ ".$new_pwd." ] 로 변경되었습니다. ".$site_iam."kiam.kr (".$row['mem_id'].")";
			$content1=$row['mem_name']."님 온리원문자 비밀번호가[ ".$new_pwd." ] 로 변경되었습니다.";
            $subject="온리원문자 비밀번호찾기";
            if($_POST[search_id_pw_type]=="email") {
                sendemail("", $row[mem_email], "admin@kiam.kr", $subject, $content);
                $msg = "회원님의 비밀번호가 변경되었습니다.이메일 ".$row[mem_email]."로 발송되었습니다.";
            }
            else {
				$s++;
				$sql_app_mem = "select * from Gn_MMS_Number where (sendnum='$phone_num' and sendnum is not null and sendnum != '')";
				// echo $sql_app_mem."pwd"; exit;
				$res_app_mem = mysqli_query($self_con,$sql_app_mem);
				if(mysqli_num_rows($res_app_mem)){					
					$number_row = mysqli_fetch_array($res_app_mem);
					sendmms(5, $number_row['mem_id'], $phone_num, $phone_num, "", $subject, $content, "", "", "", "Y");
				}
				else{
					//$msg = message_send($phone_num, $subject, $content1,"회원님의 비밀번호가 변경되었습니다.");
					//회사폰을 발송폰으로 설정해서 발송한다.
					sendmms(5, $mem_id, $send_num, $phone_num, "", $subject, $content, "", "", "", "Y");

				}
				$msg = "회원님의 비밀번호가 변경되었습니다.셀폰 ".$phone_num."으로 발송되었습니다.";
            }
?>
            <script language="javascript">
                var msg = "<?=$msg?>";
                msg = msg.split(".");
                alert(msg[0] + ".\r\n" + msg[1]);
                location.reload();
            </script>
<?
        }else{
			$content = "";
			if($_POST[search_id_pw_type]=="phone") {
				$sql_serch = " and REPLACE(mem_phone,'-','')='{$phone_num}'";
			}
			else if($_POST[search_id_pw_type]=="email")
				$sql_serch =" and mem_email='$_POST[search_id_pw_email]' ";
			$sql_doub_mem = "select * from Gn_Member where mem_name='$_POST[search_id_pw_mem_name]' ".$sql_serch;
			$res_doub_mem = mysqli_query($self_con,$sql_doub_mem);
			while($row1 = mysqli_fetch_array($res_doub_mem)){
				if($row1[site_iam] == "kiam" || $row1[site_iam] == ""){
					$site_iam = "";
				}
				else{
					$site_iam = $row1[site_iam].".";
				}
				$content.= $row1['mem_name']."님 온리원문자 아이디는[ ".$row1['mem_id']." ] 입니다. ".$site_iam."kiam.kr (".$row1['mem_id'].")\n";
				$subject="온리원문자 아이디찾기";
			}
			if($_POST[search_id_pw_type]=="email") {
				sendemail("", $row[mem_email], "admin@kiam.kr", $subject, $content);
				$msg = "회원님의 아이디가 이메일 ".$row[mem_email]."로 발송되었습니다.";
			}
			else {
				$s++;
				$sql_app_mem = "select * from Gn_MMS_Number where (sendnum='$phone_num' and sendnum is not null and sendnum != '')";
				// echo $sql_app_mem."id"; exit;
				$res_app_mem = mysqli_query($self_con,$sql_app_mem);
				if(mysqli_num_rows($res_app_mem)){
					$number_row = mysqli_fetch_array($res_app_mem);
					sendmms(5, $number_row['mem_id'], $phone_num, $phone_num, "", $subject, $content, "", "", "", "Y");
				}
				else{
					//$msg = message_send($phone_num, $subject, $content, "회원님의 아이디가 ");
					//회사폰을 발송폰으로 설정해서 발송한다.
					sendmms(5, $mem_id, $send_num, $phone_num, "", $subject, $content, "", "", "", "Y");
				}
				$msg = "회원님의 아이디가 셀폰 ".$phone_num."으로 발송되었습니다.";
			}
?>
            <script language="javascript">
                var msg = "<?=$msg?>";
                msg = msg.split(".");
                alert(msg[0] + ".\r\n" + msg[1]);
                location.reload();
            </script>
<?
        }
    }else{
?>
            <script language="javascript">
            alert('입력하신 정보가 틀렸습니다.\n카톡상담창을 통해 관리자에게 문의해주세요.');
            </script>
<?
        exit;
    }
}
?>