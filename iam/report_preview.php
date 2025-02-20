<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
$repo_id = $_GET['repo'];
$sql = "select * from gn_report_form where id=$repo_id";
$res = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($res);
?>
<style>
    .report_item {
        width: 100%;
        margin-top: 10px;
        padding-left: 20px;
        padding-right: 20px;
        display: table;
    }

    .report_item:last-child {
        margin-bottom: 20px;
    }

    .report_item_title {
        writing-mode: vertical-lr;
        text-orientation: upright;
        color: #ffffff;
        background-color: #7f7f7f;
        font-size: 16px;
        margin-bottom: 5px;
        padding: 5px 8px;
        display: table-cell;
        text-align: center;
    }

    .report_item_div {
        width: 100%;
        padding-left: 10px;
        display: table-cell;
        vertical-align: top;
    }

    .report_item_body:last-child {
        margin-bottom: 0;
    }

    .report_item_body {
        width: 100%;
        display: table;
        margin-bottom: 0px;
    }

    .report_item_tag {
        display: table-cell;
        font-size: 15px;
        background-color: #bfbfbf;
        width: 30%;
        padding-left: 10px;
    }

    .report_item_tag1 {
        display: table-cell;
        font-size: 15px;
        width: 100%;
        text-align: left;
        padding: 5px 10px;
    }

    .report_item_tag1:first-line {
        line-height: 0px;
    }

    .report_item_tag2 {
        display: block;
        font-size: 15px;
        width: 100%;
        text-align: left;
        border: 1px solid #ddd;
        padding: 5px 10px;
        background-color: #bfbfbf;
    }

    .report_item_tag_val {
        display: table-cell;
        padding-left: 5px;
        font-size: 15px;
        width: 70%;
    }

    .report_item_input {
        width: 100%;
        height: 28px;
        padding: 5px 10px;
        background-color: #fff;
        border: 1px solid #ccc;
        font-size: 12px;
        line-height: 16px;
    }

    .report_item_container {
        width: 100%;
        height: 100%;
        padding: 5px 10px;
        background-color: #fff;
        border: 1px solid #ccc;
        font-size: 12px;
    }

    .kbw-signature {
        width: 100%;
        height: 100px;
        background-color: #f1f1f1;
        display: inline-block;
        border: 1px solid #eee;
        -ms-touch-action: none;
    }
</style>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
    <meta property="og:title" content="<?= $row['title'] ?>"> <!--제목-->
    <meta property="og:description" content="<?= $row['descript'] ?>"> <!--내용-->
    <!--오픈그래프 끝-->
    <title>아이엠 하나이면 홍보와 소통이 가능하다</title>
    <link rel="shortcut icon" href="img/common/iconiam.ico">
    <link rel="stylesheet" href="css/notokr.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/new_style.css">
    <link rel="stylesheet" href="css/grid.min.css">
    <link rel="stylesheet" href="css/slick.min.css">
    <link rel="stylesheet" href="css/style_j.css">
    <!-- ########## TODO COMMENT FOR TEST  패치할떄 해제해야함 ###########  -->
    <!--script src="//developers.kakao.com/sdk/js/kakao.min.js" charset="utf-8"></script-->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/main.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <link rel='stylesheet' href='../plugin/toastr/css/toastr.css' />
    <script src='../plugin/toastr/js/toastr.min.js'></script>
    <script src="js/jquery-ui-1.10.3.custom.js"></script>
    <script src="js/jquery-signature.js"></script>
    <script src="js/jquery.report_form.js"></script>
</head>

