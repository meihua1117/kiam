<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$sql = "select card.*,mem.mem_code from Gn_Iam_Name_Card card inner join Gn_Member mem on mem.mem_id=card.mem_id where card.mem_id= '{$_SESSION['iam_member_id']}' order by card.req_data";
$res = mysqli_query($self_con,$sql);
$G_card = mysqli_fetch_array($res);
if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
    $query = "select * from Gn_Iam_Service where sub_domain like 'http://" . $HTTP_HOST . "'";
else
    $query = "select * from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
$res = mysqli_query($self_con,$query);
$domainData = mysqli_fetch_array($res);

$sql = "select manager from gn_group_info where idx = '$group'";
$res = mysqli_query($self_con,$sql);
$row = mysqli_fetch_array($res);
$manager = $row[manager];
if($_GET['page2']) {
    $page2 = $_GET['page2'];
} else {
    $page2 = 1;
}
$now = date("Y-m-d");
?>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--meta http-equiv="Content-Type" content="text/html; charset=utf-8"-->
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href="img/common/icon-os.ico">
    <!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
    <meta property="og:title" content="멤버">
    <!--제목-->
    <meta property="og:description" content="멤버">
    <!--내용-->
    <title>멤버</title>
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <link rel="shortcut icon" href="img/common/iconiam.ico">
    <link rel="stylesheet" href="css/notokr.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/slick.min.css">
    <link rel="stylesheet" href="css/style_j.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/main.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <style>
        .button-wrap .button{
            display: inline-block;
            width: 120px;
            margin: 0 5px;
            padding: 5px 0;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            line-height: 20px;
            text-align: center;
        }
        .pagination ul li > * {
            display: block;
            width: 20px;
            height: 20px;
            border: 1px solid #000;
            font-size: 12px;
            line-height: 18px;
            color: #000;
            text-align: center;
            border-radius: 50%;
            margin: 3px;
        }
        .pagination ul li.active > * {
            background-color: #262626;
            color: #fff;
        }
        .pagination ul li {
            margin-left: -1px;
            display: inline-block;
        }
        .box-body .search-box .left .selects {
            float: left;
            color: #000000;
            font-size: 12px;
            font-weight: bold;
            line-height: 26px;
            margin-left: 20px;
        }
        
        .box-body .search-box .right {
            float: right;
            font-size: 0;
            text-align: right;
        }
        .box-body .search-box .right select{
            font-size:12px;
            margin-right:20px;
            margin-right: 20px;
            border: 1px solid #ddd;
            background: #fff;
            height: 33px;
            border-radius: 5px;
        }
        .contact-list .list-item {
            padding: 10px 15px;
            border: 1px solid #eee;
            margin-top: 5px;
            margin-bottom: 5px;
            background: rgb(251, 251, 251);
        }
        .contact-list .list-item .item-wrap {
            display: table;
            width: 100%;
        }
        .contact-list .list-item .item-wrap>* {
            display: table-cell;
            vertical-align: middle;
        }
        .contact-list .list-item .item-wrap .thumb {
            width: 42px;
            font-size: 0;
        }
        .contact-list .list-item .item-wrap .thumb .thumb-inner {
            border: 1px solid #eee;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            width: 42px;
            height: 42px;
        }
        .contact-list .list-item .item-wrap .info {
            padding-left: 10px;
        }
        .contact-list .list-item .item-wrap .info .upper {
            margin-bottom: 5px;
            font-size: 0;
        }
        .contact-list .list-item .item-wrap .info .upper .name {
            display: inline-block;
            line-height: 18px;
            font-size: 16px;
            font-weight: bold;
            vertical-align: middle;
        }
        .contact-list .list-item .item-wrap .info .upper .company {
            display: inline-block;
            margin-left: 10px;
            font-size: 12px;
            line-height: 18px;
            vertical-align: middle;
        }
        .contact-list .list-item .item-wrap .info .downer {
            font-weight: bold;
            color: #7abd31;
            font-size: 14px;
        }
        .contact-list .list-item .item-wrap .check {
            width: 15px;
            padding-left: 20px;
        }
    </style>
    <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
