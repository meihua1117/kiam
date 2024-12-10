<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/common_func.php";
$userId = strtolower(trim($_REQUEST["id"]));
$useType = trim($_REQUEST["useType"]);
if ($useType == '1') {
	//insert image
	if (isset($_POST['idx']) && $_POST['idx'] > 0) {
		$idx = time() . "_" . rand(1000, 9999);
		$file = explode(".", $_FILES['photo']['name']);
		$filename = $_FILES['photo']['name'];
		$filePath = $_FILES['photo']['tmp_name'];
		$exif = exif_read_data($_FILES['photo']['tmp_name']);
		$image = imagecreatefromjpeg($filePath); // provided that the image is jpeg. Use relevant function otherwise
		if (!empty($exif['Orientation'])) {
			$imageResource = imagecreatefromjpeg($filePath); // provided that the image is jpeg. Use relevant function otherwise
			switch ($exif['Orientation']) {
				case 3:
					$image = imagerotate($imageResource, 180, 0);
					break;
				case 6:
					$image = imagerotate($imageResource, -90, 0);
					break;
				case 8:
					$image = imagerotate($imageResource, 90, 0);
					break;
				default:
					$image = $imageResource;
			}
		}
		imagejpeg($image, $filename, 90);
		$comment = trim($_REQUEST["comment"]);
		$up_dir = make_folder_month(1);
		if ($up_dir != '') {
			$uploaddir = '..' . $up_dir;
		} else {
			$uploaddir = '../upload/';
			$up_dir = "/upload/";
		}
		$file_path = $uploaddir . $idx . '.' . $file[1]; //이미지화일명은 인덱스번호로 지정
		if (move_uploaded_file($filePath, $file_path)) {
			unlink($filename);
			$img_url = 'http://www.kiam.kr' . $up_dir . $idx . '.' . $file[1];
			$sql = "insert into Gn_Member_card (mem_id , img_url, comment, create_time) values ('{$userId}' ,'{$img_url}' , '{$comment}' , now())";
			mysqli_query($self_con, $sql);
			$paper_seq = mysqli_insert_id($self_con);
			
			$select_user = "select * from Gn_Member_card where mem_id = '{$userId}' and type = 0 order by create_time desc limit 1";
			$resul_p = mysqli_query($self_con, $select_user);
			$row_p = mysqli_fetch_array($resul_p);

			if ((!$row_p['name'] || !$row_p['job'] || !$row_p['org_name'] || !$row_p['address'] || !$row_p['mobile'] || !$row_p['email1']) && $row_p['comment']) {
				$cur_url = "http://aiserver.kiam.kr:8080/get_profile_info.php";
				$postvars = 'box_text=' . $row_p['comment'];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $cur_url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);

				$is_success = false;
				$cnt = 0;

				while (!$is_success && $cnt < 3) {
					$response = curl_exec($ch);
					$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					$cnt++;
					if ($status_code == 200) {

						$response = str_replace("```", "", $response);
						$response = str_replace("json", "", $response);

						$responseArray = json_decode($response, true);

						if (isset($responseArray['choices'][0]['message']['content'])) {
							$jsonData = json_decode($responseArray['choices'][0]['message']['content'], true);
							//var_dump($jsonData);
							if (is_array($jsonData)) {
								foreach ($jsonData as $key => $value) {
									if (is_array($value)) {
										$result = implode(", ", $value);
										$row_p[$key] = $result;
									} else {
										$row_p[$key] = $value;
									}
								}
								$is_success = true;
							}
						}
					}
				}
				curl_close($ch);
				if (!$is_success) {

					$arr = explode("\n", $row_p['comment']);
					for ($i = 0; $i < count($arr); $i++) {
						$pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
						$phonePattern = '/[0-9]{3}[\-][0-9]{4}[\-][0-9]{4}|[0-9]{3}[\.][0-9]{4}[\.][0-9]{4}|[0-9]{3}[\s][0-9]{4}[\s][0-9]{4}|[0-9]{3}[\-][\s][0-9]{4}[\-][\s][0-9]{4}|[0-9]{3}[\.][\s][0-9]{4}[\.][\s][0-9]{4}/';
						$faxPatter = '/[0-9]{2}[\-][0-9]{4}[\-][0-9]{4}|[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}|[0-9]{2}[\s][0-9]{4}[\s][0-9]{4}|[0-9]{2}[\.][0-9]{4}[\.][0-9]{4}|[0-9]{3}[\s][0-9]{3}[\s][0-9]{4}|[0-9]{3}[\.][0-9]{3}[\.][0-9]{4}|[0-9]{4}[\-][0-9]{3}[\-][0-9]{4}|[0-9]{2}[\-][0-9]{3}[\-][0-9]{4}/';
						preg_match_all($phonePattern, $arr[$i], $matches1);
						// $matches1 = $matches1[0];
						if ($matches1[0] != NULL) {
							if (strstr($matches1[0][0], "010")) {
								$mobile = $matches1[0][0];
								$mobile = str_replace(" ", "", $mobile);
								$mobile = str_replace("-", "", $mobile);
								$row_p['mobile'] = str_replace(".", "", $mobile);
							} else {
								$row_p['phone1'] = $matches1[0][0];
							}
							// echo $mobile.">>".$phone;
						}

						if (strstr($arr[$i], "F")) {
							preg_match_all($faxPatter, $arr[$i], $matches_fax);
							if ($matches_fax[0] != NULL) {
								$row_p['fax'] = $matches_fax[0][0];
								// echo "fax".$fax;
							}
						}

						preg_match_all($pattern, $arr[$i], $matches);
						if ($matches[1] != NULL) {
							$row_p['email1'] = $matches[0][0];
							// echo $email;
						}

						$name = str_replace(" ", "", trim($arr[$i]));
						if (mb_strlen($name, 'utf-8') == 3) {
							$row_p['name'] = trim($arr[$i]);
							// echo $name;
						}

						$addr_c = trim($arr[$i]);
						if ((strstr($addr_c, "시") && strstr($addr_c, "구") && strstr($addr_c, "로")) || (strstr($addr_c, "시") && strstr($addr_c, "구")) || (strstr($addr_c, "시") && strstr($addr_c, "로")) || (strstr($addr_c, "구") && strstr($addr_c, "로"))) {
							$row_p['address'] = trim($arr[$i]);
							// echo $address;
						}
					}
				}
			}
			//연락처 추가
			$mem_sql = "select mem_phone from Gn_Member where mem_id='{$userId}'";
			$mem_res = mysqli_query($self_con, $mem_sql);
			$mem_row = mysqli_fetch_assoc($mem_res);
			$mem_phone = str_replace('-', '', $mem_row['mem_phone']);
			$sql_grp_id = "select idx from Gn_MMS_Group where mem_id='{$userId}' and grp='아이엠'";
			$res_grp = mysqli_query($self_con, $sql_grp_id);
			$row_grp = mysqli_fetch_array($res_grp);
			$sql_insert = "insert into Gn_MMS_Receive_Iam set grp_id='{$row_grp['idx']}', mem_id='{$userId}', grp='아이엠', grp_2='아이엠', send_num='{$mem_phone}',recv_num='{$row_p['mobile']}', name='{$row_p['name']}',reg_date=now(), iam=1, paper_yn=1, paper_seq='{$paper_seq}'";
			$res = mysqli_query($self_con, $sql_insert);
			////////////////////////////////////
			if ($row_p['mem_id']) {
				$result = array();
				$result['result'] = true;
				$result['img_url'] = $row_p['img_url'];
				$result['seq'] = $row_p['seq'];
				$result['comment'] = $row_p['comment'];
				$result['name'] = $row_p['name'];
				$result['job'] = $row_p['job'];
				$result['org_name'] = $row_p['org_name'];
				$result['address'] = $row_p['address'];
				$result['phone1'] = $row_p['phone1'];
				$result['phone2'] = $row_p['phone2'];
				$result['mobile'] = $row_p['mobile'];
				$result['fax'] = $row_p['fax'];
				$result['email1'] = $row_p['email1'];
				$result['email2'] = $row_p['email2'];
				$result['memo'] = $row_p['memo'];
				echo json_encode($result);
			} else {
				$result = "error";
				$img_url = "none";
				echo json_encode(array("result" => $result, "img_url" => $img_url));
			}
		} else {
			echo json_encode(array("result" => "error"));
		}
	} else {
		echo json_encode(array("result" => "error"));
	}
} else if ($useType == '2') {
	//get image
	$select_user = "select * from Gn_Member_card where mem_id = '{$userId}' and type = 0 order by create_time desc limit 1";
	$resul_p = mysqli_query($self_con, $select_user);
	$row_p = mysqli_fetch_array($resul_p);

	if ((!$row_p['name'] || !$row_p['job'] || !$row_p['org_name'] || !$row_p['address'] || !$row_p['mobile'] || !$row_p['email1']) && $row_p['comment']) {

		$cur_url = "http://aiserver.kiam.kr:8080/get_profile_info.php";
		$postvars = 'box_text=' . $row_p['comment'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $cur_url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);

		$is_success = false;
		$cnt = 0;

		while (!$is_success && $cnt < 3) {
			$response = curl_exec($ch);
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$cnt++;
			if ($status_code == 200) {

				$response = str_replace("```", "", $response);
				$response = str_replace("json", "", $response);

				$responseArray = json_decode($response, true);

				if (isset($responseArray['choices'][0]['message']['content'])) {
					$jsonData = json_decode($responseArray['choices'][0]['message']['content'], true);
					//var_dump($jsonData);
					if (is_array($jsonData)) {
						foreach ($jsonData as $key => $value) {
							if (is_array($value)) {
								$result = implode(", ", $value);
								$row_p[$key] = $result;
							} else {
								$row_p[$key] = $value;
							}
						}
						$is_success = true;
					}
				}
			}
		}
		curl_close($ch);

		if (!$is_success) {

			$arr = explode("\n", $row_p['comment']);
			for ($i = 0; $i < count($arr); $i++) {
				$pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
				$phonePattern = '/[0-9]{3}[\-][0-9]{4}[\-][0-9]{4}|[0-9]{3}[\.][0-9]{4}[\.][0-9]{4}|[0-9]{3}[\s][0-9]{4}[\s][0-9]{4}|[0-9]{3}[\-][\s][0-9]{4}[\-][\s][0-9]{4}|[0-9]{3}[\.][\s][0-9]{4}[\.][\s][0-9]{4}/';
				$faxPatter = '/[0-9]{2}[\-][0-9]{4}[\-][0-9]{4}|[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}|[0-9]{2}[\s][0-9]{4}[\s][0-9]{4}|[0-9]{2}[\.][0-9]{4}[\.][0-9]{4}|[0-9]{3}[\s][0-9]{3}[\s][0-9]{4}|[0-9]{3}[\.][0-9]{3}[\.][0-9]{4}|[0-9]{4}[\-][0-9]{3}[\-][0-9]{4}|[0-9]{2}[\-][0-9]{3}[\-][0-9]{4}/';
				preg_match_all($phonePattern, $arr[$i], $matches1);
				// $matches1 = $matches1[0];
				if ($matches1[0] != NULL) {
					if (strstr($matches1[0][0], "010")) {
						$mobile = $matches1[0][0];
						$mobile = str_replace(" ", "", $mobile);
						$mobile = str_replace("-", "", $mobile);
						$row_p['mobile'] = str_replace(".", "", $mobile);
					} else {
						$row_p['phone1'] = $matches1[0][0];
					}
					// echo $mobile.">>".$phone;
				}

				if (strstr($arr[$i], "F")) {
					preg_match_all($faxPatter, $arr[$i], $matches_fax);
					if ($matches_fax[0] != NULL) {
						$row_p['fax'] = $matches_fax[0][0];
						// echo "fax".$fax;
					}
				}

				preg_match_all($pattern, $arr[$i], $matches);
				if ($matches[1] != NULL) {
					$row_p['email1'] = $matches[0][0];
					// echo $email;
				}

				$name = str_replace(" ", "", trim($arr[$i]));
				if (mb_strlen($name, 'utf-8') == 3) {
					$row_p['name'] = trim($arr[$i]);
					// echo $name;
				}

				$addr_c = trim($arr[$i]);
				if ((strstr($addr_c, "시") && strstr($addr_c, "구") && strstr($addr_c, "로")) || (strstr($addr_c, "시") && strstr($addr_c, "구")) || (strstr($addr_c, "시") && strstr($addr_c, "로")) || (strstr($addr_c, "구") && strstr($addr_c, "로"))) {
					$row_p['address'] = trim($arr[$i]);
					// echo $address;
				}
			}
		}
	}
	if ($row_p['mem_id']) {
		$result = array();
		$result['result'] = true;
		$result['img_url'] = $row_p['img_url'];
		$result['seq'] = $row_p['seq'];
		$result['comment'] = $row_p['comment'];
		$result['name'] = $row_p['name'];
		$result['job'] = $row_p['job'];
		$result['org_name'] = $row_p['org_name'];
		$result['address'] = $row_p['address'];
		$result['phone1'] = $row_p['phone1'];
		$result['phone2'] = $row_p['phone2'];
		$result['mobile'] = $row_p['mobile'];
		$result['fax'] = $row_p['fax'];
		$result['email1'] = $row_p['email1'];
		$result['email2'] = $row_p['email2'];
		$result['memo'] = $row_p['memo'];
		echo json_encode($result);
	} else {
		$result = "error";
		$img_url = "none";
		echo json_encode(array("result" => $result, "img_url" => $img_url));
	}
} else if ($useType == '3') {
	//delete img
	$select_user = "select * from Gn_Member_card where mem_id = '{$userId}' and type = 1";
	$resul_p = mysqli_query($self_con, $select_user);
	$row_p = mysqli_fetch_array($resul_p);
	if ($row_p['mem_id']) {
		$update_sql = "update Gn_Member_card set type=2 where mem_id = '{$userId}'";
		mysqli_query($self_con, $update_sql);
		$resul_p = mysqli_query($self_con, $select_user);
		$row_p = mysqli_fetch_array($resul_p);
		if ($row_p['mem_id']) {
			$result = array();
			$result['result'] = true;
			$result['img_url'] = $row_p['img_url'];
			$result['seq'] = $row_p['seq'];
			$result['comment'] = $row_p['comment'];
			$result['name'] = $row_p['name'];
			$result['job'] = $row_p['job'];
			$result['org_name'] = $row_p['org_name'];
			$result['address'] = $row_p['address'];
			$result['phone1'] = $row_p['phone1'];
			$result['phone2'] = $row_p['phone2'];
			$result['mobile'] = $row_p['mobile'];
			$result['fax'] = $row_p['fax'];
			$result['email1'] = $row_p['email1'];
			$result['email2'] = $row_p['email2'];
			$result['memo'] = $row_p['memo'];
			echo json_encode($result);
		} else {
			$result = "error";
			$img_url = "none";
			echo json_encode(array("result" => $result, "img_url" => $img_url));
		}
	} else {
		$result = "error";
		$img_url = "none";
		echo json_encode(array("result" => $result, "img_url" => $img_url));
	}
} else if ($useType == '4') {
	$card_seq = trim($_REQUEST["card_seq"]);
	$card_name = trim($_REQUEST["card_name"]);
	$card_job = trim($_REQUEST["card_job"]);
	$card_org_name = trim($_REQUEST["card_org_name"]);
	$card_address = trim($_REQUEST["card_address"]);
	$card_phone1 = trim($_REQUEST["card_phone1"]);
	$card_phone1 = preg_replace('/\D/', '', $card_phone1);
	$card_phone2 = trim($_REQUEST["card_phone2"]);
	$card_phone2 = preg_replace('/\D/', '', $card_phone2);
	$card_mobile = trim($_REQUEST["card_mobile"]);
	$card_mobile = preg_replace('/\D/', '', $card_mobile);
	$card_fax = trim($_REQUEST["card_fax"]);
	$card_email1 = trim($_REQUEST["card_email1"]);
	$card_email2 = trim($_REQUEST["card_email2"]);
	$card_memo = trim($_REQUEST["card_memo"]);
	$sql_update = "update Gn_Member_card set name='{$card_name}', job='{$card_job}', org_name='{$card_org_name}', address='{$card_address}', phone1='{$card_phone1}', phone2='{$card_phone2}', mobile='{$card_mobile}', fax='{$card_fax}', email1='{$card_email1}', email2='{$card_email2}', memo='{$card_memo}' where seq='{$card_seq}'";
	$res = mysqli_query($self_con, $sql_update);

	$select_user = "select * from Gn_Member_card where seq = '{$card_seq}' and type = 0";
	$resul_p = mysqli_query($self_con, $select_user);
	$row_p = mysqli_fetch_array($resul_p);

	if ($row_p['mem_id']) {
		$result = array();
		$result['result'] = true;
		$result['img_url'] = $row_p['img_url'];
		$result['seq'] = $row_p['seq'];
		$result['comment'] = $row_p['comment'];
		$result['name'] = $row_p['name'];
		$result['job'] = $row_p['job'];
		$result['org_name'] = $row_p['org_name'];
		$result['address'] = $row_p['address'];
		$result['phone1'] = $row_p['phone1'];
		$result['phone2'] = $row_p['phone2'];
		$result['mobile'] = $row_p['mobile'];
		$result['fax'] = $row_p['fax'];
		$result['email1'] = $row_p['email1'];
		$result['email2'] = $row_p['email2'];
		$result['memo'] = $row_p['memo'];
		echo json_encode($result);
	} else {
		$result = "error";
		$img_url = "none";
		echo json_encode(array("result" => $result, "img_url" => $img_url));
	}
}
