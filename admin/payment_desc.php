<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
$pay_row = array();
$sql = "select * from payment_info order by idx";
$res = mysqli_query($self_con,$sql);
while($row = mysqli_fetch_array($res)){
    array_push($pay_row,$row);
}
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    $(function(){
        $("input[type=checkbox]").change(function(){
            var idx = $(this).data("idx");
            var field = $(this).data("name");
            var value = $(this).is(":checked")==true?"Y":"N";
            $.ajax({
                type: "POST",
                async: false,
                url: "ajax/save_payment_info.php",
                data: {
                    idx : idx,
                    field : field,
                    value : value
                },
                cache: false,
                success: function(html) {
                }
            });
        });
        $("input[type=text]").change(function(){
            var idx = $(this).data("idx");
            var field = $(this).data("name");
            var value = $(this).val();
            $.ajax({
                type: "POST",
                async: false,
                url: "ajax/save_payment_info.php",
                data: {
                    idx : idx,
                    field : field,
                    value : value
                },
                cache: false,
                success: function(html) {
                }
            });
        });
        $("input[type=number]").change(function(){
            var idx = $(this).data("idx");
            var field = $(this).data("name");
            var value = $(this).val();
            $.ajax({
                type: "POST",
                async: false,
                url: "ajax/save_payment_info.php",
                data: {
                    idx : idx,
                    field : field,
                    value : value
                },
                cache: false,
                success: function(html) {
                }
            });
        });
    });
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if(navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top",contHeaderH);
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 196;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
</script>
<style>
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
        border: 1px solid #ddd!important;
    }
    input, select, textarea {
        vertical-align: middle;
        border: 1px solid #CCC;
    }
    thead tr th{
        text-align: center;
    }
    td{ text-align:center }
    td:first-child{text-align:left}
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
    .wrapper{height:100%;overflow:auto;}
    .content-wrapper{min-height : 80% !important;}
    .box-body{overflow:auto;padding:0px !important}
</style>