<body>
    <div id="wrap" class="common-wrap" style="padding:0px">
        <main id="star" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
            <section id="bottom">
                <div class="content-item">
                    <div style="margin-top: 20px;margin-left: 20px">
                        <h2 class="title"><?= $row['title'] ?></h2>
                    </div>
                    <div style="margin-top: 10px;margin-left: 20px;margin-right: 20px;">
                        <h4 class="desc" style="white-space: pre-line;"><?= $row['descript'] ?></h4>
                    </div>
                    <?
                    $sql1 = "select * from gn_report_form1 where form_id=$repo_id order by item_order";
                    $res1 = mysqli_query($self_con, $sql1);
                    while ($row1 = mysqli_fetch_array($res1)) {
                        if ($row1['item_req']) { ?>
                            <div style="width:100%;margin-top:20px">
                                <div class="report_item_tag" style="width: 100%;text-align: left;padding:5px 10px;display:block">
                                    <?= $row1['item_req']; ?>
                                </div>
                            </div>
                        <? } ?>
                        <div class="report_item">
                            <? if ($row1['item_title']) {
                                $style = ""; ?>
                                <div class="report_item_title">
                                    <?= $row1['item_title'] ?>
                                </div>
                            <? } else {
                                $style = "padding-left:0px;";
                            } ?>
                            <div class="report_item_div" style="<?= $style ?>">
                                <?
                                $sql2 = "select * from gn_report_form2 where form_id=$repo_id and item_id = {$row1['id']} order by id";
                                $res2 = mysqli_query($self_con, $sql2);
                                while ($row2 = mysqli_fetch_array($res2)) {
                                    if ($row1['item_type'] == 0) {
                                ?>
                                        <div class="report_item_body">
                                            <div class="report_item_tag">
                                                <?= $row2['tag_name'] ?>
                                            </div>
                                            <div class="report_item_tag_val">
                                                <input type="text" class="report_item_input">
                                            </div>
                                        </div>
                                    <?  } else if ($row1['item_type'] == 1) { ?>
                                        <div class="report_item_body">
                                            <div class="report_item_tag1">
                                                <input type="checkbox" style="margin: 2px 0px">
                                                <?= $row2['tag_name'] ?>
                                            </div>
                                        </div>
                                    <?  } else if ($row1['item_type'] == 3) { ?>
                                        <div class="report_item_body">
                                            <div class="report_item_tag2">
                                                <?= $row2['tag_name'] ?>
                                            </div>
                                            <div class="report_item_tag2" style="background-color: transparent;margin-top: 5px;padding: 0px">
                                                <input type="text" class="report_item_input" style="border: none">
                                            </div>
                                        </div>
                                    <?  } else {
                                        $pre_style = "";
                                        if ($row2['tag_name'] != "")
                                            $pre_style = "white-space: pre-line";
                                    ?>
                                        <div class="report_item_body" style="min-height: 100">
                                            <div class="report_item_tag1" style="vertical-align: top;<?= $pre_style ?>;padding:0px;border:none">
                                                <?= $row2['tag_name'] ?>
                                                <br>
                                                <?
                                                $img = $row2['tag_img'];
                                                $link = $row2['tag_link'];
                                                $pos = strpos($row2['tag_img'], "youtu");
                                                //$pos_img = stripos($row2['tag_img'],".jpg") + stripos($row2['tag_img'],".jpeg") +stripos($row2['tag_img'],".png") +stripos($row2['tag_img'],".gif") +stripos($row2['tag_img'],".svc");
                                                if ($pos >= 0) {
                                                    if (strpos($row2['tag_img'], "youtu.be") != false) {
                                                        $img = str_replace("youtu.be/", "www.youtube.com/embed/", $img);
                                                    } else if (strpos($row2['tag_img'], "playlist") != false) {
                                                        $code = substr($row2['tag_img'], strpos($row2['tag_img'], "playlist") + 14);
                                                        $img = "https://www.youtube.com/embed/?listType=playlist&list=$code";
                                                    } else if (strpos($row2['tag_img'], "watch?v=") != false) {
                                                        $img = str_replace("watch?v=", "embed/", $img);
                                                    }
                                                }
                                                if ($pos) { ?>
                                                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;">
                                                        <iframe style="width:100%;height:300px;border-radius: 10px;" src="<?= $img ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                                                        </iframe>
                                                    </div>
                                                <? } else if ($img == "") { ?>
                                                    <a href="<?= $link ?>" target="_blank"><?= $link ?></a>
                                                <? } else if ($link == "") { ?>
                                                    <img src="<?= $img ?>" style="width:100%">
                                                <? } else { ?>
                                                    <img src="<?= $img ?>" style="width:100%" onclick="window.open('<?=$link?>')">
                                                <? } ?>
                                            </div>
                                        </div>
                                <?  }
                                } ?>
                            </div>
                        </div>
                    <? } ?>
                    <? if ($row['sign_visible'] == 1) { ?>
                        <div class="report_item">
                            <div id="signature-pad" class="m-signature-pad">
                                <p class="marb3"><strong class="blink">아래 박스안에 서명을 남겨주세요.</strong><button type="button" id="clear" class="btn_ssmall bx-white">서명 초기화</button></p>
                                <div id="sign"></div>
                                <textarea name="signatureJSON" id="signatureJSON" style="display: none" readonly=""></textarea>
                            </div>
                        </div>
                    <? } ?>
            </section>
        </main>
    </div>
</body>
<script>
</script>

</html>