<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
?>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--meta http-equiv="Content-Type" content="text/html; charset=utf-8"-->
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <link rel="shortcut icon" href="img/common/icon-os.ico">
    <!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
    <meta property="og:title" content="그룹 참여하기">
    <!--제목-->
    <meta property="og:description" content="그룹참여">
    <!--내용-->
    <title>그룹참여</title>
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
        .user-item {
            position: relative;
            display: flex;
            padding: 4px;
            border: none;
            border-bottom: 1px solid #dddddd;
            padding-top: 12px;
            padding-bottom: 12px;
        }
        .user-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            overflow: hidden;
        }
        .user-item .wrap {
            display: flex;
            flex-direction: column;
            padding: 4px 6px;
        }
        .date {
            font-size: 13px;
            color: rgb(153, 153, 153);
            display: block;
        }
        .user-name {
            display: block;
            margin-top: 0px;
            font-size: 15px;
            font-weight: 700;
            color: #000000;
        }
        .img-box {
            padding: 4px;
        }
        .user-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div id="wrap" class="common-wrap">
    <main id="star" class="common-wrap" style="border:1px solid #ddd">
        <section id="middle">
            <?if($type == "sample"){
                $img = "img/main/img-foryou.png";
                $title = "회원님을 위한 추천";
            }else if($type == "friend"){
                $img = "img/main/img-forfds.jpg";
                $title = "친구의 그룹";
            }
            $sql = "select * from gn_group_member where mem_id = '{$_SESSION['iam_member_id']}'";
            $res = mysqli_query($self_con, $sql);
            $my_group = array();
            while($row = mysqli_fetch_array($res)){
                array_push($my_group,$row['group_id']);
            }
            $my_group = implode(",",$my_group);
            if($type != "general"){
                ?>
                <div style="background-image: url(<?=$img?>);height:30%;background-size: cover;background-position: center;display: flex;flex-direction: column;justify-content: space-between;">
                    <div  style="width: 80%;margin-top:3px;margin-left: auto;margin-right: auto;position:relative;opacity:90%;">
                        <div style="position:absolute; display: table-cell;top:8px;left:10px">
                            <i class="fa fa-search" style="font-size: 20px;" onclick="search_clicked()"></i>
                        </div>
                        <form method="get" name="search_form" id="search_form">
                            <input type="text" name="search_str" class="form-control" placeholder="그룹 검색" style="border-radius: 20px;border: none;padding-left:40px;display: block;" autocomplete="true" value="<?=$search_str?>">
                            <input type="hidden" name = "type" value="<?=$type?>">
                        </form>
                    </div>
                    <h3 style="color: white;margin-left:30px;margin-bottom:10px;"><?=$title?></h3>
                </div>
            <?}else{
                if(!$type2)
                    $type2 = "group";
                if($type2 == "group"){
                    ?>
                    <div style="margin-top : 10px">
                        <div  style="width: 95%;margin-top:3px;margin-left: auto;margin-right: auto;position:relative;">
                            <div style="position:absolute; display: table-cell;top:8px;left:10px">
                                <i class="fa fa-search" style="font-size: 20px;" onclick="search_clicked()"></i>
                            </div>
                            <form method="get" name="search_form" id="search_form">
                                <input type="search" name="search_str" class="form-control" placeholder="그룹 검색" style="border-radius: 20px;border: none;padding-left:40px;display: block;background:#f0f0f0" value="<?=$search_str?>">
                                <input type="hidden" name = "type" value="<?=$type?>">
                                <input type="hidden" name = "type2" value="<?=$type2?>">
                            </form>
                        </div>
                    </div>
                    <div style="display: flex;margin-top:10px">
                        <button type="button" class = "btn <?=$type2 == 'group'?'btn-primary':'btn-default'?>" style="margin-left: 20px" onclick="location.href = '?type=general&type2=group'">그룹</button>
                        <button type="button" class = "btn <?=$type2 != 'group'?'btn-primary':'btn-default'?>" style="margin-left: 10px" onclick="location.href = '?type=general&type2=con'">그룹게시물</button>
                    </div>
                <?}else{
                    if(!$type3)
                        $type3 = "sample";
                    ?>
                    <div style="margin-top : 10px">
                        <div  style="width: 95%;margin-top:3px;margin-left: auto;margin-right: auto;position:relative;">
                            <div style="position:absolute; display: table-cell;top:8px;left:10px">
                                <i class="fa fa-search" style="font-size: 20px;" onclick="search_clicked('<?=$type?>')"></i>
                            </div>
                            <form method="get" name="search_form" id="search_form">
                                <input type="search"  name="search_str" class="form-control" placeholder="그룹 검색" style="border-radius: 20px;border: none;padding-left:40px;display: block;background:#f0f0f0" value="<?=$search_str?>">
                                <input type="hidden" name = "type" value="<?=$type?>">
                                <input type="hidden" name = "type2" value="<?=$type2?>">
                                <input type="hidden" name = "type3" value="<?=$type3?>">
                            </form>
                        </div>
                    </div>
                    <div style="display: flex;margin-top:10px">
                        <button type="button" class = "btn <?=($type3 == 'sample'&& !$search_str)?'btn-primary':'btn-default'?>" style="margin-left: 20px" onclick="location.href = '?type=general&type2=con&type3=sample'">게시물</button>
                        <button type="button" class = "btn <?=($type3 != 'sample' && !$search_str)?'btn-primary':'btn-default'?>" style="margin-left: 10px" onclick="location.href = '?type=general&type2=con&type3=recent'">최근 게시물</button>
                    </div>
                <?  }
            }?>
            <div style="margin-top:10px;background-color: #ffffff;">
                <?
                if($_GET['page2']) {
                    $page2 = $_GET['page2'];
                } else {
                    $page2 = 1;
                }
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

                $mem_sql = "select site_iam from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
                $mem_res = mysqli_query($self_con, $mem_sql);
                $mem_row = mysqli_fetch_array($mem_res);
                if($type == "friend") {
                    $group_sql = "select name,ggm.group_id,count(*) as g_mem_count,card_short_url,main_img1 
                            from gn_group_member ggm 
                            inner join gn_group_info ggi on ggi.idx=ggm.group_id 
                            inner join Gn_Iam_Name_Card card on card.idx = ggi.card_idx 
                            inner join Gn_Member mem on mem.mem_id = card.mem_id 
                            where ggm.mem_id in (select mem_id from Gn_Member where site_iam='{$mem_row['site_iam']}') 
                                and ggm.group_id not in (select group_id from gn_group_member where mem_id ='{$_SESSION['iam_member_id']}')";
                    if($search_str)
                        $group_sql .= " and ggi.name like '%$search_str%' ";
                    $group_sql .= " group by group_id order by g_mem_count desc";
                    $group_sql .= " limit " . $start_num2 . ", " . $list2;
                }else if($type == "sample"){
                    $group_sql = "select card_short_url,mem_code,main_img1,name,group_id from Gn_Iam_Name_Card card 
                                    inner join gn_group_info info on info.idx = card.group_id 
                                    inner join Gn_Member mem on mem.mem_id = card.mem_id 
                                    where group_id is not NULL and group_id > 0";
                    if($my_group != "")
                        $group_sql .= " and group_id not in ($my_group)";
                    $group_sql .= "  and sample_click = 'Y'";
                    if($search_str)
                        $group_sql .= " and info.name like '%$search_str%'";
                    $group_sql .= " order by sample_order desc";
                    $group_sql .= " limit " . $start_num2 . ", " . $list2;
                }else if($type == "general"){
                    if($type2 == "group") {
                        $group_sql = "select card_short_url,mem_code,main_img1,name,group_id from gn_group_info info
                                    inner join Gn_Iam_Name_Card card on info.card_idx = card.idx 
                                    inner join Gn_Member mem on mem.mem_id = card.mem_id where info.public_status = 'Y' ";
                        if($my_group != "")
                            $group_sql .= " and info.idx not in ($my_group)";
                        if ($search_str)
                            $group_sql .= " and info.name like '%$search_str%'";
                        $group_sql .= " order by req_date desc";
                        $group_sql .= " limit " . $start_num2 . ", " . $list2;
                    }else if($type2 == "con"){
                        if ($search_str) {
                            if($my_group != "")
                                $group_sql = "select cont.*,ggi.name,ggi.card_idx as group_card from Gn_Iam_Contents cont inner join gn_group_info ggi on ggi.idx=cont.group_id where group_id is not NULL and group_id > 0 and group_id not in (" . $my_group . ") and (cont.contents_title like '%$search_str%' or cont.contents_desc like '%$search_str%')";
                            else
                                $group_sql = "select cont.*,ggi.name,ggi.card_idx as group_card from Gn_Iam_Contents cont inner join gn_group_info ggi on ggi.idx=cont.group_id where group_id is not NULL and group_id > 0 and (cont.contents_title like '%$search_str%' or cont.contents_desc like '%$search_str%')";
                            $group_sql .= " order by sample_display,idx desc limit " . $start_num2 . ", " . $list2;
                        }else if($type3 == "sample"){
                            if($my_group != "")
                                $group_sql = "select cont.*,ggi.name,ggi.card_idx as group_card from Gn_Iam_Contents cont inner join gn_group_info ggi on ggi.idx=cont.group_id where group_id is not NULL and group_id > 0 and group_id not in (".$my_group.") ";
                            else
                                $group_sql = "select cont.*,ggi.name,ggi.card_idx as group_card from Gn_Iam_Contents cont inner join gn_group_info ggi on ggi.idx=cont.group_id where group_id is not NULL and group_id > 0 ";
                            $group_sql .= " and sample_display='Y' order by sample_order desc limit " . $start_num2 . ", " . $list2;
                        }else if($type3 == "recent"){
                            if($my_group != "")
                                $group_sql = "select cont.*,ggi.name,ggi.card_idx as group_card from Gn_Iam_Contents cont inner join gn_group_info ggi on ggi.idx=cont.group_id where group_id is not NULL and group_id > 0 and group_id not in (".$my_group.") ";
                            else
                                $group_sql = "select cont.*,ggi.name,ggi.card_idx as group_card from Gn_Iam_Contents cont inner join gn_group_info ggi on ggi.idx=cont.group_id where group_id is not NULL and group_id > 0 ";
                            $group_sql .= " order by idx desc limit " . $start_num2 . ", " . $list2;
                        }
                    }
                }
                $gm_index = 0;
                $group_res = mysqli_query($self_con, $group_sql);
                while($group_row = mysqli_fetch_array($group_res)){
                    if($type == "sample" || $type == "friend" || ($type == "general" && $type2 == "group")){
                        $mem_sql = "select count(*) from gn_group_member where group_id='{$group_row['group_id']}'";
                        $mem_res = mysqli_query($self_con, $mem_sql);
                        $mem_row = mysqli_fetch_array($mem_res);
                        $weekMondayTime = date("Y-m-d",strtotime('last Monday'));
                        $cont_sql = "select count(*) from Gn_Iam_Contents where group_id='{$group_row['group_id']}' and req_data >= '$weekMondayTime'";
                        $cont_res = mysqli_query($self_con, $cont_sql);
                        $cont_row = mysqli_fetch_array($cont_res);
                        $f_sql = "select * from Gn_Member where site_iam = '{$Gn_mem_row['site_iam']}' and mem_id in (select mem_id from gn_group_member where group_id='{$group_row['group_id']}')";
                        $f_res = mysqli_query($self_con, $f_sql);
                        $f_count = mysqli_num_rows($f_res);
                        ?>
                        <div style="padding-top: 2px;">
                            <div style="border:1px solid #ddd;background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display:flex;justify-content: space-between;">
                                <div style="display: flex" onclick = "location.href = '/index.php?' + '<?=$group_row['card_short_url'].$group_row['mem_code']?>'+'&cur_win=group-con&gkind=' +'<?=$group_row['group_id']?>'">
                                    <div style="border-radius: 10%;width: 50px;height: 50px;overflow: hidden;margin: 10px;position:relative">
                                        <img src="<?=$group_row['main_img1']?>" style="width: 50px;height:50px;">
                                    </div>
                                    <div>
                                        <h4 style="margin-left: 10px;margin-top: 10px"><?=$group_row['name']?></h4>
                                        <h5 style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px">
                                            <small>멤버 <?=$mem_row[0]?>명 &bull; 주간 게시물 <?=$cont_row[0]?>개</small>
                                        </h5>
                                        <?if($f_count > 0){?>
                                            <div style = "display:flex;margin-left:20px;margin-top:10px">
                                                <?
                                                $f_index = 0;
                                                while($f_row = mysqli_fetch_array($f_res)){
                                                    if($f_index == 0)
                                                        $f_name = $f_row['mem_name'];
                                                    if($f_index++ < 12){?>
                                                        <div style="border:1px solid #ddd;border-radius: 50%;width: 30px;height: 30px;overflow: hidden;margin-left: -10px;">
                                                            <img src="<?=$f_row['profile']?>" style="width: 100%;height:100%;">
                                                        </div>
                                                    <?  }
                                                }?>
                                            </div>
                                            <h5 style="margin-left:10px;margin-top:5px"><small><?=$f_name."님 외 친구 ".($f_count-1)."명이 멤버입니다."?></small></h5>
                                        <?}?>
                                    </div>
                                </div>
                                <div style="display:flex">
                                    <div class="dropdown " style="margin-right: 10px;margin-top: auto;margin-bottom: auto;">
                                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" style="height: 34px">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu comunity" style="border: 1px solid #ccc;padding:0px;">
                                            <?
                                            $card_sql = "select card_name from Gn_Iam_Name_Card where group_id = '{$group_row['group_id']}' and phone_display = 'Y' order by req_data";
                                            $card_res = mysqli_query($self_con, $card_sql);
                                            $card_num = 1;
                                            while($card_row = mysqli_fetch_array($card_res)){
                                                ?>
                                                <li><a onclick="javascript:;;" style="padding:3px 3px 0px 3px !important;"><?=$card_row[0] == ""?$card_num."번카드":$card_row[0];?></a></li>
                                                <?
                                                $card_num++;
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <button class = "btn btn-link" type="button" onclick = "join_group('<?=$group_row['group_id']?>')">참여</button>
                                </div>
                            </div>
                        </div>
                    <?}else{
                        if(!$group_row['contents_img'])
                            $content_images = null;
                        else
                            $content_images = explode(",",$group_row['contents_img']);
                        for($i = 0; $i < count($content_images); $i++) {
                            if (strstr($content_images[$i], "kiam")) {
                                $content_images[$i] = str_replace("http://kiam.kr", "", $content_images[$i]);
                                $content_images[$i] = str_replace("http://www.kiam.kr", "", $content_images[$i]);
                                //$content_images[$i] = $cdn_ssl . $content_images[$i];
                            } 
                            if (!strstr($content_images[$i], "http") && $content_images[$i]) {
                                $content_images[$i] = $cdn_ssl . $content_images[$i];
                            }
                        }
                        $card_sql = "select card.*,mem.mem_code from Gn_Iam_Name_Card card inner join Gn_Member mem on card.mem_id = mem.mem_id where idx = '$group_row[group_card]'";
                        $card_res = mysqli_query($self_con, $card_sql);
                        $card_row = mysqli_fetch_array($card_res);
                        $contents_card_url = $card_row['card_short_url'];
                        $m_code = $card_row['mem_code'];
                        $group_info = $group_row['public_status'] == "Y"?"공개그룹":"비공개그룹";
                        $group_info .= "&bull;";
                        $mem_sql = "select count(*) from gn_group_member where group_id = '{$group_row['group_id']}'";
                        $mem_res = mysqli_query($self_con, $mem_sql);
                        $mem_row = mysqli_fetch_array($mem_res);
                        $group_info .= "멤버 ".$mem_row[0]."명";
                        ?>
                        <div class="content-item" id="contents_image" style="margin-bottom: 20px;box-shadow: 2px 3px 3px 1px #eee;border: 1px solid #ccc;">
                            <div class="user-item" style="position: relative;display: flex;padding: 4px;border: none;border-bottom: 1px solid #dddddd;padding-top: 12px;padding-bottom: 12px;">
                                <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="img-box">
                                    <div class="user-img" style="width: 38px;height: 38px;border-radius: 50%;overflow: hidden;">
                                        <img src="<?=$card_row['main_img1'] ?>" alt="">
                                    </div>
                                </a>
                                <div class="wrap" style="margin-left: 10px">
                                    <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="user-name" style="display: block;margin-top: 0px;font-size: 15px;font-weight: 700;color: #000000;">
                                        <?=$group_row['name'] ?>
                                    </a>
                                    <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="user-name" >
                                        <?=$group_info?>
                                    </a>
                                </div>
                                <button class = "btn btn-link" type="button" style="position: absolute; right: 40px; top: 16px;" onclick = "join_group('<?=$group_row['group_id']?>')">참여</button>
                                <?if( $_SESSION['iam_member_id'] != "" && $_SESSION['iam_member_id'] != $group_row['mem_id']  ){?>
                                    <div class="dropdown" style="position: absolute; right: 10px; top: 8px;">
                                        <button class="btn-link dropdown-toggle westory_dropdown" type="button" data-toggle="dropdown" >
                                            <img src="/iam/img/menu/icon_dot.png" style="height:24px">
                                        </button>
                                        <ul class="dropdown-menu comunity">
                                            <li><a onclick="location.href='/?<?=strip_tags($card_row['card_short_url'])?>'">이 콘텐츠 게시자 보기</a></li>
                                            <li><a onclick="set_friend('<?=$card_row['mem_id'] ?>','<?=$card_row['card_name'] ?>','<?=$card_row['card_short_url'] ?>','<?=$card_row['idx'] ?>')">이 게시자와 프렌즈 하기</a></li>
                                            <li><a onclick="set_my_share_contents('<?=$group_row['idx']?>')">이 콘텐츠 나에게 가져오기</a></li>
                                        </ul>
                                    </div>
                                <?}?>
                            </div>
                            <div class="desc-wrap" style="border-bottom: 1px solid #dddddd;">
                                <?if($group_row['contents_title'] != "") { ?>
                                    <div class="title service_title" style="display: flex;flex-direction: column;justify-content: center;height: 70px;min-height: 30px;position: relative;padding: 5px 10px;">
                                        <span style="text-align: center;font-size: 17px;overflow: hidden;text-overflow: clip;height: 40px;margin-top: auto;"><?= $group_row['contents_title'] ?></span>
                                    </div>
                                    <?
                                }
                                if((int)$group_row['contents_type'] == 3) {?>
                                    <div class="desc is-product" style="border: 1px solid #d9d9d9;position: relative;">
                                        <div class="desc-inner" style="padding: 5px 10px;font-size: 12px;line-height: 16px;">
                                            <div class="outer" style="display: table;width: 100%;">
                                                <?if($group_row['contents_price'] > 0){
                                                    $discount = 100 - ($group_row['contents_sell_price'] / $group_row['contents_price']) * 100;?>
                                                    <div class="percent" style="width: 80px;padding: 10px 0;color: red;font-size: 30px;line-height: 34px;font-weight: 700;display: table-cell;vertical-align: middle;"><?=(int)$discount?>%</div>
                                                    <div class="price" style="display: table-cell;vertical-align: middle;">
                                                        <span class="upper" style="display: block;text-decoration: line-through;font-size: 12px;color: #666;"><?=number_format($group_row['contents_price'])?>원</span>
                                                        <span class="downer" style="display: block;font-size: 16px;font-weight: 500;"><?=number_format($group_row['contents_sell_price'])?>원</span>
                                                    </div>
                                                <?}else{?>
                                                    <div class="percent" style="width: 80px;padding: 10px 0;color: red;font-size: 30px;line-height: 34px;font-weight: 700;display: table-cell;vertical-align: middle;">0%</div>
                                                    <div class="price" style="display: table-cell;vertical-align: middle;">
                                                        <span class="upper" style="display: block;text-decoration: line-through;font-size: 12px;color: #666;"><?=number_format($group_row['contents_sell_price'])?>원</span>
                                                        <span class="downer" style="display: block;font-size: 16px;font-weight: 500;"><?=number_format($group_row['contents_sell_price'])?>원</span>
                                                    </div>
                                                <?}?>
                                                <div class="order" style="text-align: right;display: table-cell;vertical-align: middle;">
                                                    <?if($_SESSION['iam_member_id']){
                                                        $price_service = $group_row['contents_sell_price'];
                                                        $name_service = $group_row['contents_title'];
                                                        $sellerid_service = $group_row['mem_id'];
                                                        $contents_url = $group_row['contents_url'];
                                                        $pay_link = "pay_spgd.php?item_name=". $group_row['contents_title'].'&item_price='.$group_row['contents_sell_price'].'&manager='.$group_row['mem_id'];
                                                        ?>
                                                        <div class="dropdown" style="float:right;position: relative;">
                                                            <a class = "dropdown-toggle" data-toggle="dropdown" expanded = "false" style="cursor:pointer;display: inline-block;padding: 5px 15px;background:#99cc00;border-radius:10px;font-size: 24px;color: #fff;font-weight: 700;line-height: 28px;">구매하기</a>
                                                            <ul class="dropdown-menu buy_servicecon" style="top: 37px;text-align: center;min-width: 0px;left: 0px !important;">
                                                                <li>
                                                                    <a href="<?=$pay_link?>" target="_blank" style="font-size: 15px;background-color:blue;">카드결제</a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="point_settle_modal(<?=$group_row['contents_sell_price']?>, '<?=$group_row['contents_title']?>', '<?=$group_row['idx']?>', '<?=$group_row['mem_id']?>')" style="font-size: 15px;background-color:blue;">포인트결제</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    <?}else{?>
                                                        <a href="<?echo 'login.php?contents_idx='. $group_row['idx']?>" target="_self" style="background:#99cc00;border-radius:10px;">구매하기</a>
                                                    <?}?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?}?>
                            </div>
                            <div class="media-wrap" style="position: relative;">
                                <div class="media-inner" style="max-height: 1000px;overflow-y: auto;border-top: 0px solid #7f7f7f;border-bottom: 0px solid #7f7f7f;padding: 0px 0px 0px;font-size: 10px;text-align: center;<?=($group_row['contents_type'] == 1 && count($content_images) == 0)?'min-height :30px':'min-height: 100px;';?>">
                                    <?
                                    if((int)$group_row['contents_type'] == 1 || (int)$group_row['contents_type'] == 3 ) {
                                        if($group_row['contents_url']) {
                                            $kk++;
                                            if(count($content_images) > 1){?>
                                                <button onclick="show_all_content_images('<?=$group_row['idx']?>')"  id = "content_all_image<?=$group_row['idx']?>"
                                                    style="position: absolute;right:0px;bottom:0px;font-size: 14px;opacity: 60%;background: black;color: white;"><?="+".(count($content_images)-1)?></button>
                                                <button onclick="hide_all_content_images('<?=$group_row['idx']?>')"  id = "hide_content_all_image<?=$group_row['idx']?>"
                                                    style="position: absolute;left:0px;top:300px;font-size: 14px;display:none;background: transparent">
                                                    <img src="img/main/icon-img_fold.png" style="width:30px">
                                                </button>
                                            <?}
                                            if((int)$group_row['open_type'] == 1){
                                                if(count($content_images) > 0){?>
                                                    <div onclick="showpage<?=$kk?>('<?=$cross_page.urlencode($group_row['contents_url'])?>')" id="pagewrap<?=$kk?>">
                                                        <img src="<?=cross_image($content_images[0])?>" class="contents_img">
                                                        <?
                                                        for($c = 1;$c < count($content_images);$c ++){?>
                                                            <img src="<?=cross_image($content_images[$c])?>" class="contents_img hidden_image<?=$group_row['idx']?>" style="display:none">
                                                        <?}?>
                                                    </div>
                                                <?}else{?>
                                                    <div>
                                                        <iframe src="<?=$cross_page.urlencode($group_row['contents_url'])?>" width="100%" height="600px" sandbox="allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation"></iframe>
                                                    </div>
                                                <?}?>
                                            <?}else{?>
                                                <a href='<?=$group_row['contents_url']?>' target="_blank" id="pagewrap<?=$kk?>">
                                                    <img src="<?=cross_image($content_images[0])?>" class="contents_img">
                                                    <?
                                                    if(count($content_images) > 1){
                                                        for($c = 1;$c < count($content_images);$c ++){?>
                                                            <img src="<?=cross_image($content_images[$c])?>" class="contents_img hidden_image<?=$group_row['idx']?>" style="display:none">
                                                    <?}
                                                    }?>
                                                </a>
                                            <?}?>
                                            <script type="text/javascript">
                                                function showpage<?=$kk;?>(url) {
                                                    document.getElementById('pagewrap<?=$kk;?>').innerHTML = '<iframe src="'+url+'" width="100%" height="600px" sandbox="allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation"></iframe>';
                                                }
                                            </script>
                                        <?} else {?>
                                            <img src="<?=$content_images[0]?>" class="contents_img">
                                            <?if(count($content_images) > 1){?>
                                                <button onclick="show_all_content_images('<?=$group_row['idx']?>')"  id = "content_all_image<?=$group_row['idx']?>"
                                                    style="position: absolute;right:0px;bottom:0px;font-size: 14px;opacity: 60%;background: black;color: white;"><?="+".(count($content_images)-1)?></button>
                                                <button onclick="hide_all_content_images('<?=$group_row['idx']?>')"  id = "hide_content_all_image<?=$group_row['idx']?>"
                                                    style="position: absolute;left:0px;top:300px;font-size: 14px;display:none;background: transparent">
                                                    <img src="img/main/icon-img_fold.png" style="width:30px">
                                                </button>
                                            <?
                                                for($c = 1;$c < count($content_images);$c ++){?>
                                                    <img src="<?=cross_image($content_images[$c])?>" class="contents_img hidden_image<?=$group_row['idx']?>" style="display:none">
                                            <?  }
                                            }
                                        }
                                    } else if((int)$group_row['contents_type'] == 2) {
                                    $contents_movie = true;
                                    if(!$group_row['contents_img']){
                                        $contents_movie = false;
                                        echo $group_row['contents_iframe'];
                                    }else{
                                    $kk++;
                                    ?>
                                    <?if(count($content_images) > 1){?>
                                        <button onclick="show_all_content_images('<?=$group_row['idx']?>')"  id = "content_all_image<?=$group_row['idx']?>"
                                                style="position: absolute;right:0px;bottom:0px;font-size: 14px;opacity: 60%;background: black;color: white;"><?="+".(count($content_images)-1)?></button>
                                        <button onclick="hide_all_content_images('<?=$group_row['idx']?>')"  id = "hide_content_all_image<?=$group_row['idx']?>"
                                                style="position: absolute;left:0px;top:300px;font-size: 14px;display:none;background: transparent">
                                            <img src="img/main/icon-img_fold.png" style="width:30px">
                                        </button>
                                    <?}
                                    if((int)$group_row['open_type'] == 1){?>
                                        <div onclick="play<?=$kk;?>();" id="vidwrap<?=$kk;?>" style="position: relative;">
                                            <img src="<?=cross_image($content_images[0])?>" class="contents_img">
                                            <?if($contents_movie){?>
                                                <img class="movie_play" src="/iam/img/movie_play.png" >
                                            <?}?>
                                            <?if(count($content_images) > 1){?>
                                                <?for($c = 1;$c < count($content_images);$c ++){?>
                                                    <img src="<?=cross_image($content_images[$c])?>" class="contents_img hidden_image<?=$group_row['idx']?>" style="display:none">
                                                <?}?>
                                            <?}?>
                                        </div>
                                    <?}else{?>
                                        <a href="<?=$group_row['contents_url']?>" target="_blank" id="vidwrap<?=$kk;?>" style="position: relative;">
                                            <img src="<?=cross_image($content_images[0])?>" class="contents_img">
                                            <?if($contents_movie){?>
                                                <img class="movie_play" src="/iam/img/movie_play.png" >
                                            <?}?>
                                            <?if(count($content_images) > 1){?>
                                                <?for($c = 1;$c < count($content_images);$c ++){?>
                                                    <img src="<?=cross_image($content_images[$c])?>" class="contents_img hidden_image<?=$group_row['idx']?>" style="display:none">
                                                <?}?>
                                            <?}?>
                                        </a>
                                    <?}?>
                                        <script type="text/javascript">
                                            function play<?=$kk;?>() {
                                                document.getElementById('vidwrap<?=$kk;?>').innerHTML = "<?=$group_row['contents_iframe']?>";
                                            }
                                        </script>
                                    <?
                                    }
                                    } else if((int)$group_row['contents_type'] == 4) {
                                    if((int)$group_row['open_type'] == 1){?>
                                        <div onclick="play<?=$kk;?>();" id="vidwrap<?=$kk;?>">
                                            <?if($content_images[0]){?>
                                                <img src="<?=cross_image($content_images[0])?>" class="contents_img">
                                                <iframe src="<?=$vid_data?>" style="width:100%;height: 600px;display:none"></iframe>
                                            <?}else{?>
                                                <iframe src="<?=$vid_data?>" style="width:100%;height: 600px;"></iframe>
                                            <?}?>
                                        </div>
                                    <?}else{?>
                                        <a href="<?=$group_row['contents_url']?>" id="vidwrap<?=$kk;?>">
                                            <?if($content_images[0]){?>
                                                <img src="<?=cross_image($content_images[0])?>" class="contents_img">
                                                <iframe src="<?=$vid_data?>" style="width:100%;height: 600px;display:none"></iframe>
                                            <?}else{?>
                                                <iframe src="<?=$vid_data?>" style="width:100%;height: 600px;"></iframe>
                                            <?}?>
                                        </a>
                                    <?}?>
                                        <script type="text/javascript">
                                            function play<?=$kk;?>() {
                                                $('#vidwrap<?=$kk;?> > iframe').css("display","block");
                                                $('#vidwrap<?=$kk;?> > img').css("display","none");
                                            }
                                        </script>
                                    <?}?>
                                </div>
                            </div>
                            <div class="info-wrap"
                                <?
                                if($group_row['contents_type'] == 1 && count($content_images) == 0)
                                    echo "style = 'min-height :30px;border:none;'";
                                else
                                    echo "style = 'border:none;border-top:1px solid #dddddd;'";
                                ?>>
                                <?if($group_row['contents_url']){?>
                                    <div class="media-tit" style="padding-bottom:0px;font-size: 16px; font-weight: bold;padding: 15px;">
                                    </div>
                                <?}
                                if($group_row['contents_desc']) {?>
                                    <div class="desc" style="padding: 15px;" >
                                        <div class="desc-inner desc-text desc-inner-content" style="font-size:15px;font-weight: bold;line-height: 30px;height: 55px;overflow: hidden;">
                                            <?=nl2br($group_row['contents_desc'])?>
                                        </div>
                                        <a href="javascript:;;" class="arrow" style="color:#888;font-weight:bold;">
                                            [+]
                                        </a>
                                    </div>
                                <?}?>
                                <div class="second-box" style="padding: 15px;border-top: 1px solid #dddddd;<?=$card_row['post_display'] == 0 ?'display:none !important':'display:flex';?>">
                                    <div class="in-box" style="display: flex;border-top: none;">
                                        <div style="display: flex;vertical-align: middle">
                                            <?
                                                $post_sql = "select SQL_CALC_FOUND_ROWS * from Gn_Iam_Post p inner join Gn_Member m on p.mem_id = m.mem_id where p.content_idx = '$group_row[idx]' and p.lock_status = 'N' order by p.reg_date";
                                                $post_res = mysqli_query($self_con, $post_sql);
                                            ?>
                                            <a  class = "hand" href="javascript:contents_like('<?=$group_row['idx']?>','<?=$_SESSION['iam_member_id']?>');">
                                                <?if(in_array($_SESSION['iam_member_id'],explode(",",$group_row['contents_like']))){?>	
                                                <img src="/iam/img/menu/icon_like_active.png" width="24px" alt="" id="like_img_<?=$group_row['idx']?>">
                                                <?}else{?>
                                                <img src="/iam/img/menu/icon_like.png" width="24px" alt="" id="like_img_<?=$group_row['idx']?>">
                                                <?}?>
                                            </a>
                                            <p class = "second-box-like like-count like_<?=$group_row['idx']?>" style="font-size:13px;margin-top:2px;">
                                                <?=number_format(count(explode(",",$group_row['contents_like'])))?>개
                                            </p>
                                            <a href="javascript:show_post('<?=$group_row['idx']?>');" class="hand" style="display: block;margin-left:10px">
                                                <img src="img/menu/icon_post.png" height="24px" alt="">
                                                <label style="font-size: 10px;background: #ff3333;border-radius: 50%!important;padding: 3px 5px!important;color: #fff;
                                                                            text-align: center;line-height: 1;position: absolute;margin-left: -15px" id = "<?='post_alarm_'.$group_row['idx']?>"></label>
                                            </a>
                                            <p onclick = "refresh_post('<?=$group_row['idx']?>')" style="margin-left:5px;font-size: 15px;font-weight: 600;" class = "second-box-like like-count" id = "<?='post_count_'.$group_row['idx']?>"><?=$post_count?>  &#x21BA;</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?
                            $post_status_sql = "select count(*) from Gn_Iam_Post where content_idx = '$group_row[idx]' and status = 'N' and lock_status = 'N'";
                            $post_status_res = mysqli_query($self_con, $post_status_sql);
                            $post_status_row =  mysqli_fetch_array($post_status_res);
                            $post_status_count = $post_status_row[0];
                            if ($post_status_count  > 0)
                                echo "<script>  $('#post_alarm_".$group_row['idx']."').html(".$post_status_count."); </script>";
                            else
                                echo "<script>  $('#post_alarm_".$group_row['idx']."').hide(); </script>";
                            ?>
                            <div class="post-wrap <?='post_wrap'.$group_row['idx']?>" style="display:none" id = "<?='post_wrap'.$group_row['idx']?>">
                                <div style="display: flex;justify-content: flex-end;">
							        <div style="margin-left:30px;margin-right:35px;width:100%">
                                        <textarea id = "post_content<?=$group_row['idx']?>" name = "post_content<?=$group_row['idx']?>" class="post_content" maxlength="300" style="font-size:14px;width:100%;height:35px;border: 1px" placeholder="댓글은 300자 이내로 작성해주세요" ></textarea>
                                    </div>
                                    <div style="width:35px">
                                        <button type="button" class="btn btn-link" style="position: absolute; right: 1px; padding: 9px 12px;color:#99cc00" id="send_post" onclick="add_post('<?=$group_row['idx']?>')">작성</button>
                                    </div>
                                </div>
                                <div style="margin-left:30px;">
                                    <span id = "post_status" name = "post_status" style="padding: 10px;font-size:10px">0/300</span>
                                </div>
                                <div style="border: 0px solid #dddddd;margin-left:30px;" id = "<?='post_list_'.$group_row['idx']?>" name = "<?='post_list_'.$group_row['idx']?>">
                                    <?while($post_row = mysqli_fetch_array($post_res)){
                                        $post_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$post_row['mem_id']}' order by req_data asc";
                                        $post_card_result = mysqli_query($self_con, $post_card_sql);
                                        $post_card_row = mysqli_fetch_array($post_card_result);
                                        ?>
                                        <div class="user-item" id="<?='post_reply'.$post_row['id']?>">
                                            <a href="/?<?=strip_tags($post_card_row['card_short_url'])?>" class="img-box">
                                                <div class="user-img" style="margin: 5px;width:32px;height:32px;">
                                                    <?if($post_row['profile']){?>
                                                        <img src="<?=$post_row['profile'] ?>" alt="">
                                                    <?}else{?>
                                                        <img src="img/profile_img.png" alt="">
                                                    <?}?>
                                                </div>
                                            </a>
                                            <div class="wrap" style="margin:10px 0px;">
                                                <span class="date">
                                                    <?=$post_row['mem_name'] ." ".$post_row['reg_date']?>
                                                </span>
                                                <span class="user-name">
                                                    <?=$post_row['content']?>
                                                </span>
                                            </div>
                                            <?if($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $post_row['mem_id']){?>
                                                <div class="dropdown" style="top : 10px;position : absolute;right:30px;height:24px">
                                                    <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height: 24px;">
                                                    <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                        <li>
                                                            <a href="javascript:void(0)" onclick="edit_post('<?=$group_row['idx']?>','<?=$post_row['id']?>','<?=$post_row['content']?>')" title="댓글 수정">
                                                                <p>수정</p>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" onclick="delete_post('<?=$group_row['idx']?>','<?=$post_row['id']?>')" title="댓글 삭제">
                                                                <p>삭제</p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?}else if($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $group_row['mem_id']){?>
                                                <div class="dropdown" style="top : 10px;position : absolute;right:30px;height:24px">
                                                    <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height: 24px;">
                                                    <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                        <li>
                                                            <a href="javascript:void(0)" onclick="delete_post('<?=$group_row['idx']?>','<?=$post_row['id']?>')" title="댓글 삭제">
                                                                <p>삭제</p>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" onclick="lock_post('<?=$group_row['idx']?>','<?=$post_row['id']?>')" title="댓글 차단">
                                                                <p>차단</p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?}?>
                                            <div style="position: absolute;left: 60px;bottom: 0px">
                                                <span style="color: #bdbdbd;cursor:pointer;font-size:13px" onclick="show_post_reply(<?=$post_row['id']?>);">
                                                    답글달기
                                                </span>
                                            </div>
                                        </div>
                                        <div id = "<?='post_reply_'.$post_row['id']?>"  class = "post_reply_wrap" style="display: none;margin : 10px 0px" >
                                            <div style="display: flex;justify-content: flex-end;">
                                                <div style="margin-left:60px;margin-right:35px;width:100%">
                                                    <textarea id = "<?='post_reply_'.$post_row['id'].'_content'?>" name = "<?='post_reply_'.$post_row['id'].'_content'?>" class  = "post_reply_content" maxlength="300" placeholder="답글은 300자 이내로 작성해주세요" style="font-size:14px;height:35px;width: 100%;border: 1px;"></textarea>
                                                </div>
                                                <div style="width:35px">
                                                    <button type="button" class="btn btn-link" style="position: absolute; right: 1px; padding: 5px 12px;color:#99cc00;font-size:18px" onclick="add_post_reply('<?=$group_row['idx']?>','<?=$post_row['id']?>')">작성</button>
                                                </div>
                                            </div>
                                            <div style="border-bottom: 0px solid #dddddd;margin-left:60px">
                                                <span id = "post_reply_status" name = "post_reply_status" style="padding: 10px">0/300</span>
                                            </div>
                                        </div>
                                        <?
                                        $reply_sql = "select * from Gn_Iam_Post_Response r inner join Gn_Member m on r.mem_id = m.mem_id where r.post_idx = {$post_row['id']} order by r.reg_date";
                                        $reply_res = mysqli_query($self_con, $reply_sql);
                                        while($reply_row = mysqli_fetch_array($reply_res)){
                                            $reply_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$reply_row['mem_id']}' order by req_data asc";
                                            $reply_card_result = mysqli_query($self_con, $reply_card_sql);
                                            $reply_card_row = mysqli_fetch_array($reply_card_result);
                                            ?>
                                            <div class="user-item" style="padding-left: 50px">
                                                <a href="/?<?=strip_tags($reply_card_row['card_short_url'])?>" class="img-box">
                                                    <div class="user-img" style="margin: 5px;width:32px;height:32px;">
                                                        <?if($reply_row['profile']){ ?>
                                                            <img src="<?=$reply_row['profile'] ?>" alt="">
                                                        <?}else{?>
                                                            <img src="img/profile_img.png" alt="">
                                                        <?}?>
                                                    </div>
                                                </a>
                                                <div class="wrap">
                                                    <span class="date">
                                                        <?=$reply_row['mem_name'] ?>&nbsp;&nbsp;&nbsp;<?=$reply_row['reg_date']?>
                                                    </span>
                                                    <span class="user-name" id = "<?='reply_list_'.$reply_row['id']?>">
                                                        <?=$reply_row['contents']?>
                                                    </span>
                                                </div>
                                                <?if($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $post_row['mem_id']){?>
                                                    <div class="dropdown" style="top : 10px;position : absolute;right:30px;height:24px">
                                                        <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height:24px">
                                                        <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                            <li>
                                                                <a href="javascript:void(0)" onclick="edit_post_reply('<?=$group_row['idx']?>','<?=$post_row['id']?>','<?=$reply_row['id']?>','<?=$reply_row['contents']?>')" title="답글 수정">
                                                                    <p>수정</p>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" onclick="delete_post_reply('<?=$group_row['idx']?>','<?=$reply_row['id']?>')" title="답글 삭제">
                                                                    <p>삭제</p>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                <?}else if($_SESSION['iam_member_id'] &&$_SESSION['iam_member_id'] == $group_row['mem_id']){?>
                                                    <div class="dropdown" style="top : 10px;position : absolute;right:30px;height:24px">
                                                        <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height: 24px;">
                                                        <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                            <li>
                                                                <a href="javascript:void(0)" onclick="delete_post_reply('<?=$group_row['idx']?>','<?=$reply_row['id']?>')" title="답글 삭제">
                                                                    <p>삭제</p>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" onclick="lock_post_reply('<?=$group_row['idx']?>','<?=$reply_row['id']?>')" title="답글 차단">
                                                                    <p>차단</p>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                <?}?>
                                            </div>
                                        <?}
                                    }?>
                                </div>
                            </div>
                        </div>
                    <?}?>
                <?}?>
                <div class="pagination" style="<?=$style_page?>">
                    <ul>
                        <?
                        if($page2 > 1) { //만약 page가 1보다 크다면
                            $pre2 = $page2-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
                            echo "<li class='arrow'><a href='?type=$type&search_str=$search_str&page2=$pre2'><i class='fa fa-angle-left' aria-hidden='true'></i></a></li>";
                        }

                        for($i=$block_start2; $i<=$block_end2; $i++){
                            //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
                            if($page2 == $i) { //만약 page가 $i와 같다면
                                echo "<li class='active'><span>$i</span></li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
                            } else {
                                echo "<li><a href='?type=$type&type2=$type2&search_str=$search_str&page2=$i'>$i</a></li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
                            }
                        }
                        if($block_num2 < $total_block2) { //만약 현재 블록이 블록 총개수보다 크거나 작다면
                            $next2 = $page2 + 1; //next변수에 page + 1을 해준다.
                            echo "<li class='arrow'><a href='?type=$type&type2=$type2&search_str=$search_str&page2=$next2'><i class='fa fa-angle-right' aria-hidden='true'></i></a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
                        }?>
                    </ul>
                </div>
                <div class = "button-group" style="display: flex;justify-content: center;">
                    <button class="btn btn-default" onclick = "popup_close()">끝내기</button>
                </div>
            </div>
        </section>
    </main>
