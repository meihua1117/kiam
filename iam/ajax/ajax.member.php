<?
include_once "../../lib/rlatjd_fun.php";
include_once "../../lib/class.image.php";
//회원가입 1단계
if($_REQUEST[nick])
{	
	$member_info[mem_nick]=htmlspecialchars($_REQUEST[nick]);
	$member_info['mem_name']=htmlspecialchars($_REQUEST['name']);
	$member_info['mem_email']=$_REQUEST[email_1]."@".$_REQUEST[email_2];
	$member_info[mem_add1]=$_REQUEST[add1];
	$member_info[zy]=$_REQUEST[zy];
	$member_info[mem_sch]=$_REQUEST[mem_sch];
	$member_info[keywords]=$_REQUEST[keywords];
	$member_info[mem_birth]=$_REQUEST[birth_1]."-".$_REQUEST[birth_2]."-".$_REQUEST[birth_3];

	if($_REQUEST[is_message])
		$member_info[is_message]="Y";
	else
		$member_info[is_message]="N";
	if($_FILES[profile]) {
    	$tempFile = $_FILES[profile]['tmp_name'];
    	if($tempFile) {
    	    $file_arr=explode(".",$_FILES[profile]['name']);
    	    $tmp_file_arr=explode("/",$tempFile);
    	    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];	
			$up_dir = make_folder_month(2);
			if($up_dir != ''){
				$uploaddir = '../..'.$up_dir;
			}
			else{
				$uploaddir = '../../upload/';
				$up_dir = "/upload/";
			}
    	    $upload_file = $uploaddir.$file_name;
        	if(move_uploaded_file($_FILES['profile']['tmp_name'], $upload_file))
        	{
				$handle = new Image($upload_file, 800);
				$handle->resize();
        	    $member_info[profile] = $up_dir.$file_name;
				uploadFTP($upload_file);
        	}	    
    	}
	}
	if($_REQUEST[recommend_id])
	    $member_info[recommend_id]=$_REQUEST[recommend_id];
	if($_REQUEST[bank_name])
		$member_info[bank_name]=$_REQUEST[bank_name];
	if($_REQUEST[bank_account])
		$member_info[bank_account]=$_REQUEST[bank_account];
	if($_REQUEST[bank_owner])
		$member_info[bank_owner]=$_REQUEST[bank_owner];
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
	$sql.=" where mem_code='$_REQUEST[join_modify]' ";
	if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con)))
	{
                $sql2 = "update Gn_Iam_Name_Card set card_email='{$member_info['mem_email']}',
                                                     card_addr='{$member_info['mem_add1']}',
                                                     card_company='{$member_info['zy']}'
                where mem_id='$mem_id'";
                $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
	    //메일, 주소, 소속직책
		?>
        <script language="javascript">
		alert('수정완료되었습니다.');
		location.reload();
		</script>
        <?			
	}
}
?>