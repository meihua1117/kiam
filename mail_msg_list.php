<?
$path = "./";
include_once "_head_open.php";

?>
<script language="javascript">
	function deleteRow(idx) {
		if (confirm('삭제하시겠습니까?')) {
			$.ajax({
				type: "POST",
				url: "ajax/mail_msg_func.php",
				data: {
					mode: "del",
					idx: idx
				},
				success: function(data) {
					location.href = "/mail_msg_list.php";
				},
			})
		}

	}
</script>
<div class="big_sub">
	<div class="m_div msg_serch">
		<form name="msg_serch_form" method="post">
			<div class="sub_4_3_t1">
				<div class="sub_4_1_t3">
					<a href="#">이메일 메시지</a>
				</div>
				<?
				$sql_serch = " mem_id ='{$_SESSION['one_member_id']}' ";
				if ($_REQUEST['lms_text']) {
					if ($_REQUEST['lms_select'])
						$sql_serch .= " and {$_REQUEST['lms_select']} like '{$_REQUEST['lms_text']}%' ";
					else
						$sql_serch .= " and (message like '{$_REQUEST['lms_text']}%'  or title like '{$_REQUEST['lms_text']}%') ";
				}
				$sql = "select count(idx) as cnt from gn_mail_message where $sql_serch ";
				$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
				$row = mysqli_fetch_array($result);
				$intRowCount = $row['cnt'];
				if ($_POST['page'])
					$page = (int)$_POST['page'];
				else
					$page = 1;
				if ($_POST['page2'])
					$page2 = (int)$_POST['page2'];
				else
					$page2 = 1;
				if (!$_POST['lno'])
					$intPageSize = 8;
				else
					$intPageSize = $_POST['lno'];
				$int = ($page - 1) * $intPageSize;
				if ($_REQUEST['order_status'])
					$order_status = $_REQUEST['order_status'];
				else
					$order_status = "desc";
				if ($_REQUEST['order_name'])
					$order_name = $_REQUEST['order_name'];
				else
					$order_name = "idx";
				$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
				$sql = "select * from gn_mail_message where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
				$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
				?>
				<div class="sub_4_3_t2">
					<div class="sub_4_4_t3">
						<select name="lms_select">
							<option value="">전체</option>
							<?
							$select_lms_arr = array("title" => "제목", "message" => "내용");
							foreach ($select_lms_arr as $key => $v) {
								$selected = $_REQUEST['lms_select'] == $key ? "selected" : "";
							?>
								<option value="<?= $key ?>" <?= $selected ?>><?= $v ?></option>
							<?
							}
							?>
						</select>
						<input type="text" name="lms_text" value="<?= $_REQUEST['lms_text'] ?>" />
						<input type="image" src="images/sub_button_703.jpg" />
					</div>
					<div class="d_div_font">LMS</div>
					<div class="sub_4_3_t2_left">
						<input type="hidden" name="txt_file" value="" />
						<div><input type="text" name="title" placeholder="제목" value="" /></div>
						<div>
							<textarea name="lms_content" placeholder="내용" onkeydown="textCounter(document.getElementsByName('lms_content')[0],'wenzi_cnt',2000,0)" onkeyup="textCounter(document.getElementsByName('lms_content')[0],'wenzi_cnt',2000,0)"></textarea>
						</div>
						<div style="margin-bottom:14px;"><span class="wenzi_cnt">0</span> Byte</div>
						<div><a href="javascript:void(0)" onclick="move_parent('txt')"><img src="images/sub_04_02_img_21.jpg" /></a></div>
					</div>
					<div class="sub_4_3_t2_right">
						<?
						if ($intRowCount) {
						?>
							<div>
								<?
								$i = 0;
								while ($row = mysqli_fetch_array($result)) {
								?>
									<div class="sub_4_3_t2_right_1">
										<div class="sub_4_3_t2_right_1_1">
											<div class="msg_title" style="margin-bottom:2px;"><?= str_replace("{|REP|}", "{|name|}", $row['title']) ?></div>
											<div class="msg_content"><?= html_entity_decode(str_replace("{|REP|}", "{|name|}", $row['message'])) ?></div>
										</div>
										<div class="sub_4_3_t2_right_1_3"><label><input type="radio" name="ab" onclick="show_msg('<?= $i ?>')" />사용시 선택</label>
											<div class="sub_4_3_t2_right_1_3" style="float:right"><a href="javascript:deleteRow('<?= $row['idx'] ?>');">삭제</a></div>
										</div>
									</div>
									<input type="hidden" class="attach_file" value="<?= $row['file'] ?>" />

								<?
									$i++;
								}
								?>
								<p style="clear:both;"></p>
							</div>
							<div>
								<?
								page_f($page, $page2, $intPageCount, "msg_serch_form");
								?>
							</div>
						<?
						} else {
						?>
							<div style="text-align:center">등록된 내용이 없습니다.</div>
						<?
						}
						?>
					</div>
					<p style="clear:both;"></p>
				</div>
			</div>
			<input type="hidden" name="order_name" value="<?= $order_name ?>" />
			<input type="hidden" name="order_status" value="<?= $order_status ?>" />
			<input type="hidden" name="page" value="<?= $page ?>" />
			<input type="hidden" name="page2" value="<?= $page2 ?>" />
		</form>
	</div>
</div>

<script language="javascript">
	$('.ad_layer_login').hide();
	$(window).load(function() {
		$('.ad_layer_login').hide();
		var screen_width = (window.screen.width) / 2;
		var screen_height = (window.screen.height) / 2;
	});
	function move_parent() {
		if (document.getElementsByName('lms_content')[0].value) {
			window.opener.document.getElementsByName("txt_mail_content")[0].value = document.getElementsByName('lms_content')[0].value;
			window.opener.document.getElementsByName('txt_mail_title')[0].value = document.getElementsByName('title')[0].value;
			window.opener.document.getElementsByName('link_attachment')[0].value = document.getElementsByName('txt_file')[0].value;
			window.close();
		} else
			document.getElementsByName('lms_content')[0].focus();
		window.opener.type_check();
	}

	//문자저장 이동
	function show_msg(i) {
		document.getElementsByName('title')[0].value = $($(".msg_title")[i]).html();
		document.getElementsByName('lms_content')[0].value = $($(".msg_content")[i]).html().replace("&gt;", ">").replace("&lt;", "<");
		document.getElementsByName('lms_content')[0].focus();
		document.getElementsByName('txt_file')[0].value = $($(".attach_file")[i]).val();
	}

	function page_p(e1, e2, e3) {
		e3.page.value = e1
		if (e2 % parseInt(e2) == 0) {
			e3.page2.value = e2
		} else {
			e3.page2.value = parseInt(e2) + 1
		}
		e3.submit();
	}
</script>
<?
mysqli_close($self_con);
//include_once "_foot.php";
?>