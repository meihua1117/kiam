<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
?>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--meta http-equiv="Content-Type" content="text/html; charset=utf-8"-->
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href="img/common/icon-os.ico">
    <!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
    <meta property="og:title" content="그룹 고정하기">
    <!--제목-->
    <meta property="og:description" content="그룹고정">
    <!--내용-->
    <title>그룹고정하기</title>
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
            border-radius: 25%;
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
                <div style="margin-top:10px;border-bottom:1px solid #ddd;display:flex;justify-content: space-between;">
                    <h3 style="margin-left:10px;">그룹 정보리스트</h3>
                    <div class="dropdown " style="margin-right: 10px;margin-top: auto;margin-bottom: auto;">
                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" style="background:white">
                            <img src = "img/main/icon-rearrow.png" style="height:20px">
                        </button>
                        <ul class="dropdown-menu comunity" style="border: 1px solid #ccc;padding:0px;">
                            <li><a href = "?etc_order=fix" style="padding:3px 3px 0px 3px !important;">고정순</a></li>
                            <li><a href = "?etc_order=visit" style="padding:3px 3px 0px 3px !important;">방문순</a></li>
                            <li><a href = "?etc_order=name" style="padding:3px 3px 0px 3px !important;">그룹 이름순</a></li>
                            <li><a href = "?etc_order=reg" style="padding:3px 3px 0px 3px !important;">최근 가입한 그룹</a></li>
                        </ul>
                    </div>
                </div>
                <div style="padding: 10px 15px;background-color: #ffffff;">
                    <div class="box-body">
                        <div class="inner">
                            <div class="contact-list" style="<?=$style_list?>">
                                <ul>
                                    <?
                                    if($etc_order == "")
                                        $etc_order = "fix";
                                    $group_order = array("visit"=>"visit_date","name"=>"name","reg"=>"g_mem.req_date","fix"=>"g_mem.fix_status");
                                    $group_sql = "select main_img1 as group_img,g_mem.group_id,description,name,fix_status from gn_group_member g_mem
                                                inner join gn_group_info info on g_mem.group_id = info.idx
                                                inner join Gn_Iam_Name_Card card on card.idx = info.card_idx 
                                                inner join Gn_Member mem on mem.mem_id = g_mem.mem_id where g_mem.mem_id = '$_SESSION[iam_member_id]'";
                                    $group_sql .= " order by ".$group_order[$etc_order];
                                    $group_res = mysqli_query($self_con,$group_sql);
                                    $row_num = mysqli_num_rows($group_res);
                                    $list2 = 10; //한 페이지에 보여줄 개수
                                    $block_ct2 = 10; //블록당 보여줄 페이지 개수

                                    if($_GET['page2']) {
                                        $page2 = $_GET['page2'];
                                    } else {
                                        $page2 = 1;
                                    }

                                    $block_num2 = ceil($page2/$block_ct2); // 현재 페이지 블록 구하기
                                    $block_start2 = (($block_num2 - 1) * $block_ct2) + 1; // 블록의 시작번호
                                    $block_end2 = $block_start2 + $block_ct2 - 1; //블록 마지막 번호
                                    $total_page2 = ceil($row_num / $list2); // 페이징한 페이지 수 구하기
                                    if($block_end2 > $total_page2)
                                        $block_end2 = $total_page2; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
                                    $total_block2 = ceil($total_page2/$block_ct2); //블럭 총 개수
                                    $start_num2 = ($page2-1) * $list2; //시작번호 (page-1)에서 $list를 곱한다.

                                    $limit_str = " limit " . $start_num2 . ", " . $list2;
                                    $group_sql .= $limit_str;
                                    $group_res=mysqli_query($self_con,$group_sql) or die(mysqli_error($self_con));
                                    while($row = mysqli_fetch_array($group_res)){
                                        $friends_main_img = $row[group_img];
                                        if(!$friends_main_img)
                                            $friends_main_img = "img/profile_img.png";
                                        if($row[fix_status] == "Y")
                                            $pin = "img/main/icon-redpin.png";
                                        else
                                            $pin = "img/main/icon-pin.png";
                                        ?>
                                        <li class="list-item">
                                            <div class="item-wrap">
                                                <div class="thumb">
                                                    <div class="thumb-inner">
                                                        <a href="javascript:;;" target="blank">
                                                            <img src="<?=$friends_main_img?>" id="friends_logo" class="friends_logo" style="width:100%;height:100%;object-fit:cover;"></a>
                                                    </div>
                                                </div>
                                                <div class="info">
                                                    <div class="upper">
                                                        <span class="name"><?=$row[name]?></span>
                                                    </div>
                                                    <div class="downer">
                                                        <span class="name"><?=$row[description]?></span>
                                                    </div>
                                                </div>
                                                <div class="dropdown" style="width:30px">
                                                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" style="padding:0px">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu comunity" style="border: 1px solid #ccc;padding:0px;">
                                                        <?
                                                        $card_sql = "select card_name from Gn_Iam_Name_Card where group_id = '$row[group_id]' order by req_data";
                                                        $card_res = mysqli_query($self_con,$card_sql);
                                                        $card_num = 1;
                                                        while($card_row = mysqli_fetch_array($card_res)){
                                                            ?>
                                                            <li><a style="padding:3px 3px 0px 3px !important;"><?=$card_row[0] == ""?$card_num."번카드":$card_row[0];?></a></li>
                                                            <?
                                                            $card_num++;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <div style="width:20px">
                                                    <a onclick="click_pin('<?=$row[group_id]?>')">
                                                        <img src="<?=$pin?>" id="<?='pin'.$row[group_id]?>" class="friends_logo" style="width:20px;height:20px;object-fit:cover;">
                                                    </a>
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
                                        echo "<li class='arrow'><a href='?page2=$pre2'><i class='fa fa-angle-left' aria-hidden='true'></i></a></li>";
                                    }
                                    for($i=$block_start2; $i<=$block_end2; $i++){
                                        //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
                                        if($page2 == $i) { //만약 page가 $i와 같다면
                                            echo "<li class='active'><span>$i</span></li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
                                        } else {
                                            echo "<li><a href='?page2=$i'>$i</a></li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
                                        }
                                    }

                                    if($block_num2 < $total_block2) { //만약 현재 블록이 블록 총개수보다 크거나 작다면
                                        $next2 = $page2 + 1; //next변수에 page + 1을 해준다.
                                        echo "<li class='arrow'><a href='?page2=$next2'><i class='fa fa-angle-right' aria-hidden='true'></i></a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
                                    }?>
                                </ul>
                            </div>
                            <div class = "button-group" style="display: flex;justify-content: center;">
                                <button class="btn btn-primary" style="" onclick = "fix_close()">끝내기</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
<script>
    function click_pin(group_id) {
        $.ajax({
            type: "POST",
            url: "ajax/group.proc.php",
            dataType:"json",
            data: {
                method : "pin",
                group_id : group_id
            },
            success: function (data) {
                if(data.result == "Y")
                    $("#pin" + group_id).attr("src","img/main/icon-redpin.png");
                else
                    $("#pin" + group_id).attr("src","img/main/icon-pin.png");
            }
        });
    }
    function fix_close(){
        window.opener.location.reload();
        window.close();
    }
</script>
</html>