</head>
<body>
<div id="wrap" class="common-wrap">
    <main id="star" class="common-wrap" style="border:1px solid #ddd">
        <section id="middle">
            <div style="margin-top:10px;border-bottom:1px solid #ddd;display:flex;justify-content: space-between;padding-bottom: 5px;">
                <h3 style="margin-top:5px;margin-left:10px;">멤버</h3>
            </div>
            <div style="padding: 10px 15px;background-color: #ffffff;">
                <div class="box-body">
                    <?
                    $mem_sql = "select site_iam from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
                    $mem_res = mysqli_query($self_con,$mem_sql);
                    $mem_row = mysqli_fetch_array($mem_res);
                    $site_iam = $mem_row[0];
                    $group = $_GET['group'];
                    $search_range2 = $_GET['search_range2'];
                    $search_str2 = $_GET['search_str2'];
                    $search_type = $_GET['search_type'];

                    if(is_null($search_str2))
                        $search_str2 = "";
                    if(!$search_type)
                        $search_type = 1;
                    $fColor1 = $fColor2 = "black";
                    $bColor1 = $bColor2 = "white";
                    if($search_range2==1){
                        $bColor1 = "black";
                        $fColor1 = "white";
                    }else if($search_range2 == 3){
                        $bColor2 = "black";
                        $fColor2 = "white";
                    }
                    ?>
                    <div class="search-box clearfix J2" id="friends_search" style="padding: 10px 15px;background-color: #ffffff;">
                        <div class="row" style='margin-left: 8px;margin-bottom: 10px;margin-top: 7px;'>
                            <div class="left" style="float: left;">
                                <div class="buttons" style="float: left;">
                                    <a href="javascript:friends_range('1');" class="button" style="background-color: <?=$bColor1?>;color:<?=$fColor1?>;">가</a>
                                    <a href="javascript:friends_range('3');" class="button" style="padding-top: 5px;background-color: <?=$bColor2?>;color:<?=$fColor2?>">
                                        <i class="fa fa-history" aria-hidden="true"></i>
                                    </a>
                                    <input type="hidden" name="search_range2" id="search_range2"
                                           value="<?=$search_range2?>">
                                </div>
                                <div id="friends_chk_count" class="selects" style="float: left;">0개 선택됨</div>
                            </div>
                            <div class="right">
                                <select name="search_type" id="search_type" onchange="friends_submit();">
                                    <option value="1" <?if((int)$search_type==1){?>selected
                                        <?}?>>공개 프로필</option>
                                    <option value="2" <?if((int)$search_type==2){?>selected
                                        <?}?>>소속 회원</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style = "line-height : 30px">
                            <div class="search J_search" style="display:inline;width:60%;margin:10px 20px;">
                                <button type="button" onclick="friends_submit();" class="submit" style="top: 3px;background: #f9f9f9;font-size: 17px;"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <input type="text" name="search_str2" id="search_str2" class="input" value="<?=$search_str2?>" onkeyup="enterkey();" style="font-size:12px;height: 25px;background:#f9f9f9;width:50%;outline:none;" placeholder="검색어를 입력해주세요.">
                            </div>
                            <div style="float:right;padding-top:5px;padding-left:20px;">
                                <input type="checkbox" name="cbG01" id="cbG01" class="css-checkbox" onclick='groupCheckClick(this);'>
                                <label for="cbG01" class="css-label cb0">전체선택</label>
                            </div>
                        </div>
                    </div>
                    <div class="inner">
                        <div class="contact-list" style="<?=$style_list?>">
                            <ul>
                                <?
                                if($page2 == 1){
                                    $sql = "select gmem.*,mem.mem_name,zy,mem_phone,mem_code from gn_group_member gmem inner join Gn_Member mem on mem.mem_id = gmem.mem_id where gmem.mem_id = '$manager'";
                                    $res = mysqli_query($self_con,$sql);
                                    $row = mysqli_fetch_array($res);

                                    $card_sql="select main_img1 ,card_short_url from Gn_Iam_Name_Card where mem_id = '$manager' order by req_data";
                                    $card_result=mysqli_query($self_con,$card_sql) or die(mysqli_error($self_con));
                                    $card_row=mysqli_fetch_array($card_result);
                                    $friends_main_img = $row['profile'];
                                    $row[friends_url] = $card_row['card_short_url'].$row['mem_code'];
                                    if(!$friends_main_img) {
                                        $friends_main_img = $card_row['main_img1'];
                                        if(!$friends_main_img) {
                                            $friends_main_img = "img/profile_img.png";
                                        }
                                    }
                                    ?>
                                    <li class="list-item">
                                        <div class="item-wrap">
                                            <div class="thumb" style="width: 60px">
                                                <div class="thumb-inner" style="width: 60px;height:60px">
                                                    <a href="/?<?=$row[friends_url]?>" target="blank">
                                                        <img src="<?=$friends_main_img?>" id="friends_logo" class="friends_logo" style="max-height:60px;height:100%;object-fit:cover;"></a>
                                                </div>
                                            </div>
                                            <div class="info">
                                                <div class="upper">
                                                    <span class="name" style="font-size: 18px"><?=$row['mem_name']?></span>
                                                    <span class="company" style="font-size: 14px"><?=$row[zy]?></span>
                                                    <span class="name" style="margin-left:10px;color:red;font-size: 18px">(관리자)</span>
                                                </div>
                                                <div class="downer" style="font-size: 18px">
                                                    <a href="tel:<?=$row['mem_phone']?>"><?=$row['mem_phone']?></a>
                                                </div>
                                            </div>
                                            <div class="check">
                                                <div style="display: flex;margin-right: 20px;">
                                                    <input type="checkbox" name="friends_chk" id="inputItem<?=$row['mem_code']?>" class="friends checkboxes input css-checkbox" onclick='friends_chk_count() ' value="<?=$row['mem_code']?>">
                                                    <label for="inputItem<?=$row['mem_code']?>" class="css-label cb0"></label>
                                                    <input type="hidden" name="friends_idx<?=$row['mem_code']?>" id="friends_idx<?=$row['mem_code']?>" value="<?=$row['mem_id']?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?}
                                if($search_str2 !== "")
                                    $friends_sql_msg = "and (mem_name like '%$search_str2%' or mem_phone like '%$search_str2%' or mem.mem_id like '%$search_str2%')";
                                else
                                    $friends_sql_msg = "";
                                if((int)$search_type == 1)
                                    $sql = "select gmem.*,mem.mem_name,zy,mem_phone,mem_code from gn_group_member gmem inner join Gn_Member mem on mem.mem_id = gmem.mem_id where group_id=$group and gmem.mem_id != '{$_SESSION['iam_member_id']}' and gmem.mem_id != '$manager' $friends_sql_msg";
                                else
                                    $sql = "select gmem.*,mem.mem_name,zy,mem_phone,mem_code from gn_group_member gmem inner join Gn_Member mem on mem.mem_id = gmem.mem_id where group_id=$group and gmem.mem_id != '{$_SESSION['iam_member_id']}' and site_iam='$site_iam' $friends_sql_msg";
                                $result = mysqli_query($self_con,$sql);
                                $row_num = mysqli_num_rows($result);
                                $list2 = 10; //한 페이지에 보여줄 개수
                                $block_ct2 = 10; //블록당 보여줄 페이지 개수

                                $block_num2 = ceil($page2/$block_ct2); // 현재 페이지 블록 구하기
                                $block_start2 = (($block_num2 - 1) * $block_ct2) + 1; // 블록의 시작번호
                                $block_end2 = $block_start2 + $block_ct2 - 1; //블록 마지막 번호
                                $total_page2 = ceil($row_num / $list2); // 페이징한 페이지 수 구하기
                                if($block_end2 > $total_page2)
                                    $block_end2 = $total_page2; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
                                $total_block2 = ceil($total_page2/$block_ct2); //블럭 총 개수
                                $start_num2 = ($page2-1) * $list2; //시작번호 (page-1)에서 $list를 곱한다.

                                if((int)$search_range2 == 1) {
                                    $friends_sql_msg = $friends_sql_msg. " order by mem_name,req_date desc ";
                                } else if((int)$search_range2 == 2) {
                                    $friends_sql_msg = $friends_sql_msg. " order by idx desc";
                                } else if((int)$search_range2 == 3) {
                                    $friends_sql_msg = $friends_sql_msg. " order by req_date desc ,mem.mem_id desc";
                                } else if((int)$search_range2 == 4) {
                                    $friends_sql_msg = $friends_sql_msg. " order by idx";
                                } else {
                                    $friends_sql_msg = $friends_sql_msg. " order by idx desc";
                                }
                                $limit_str = " limit " . $start_num2 . ", " . $list2;
                                $sql .= $friends_sql_msg;
                                $sql .= $limit_str;
                                $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                                while($row = mysqli_fetch_array($result)){
                                    $card_sql="select main_img1 ,card_short_url from Gn_Iam_Name_Card where mem_id = '{$_SESSION['iam_member_id']}' order by req_data";
                                    $card_result=mysqli_query($self_con,$card_sql) or die(mysqli_error($self_con));
                                    $card_row=mysqli_fetch_array($card_result);
                                    $friends_main_img = $row['profile'];
                                    $row[friends_url] = $card_row['card_short_url'].$row['mem_code'];
                                    if(!$friends_main_img) {
                                        $friends_main_img = $card_row['main_img1'];
                                        if(!$friends_main_img) {
                                            $friends_main_img = "img/profile_img.png";
                                        }
                                    }
                                    ?>
                                    <li class="list-item">
                                        <div class="item-wrap">
                                            <div class="thumb">
                                                <div class="thumb-inner">
                                                    <a href="/?<?=$row[friends_url]?>" target="blank">
                                                        <img src="<?=$friends_main_img?>" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;"></a>
                                                </div>
                                            </div>
                                            <div class="info">
                                                <div class="upper">
                                                    <span class="name"><?=$row['mem_name']?></span>
                                                    <span class="company"><?=$row[zy]?></span>
                                                    <?if($row[req_date] >= $now){?>
                                                        <span class="name" style="margin-left:10px;color:red">(신규)</span>
                                                    <?}?>
                                                </div>
                                                <div class="downer">
                                                    <a href="tel:<?=$row['mem_phone']?>"><?=$row['mem_phone']?></a>
                                                </div>
                                            </div>
                                            <div class="check">
                                                <div style="display: flex;margin-right: 20px;">
                                                    <input type="checkbox" name="friends_chk" id="inputItem<?=$row['mem_code']?>" class="friends checkboxes input css-checkbox" onclick='friends_chk_count() ' value="<?=$row['mem_code']?>">
                                                    <label for="inputItem<?=$row['mem_code']?>" class="css-label cb0"></label>
                                                    <input type="hidden" name="friends_idx<?=$row['mem_code']?>" id="friends_idx<?=$row['mem_code']?>" value="<?=$row['mem_id']?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?}?>
                            </ul>
                        </div>
                        <div class="pagination" style="<?=$style_page?>">
                            <ul>
                                <?
                                if($page2 > 1) { //만약 page가 1보다 크다면
                                    $pre2 = $page2-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
                                    echo "<li class='arrow'><a href='?search_type=$search_type&search_str2=$search_str2&search_range2=$search_range2&page2=$pre2&group=$group'><i class='fa fa-angle-left' aria-hidden='true'></i></a></li>";
                                }
                                for($i=$block_start2; $i<=$block_end2; $i++){
                                    //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
                                    if($page2 == $i) { //만약 page가 $i와 같다면
                                        echo "<li class='active'><span>$i</span></li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
                                    } else {
                                        echo "<li><a href='?search_type=$search_type&search_str2=$search_str2&search_range2=$search_range2&page2=$i&group=$group'>$i</a></li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
                                    }
                                }

                                if($block_num2 < $total_block2) { //만약 현재 블록이 블록 총개수보다 크거나 작다면
                                    $next2 = $page2 + 1; //next변수에 page + 1을 해준다.
                                    echo "<li class='arrow'><a href='?search_type=$search_type&search_str2=$search_str2&search_range2=$search_range2&page2=$next2&group=$group'><i class='fa fa-angle-right' aria-hidden='true'></i></a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
                                }?>
                                <li style="float:right">
                                    <a style="background-image: url(/iam/img/main/icon-kakaoimg.png);border:none !important;border-radius: 0%;height: 27px; width: 27px; background-size: 27px 27px;" href="javascript:contact_sms();"></a>
                                </li>
                            </ul>
                        </div>
                        <div class = "button-group" style="display: flex;justify-content: center;">
                            <button class="btn btn-default" onclick = "close_window()">끝내기</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
<script>
    function friends_chk_count() {
        $("#friends_chk_count").text($("input[name=friends_chk]:checked").length + "개 선택됨");
    }
    function groupCheckClick(e){
        var checkboxes = $(".friends.checkboxes");
        if (e.checked) {
            for (var i = 0; i < checkboxes.length; i++) {
                $(checkboxes[i]).prop("checked", true);
            }
        } else {
            for (var i = 0; i < checkboxes.length; i++) {
                $(checkboxes[i]).prop("checked", false);
            }
        }
        friends_chk_count();
    }
    function friends_range(v1) {
        if (Number($("#search_range2").val()) == 1 && Number(v1) == 1) {
            location.href =
                "?group=<?=$group?>&search_type=<?=$search_type?>&search_range2=2&search_str2=<?=$search_str2?>"
        } else if (Number($("#search_range2").val()) == 2 && Number(v1) == 1) {
            location.href =
                "?group=<?=$group?>&search_type=<?=$search_type?>&search_range2=1&search_str2=<?=$search_str2?>"
        } else if (Number($("#search_range2").val()) == 3 && Number(v1) == 3) {
            location.href =
                "?group=<?=$group?>&search_type=<?=$search_type?>&search_range2=4&search_str2=<?=$search_str2?>"
        } else if (Number($("#search_range2").val()) == 4 && Number(v1) == 3) {
            location.href =
                "?group=<?=$group?>&search_type=<?=$search_type?>&search_range2=3&search_str2=<?=$search_str2?>"
        } else {
            location.href =
                "?group=<?=$group?>&search_type=<?=$search_type?>&search_range2=" + v1 +"&search_str2=<?=$search_str2?>"
        }
    }
    function friends_submit() {
        location.href = "?group=<?=$group?>&search_type=" + $("#search_type").val() + "&search_str2=" + $("#search_str2").val();
    }
    function enterkey(v1) {
        if (window.event.keyCode == 13) {
            // 엔터키가 눌렸을 때 실행할 내용
            friends_submit();
        }
    }
    function close_window(){
        window.opener.location.reload();
        window.close();
    }
    //연락처 문자발송
    function contact_sms() {
        var sms = "";
        for (var i = 0; i < $("input[name=friends_chk]:checked").length; i++) {
            sms = sms + $("#friends_idx" + $("input[name=friends_chk]:checked").eq(i).val()).val() + ",";
        }
        sms = sms.substring(0, sms.length - 1);
        var navCase = navigator.userAgent.toLocaleLowerCase();
        if(navCase.search("android") > -1){
            location.href = "sms:" + sms +
                "<?echo (preg_match('/iPhone/',$_SERVER['HTTP_USER_AGENT']))?'&':'?';?>body="+
                "<?=$G_card['card_name']?>님의 명함 <?=$G_card['card_company']?> <?=htmlspecialchars($G_card['card_position'])?> <?=$G_card['card_phone']?> <?php echo $domainData['sub_domain'];?>/?<?=$G_card['card_short_url'].$G_card['mem_code']?>";
        }
        else{
            alert("휴대폰에서 이용해주세요.");
        }
    }
</script>
</html>