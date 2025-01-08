      <style>
      	.btmbanner_list .layout {
      		width: 420px;
      		margin-left: auto;
      		margin-right: auto;
      	}

      	.btmbanner_list .area_right {
      		position: relative;
      		overflow: hidden;
      		height: 40px;
      		padding: 0 60px 0 45px;
      	}

      	.btmbanner_list .area_right .btmbr_list {
      		position: relative;
      		overflow: hidden;
      		height: 100%;
      	}

      	.btmbanner_list .area_right .btmbr_list .btmbrl_obj {
      		position: relative;
      		width: 200%;
      		height: 100%;
      	}

      	.btmbanner_list .area_right .btmbr_list .btmbrl_obj .btmbrl_item {
      		float: left;
      		height: 100%;
      		padding-top: 7px;
      	}

      	.btmbanner_list .area_right .btmbr_list .btmbrl_obj .btmbrl_item a {
      		position: relative;
      		display: block;
      		height: 100%;
      		padding: 0 23px;
      		font-size: 17px;
      	}

      	.btmbanner_list .area_right .btmbr_ctlbtns {
      		position: absolute;
      		top: 0;
      		left: 0;
      		width: 100%;
      	}

      	.btmbanner_list .area_right .btmbr_ctlbtns a {
      		position: absolute;
      		top: 12px;
      		width: 16px;
      		height: 23px;
      		background: no-repeat 0 0;
      	}

      	.btmbanner_list .area_right .btmbr_ctlbtns .ctl_prev {
      		left: 15px;
      		background-image: url(/iam/img/btn_slide_pre.png);
      	}

      	.btmbanner_list .area_right .btmbr_ctlbtns .ctl_next {
      		right: 25px;
      		background-image: url(/iam/img/btn_slide_next.png);
      	}

      	.container {
      		width: 100%;
      	}

      	.wrapper {
      		margin-left: auto;
      		margin-right: auto;
      		position: relative;
      		max-width: 750px;
      	}

      	@media only screen and (max-width: 500px) {
      		.btmbanner_list .layout {
      			width: auto;
      			margin-left: auto;
      			margin-right: auto;
      		}
      	}

      	thead tr th {
      		position: sticky;
      		top: 0;
      		background: #ebeaea;
      		z-index: 10;
      	}

      	th {
      		text-align: center !important;
      	}

      	.wrapper {
      		height: 100%;
      		overflow: auto !important;
      	}

      	.content-wrapper {
      		min-height: 80% !important;
      	}

      	.box-body {
      		overflow: auto;
      		padding: 0px !important;
      	}

      	.content-header {
      		padding: 10px 0px 5px 0px !important
      	}
      </style>
      <script src="/js/rlatjd.js"></script>
      <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
      <!-- Top 메뉴 -->
      <header class="main-header" style="max-height:120px;">
      	<!-- Logo -->
      	<a href="/admin/member_list.php" class="logo" style="background-color: white;">
      		<!-- mini logo for sidebar mini 50x50 pixels -->
      		<span class="logo-mini"><b>A</b>LT</span>
      		<!-- logo for regular state and mobile devices -->
      		<span class="logo-lg" style="color:grey;"><b>IAM 마이플랫폼 관리자</b></span>
      	</a>
      	<div class="row" style="background:#ffffff;margin:0px !important">
      		<div class="col-xs-12" style="padding-bottom:0px">
      			<div class="btmbanner_list" style="margin-top: 2px;padding: 0 20px;">
      				<div class="layout">
      					<div class="area_right">
      						<div class="btmbr_list <?= $_SERVER['REQUEST_URI'] ?>">
      							<div class="btmbrl_obj" style="left: 0px;">
      								<div class="btmbrl_item">
      									<a class="form-check-input" style="cursor: pointer;padding:3px 0px 0px 10px;<? if ($_SERVER['REQUEST_URI'] == "/iama/index.php") echo 'color:#99cc00;' ?>" href="index.php">홈</a>
      								</div>
      								<div class="btmbrl_item">
      									<a class="form-check-input" style="cursor: pointer;padding:3px 0px 0px 10px;<? if (strpos($_SERVER['REQUEST_URI'], "/iama/service_Iam_admin.php") !== false) echo 'color:#99cc00;' ?>" href="service_Iam_admin.php">분양</a>
      								</div>
      								<div class="btmbrl_item">
      									<a class="form-check-input" style="cursor: pointer;padding:3px 0px 0px 10px;<? if (strpos($_SERVER['REQUEST_URI'], "/iama/member_list.php") !== false) echo 'color:#99cc00;' ?>" href="member_list.php">회원</a>
      								</div>
      								<div class="btmbrl_item">
      									<a class="form-check-input" style="cursor: pointer;padding:3px 0px 0px 10px;<? if (strpos($_SERVER['REQUEST_URI'], "/iama/customer_list.php") !== false) echo 'color:#99cc00;' ?>" href="customer_list.php">고객</a>
      								</div>
      								<div class="btmbrl_item">
      									<a class="form-check-input" style="cursor: pointer;padding:3px 0px 0px 10px;<? if (strpos($_SERVER['REQUEST_URI'], "/iama/card_list.php") !== false) echo 'color:#99cc00;' ?>" href="card_list.php">카드</a>
      								</div>
      								<div class="btmbrl_item">
      									<a class="form-check-input" style="cursor: pointer;padding:3px 0px 0px 10px;<? if (strpos($_SERVER['REQUEST_URI'], "/iama/contents_list.php") !== false) echo 'color:#99cc00;' ?>" href="contents_list.php">콘텐츠</a>
      								</div>
      								<div class="btmbrl_item">
      									<a class="form-check-input" style="cursor: pointer;padding:3px 0px 0px 10px;<? if (strpos($_SERVER['REQUEST_URI'], "/index.php") !== false) echo 'color:#99cc00;' ?>" href="/index.php">IAM</a>
      								</div>
      							</div>
      						</div>
      						<div class="btmbr_ctlbtns">
      							<a class="ctl_prev" data-control="prev"></a>
      							<a class="ctl_next" data-control="next"></a>
      						</div>
      					</div>
      					<script>
      						$(function() {
      							var param = '.btmbanner_list',
      								obj = '.btmbrl_item',
      								btn = param + ' .btmbr_ctlbtns',
      								interval = 3000,
      								speed = 300,
      								viewSize = 1,
      								moreSize = 0,
      								dir = 'x',
      								data = 1,
      								auto = false,
      								hover = false,
      								method = '',
      								op1 = false;

      							stateScrollObj(param, obj, btn, interval, speed, viewSize, moreSize, dir, data, auto, hover, method, op1);
      						});
      					</script>
      				</div>
      			</div>
      			<form method="get" name="search_form" id="search_form" class="" style="padding-right: 5px;">
      				<div class="box-tools" style="margin-top: 5px">
      					<div class="input-group" style="width: 200px;margin-left: auto;margin-right: auto;">
      						<? foreach ($_GET as $key => $value) {
									if ($key !== 'search_key') {  // 'search' 파라미터는 제외
										echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
									}
								} ?>
      						<input type="text" name="search_key" id="search_key" value="<?= $_GET['search_key'] ? $_GET['search_key'] : ''; ?>" class="form-control input-sm pull-right" placeholder="검색어">
      						<div class="input-group-btn">
      							<button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
      						</div>
      					</div>
      				</div>
      			</form>
      		</div>
      	</div>
      </header>