</div>
<!--내 콘텐츠로 가져오기 팝업-->
<div id="contents_get_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document" style="width:90%;max-width:768px;margin-left:auto;margin-right:auto">
        <div class="modal-content" >
            <div class="modal-header" style="display: flex">
            </div>
            <div class="modal-body">
                <table class='table table-bordered'>
                    <input type="hidden" value="" id="contents_get_card_url">
                    <tr>
                        <td>
                            <div class="attr-value" style="display:flex;flex-wrap: wrap;">
                                <?
                                $sql5="select card_short_url,card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                                $result5=mysqli_query($self_con, $sql5);
                                $i = 0;
                                while($row5=mysqli_fetch_array($result5)) {
                                    ?>
                                    <div title="<?=$row5['card_title']?>">
                                        <input type="checkbox" onchange="onChangeCardGetCheck(this)" class="contents_get_check" value="<?= $row5['card_short_url'] ?>">
                                        <?echo(($i+1).$MENU['IAM_CONTENTS']['CONTS3']."(".$row5['card_title'].")");?>
                                    </div>
                                    <?
                                    $i++;
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn btn-secondary" id = "contents_get_cancel" data-dismiss="modal" onclick="location.reload();">취소하기</button>
                <button type="button" class="btn btn-primary" id  = "contents_get_ok" onclick="get_shared_contents()">가져오기</button>
            </div>
        </div>
    </div>
</div>
</body>

<script>
    function join_group(group_id){
        $.ajax({
            type: "POST",
            url: "ajax/group.proc.php",
            dataType:"json",
            data: {
                method : "accept_invite",
                group_id : group_id
            },
            success: function (data) {
                if(data.result == "success"){
                    alert("그룹에 성공적으로 참여되었습니다.");
                }else{
                    alert(data.result);
                }
                location.reload();
            }
        });
    }
    function popup_close(){
        if('<?=$type?>' == "sample" || '<?=$type?>' == "friend" || ('<?=$type?>' == "general" && '<?=$type2?>' == "group")) {
            window.opener.location.reload();
            window.close();
        }else{
            location.href = "?type=general"
        }
    }
    function search_clicked(){
        $("#search_form").submit();
    }
    //콘텐츠에서 게시자 프렌즈 등록
    function set_friend(mem_id, card_name, card_short_url, card_idx) {
        var m_card_url = '<?=$_SERVER['SERVER_NAME']?>/?' + card_short_url;
        var formData = new FormData();
        formData.append('mode', "add_one");
        formData.append('card_idx', card_idx);
        formData.append('card_url', m_card_url);
        $.ajax({
            type: "POST",
            url: "ajax/friends_join.proc.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                alert(data, "프렌즈");
            }
        });
    }
    function set_my_share_contents(contents_id){
        $("#contents_get_modal").modal("show");
        $("#contents_get_ok").data("cont_id",contents_id);
    }
    function get_shared_contents(){
        var checkedCard = $("#contents_get_card_url").val();
        if(checkedCard == ""){
            alert("카드를 선택해주세요.");
            return;
        }
        var cont_id = $("#contents_get_ok").data("cont_id");
        var formData = new FormData();
        formData.append("post_type", "save_share_contents");
        formData.append("contents_id", cont_id);
        formData.append("checked_cards", checkedCard);
        $.ajax({
            type: "POST",
            url: "ajax/contents.proc.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                alert(data);
                $("#contents_get_modal").modal("hide");
            }
        });
    }
    function onChangeCardGetCheck(cb) {
        var checkedCard = $("#contents_get_card_url").val();
        var checkedCardArr = checkedCard.split(",");
        if(checkedCardArr[0] == "")
            checkedCardArr.pop();
        var cardURL = cb.value;
        if (cb.checked) {
            var index = checkedCardArr.indexOf(cardURL);
            if(index == -1)
                checkedCardArr.push(cardURL);
        } else {
            var index = checkedCardArr.indexOf(cardURL);
            checkedCardArr.splice(index,1);
        }
        $("#contents_get_card_url").val(checkedCardArr.toString());
    }
    // 콘텐츠 좋아요
    function contents_like(idx,like_id) {
        var formData = new FormData();
        formData.append("mode", "like");
        formData.append("contents_idx", idx);
        formData.append("like_id", like_id);
        $.ajax({
            type: "POST",
            url: "/iam/ajax/contents_like.proc.php",
            data: formData,
            contentType: false,
            processData: false,
            dataType:"json",
            success: function(data) {
                if(data.like_status == "Y"){
                    alert("성공되었습니다.", "좋아요");
                    $("#like_img_"  +idx).prop("src","/iam/img/menu/icon_like_active.png");
                }else{
                    alert("삭제되었습니다.", "좋아요");
                    $("#like_img_"  +idx).prop("src","/iam/img/menu/icon_like.png");
                }
                $(".like_" + idx).html(data.count + "개");
            }
        });
    }
    function show_post(content_idx){
        var recent_post = $("#recent_post").val();
        if(recent_post == 0){
            $.ajax({
                type:"POST",
                url:"ajax/ajax.v1.php",
                dataType:"json",
                data:{
                    post_alert : "Y",
                    mem_id: '<?=$_SESSION['iam_member_id']?>'
                },
                success:function(data){
                    $("#recent_post").val(1);
                },
                error: function(data){
                }
            });

        }
        if($(".post_wrap" + content_idx).css('display') == "none") {
            $(".post_wrap" + content_idx).show();
            $.ajax({
                type: "POST",
                url: "ajax/add_post.php",
                dataType: "json",
                data: {
                    content_idx: content_idx,
                    mode: 'read',
                    mem_id: '<?=$_SESSION['iam_member_id']?>'
                },
                success: function (data) {
                    if (data.result == 'success') {
                        $('#post_alarm_' + content_idx).hide();
                        reloadShareCount(data.share_recv_count,data.share_send_count,data.share_post_count);
                    }
                }
            });
        }else
            $(".post_wrap" + content_idx).hide();
    }
    function reloadShareCount(share_recv_count,share_send_count,share_post_count){
        var share_count = share_recv_count*1 + share_send_count*1 + share_post_count*1;
        if(share_count == 0){
            $('#share_count').hide();
            $('#share_recv_count').hide();
            $('#share_send_count').hide();
            $('#share_post_count').hide();
        }else{
            $('#share_count').html(share_count);
            $('#share_count').show();
            if(share_recv_count <= 0) {
                $('#share_recv_count').hide();
            }else{
                $('#share_recv_count').html(share_recv_count);
                $('#share_recv_count').show();
            }

            if(share_send_count <= 0) {
                $('#share_send_count').hide();
            }else{
                $('#share_send_count').html(share_send_count);
                $('#share_send_count').show();
            }

            if(share_post_count <= 0) {
                $('#share_post_count').hide();
            }else{
                $('#share_post_count').html(share_post_count);
                $('#share_post_count').show();
            }
        }
    }
    function refresh_post(content_idx){//refresh
        $.ajax({
            type:"POST",
            url:"ajax/add_post.php",
            dataType:"json",
            data:{
                content_idx : content_idx,
                mode : 'refresh',
                mem_id : '<?=$_SESSION['iam_member_id']?>'
            },
            success:function(data){
                reloadShareCount(data.share_recv_count,data.share_send_count,data.share_post_count);
                $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                if(data.post_status > 0) {
                    $('#post_alarm_' + content_idx).show();
                    $('#post_alarm_' + content_idx).html(data.post_status);
                }else{
                    $('#post_alarm_' + content_idx).hide();
                }
                $("#post_list_" +  content_idx).empty();
                for(var i in data.contents) {
                    add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                        data.contents[i].reg_date, data.contents[i].post_content,data.contents[i].post_mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    for(var reply_index in data.contents[i].reply_content){
                        var reply_content = data.contents[i].reply_content[reply_index];
                        add_one_reply(content_idx ,data.contents[i].post_idx,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date,
                            reply_content.post_content,reply_content.mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    }
                }
            },
            error : function(data){
                //console.log(data);
            }
        })
    }
    function add_one_post(content_idx ,post_idx,name_card,imglink,mem_name,reg_date,post_content,post_mem,login_mem,card_mem){
        if(imglink == "")
            imglink = 'img/profile_img.png';
        var cont = "<div class=\"user-item\" id=\"post_reply" + post_idx + "\">"+
            "<a href=\"/?"+name_card + "\" class=\"img-box\">"+
            "<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">"+
            "<img src=\"" +imglink + "\" >"+
            "</div>"+
            "</a>"+
            "<div class=\"wrap\" style=\"margin:10px 0px;\">"+
            "<span class=\"date\">"+
            mem_name+' '+reg_date +
            "</span>"+
            "<span class=\"user-name\">"+
            post_content+
            "</span>"+
            "</div>";
        if(post_mem == login_mem){
            cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">"+
                "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">"+
                "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">"+
                "<li>"+
                "<a href=\"javascript:void(0)\" onclick=\"edit_post('"+ content_idx + "','" +post_idx+ "','"+post_content+"')\" title=\"댓글 수정\">"+
                "<p>수정</p>"+
                "</a>"+
                "</li>"+
                "<li>"+
                "<a href=\"javascript:void(0)\" onclick=\"delete_post('"+ content_idx + "','" + post_idx + "')\" title=\"댓글 삭제\">"+
                "<p>삭제</p>"+
                "</a>"+
                "</li>"+
                "</ul>"+
                "</div>";
        }
        else if(card_mem == login_mem){
            cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:0px\">"+
                "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/main/custom.png\" style=\"height: 20px;\">"+
                "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">"+
                "<li>"+
                "<a href=\"javascript:void(0)\" onclick=\"delete_post('"+ content_idx + "','" + post_idx + "')\" title=\"댓글 삭제\">"+
                "<p>삭제</p>"+
                "</a>"+
                "</li>"+
                "<li>"+
                "<a href=\"javascript:void(0)\" onclick=\"lock_post('"+ content_idx + "','" + post_idx + "')\" title=\"댓글 차단\">"+
                "<p>차단</p>"+
                "</a>"+
                "</li>"+
                "</ul>"+
                "</div>";
        }
        cont+="<div style=\"position: absolute;left: 60px;bottom: 0px\">"+
            "<span style=\"color: #bdbdbd;cursor:pointer;font-size:13px\" onclick=\"show_post_reply(" + post_idx +");\">"+
            "답글달기"+
            "</span>"+
            "</div>"+
            "</div>"+
            "<div id = \"post_reply_" + post_idx +"\"  class = \"post_reply_wrap\" style=\"display: none;margin : 10px 0px\" >"+
            "<div style=\"display: flex;justify-content: flex-end;\">"+
                "<div style=\"margin-left:60px;margin-right:35px;width:100%\">"+
                    "<textarea id = \"post_reply_" + post_idx +"_content\" name = \"post_reply_"+post_idx+"_content\" class=\"post_reply_content\" maxlength=\"300\" placeholder=\"답글은 300자 이내로 작성해주세요\" style=\"font-size:14px;height:35px;width: 100%;border: 1px;\"></textarea>"+
                "</div>"+
                "<div style=\"width:35px\">"+
                    "<button type=\"button\" class=\"btn btn-link\" style=\"font-size:18px;position: absolute; right: 1px; padding: 5px 12px;color:#99cc00\" onclick=\"add_post_reply('"+content_idx+"','"+post_idx+"')\">작성</button>"+
                "</div>"+
            "</div>"+
            "<div style=\"border-bottom: 0px solid #dddddd;margin-left:60px\">"+
                "<span id = \"post_reply_status\" name = \"post_reply_status\" style=\"padding: 10px\">0/300</span>"+
            "</div>"+
            "</div>";
        $('#post_list_' + content_idx).append(cont);
    }
    function add_one_reply(content_idx ,post_id,reply_id,namecard,profile,mem_name,reg_date,reply_content,post_mem,login_mem,card_mem){
        if(profile == "")
            profile = 'img/profile_img.png';
        var cont = "<div class=\"user-item\" style=\"padding-left: 30px\">"+
            "<a href=\"/?"+namecard+"\" class=\"img-box\">"+
            "<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">"+
            "<img src=\""+ profile + "\">"+
            "</div>"+
            "</a>"+
            "<div class=\"wrap\">"+
            "<span class=\"date\">"+
            mem_name+"&nbsp;&nbsp;&nbsp;"+ reg_date +
            "</span>"+
            "<span class=\"user-name\" id=\"reply_list_"+reply_id+"\">"+
            reply_content+
            "</span>"+
            "</div>";
        if(post_mem == login_mem){
            cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">"+
                "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">"+
                "<ul class=\"dropdown-menu namecard-dropdown\" style=\"background: white; color : black;top:10px;\">"+
                "<li>"+
                "<a href=\"javascript:void(0)\" onclick=\"edit_post_reply('"+content_idx+"','"+ post_id + "','"+ reply_id+"','"+ reply_content +"')\" title=\"답글 수정\">"+
                "<p>수정</p>"+
                "</a>"+
                "</li>"+
                "<li>"+
                "<a href=\"javascript:void(0)\" onclick=\"delete_post_reply('"+ content_idx +"','"+ reply_id+"')\" title=\"답글 삭제\">"+
                "<p>삭제</p>"+
                "</a>"+
                "</li>"+
                "</ul>"+
                "</div>";
        }
        else if(card_mem == login_mem) {
            cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">"+
                "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">"+
                "<ul class=\"dropdown-menu namecard-dropdown\" style=\"background: white; color : black;top:10px;\">"+
                "<li>"+
                "<a href=\"javascript:void(0)\" onclick=\"delete_post_reply('"+ content_idx +"','" + reply_id+"')\" title=\"답글 삭제\">"+
                "<p>삭제</p>"+
                "</a>"+
                "</li>"+
                "<li>"+
                "<a href=\"javascript:void(0)\" onclick=\"lock_post_reply('"+ content_idx+"','"+ reply_id+"')\" title=\"답글 차단\">"+
                "<p>차단</p>"+
                "</a>"+
                "</li>"+
                "</ul>"+
                "</div>";
        }
        cont += "</div>";
        $('#post_list_' + content_idx).append(cont);
    }
    function add_post(content_idx){//댓글추가
        var post_content = $("#post_content" + content_idx);
        $.ajax({
            type:"POST",
            url:"ajax/add_post.php",
            dataType:"json",
            data:{
                content_idx : content_idx,
                mode : 'add',
                mem_id : '<?=$_SESSION['iam_member_id']?>',
                post_content:post_content.val()
            },
            success:function(data){
                if(data.result == "success"){
                    reloadShareCount(data.share_recv_count,data.share_send_count,data.share_post_count);
                    $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                    if(data.post_status > 0) {
                        $('#post_alarm_' + content_idx).show();
                        $('#post_alarm_' + content_idx).html(data.post_status);
                    }else{
                        $('#post_alarm_' + content_idx).hide();
                    }
                    $("#post_list_" +  content_idx).empty();
                    for(var i in data.contents) {
                        add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name, data.contents[i].reg_date,
                            data.contents[i].post_content,data.contents[i].post_mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                        for(var reply_index in data.contents[i].reply_content){
                            var reply_content = data.contents[i].reply_content[reply_index];
                            add_one_reply(content_idx ,data.contents[i].post_idx,reply_content.idx,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date,
                                reply_content.post_content,reply_content.mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                        }
                    }
                }else{
                    if(data.message == "login"){
                        location.href = "login.php?contents_idx="+ content_idx;
                    }else {
                        alert(data.message);
                    }
                }
            }
        })
    }
    function show_post_reply(post_idx){
        if($("#post_reply_" + post_idx).css('display') == "none")
            $("#post_reply_" + post_idx).show();
        else
            $("#post_reply_" + post_idx).hide();
    }
    function add_post_reply(content_idx,post_idx){//답글추가
        var post_content = $("#post_reply_"+ post_idx+"_content" );
        $.ajax({
            type:"POST",
            url:"ajax/add_post.php",
            dataType:"json",
            data:{
                content_idx : content_idx,
                post_idx : post_idx,
                mode : 'add_reply',
                mem_id : '<?=$_SESSION['iam_member_id']?>',
                post_content:post_content.val()
            },
            success:function(data){
                if(data.result == "success"){
                    reloadShareCount(data.share_recv_count,data.share_send_count,data.share_post_count);
                    $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                    if(data.post_status > 0) {
                        $('#post_alarm_' + content_idx).show();
                        $('#post_alarm_' + content_idx).html(data.post_status);
                    }else{
                        $('#post_alarm_' + content_idx).hide();
                    }
                    $("#post_list_" +  content_idx).empty();
                    for(var i in data.contents) {
                        add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                            data.contents[i].reg_date, data.contents[i].post_content,data.contents[i].post_mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                        for(var reply_index in data.contents[i].reply_content){
                            var reply_content = data.contents[i].reply_content[reply_index];
                            add_one_reply(content_idx ,data.contents[i].post_idx,reply_content.idx,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date,
                                reply_content.post_content,reply_content.mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                        }
                    }
                }else{
                    if(data.message == "login"){
                        location.href = "login.php?contents_idx="+ content_idx;
                    }else {
                        alert(data.message);
                    }
                }
            }
        })
    }
    function delete_post(content_idx,post_idx){//댓글 삭제
        var post_content = $("#post_content" + content_idx);
        $.ajax({
            type:"POST",
            url:"ajax/add_post.php",
            dataType:"json",
            data:{
                content_idx : content_idx,
                post_idx : post_idx,
                mode : 'del',
                mem_id : '<?=$_SESSION['iam_member_id']?>'
            },
            success:function(data){
                reloadShareCount(data.share_recv_count,data.share_send_count,data.share_post_count);
                $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                if(data.post_status > 0) {
                    $('#post_alarm_' + content_idx).show();
                    $('#post_alarm_' + content_idx).html(data.post_status);
                }else{
                    $('#post_alarm_' + content_idx).hide();
                }
                $("#post_list_" +  content_idx).empty();
                for(var i in data.contents) {
                    add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                        data.contents[i].reg_date, data.contents[i].post_content,data.contents[i].post_mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    for(var reply_index in data.contents[i].reply_content){
                        var reply_content = data.contents[i].reply_content[reply_index];
                        add_one_reply(content_idx ,data.contents[i].post_idx,reply_content.idx,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date,
                            reply_content.post_content,reply_content.mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    }
                }
            }
        });
    }
    function delete_post_reply(content_idx,reply_idx){//답글 삭제
        var post_content = $("#post_content" + content_idx);
        $.ajax({
            type:"POST",
            url:"ajax/add_post.php",
            dataType:"json",
            data:{
                content_idx : content_idx,
                post_idx : reply_idx,
                mode : 'delete_reply',
                mem_id : '<?=$_SESSION['iam_member_id']?>'
            },
            success:function(data){
                reloadShareCount(data.share_recv_count,data.share_send_count,data.share_post_count);
                $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                if(data.post_status > 0) {
                    $('#post_alarm_' + content_idx).show();
                    $('#post_alarm_' + content_idx).html(data.post_status);
                }else{
                    $('#post_alarm_' + content_idx).hide();
                }
                $("#post_list_" +  content_idx).empty();
                for(var i in data.contents) {
                    add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                        data.contents[i].reg_date, data.contents[i].post_content,data.contents[i].post_mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    for(var reply_index in data.contents[i].reply_content){
                        var reply_content = data.contents[i].reply_content[reply_index];
                        add_one_reply(content_idx ,data.contents[i].post_idx,reply_content.idx,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date,
                            reply_content.post_content,reply_content.mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    }
                }
            }
        });
    }
    function lock_post(content_idx,post_idx){//댓글 차단
        var post_content = $("#post_content" + content_idx);
        $.ajax({
            type:"POST",
            url:"ajax/add_post.php",
            dataType:"json",
            data:{
                content_idx : content_idx,
                post_idx : post_idx,
                mode : 'lock',
                mem_id : '<?=$_SESSION['iam_member_id']?>'
            },
            success:function(data){
                reloadShareCount(data.share_recv_count,data.share_send_count,data.share_post_count);
                $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                if(data.post_status > 0) {
                    $('#post_alarm_' + content_idx).show();
                    $('#post_alarm_' + content_idx).html(data.post_status);
                }else{
                    $('#post_alarm_' + content_idx).hide();
                }
                $("#post_list_" +  content_idx).empty();
                for(var i in data.contents) {
                    add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                        data.contents[i].reg_date, data.contents[i].post_content,data.contents[i].post_mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    for(var reply_index in data.contents[i].reply_content){
                        var reply_content = data.contents[i].reply_content[reply_index];
                        add_one_reply(content_idx ,data.contents[i].post_idx,reply_content.idx,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date,
                            reply_content.post_content,reply_content.mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    }
                }
            }
        });
    }
    function lock_post_reply(content_idx,reply_idx){//답글 차단
        var post_content = $("#post_content" + content_idx);
        $.ajax({
            type:"POST",
            url:"ajax/add_post.php",
            dataType:"json",
            data:{
                content_idx : content_idx,
                post_idx : reply_idx,
                mode : 'lock_reply',
                mem_id : '<?=$_SESSION['iam_member_id']?>'
            },
            success:function(data){
                reloadShareCount(data.share_recv_count,data.share_send_count,data.share_post_count);
                $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                if(data.post_status > 0) {
                    $('#post_alarm_' + content_idx).show();
                    $('#post_alarm_' + content_idx).html(data.post_status);
                }else{
                    $('#post_alarm_' + content_idx).hide();
                }
                $("#post_list_" +  content_idx).empty();
                for(var i in data.contents) {
                    add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                        data.contents[i].reg_date, data.contents[i].post_content,data.contents[i].post_mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    for(var reply_index in data.contents[i].reply_content){
                        var reply_content = data.contents[i].reply_content[reply_index];
                        add_one_reply(content_idx ,data.contents[i].post_idx,reply_content.idx,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date,
                            reply_content.post_content,reply_content.mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    }
                }
            }
        });
    }
    function edit_post(content_idx,post_idx,content){
        var post_content = $("#post_content" + content_idx);
        $("#post_content" + content_idx).val(content);
        var post_wrap = $("#post_content" + content_idx).parents(".post-wrap");
        var status_lbl = post_wrap.find("#post_status");
        status_lbl.html($("#post_content" + content_idx).val().length + '/300');
        post_wrap.find("#send_post").attr("onClick","update_post("+content_idx+","+post_idx + ");");
    }
    function update_post(content_idx, post_idx){
        var post_content = $("#post_content" + content_idx);
        $.ajax({
            type:"POST",
            url:"ajax/add_post.php",
            dataType:"json",
            data:{
                content_idx : content_idx,
                post_idx : post_idx,
                mode : 'edit',
                mem_id : '<?=$_SESSION['iam_member_id']?>',
                post_content:post_content.val()
            },
            success:function(data){
                reloadShareCount(data.share_recv_count,data.share_send_count,data.share_post_count);
                $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                if(data.post_status > 0) {
                    $('#post_alarm_' + content_idx).show();
                    $('#post_alarm_' + content_idx).html(data.post_status);
                }else{
                    $('#post_alarm_' + content_idx).hide();
                }
                $("#post_list_" +  content_idx).empty();
                for(var i in data.contents) {
                    add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name, data.contents[i].reg_date,
                        data.contents[i].post_content,data.contents[i].post_mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    for(var reply_index in data.contents[i].reply_content){
                        var reply_content = data.contents[i].reply_content[reply_index];
                        add_one_reply(content_idx ,data.contents[i].post_idx,reply_content.idx,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date,
                            reply_content.post_content,reply_content.mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    }
                }
            }
        });
        post_content.val("");
        var post_wrap = post_content.parents(".post-wrap");
        var status_lbl = post_wrap.find("#post_status");
        status_lbl.html($("#post_content" + content_idx).val().length + '/300');
        post_wrap.find("#send_post").attr("onClick","add_post(" + content_idx + ");");
    }
    function edit_post_reply(content_idx,post_idx,reply_idx,content){
        var post_reply_content = $("#post_reply_"+ post_idx +"_content" );
        var post_wrap = post_reply_content.parents(".post_reply_wrap");
        var status_lbl = post_wrap.find("#post_reply_status");
        if($("#post_reply_" + post_idx).css('display') == "none") {
            $("#post_reply_" + post_idx).show();
            post_reply_content.html(content);
            status_lbl.html(content.length + '/300');
            post_wrap.find("button").attr("onClick","update_post_reply("+content_idx+","+post_idx +","+reply_idx + ");");
        }
        else {
            $("#post_reply_" + post_idx).hide();
            post_reply_content.html("");
            status_lbl.html('0/300');
            post_wrap.find("button").attr("onClick","add_post_reply("+content_idx+","+post_idx +","+reply_idx + ");");
        }
    }
    function update_post_reply(content_idx, post_idx,reply_idx){
        var post_content = $("#post_reply_"+ post_idx +"_content" );
        $.ajax({
            type:"POST",
            url:"ajax/add_post.php",
            dataType:"json",
            data:{
                content_idx : content_idx,
                post_idx : reply_idx,
                mode : 'edit_reply',
                mem_id : '<?=$_SESSION['iam_member_id']?>',
                post_content:post_content.val()
            },
            success:function(data){
                reloadShareCount(data.share_recv_count,data.share_send_count,data.share_post_count);
                $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                if(data.post_status > 0) {
                    $('#post_alarm_' + content_idx).show();
                    $('#post_alarm_' + content_idx).html(data.post_status);
                }else{
                    $('#post_alarm_' + content_idx).hide();
                }
                $("#post_list_" +  content_idx).empty();
                for(var i in data.contents) {
                    add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name, data.contents[i].reg_date,
                        data.contents[i].post_content,data.contents[i].post_mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    for(var reply_index in data.contents[i].reply_content){
                        var reply_content = data.contents[i].reply_content[reply_index];
                        add_one_reply(content_idx ,data.contents[i].post_idx,reply_content.idx,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date,
                            reply_content.post_content,reply_content.mem_id,data.contents[i].mem_id,data.contents[i].card_mem_id);
                    }
                }
            }
        });
        post_content.val("");
        var post_wrap = post_content.parents(".post-wrap");
        var status_lbl = post_wrap.find("#post_status");
        status_lbl.html($("#post_content" + content_idx).val().length + '/300');
        post_wrap.find("#send_post").attr("onClick","add_post(" + content_idx + ");");
    }
</script>
</html>