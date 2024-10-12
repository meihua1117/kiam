<?php
include_once "../../lib/rlatjd_fun.php";

// 자료가 많을 경우 대비 설정변경
set_time_limit(0);
ini_set('memory_limit', '50M');

if ($_FILES['excelfile']['tmp_name']) {
	$file = $_FILES['excelfile']['tmp_name'];
	include_once "../../excel_down/Classes/reader.php";

	$data = new Spreadsheet_Excel_Reader();

	// Set output Encoding.
	$data->setOutputEncoding('UTF-8');

	$data->read($file);
	error_reporting(E_ALL ^ E_NOTICE);

	for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
		if (trim($data->sheets[0]['cells'][$i][1]) == '')
			continue;

		$total_count++;

		$j = 1;

		$img_link = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //이미지
		$contents_title = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //상품제목
		$contents_desc = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //상품설명
		$contents_url = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //상품링크
		$contents_price = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //시중가
		$contents_sell_price = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //할인가
		$send_provide_price = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //공급가
		$prod_manufact_price = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //생산가
		$send_salary_price = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //배송요
		$deliver_id = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //배송아이디
		$card_no = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //카드번호
		$product_seperate = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //상품분류

		$reduce_val = 100 - ($contents_sell_price / $contents_price) * 100;

		$product_code = generateRandomCode();
		$date = date('Ymd-Hi') . "s";
		$model = $prod_name . "+" . substr($date, 2, -1);

		$card_no = $card_no * 1 - 1;
		$sql_card_idx = "select card_short_url,idx from Gn_Iam_Name_Card where mem_id = 'iamstore' and idx not in(934328, 2477701, 1274691, 1268514) order by req_data asc limit " . $card_no . ", 1";
		$res_card_idx = mysqli_query($self_con, $sql_card_idx);
		$row_card_idx = mysqli_fetch_array($res_card_idx);

		$sql = "select max(contents_order) from Gn_Iam_Contents_Gwc where mem_id = '{$_SESSION['iam_member_id']}' and card_idx = '{$row_card_idx['idx']}'";
		$result = mysqli_query($self_con, $sql);
		$comment_row = mysqli_fetch_array($result);
		$contents_order = (int)$comment_row[0] + 1;

		$sql_deliver_sql = "select mem_code from Gn_Member where mem_id='{$deliver_id}'";
		$res_deliver = mysqli_query($self_con, $sql_deliver_sql);
		$row_deliver = mysqli_fetch_array($res_deliver);

		$sql2 = "insert into Gn_Iam_Contents_Gwc (
			mem_id, 
			contents_type, 
			contents_img, 
			contents_title, 
			contents_url, 
			contents_price, 
			contents_sell_price, 
			contents_desc,
			contents_type_display,
			contents_westory_display, 
			contents_user_display, 
			contents_footer_display, 
			req_data, 
			up_data,
			card_short_url,
			westory_card_url,
			public_display,
			card_idx,
			contents_order,
			gwc_con_state,
			init_reduce_val,
			landing_mode,
			share_send_cont,
			reduce_val,
			product_code,
			product_model_name,
			product_seperate,
			send_provide_price,
			prod_manufact_price,
			send_salary_price,
			ai_map_gmarket,
			provider_req_prod,
			delivery_id_code,
			group_display) values 
			('{$_SESSION['iam_member_id']}',
			\"3\", 
			\"$img_link\", 
			\"$contents_title\", 
			\"$contents_url\", 
			\"$contents_price\", 
			\"$contents_sell_price\", 
			\"$contents_desc\",
			\"\",
			\"Y\", 
			\"Y\", 
			\"Y\", 
			now(),
			now(),
			\"{$row_card_idx['card_short_url']}\",
			\"{$row_card_idx['card_short_url']}\",
			\"N\",
			\"{$row_card_idx['idx']}\",
			$contents_order,
			'1',
			'$reduce_val',
			\"Y\",
			\"$contents_idx\",
			'$reduce_val',
			'$product_code',
			'$model',
			'$product_seperate',
			'$send_provide_price',
			'$prod_manufact_price',
			'$send_salary_price',
			'3',
			'Y',
			'$row_deliver[0]',
			'Y'
			)";
		$result2 = mysqli_query($self_con, $sql2) or die(mysqli_error($self_con));
		$content_idx = mysqli_insert_id($self_con);
		$sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$content_idx,card_idx={$row_card_idx['idx']},main_card={$row_card_idx['idx']}";
		mysqli_query($self_con, $sql2) or die(mysqli_error($self_con));
	}
	echo "<script>alert('등록되었습니다.'); location.href='/iam/req_provider_list.php';</script>";
}
