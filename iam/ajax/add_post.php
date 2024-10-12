<?
include_once "../../lib/rlatjd_fun.php";
$idx = $_POST['content_idx'];
$mode = $_POST['mode'];
$post_content = $_POST['post_content'];
$mem_id = $_POST['mem_id'];
$result = "success";
if($mode == "add") {//댓글 추가
	if($mem_id == ""){
		echo json_encode(array('result'=>'failed','message'=>'login'));
		exit;
	}
	$post_time = date("Y-m-d H:i:s", strtotime("-1 minute"));
	$sql = "select count(*) from Gn_Iam_Post where mem_id = '$mem_id' and content_idx = '$idx' and reg_date > '$post_time'";
	$res = mysqli_query($self_con,$sql);
	$row = mysqli_fetch_array($res);
	$post_count = $row[0];
	if($post_count > 0)
	{
		echo json_encode(array('result'=>'failed','message'=>'댓글/답글은 60초 내에 한 개만 등록하실 수 있습니다.'));
		exit;
	}
	$sql = "select count(*) from Gn_Iam_Post where content_idx = '$idx' and mem_id = '$mem_id'";
	$res = mysqli_query($self_con,$sql);
	$row = mysqli_fetch_array($res);
	$post_count = $row[0];
	if($post_count >= 3)
	{
		echo json_encode(array('result'=>'failed','message'=>'콘텐츠 당 작성 가능한 댓글은 최대 3개입니다.'));
		exit;
	}
	$post_time = date("Y-m-d H:i:s", strtotime("-1 day"));
	$sql = "select count(*) from Gn_Iam_Post where mem_id = '$mem_id' and reg_date > '$post_time'";
	$res = mysqli_query($self_con,$sql);
	$row = mysqli_fetch_array($res);
	$post_count = $row[0];
	if($post_count > 20)
	{
		echo json_encode(array('result'=>'failed','message'=>'최근 24시간 내 댓글수 20개로 제한됩니다.'));
		exit;
	}
	$sql = "insert into Gn_Iam_Post set mem_id = '$mem_id',content_idx = '$idx', content = '$post_content',reg_date=now() ";
	mysqli_query($self_con,$sql);
}else if($mode == "add_reply"){//답글 추가
	if($mem_id == ""){
		echo json_encode(array('result'=>'failed','message'=>'login'));
		exit;
	}
	$post_time = date("Y-m-d H:i:s", strtotime("-1 minute"));
	$sql = "select count(*) from Gn_Iam_Post_Response where mem_id = '$mem_id' and reg_date > '$post_time'";
	$res = mysqli_query($self_con,$sql);
	$row = mysqli_fetch_array($res);
	$post_count = $row[0];
	if($post_count > 0)
	{
		echo json_encode(array('result'=>'failed','message'=>'댓글/답글은 60초 내에 한 개만 등록하실 수 있습니다.'));
		exit;
	}
	$post_time = date("Y-m-d H:i:s", strtotime("-1 day"));
	$sql = "select count(*) from Gn_Iam_Post_Response where mem_id = '$mem_id' and reg_date > '$post_time'";
	$res = mysqli_query($self_con,$sql);
	$row = mysqli_fetch_array($res);
	$post_count = $row[0];
	if($post_count > 40)
	{
		echo json_encode(array('result'=>'failed','message'=>'최근 24시간 내 답글수 40개로 제한됩니다.'));
		exit;
	}
	$post_idx = $_POST['post_idx'];
	$sql = "insert into Gn_Iam_Post_Response set mem_id = '$mem_id',post_idx = '$post_idx', contents = '$post_content',reg_date=now() ";
	mysqli_query($self_con,$sql);
}else if($mode == "read"){//댓글 읽기
	$content_sql = "select mem_id from Gn_Iam_Contents where idx = '$idx'";
	$content_res = mysqli_query($self_con,$content_sql);
	$content_row = mysqli_fetch_array($content_res);
	$card_owner = $content_row['mem_id'];
	if($mem_id == $card_owner){
		$sql = "update Gn_Iam_Post set status = 'Y' where content_idx = '$idx'";
		mysqli_query($self_con,$sql);
		//echo json_encode(array('result'=>'Y'));
	}
	else{
		//echo json_encode(array('result'=>'N'));
		$result = "unread";
	}
	//exit;
}
else if($mode == "del"){//댓글 삭제
	$post_idx = $_POST['post_idx'];
	$sql = "delete from Gn_Iam_Post where id = '$post_idx' ";
	mysqli_query($self_con,$sql);
	$sql = "delete from Gn_Iam_Post_Response where post_idx = '$post_idx' ";
	mysqli_query($self_con,$sql);
}
else if($mode == "delete_reply"){//답글 삭제
	$post_idx = $_POST['post_idx'];
	$sql = "delete from Gn_Iam_Post_Response where id = '$post_idx' ";
	mysqli_query($self_con,$sql);
}
else if($mode == "multi_del"){//댓글 삭제
	$post_idx = $_POST['post_idx'];
	$post_count = count($post_idx);
	for($x = 0; $x < $post_count; $x++) {
		if($post_idx[$x] != "")
		{
			$sql = "delete from Gn_Iam_Post where id = '$post_idx[$x]' ";
			mysqli_query($self_con,$sql);
			$sql = "delete from Gn_Iam_Post_Response where post_idx = '$post_idx[$x]' ";
			mysqli_query($self_con,$sql);
		}
	}
}
else if($mode == "multi_delete_reply"){//답글 삭제
	$post_idx = $_POST['post_idx'];
	$post_count = count($post_idx);
	for($x = 0; $x < $post_count; $x++) {
		if($post_idx[$x] != "")
		{
			$sql = "delete from Gn_Iam_Post_Response where post_id = '$post_idx[$x]' ";
			mysqli_query($self_con,$sql);
		}
	}
}
else if($mode == "edit"){//댓글 수정
	$post_idx = $_POST['post_idx'];
	$sql = "update Gn_Iam_Post set content = '$post_content' where id = '$post_idx' ";
	mysqli_query($self_con,$sql);
}
else if($mode == "edit_reply"){//답글 수정
    $post_idx = $_POST['post_idx'];
    $sql = "update Gn_Iam_Post_Response set contents = '$post_content' where id = '$post_idx' ";
    mysqli_query($self_con,$sql);
}
else if($mode == "lock"){//댓글 차단
	$post_idx = $_POST['post_idx'];
	$sql = "update Gn_Iam_Post set lock_status = 'Y' where id = '$post_idx' ";
	mysqli_query($self_con,$sql);
}
else if($mode == "unlock"){//댓글 차단 해제
	$post_idx = $_POST['post_idx'];
	if($post_idx != 0){
		$sql = "update Gn_Iam_Post set lock_status = 'N' where id = '$post_idx' ";
		mysqli_query($self_con,$sql);
	}else{
		$sql = "update Gn_Iam_Post p INNER JOIN Gn_Iam_Contents cont on cont.idx = p.content_idx set lock_status = 'N' where cont.mem_id = '$mem_id'";
		mysqli_query($self_con,$sql);
	}
	exit;
}
else if($mode == "lock_reply"){//답글 차단
    $post_idx = $_POST['post_idx'];
    $sql = "update Gn_Iam_Post_Response set lock_status = 'Y' where id = '$post_idx' ";
    mysqli_query($self_con,$sql);
}
else if($mode == "unlock_reply"){//답글 차단 해제
    $post_idx = $_POST['post_idx'];
    if($post_idx != 0){
        $sql = "update Gn_Iam_Post_Response set lock_status = 'N' where id = '$post_idx' ";
        mysqli_query($self_con,$sql);
    }else{
        $sql = "update Gn_Iam_Post_Response p INNER JOIN Gn_Iam_Contents cont on cont.idx = p.content_idx set lock_status = 'N' where cont.mem_id = '$mem_id'";
        mysqli_query($self_con,$sql);
    }
    exit;
}

