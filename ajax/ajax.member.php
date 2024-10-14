<?
include_once "../lib/rlatjd_fun.php";
include_once "../lib/class.image.php";
extract($_REQUEST);
if($_REQUEST['nick'])
{	
	$member_info['mem_nick']=htmlspecialchars($_REQUEST['nick']);
	$member_info['mem_name']=htmlspecialchars($_REQUEST['name']);
	$member_info['mem_email']=$_REQUEST['email_1']."@".$_REQUEST['email_2'];
	$member_info['mem_add1']=$_REQUEST['add1'];
	if(isset($_REQUEST['zip'])){
		$member_info['mem_zip']=$_REQUEST['zip'];
	}
	$member_info['zy']=$_REQUEST['zy'];
	$member_info['mem_sch']=$_REQUEST['mem_sch'];
	$member_info['keywords']=$_REQUEST['keywords'];
	$member_info['mem_birth']=$_REQUEST['birth_1']."-".$_REQUEST['birth_2']."-".$_REQUEST['birth_3'];
	$member_info['mem_sex']=$_REQUEST['mem_sex'];
	$member_info['gwc_req_leb']=$_REQUEST['gwc_leb'];
	$member_info['mem_add2']=$_REQUEST['add2'];
	$member_info['com_add1']=$_REQUEST['com_add1'];
	$member_info['gwc_req_date']=date("Y-m-d H:i:s");
	$member_info['gwc_accept_date']=date("Y-m-d H:i:s");
	$member_info['gpt_chat_api_key']=$_REQUEST['gpt_chat_api_key'];

	$sql_chk = "select gwc_state from Gn_Member where mem_code='{$_REQUEST['join_modify']}'";
	$res_chk = mysqli_query($self_con,$sql_chk);
	$row_chk = mysqli_fetch_array($res_chk);
	$member_info['gwc_state'] = $row_chk[0];

	if($_REQUEST['is_message'])
		$member_info['is_message']="Y";
	else
		$member_info['is_message']="N";
	if($_FILES['profile']) {
    	$tempFile = $_FILES['profile']['tmp_name'];
    	if($tempFile) {
    	    $file_name=date('dmYHis').str_replace(" ", "", basename($_FILES["profile"]["name"]));
			$up_dir = make_folder_month(1);
			if($up_dir != ''){
				$uploaddir = '..'.$up_dir;
			}
			else{
				$uploaddir = '../upload/';
				$up_dir = "/upload/";
			}
			$uploadfile = $uploaddir.basename($file_name);
        	if(move_uploaded_file($_FILES['profile']['tmp_name'], $uploadfile))
        	{
				$handle = new Image($uploadfile, 800);
				$handle->resize();				
        	    $member_info['profile'] = $up_dir.$file_name;
				uploadFTP($uploadfile);
        	}	    
    	}
	}
	if($_REQUEST['recommend_id'])
	    $member_info['recommend_id']=$_REQUEST['recommend_id'];
	if($_REQUEST['bank_name'])
		$member_info['bank_name']=$_REQUEST['bank_name'];
	if($_REQUEST['bank_account'])
		$member_info['bank_account']=$_REQUEST['bank_account'];
	if($_REQUEST['bank_owner'])
		$member_info['bank_owner']=$_REQUEST['bank_owner'];
	
	if($_REQUEST['gwc_req'] == "Y"){
		$member_info['gwc_state'] = 1;
		$member_info['gwc_req_leb'] = 1;
		$member_info['gwc_leb'] = 1;
	}
	$sql=" update Gn_Member set ";
	$i=0;
	foreach($member_info as $key=>$v)
	{
	 $bd=$i==(count($member_info)-1)?"":",";
	 $v=$key=="web_pwd"?"password('$v')":"'$v'";
	 $sql.=" $key=$v $bd ";
	 $i++;	 
	}
    $site = explode(".", $_SERVER['SERVER_NAME']);
	$sql.=" where mem_code='{$_REQUEST['join_modify']}' ";
	if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con)))
	{
		if($_REQUEST['gwc_req'] == "Y"){
			$msg = "굿마켓 가입을 환영합니다. 이제부터 여러분은 소비, 판매, 공급을 할수 있는 굿마켓의 굿슈머입니다. 자, 이제 먼저 2만원 이상 소비하면 수백만원 상품도 팔수 있는 판매자가 됩니다. 시작해볼까요?";
		}
		else{
			$msg = '수정완료되었습니다.';
		}
		?>
        <script language="javascript">
		var gwc_req = '<?=$_REQUEST['gwc_req']?>';
		alert('<?=$msg?>');
		$('body,html').animate({
			scrollTop: 0 ,
			}, 100
		);
		if(gwc_req == "Y"){
			location.href = "/iam/mypage.php?gwc_req=Y&show_modal=Y";
		}
		else{
			location.reload();
		}
		</script>
        <?			
	}
}
?>