<div class="wrapper">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>결제상품 구성 관리<small>결제상품 구성정보를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">결제상품 구성 관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="150px">
                                <col width="80px">
                                <col width="80px">
                                <col width="80px">
                                <col width="80px">
                                <col width="80px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th></th>
                                    <?foreach($pay_row as $row){?>
                                        <th><?=$row[kind]?></th>
                                    <?}?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>가격</td>
                                    <?foreach($pay_row as $row){?>
                                        <td><input style="text-align:right" type="number" min=0 value=<?=$row[price]?> data-idx="<?=$row['idx']?>" data-name="price"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">IAM카드</td>
                                </tr>
                                <tr>
                                    <td colspan="5">[마이플랫폼]</td>
                                </tr>
                                <tr>
                                    <td>독립서브도메인 구축</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[domain] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="domain"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>메인로고, 푸터로고</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[logo] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="logo"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>플랫폼 회원승인수</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="number" value=<?=$row[mem_cnt]?> min="0" data-idx="<?=$row['idx']?>" data-name="mem_cnt"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>마케팅통합관리자기능</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[marketing] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="marketing"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>오토회원생성기능</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[auto_mem] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="auto_mem"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>단체콜백전송기능</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[callback] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="callback"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>전체회원스텝문자기능</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[step] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="step"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>관리자공지기능</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[alert] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="alert"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">[카드생성수]</td>
                                </tr>
                                <tr>
                                    <td>포토+프로필+콘텐츠</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="number" value=<?=$row[card_cnt]?> min="0" data-idx="<?=$row['idx']?>" data-name="card_cnt"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">[콘텐츠생성]</td>
                                </tr>
                                <tr>
                                    <td>한개/다수/자동/상품 등록</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[cont_make] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="cont_make"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">[공유기능]</td>
                                </tr>
                                <tr>
                                    <td>IAM카드별 공유</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[card_share] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="card_share"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>콘텐츠별 공유</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[cont_share] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="cont_share"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">[연락처기능]</td>
                                </tr>
                                <tr>
                                    <td>폰주소록 관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[phone_addr_list] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="phone_addr_list"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">[AI카드생성기능]</td>
                                </tr>
                                <tr>
                                    <td>AI자동생성</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="text" value=<?=$row[ai_auto]?> data-idx="<?=$row['idx']?>" data-name="ai_auto"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>오토데이트</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="text" value=<?=$row[autodate]?> data-idx="<?=$row['idx']?>" data-name="autodate"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">[회원전송기능]</td>
                                </tr>
                                <tr>
                                    <td>카드전송하기</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="text" value=<?=$row[card_send]?> data-idx="<?=$row['idx']?>" data-name="card_send"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>콘텐츠전송하기</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="text" value=<?=$row[cont_send]?> data-idx="<?=$row['idx']?>" data-name="cont_send"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">[광고카드]</td>
                                </tr>
                                <tr>
                                    <td>광고카드보기</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[banner_view] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="banner_view"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>광고카드만들기</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[banner_make] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="banner_make"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">[커넥팅기능]</td>
                                </tr>
                                <tr>
                                    <td>프렌즈</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[friends_connecting] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="friends_connecting"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">[그룹카드기능]</td>
                                </tr>
                                <tr>
                                    <td>그룹페이지참여</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[group_join] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="group_join"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>그룹페이지개설</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[group_create] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="group_create"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">IAM콜백</td>
                                </tr>
                                <tr>
                                    <td>개인자동/선택콜백</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[iam_callback] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="iam_callback"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>단체콜백전송기능</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[callback] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="callback"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">IAM폰문자</td>
                                </tr>
                                <tr>
                                    <td>폰연결수</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="text" value=<?=$row[phone_cnt] < 0 ?"무제한" : $row[phone_cnt]?> min="0" data-idx="<?=$row['idx']?>" data-name="phone_cnt"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>발송건수(+가능)</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="number" value=<?=$row[send_daily_cnt]?> min="0" data-idx="<?=$row['idx']?>" data-name="send_daily_cnt"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>대량엑셀발송</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[excel_send] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="excel_send"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>수신거부 자동관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[recv_n] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="recv_n"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>수신동의 자동관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[recv_y] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="recv_y"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>수신불가번호 자동관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[recv_block] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="recv_block"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>없는번호 자동관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[no_number] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="no_number"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>변경번호 자동관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[change_number] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="change_number"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>그룹별 발송</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[group_send] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="group_send"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>포토3장 발송</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[photo_send] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="photo_send"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>수신처제한 자동관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[recv_limit] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="recv_limit"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>고객이름 치환관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[name_change] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="name_change"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>발송비율 자동조절</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[send_percent] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="send_percent"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>예약발송</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[reserve_send] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="reserve_send"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">IAM디버</td>
                                </tr>
                                <tr>
                                    <td>휴대폰디비</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="number" value=<?=$row[phone_db]?> min="0" data-idx="<?=$row['idx']?>" data-name="phone_db"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>이메일디비</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="number" value=<?=$row[email_db]?> min="0" data-idx="<?=$row['idx']?>" data-name="email_db"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>일반번호</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[normal_number] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="normal_number"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>수집출처</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[recv_from] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="recv_from"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>쇼핑 디비</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input style="text-align:right" type="text" value=<?=$row[shop_db] < 0 ?"별도":$row[shop_db]?> min="0" data-idx="<?=$row['idx']?>" data-name="shop_db"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td colspan="5">IAM스탭</td>
                                </tr>
                                <tr>
                                    <td>랜딩페이지</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[landing] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="landing"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>고객신청창</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[request] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="request"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>스텝예약발송</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[reserve_step] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="reserve_step"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>데일리발송</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[send_daily] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="send_daily"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>신청고객관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[new_mem] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="new_mem"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>기존고객관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[old_mem] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="old_mem"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>발송예정내역관리</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[send_pre] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="send_pre"></td>
                                    <?}?>
                                </tr>
                                <tr>
                                    <td>발송결과내역확인</td>
                                    <?foreach($pay_row as $row){?>
                                    <td><input type="checkbox" <?=$row[send_result] == "Y"?"checked":""?> data-idx="<?=$row['idx']?>" data-name="send_result"></td>
                                    <?}?>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div><!-- /.wrapper -->