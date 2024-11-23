<?
//include_once "../../lib/rlatjd_fun.php";
//include_once "../../lib/class.image.php";
echo json_encode(array('result'=>'failed','msg'=>'test'));
exit;
$up_dir = make_folder_month(2);
if($up_dir != ''){
	$uploaddir = '../..'.$up_dir;
}
else{
	$uploaddir = '../../upload/';
	$up_dir = "/upload/";
}
$mode = $_POST['mode'];
$card_idx = $_POST['card_idx'];
$card_title = $_POST['card_title'];
$card_name = $_POST['card_name'];
$zy = $card_company = str_replace('"',"'",$_POST['card_company']);
$card_position = $_POST['card_position'];
$card_phone = $_POST['card_phone'];
if($card_phone == "")
	$card_phone = $_POST['mobile'];
$card_email = $_POST['card_email'];
$card_addr = $_POST['card_addr'];
$card_map = $_POST['card_map'];
$card_keyword = $_POST['card_keyword'];

$story_title4 = $_POST['story_title4'];
$story_online1_text = $_POST['story_online1_text'];
$story_online2_text = $_POST['story_online2_text'];

$story_online1 = $_POST['story_online1'];
$story_online2 = $_POST['story_online2'];

$online1_check = $_POST['online1_check'];
$online2_check = $_POST['online2_check'];
$story_title1 = $_POST['story_title1'];
$story_title2 = $_POST['story_title2'];
$story_title3 = $_POST['story_title3'];
$story_myinfo = $_POST['story_myinfo'];
$story_company = $_POST['story_company'];
$story_career = $_POST['story_career'];
$contents_count = $_POST['contents_count'];
if($_POST['mem_id'])
    $mem_id = $_POST['mem_id'];
else
    $mem_id = $_SESSION['iam_member_id'];
$date_file_name = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile"]["name"]));
$date_file_name1 = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile1"]["name"]));
$date_file_name2 = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile2"]["name"]));
$date_file_name3 = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile3"]["name"]));

$uploadfile = $uploaddir.basename($date_file_name);
$uploadfile1 = $uploaddir.basename($date_file_name1);
$uploadfile2 = $uploaddir.basename($date_file_name2);
$uploadfile3 = $uploaddir.basename($date_file_name3);
$mem_id = strtolower(trim($mem_id));
$site_info = explode(".",$HTTP_HOST);
$site_name = $site_info[0];
if($site_name == "www")
    $site_name = "kiam";