$post_status_sql = "select count(*) from Gn_Iam_Post where content_idx = '$idx' and status = 'N' and lock_status = 'N'";
$post_status_res = mysqli_query($self_con,$post_status_sql);
$post_status_row =  mysqli_fetch_array($post_status_res);
$post_status_count = $post_status_row[0];

$post_sql = "select SQL_CALC_FOUND_ROWS * from Gn_Iam_Post p where p.content_idx = '$idx' and p.lock_status='N' order by p.reg_date";
$post_res = mysqli_query($self_con,$post_sql);
$post_count	=  mysqli_num_rows($post_res);
$post_index = 0;
while($post_row = mysqli_fetch_array($post_res)) {
	$sql_mem = "select profile, mem_name from Gn_Member where mem_id='{$post_row['mem_id']}'";
	$res_mem = mysqli_query($self_con,$sql_mem);
	$row_mem = mysqli_fetch_array($res_mem);

	$arr_res['post_idx'] =$post_row['id'];
	$card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$post_row['mem_id']}' order by req_data limit 0,1";
	$card_res = mysqli_query($self_con,$card_sql);
	$card_row = mysqli_fetch_array($card_res);
	$arr_res['namecard'] =$card_row['card_short_url'];
	$arr_res['profile'] =$row_mem['profile'];
	$arr_res['mem_name'] =$row_mem['mem_name'];
	$arr_res['reg_date'] =$post_row['reg_date'];
	$arr_res['post_content'] =$post_row['content'];
	$arr_res['post_mem_id'] = $post_row['mem_id'];
	$arr_res['mem_id'] = $mem_id;
	$content_sql = "select mem_id from Gn_Iam_Contents where idx = '$idx'";
	$content_res = mysqli_query($self_con,$content_sql);
	$content_row = mysqli_fetch_array($content_res);
	$arr_res['card_mem_id'] = $content_row['mem_id'];
	$reply_sql = "select * from Gn_Iam_Post_Response r where r.post_idx = '{$post_row['id']}' and r.lock_status = 'N' order by r.reg_date";
	$reply_res = mysqli_query($self_con,$reply_sql);
	$reply_index = 0;

	while($reply_row = mysqli_fetch_array($reply_res)){
		$sql_mem_r = "select mem_name, profile from Gn_Member where mem_id='{$reply_row['mem_id']}'";
		$res_mem_r = mysqli_query($self_con,$sql_mem_r);
		$row_mem_r = mysqli_fetch_array($res_mem_r);

        $arr_reply['idx'] =$reply_row['id'];
        $arr_reply['mem_id'] =$reply_row['mem_id'];
		$arr_reply['profile'] =$row_mem_r['profile'];
		$arr_reply['mem_name'] =$row_mem_r['mem_name'];
		$arr_reply['reg_date'] =$reply_row['reg_date'];
		$arr_reply['post_content'] =$reply_row[contents];
		$reply_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$reply_row['mem_id']}' order by req_data limit 0,1";
		$reply_card_res = mysqli_query($self_con,$reply_card_sql);
		$reply_card_row = mysqli_fetch_array($reply_card_res);
		$arr_reply['namecard'] =$reply_card_row['card_short_url'];
		$reply_content[$reply_index++] = $arr_reply;
	}
	$arr_res['reply_content'] = $reply_content;
	$reply_content = "";
	$content[$post_index++] = $arr_res;
}
$receive_sql = "select count(idx) as cnt from Gn_Iam_Contents WHERE (contents_share_text like '%$mem_id%') and DATE(up_data) = DATE(now())";
$receive_result = mysqli_query($self_con,$receive_sql) or die(mysqli_error($self_con));
$r_row = mysqli_fetch_array($receive_result);

$send_sql = "select count(idx) as cnt from Gn_Iam_Contents WHERE mem_id = '$mem_id' and contents_share_text != '' and DATE(up_data) = DATE(now())";
$send_result = mysqli_query($self_con,$send_sql) or die(mysqli_error($self_con));
$s_row = mysqli_fetch_array($send_result);

// $post_sql = "select count(id) as cnt from Gn_Iam_Post p inner join Gn_Iam_Contents c on p.content_idx = c.idx WHERE c.mem_id = '$mem_id' and p.status = 'N'";
// $post_result = mysqli_query($self_con,$post_sql) or die(mysqli_error($self_con));
// $p_row = mysqli_fetch_array($post_result);

$share_recv_count = $r_row['cnt'];
$share_send_count = $s_row['cnt'];
// $share_post_count = $p_row['cnt'];
$share_post_count = 0;
echo json_encode(array('post_count'=>$post_count,'post_status'=>$post_status_count,'result'=>'success','contents'=>$content,'share_recv_count'=>$share_recv_count,'share_send_count'=>$share_send_count,
	'share_post_count'=>$share_post_count));
?>