//회원가입 1단계	
if($_POST['id'] && $_POST['pwd']){
	if(!$_POST['join_modify']){
		$member_info['mem_id']=htmlspecialchars($_POST['id']);
		$member_info['mem_leb']=22;
		$member_info['id_type']="hp";
		$member_info['join_ip']=$ip;
		$member_info['join_way']="APP";
        $member_info['mem_pass']=md5($_POST['pwd']);
	    $member_info['web_pwd']=$_POST['pwd'];
	}
	if($_FILES['profile']) {
    	$tempFile = $_FILES['profile']['tmp_name'];
    	if($tempFile) {
    	    $file_arr=explode(".",$_FILES['profile']['name']);
    	    $tmp_file_arr=explode("/",$tempFile);
			$file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];
			$profile = $uploaddir.$file_name;
        	if(move_uploaded_file($_FILES['profile']['tmp_name'], $profile)){
				$handle = new Image($profile, 800);
				$handle->resize();
				uploadFTP($profile);
        	    $member_info['profile'] = $up_dir.$file_name;
        	}
		}
	}

	if($_POST['mobile'])
	    $member_info['mem_phone']=$_POST['mobile'];

	$member_info['mem_nick']=htmlspecialchars($_POST['name']);
	$member_info['mem_name']=htmlspecialchars($_POST['name']);
	$member_info['mem_email']=$_POST['email_1']."@".$_POST['email_2'];
	$member_info['mem_add1']=$_POST['add1'];
	$member_info['zy']=$_POST['zy'];
	$member_info['mem_birth']=$_POST['birth_1']."-".$_POST['birth_2']."-".$_POST['birth_3'];
	$member_info['is_message']=$_POST['is_message'];
    $member_info['site_iam'] = $site_name;
	if($_POST['recommend_id']){
		$member_info['recommend_id']=$_POST['recommend_id'];
	}else{
		if($HTTP_HOST == 'kiam.kr' )
			$member_info['recommend_id'] = 'onlyone';
		else{
			$sql = "select mem_id from Gn_Iam_Service where sub_domain like '%http://".$HTTP_HOST."%'";
			$res = mysqli_query($self_con,$sql);
			$row = mysqli_fetch_array($res);
			$member_info['recommend_id'] = $row['mem_id'];
		}
	}
    if($HTTP_HOST != "kiam.kr")
        $query = "select * from Gn_Iam_Service where sub_domain like '%".$HTTP_HOST."'";
	else
		$query = "select * from Gn_Iam_Service where sub_domain like '%www.kiam.kr%'";
	$res = mysqli_query($self_con,$query);
	$domainData = mysqli_fetch_array($res);
	$parse = parse_url($domainData['sub_domain']);
	$site = explode(".", $parse['host']);

	$query = "select count(*) as cnt from Gn_Member where site_iam='".$site[0]."'";
	$res = mysqli_query($self_con,$query);
	$data = mysqli_fetch_array($res);
	if($domainData['mem_cnt'] <= $data[0] && !$_POST['join_modify']) {
		$result = '본 사이트에서는 더이상 회원가입이 되지 않습니다.최대회원수는 ' .$domainData['mem_cnt'].'입니다.관리자에게 문의해주세요';
		echo json_encode(array('result'=>'failed','msg'=>$result));
		exit;
	}
	$s_sql = "select count(idx) from Gn_Service where sub_domain like '%http://".$HTTP_HOST."%'";
	$s_res = mysqli_query($self_con,$s_sql);
	$s_row = mysqli_fetch_array($s_res);
	if($s_row[0] == 0){
		$r_sql = "select mem_code,site from Gn_Member where mem_id = '{$member_info['recommend_id']}'";
		$r_res = mysqli_query($self_con,$r_sql);
		$r_row = mysqli_fetch_array($r_res);
		$member_info['site'] = $r_row['site'];
	}else{ 
		$member_info['site'] = $member_info['site_iam'];
	}
	if($_POST['recommend_branch'])
	    $member_info['recommend_branch']=$_POST['recommend_branch'];
	$member_info['mem_sex']=$_POST['mem_sex'];
	if($_POST['bank_name'])
	    $member_info['bank_name']=$_POST['bank_name'];
	if($_POST['bank_account'])
	    $member_info['bank_account']=$_POST['bank_account'];
	if($_POST['bank_owner'])
	    $member_info['bank_owner']=$_POST['bank_owner'];
	if($domainData['service_type'] == 3){
		$member_info['exp_start_status']=1;
		$member_info['exp_mid_status']=1;
		$member_info['exp_limit_status']=1;
		$member_info['exp_limit_date']=date("Y-m-d H:i:s",strtotime("+{$domainData['service_price']} days"));
		$member_info['iam_type']=1;
		$member_info['iam_card_cnt']=5;
		$member_info['iam_share_cnt']=1000;
	}
    if(!$_POST['join_modify']) {
        if($domainData['service_type'] == 3) {
            $member_info['iam_card_cnt'] = 5;
            $member_info['iam_share_cnt'] = 1000;
        }else{
            $member_info['iam_card_cnt'] = $domainData['iamcard_cnt'];
            $member_info['iam_share_cnt'] = $domainData['send_content'];
        }
    }
	if(!$_POST['join_modify']) {
	    if($_POST['rnum'] == "" && $_POST['code'] == "KR") {
			echo json_encode(array('result'=>'failed','msg'=>'인증번호가 없습니다.'));
			exit;
	    }
	    if($_POST['rnum'] != ""  && $_POST['code'] == "KR") {
            $sql="select * from Gn_Member_Check_Sms where mem_phone='{$member_info['mem_phone']}' and secret_key='{$_POST['rnum']}' and status='Y' order by idx desc";
            $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $data = mysqli_fetch_array($result);
            
            $query = "select count(idx) from Gn_MMS where content like '%{$_POST['rnum']}%' and type='10' order by idx desc";
            $res = mysqli_query($self_con,$query) or die(mysqli_error($self_con));
            $row = mysqli_fetch_array($res);
            if($data['idx'] == "" && $row[0] == 0) {
	            echo json_encode(array('result'=>'failed','msg'=>'인증번호를 확인해주세요..'));
				exit;
	        }
	    }
	}
	if($_POST['join_modify'])
		$sql=" update Gn_Member set ";
	else
		$sql=" insert into Gn_Member set ";

	$i=0;
	foreach($member_info as $key=>$v){
	 	$bd=$i==(count($member_info)-1)?"":",";
		//password()
	 	$v=$key=="web_pwd"?"md5('$v')":"'$v'";
	 	$sql.=" $key=$v $bd ";
	 	$i++;
	}
	if($_POST['join_modify'])
		$sql.=" where mem_code='{$_POST['join_modify']}' ";
	else
		$sql.=" ,first_regist=now() , mem_check=now() ";
	if(strstr($sql, "update") == true && $_POST['join_modify'] == "")
		$sql.=" where mem_code='{$_POST['join_modify']}' ";
	//echo json_encode(array('result'=>'failed','msg'=>$sql));
	//exit;
	if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con))){
		if($_POST['join_modify']){
			$_SESSION['iam_member_leb'] = 0;?>
			<script language="javascript">
			alert('수정완료되었습니다.');
			location='mypage.php';
			</script>
        <?}else{
        	    $mem_id = $mem_id?$mem_id:$_POST['id'];
			$sql="select mem_leb, iam_leb,site, site_iam from Gn_Member where mem_id= '{$_POST['id']}'";
			$resul=mysqli_query($self_con,$sql);
			$row=mysqli_fetch_array($resul);
			// 관리자 권한이 있으면 관리자 세션 추가 Add Cooper
			$admin_sql = "select mem_id from Gn_Admin where mem_id= '{$_POST['id']}'";
			$admin_result = mysqli_query($self_con,$admin_sql);
			$admin_row = mysqli_fetch_array($admin_result);
			if ($admin_row[0] != "") {
				$_SESSION['one_member_admin_id'] = $_POST['id'];
			}
			if($row['site'] != "") {
				$_SESSION['one_member_id'] = $_POST['id'];
				$_SESSION['one_mem_lev'] = $row['mem_leb'];
				$service_sql = "select mem_id,sub_domain from Gn_Service where mem_id= '{$_POST['id']}'";
				$service_result = mysqli_query($self_con,$service_sql);
				$service_row = mysqli_fetch_array($service_result);
				if ($service_row['mem_id'] != "") {
					$url = parse_url($service_row['sub_domain']);
					$_SESSION['one_member_subadmin_id'] = $_POST['id'];
					$_SESSION['one_member_subadmin_domain'] = $url['host'];
				}
			}
			if($row['site_iam'] != ""){
				$_SESSION['iam_member_id'] = $_POST['id'];
				$_SESSION['iam_member_leb'] = $row['iam_leb'];
				$iam_sql = "select mem_id,sub_domain from Gn_Iam_Service where mem_id= '{$_POST['id']}'";
				$iam_result = mysqli_query($self_con,$iam_sql);
				$iam_row = mysqli_fetch_array($iam_result);
				if ($iam_row['mem_id'] != "") {
					$url = parse_url($iam_row['sub_domain']);
					$_SESSION['iam_member_subadmin_id'] = $_POST['id'];
					$_SESSION['iam_member_subadmin_domain'] = $url['host'];
				}
			}
            $query = "select iam_card_cnt from Gn_Member where mem_id='$mem_id'";
            $res = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($res);

            $query = "select count(*) as cnt from Gn_Iam_Name_Card where group_id = 0 and mem_id='".$mem_id."'";
            $res = mysqli_query($self_con,$query);
            $data = mysqli_fetch_array($res);
            if($row['iam_card_cnt'] <= $data[0]) {
                $result = "고객님의 아이엠은 {$row['iam_card_cnt']}개의 카드를 사용할수 있습니다. 추가하시려면 관리자에게 문의하세요.";
                echo json_encode(array('result'=>$result));
                exit;
            }
            if(move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadfile)){
				$handle = new Image($uploadfile, 800);
				$handle->resize();
				uploadFTP($uploadfile);
                $img_url = $up_dir.basename($date_file_name);
            } else{
                if($_POST['logo_link'] == "")
                    $img_url = "/iam/img/common/logo-2.png";
                else
                    $img_url = $_POST['logo_link'];
            }
            if(move_uploaded_file($_FILES['uploadFile1']['tmp_name'], $uploadfile1)){
				$handle = new Image($uploadfile1, 800);
				$handle->resize();
				uploadFTP($uploadfile1);
                $img_url1 = $up_dir.basename($date_file_name1);
            }else{
                $img_url1 = $_POST['main_img1_link'];
            }
            if(move_uploaded_file($_FILES['uploadFile2']['tmp_name'], $uploadfile2)){
				$handle = new Image($uploadfile2, 800);
				$handle->resize();
				uploadFTP($uploadfile2);
                $img_url2 = $up_dir.basename($date_file_name2);
            }else{
                $img_url2 = $_POST['main_img2_link'];
            }
            if(move_uploaded_file($_FILES['uploadFile3']['tmp_name'], $uploadfile3)){
				$handle = new Image($uploadfile3, 800);
				$handle->resize();
				uploadFTP($uploadfile3);
                $img_url3 = $up_dir.basename($date_file_name3);
            }else{
                $img_url3 = $_POST['main_img3_link'];
            }
            $short_url = generateRandomString();

            if($card_phone == "--") {
                $sql="select mem_phone from Gn_Member where mem_id = '$mem_id'";
                $result = mysqli_query($self_con,$sql);
                $row = mysqli_fetch_array($result);
                $card_phone = $row['mem_phone'];
            }
            if($card_title != "") {
                $iam_sql = "select count(*) from Gn_Iam_Info where mem_id = '$mem_id'";
                $iam_result = mysqli_query($self_con,$iam_sql);
                $iam_row = mysqli_fetch_array($iam_result);
                if($iam_row[0] == 0) {
                    $query_info = "insert into Gn_Iam_Info (mem_id,main_img1,main_img2,main_img3, reg_data) values ('$memid','$profile_image[0]','$profile_image[1]','$profile_image[2]', now())";
                    mysqli_query($self_con,$query_info);
                }
                $sql2 = "insert into Gn_Iam_Name_Card (mem_id, card_title, card_short_url, card_name, card_company, card_position, card_phone, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title4, story_online1_text,".
                            "story_online1, online1_check, story_online2_text, story_online2, online2_check,req_data,main_img1,main_img2,main_img3,story_title1,story_title2,story_title3,story_myinfo,story_company,story_career)".
                        "values ('$mem_id', '$card_title', '$short_url', '$card_name', '$card_company', '$card_position', '$card_phone', '$card_email', '$card_addr', '$card_map', '$card_keyword', '$img_url', 0, '$story_title4',".
                            "'$story_online1_text','$story_online1', '$online1_check', '$story_online2_text', '$story_online2', '$online2_check', now(),'$img_url1','$img_url2','$img_url3',".
                            "'$story_title1','$story_title2','$story_title3','$story_myinfo','$story_company','$story_career')";
                $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
				$mem_sql = "select profile from Gn_Member where mem_id = '$mem_id'";
				$mem_res = mysqli_query($self_con,$mem_sql);
				$mem_row = mysqli_fetch_array($mem_res);
				if($mem_row['profile'] == ""){
					mysqli_query($self_con,"update Gn_Member set profile = '$img_url' where mem_id = '$mem_id'");
				}
				$card_idx = mysqli_insert_id($self_con);
				for($cont_count = 1;$cont_count <= $contents_count;$cont_count++){
					$content_file_name = date('dmYHis').str_replace(" ", "", basename($_FILES["contents_img".$cont_count]["name"]));
					$content_uploadfile = $uploaddir.basename($content_file_name);
					if(move_uploaded_file($_FILES["contents_img".$cont_count]['tmp_name'], $content_uploadfile)){
						$handle = new Image($content_uploadfile, 800);
						$handle->resize();
						uploadFTP($content_uploadfile);
						$contents_img = $up_dir.basename($content_file_name);
					}
					$contents_title = $_POST["contents_title".$cont_count];
					$contents_url = $_POST["contents_url".$cont_count];
					$contents_desc = $_POST["contents_desc".$cont_count];
					if($contents_img != "" || $contents_title != "" ||	$contents_url != "" ||	$contents_desc != "") {
						$sql = "insert into Gn_Iam_Contents (
													  mem_id,
													  contents_type,
													  contents_img,
													  contents_title,
													  contents_url,
													  contents_desc,
													  contents_display,
													  contents_westory_display,
													  contents_user_display,
													  contents_type_display,
													  contents_footer_display,
													  public_display,
													  req_data,
													  up_data,
													  card_short_url,
													  westory_card_url,
													  card_idx
													  )values
													  ('$mem_id',
													  1,
													  '$contents_img',
													  '$contents_title',
													  '$contents_url',
													  '$contents_desc',
													  'Y',
													  'Y',
													  'Y',
													  'Y',
													  'Y',
													  'Y',
													  now(),
													  now(),
													  '$short_url',
													  '$short_url',
													  '$card_idx'
													  )";
						mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        $contents_idx = mysqli_insert_id($self_con);
                        $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$contents_idx,card_idx=$card_idx,main_card=$card_idx";
                        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
						$sql2 = "update Gn_Iam_Name_Card set up_data = now() where idx={$card_idx}";
               			mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
					}
				}
			}
			$sql="select * from Gn_MMS_Group where mem_id='{$member_info['mem_id']}' and grp='아이엠'";
			$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
			$data = mysqli_fetch_array($result);
			if($data['idx'] == ""){
				$query = "insert into Gn_MMS_Group set mem_id='{$member_info['mem_id']}', grp='아이엠', reg_date=NOW()";
				mysqli_query($self_con,$query);
			}

			$content = $_POST['name']."님 온리원문자 회원이 되신걸 환영합니다.";
			$subject = "온리원문자 회원가입";
            //email_send($member_info['mem_email'],"admin@kiam.kr",$subject,$content);
			echo json_encode(array('result'=>'success'));
			exit;
		}
	}
}
if($_POST['post_alert']){
	$sql = "update Gn_Member set last_regist = now() where mem_id = '$mem_id'";
	mysqli_query($self_con,$sql);
	echo json_encode(array("result"=>"success"));
	exit;
}
if($_POST['type'] == "show_exp_popup"){
	$popup_type = $_POST['popup_type'];
	if($popup_type == 1)
		$sql = "update Gn_Member set exp_start_status = 0 where mem_id = '$mem_id'";
	else if($popup_type == 2)
		$sql = "update Gn_Member set exp_mid_status = 0 where mem_id = '$mem_id'";
	else if($popup_type == 3)
		$sql = "update Gn_Member set exp_limit_status = 0 where mem_id = '$mem_id'";
	else if($popup_type == 4)
		$sql = "update Gn_Member set exp_limit_date = NULL,iam_type=0,iam_card_cnt=1,iam_share_cnt=0 where mem_id = '$mem_id'";
	mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
	echo json_encode(array('result'=>"success"));
	exit;
}
if($_POST['type'] == "get_block_data"){
    $sql = "select block_user,block_contents from Gn_Iam_Info where mem_id='$mem_id'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    if($row['block_user'] == "")
        $block_users = null;
    else
        $block_users = explode(",",$row['block_user']);
    if($row['block_contents'] == "")
        $block_contents = null;
    else
        $block_contents = explode(",",$row['block_contents']);
    $block_users_array = array();
    foreach ($block_users as $user_id){
        $sql = "select profile,mem_name,site_iam,mem_code from Gn_Member where mem_id = '$user_id'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $profile = $row['profile'];
        $name = $row['mem_name'];
        $site = $row['site_iam'];
        $mem_code = $row['mem_code'];

        $sql = "select card_short_url,main_img1 from Gn_Iam_Name_Card where group_id = 0 and mem_id = '$user_id' order by req_data limit 0,1";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $card_short_url = $row['card_short_url'];
        if(!$profile)
            $profile = $row['main_img1'];
        if($site == "kiam")
            $link = "http://www.kiam.kr";
        else
            $link = "http://".$site.".kiam.kr";
        $link .= "/?".$card_short_url.$mem_code;
        $user_array = array("profile"=>$profile,"name"=>$name,"id"=>$user_id,"link"=>$link,"idx"=>$user_id);
        array_push($block_users_array,$user_array);
    }
    $block_contents_array = array();
    foreach ($block_contents as $contents_idx){
        $sql = "select mem_id from Gn_Iam_Contents where idx = '$contents_idx'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $user_id = $row['mem_id'];

        $sql = "select profile,mem_name,site_iam from Gn_Member where mem_id = '$user_id'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $profile = $row['profile'];
        $name = $row['mem_name'];
        $site = $row['site_iam'];

        $sql = "select main_img1 from Gn_Iam_Name_Card where card_idx = '$contents_idx'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        if(!$profile)
            $profile = $row['main_img1'];
        if($site == "kiam")
            $link = "http://www.kiam.kr";
        else
            $link = "http://".$site.".kiam.kr";
        $link .= "/iam/contents.php?contents_idx=".$contents_idx;
        $cont_array = array("profile"=>$profile,"name"=>$name,"id"=>$user_id,"link"=>$link,"idx"=>$contents_idx);
        array_push($block_contents_array,$cont_array);
    }
    echo json_encode(array('block_users'=>$block_users_array,'block_contents'=>$block_contents_array));
    exit;
}
if($_POST['type'] == "remove_all_block_data") {
    $sql = "update Gn_Iam_Info set block_user=NULL,block_contents = NULL where mem_id='$mem_id'";
    mysqli_query($self_con,$sql);
    echo json_encode(array('result'=>"success"));
    exit;
}
if($_POST['type'] == "remove_one_block_data") {
    $block_type = $_POST['block_type'];
    $block_idx = $_POST['block_idx'];
    if ($block_type == 1){
        $sql = "select block_user from Gn_Iam_Info where mem_id='$mem_id'";
    }else{
        $sql = "select block_contents from Gn_Iam_Info where mem_id='$mem_id'";
    }
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    $block_array = explode(",",$row[0]);
    $del_ids = array($block_idx);
    $block_ids = array_diff($block_array,$del_ids);
    $block_data = implode(",",$block_ids);
    if ($block_type == 1){
        $sql = "update Gn_Iam_Info set block_user = '$block_data'  where mem_id='$mem_id'";
    }else{
        $sql = "update Gn_Iam_Info set block_contents  = '$block_data' where mem_id='$mem_id'";
    }
    mysqli_query($self_con,$sql);
    echo json_encode(array('result'=>"success"));
    exit;
}

?>
