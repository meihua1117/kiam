<style>
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
/* user agent stylesheet */
input[type="checkbox" i] {
    background-color: initial;
    cursor: default;
    -webkit-appearance: checkbox;
    box-sizing: border-box;
    margin: 3px 3px 3px 4px;
    padding: initial;
    border: initial;
}
input:checked + .slider {
    background-color: #2196F3;
}
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}
input:checked + .slider {
    background-color: #2196F3;
}
.slider.round {
    border-radius: 34px;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}
input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}
.slider.round:before {
    border-radius: 50%;
}
.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}
.zoom {
  transition: transform .2s; /* Animation */
}
.zoom:hover {
  transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  border:1px solid #0087e0;
  box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
</style>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$mall_type_str = array("홍보대사","재능마켓","","중고마켓","메시지","","","","","갤러리","갤러리(회화)","갤러리(판화)","갤러리(조형)","갤러리(사진)","갤러리(AI아트)","갤러리(기타)");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
function goPage(pgNum) {
    location.href = '?nowPage='+pgNum+"&search_key=<?=$search_key?>&search_key1=<?=$search_key1?>&mall_type=<?=$mall_type;?>&orderField=<?=$orderField?>&dir=<?=$dir?>&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>";
}
function page_view(idx) {
    var desc = $("#desc_"+idx).val();
    $('.mall_desc_layer').lightbox_me({
        centered: true,
        onLoad: function() {
            var data = "<tr><td>"+desc+"</td></tr>";
            $('#mall_desc').html(data);
        }
    });
    $('.mall_desc_layer').css({"overflow-y":"auto", "height":"300px"});
}
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

<div class="wrapper">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>IAM몰 등록 리스트
                <small>IAM몰 등록 리스트를 관리합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">IAM몰 등록 리스트</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:10px">
                    <form method="get" name="search_form" id="search_form" class="form-inline">
                        <div class="box-tools">
                            <div class="input-group">
                                <div class="form-group">
                                    <input type="datetime-local" style="height: 30px" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST['search_start_date']?>"/> ~
                                    <input type="datetime-local" style="height: 30px" name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST['search_end_date']?>"/>
                                </div>
                                <div class="form-group">
                                    <select name="mall_type" class="form-control input-sm " style="margin-right:5px">
                                        <option value="">전체</option>
                                        <option value="1" <?if($_REQUEST['mall_type'] == 1) echo "selected"?>>홍보대사</option>
                                        <option value="2" <?if($_REQUEST['mall_type'] == 2) echo "selected"?>>재능마켓</option>
                                        <option value="5" <?if($_REQUEST['mall_type'] == 5) echo "selected"?>>메시지</option>
                                        <option value="4" <?if($_REQUEST['mall_type'] == 4) echo "selected"?>>중고마켓</option>
                                        <option value="10" <?if($_REQUEST['mall_type'] == 10) echo "selected"?>>갤러리(전체)</option>
                                        <option value="11" <?if($_REQUEST['mall_type'] == 11) echo "selected"?>>갤러리(회화)</option>
                                        <option value="12" <?if($_REQUEST['mall_type'] == 12) echo "selected"?>>갤러리(판화)</option>
                                        <option value="13" <?if($_REQUEST['mall_type'] == 13) echo "selected"?>>갤러리(조형)</option>
                                        <option value="14" <?if($_REQUEST['mall_type'] == 14) echo "selected"?>>갤러리(사진)</option>
                                        <option value="15" <?if($_REQUEST['mall_type'] == 15) echo "selected"?>>갤러리(AI아트)</option>
                                        <option value="16" <?if($_REQUEST['mall_type'] == 16) echo "selected"?>>갤러리(기타)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름" value="<?=$search_key?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="search_key1" id="search_key1" class="form-control input-sm pull-right" placeholder="상품제목" value="<?=$search_key1?>">
                                </div>
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" style="height: 30px;"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mall_desc_layer" style="display:none;height:150px !important;">
                <table class="table table-bordered table-striped" style="background:#fff !important">
                    <tbody id="mall_desc">
                    </tbody>
                </table>
            </div>
            <?$dir = $_REQUEST['dir'] == "desc" ? "asc" : "desc";?>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="check_all" id = "check_all" value="">
                                    </th>
                                    <th style="text-align: center;padding:2px">NO</th>
                                    <th style="text-align: center;padding:2px">소속</th>
                                    <th style="text-align: center;padding:2px">
                                        <a href="?orderField=mem_id&dir=<?=$dir?>" class="sort-by">아이디</a>
                                    </th>
                                    <th style="text-align: center;padding:2px">이름</th>
                                    <th style="text-align: center;padding:2px">상품구분</th>
                                    <th style="text-align: center;padding:2px">상품보기</th>
                                    <th style="text-align: center;padding:2px">상품제목</th>
                                    <th style="text-align: center;padding:2px">부제목</th>
                                    <th style="text-align: center;padding:2px">상세설명</th>
                                    <th style="text-align: center;padding:2px">상품정가</th>
                                    <th style="text-align: center;padding:2px">판매가격</th>
                                    <th style="text-align: center;padding:2px"><a href="?orderField=sample_display&dir=<?=$dir?>" class="sort-by">샘플선택</a></th>
                                    <th style="text-align: center;padding:2px">샘플순위</th>
                                    <th style="text-align: center;padding:2px"><a href="?orderField=display_status&dir=<?=$dir?>" class="sort-by">노출여부</a></th>
                                    <th style="text-align: center;padding:2px"><a href="?orderField=reg_date&dir=<?=$dir?>" class="sort-by">등록일자</a></th>
                                    <th style="text-align: center;padding:2px">
                                        <button class="btn btn-primary" onclick="multi_post_delete();"><i class="fa"></i> 삭제</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (mem.mem_id LIKE '%".$search_key."%' or mem.mem_name like '%".$search_key."%')" : null;
                                $searchStr .= $search_key1 ? " AND (mall.title like '%".$search_key1."%' )" : null;
                                if($_REQUEST['search_start_date'])
                                    $searchStr .= " AND reg_date >= '$_REQUEST[search_start_date]'";
                                if($_REQUEST['search_end_date'])
                                    $searchStr .= " AND reg_date <= '$_REQUEST[search_end_date]'";
                                if($_REQUEST['mall_type']){
                                    if($_REQUEST['mall_type'] != 10)
                                        $searchStr .= " AND mall_type = '$_REQUEST[mall_type]'";
                                    else
                                        $searchStr .= " AND mall_type > '$_REQUEST[mall_type]'";
                                }

                                $count_query = "select count(idx) from Gn_Iam_Mall mall inner join Gn_Member mem on mem.mem_id = mall.mem_id WHERE 1=1 $searchStr";
                                $count_result = mysqli_query($self_con,$count_query);
                                $count_row = mysqli_fetch_array($count_result);
                                $totalCnt	=  $count_row[0];

                                //소트방향
                                $query = "select * from (SELECT mall.*,mem.mem_name,mem.site_iam FROM Gn_Iam_Mall mall inner join Gn_Member mem on mem.mem_id = mall.mem_id WHERE 1=1 $searchStr) as t";
                                $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number	= $totalCnt - ($nowPage - 1) * $pageCnt;
                                if(!$orderField)
                                    $orderField = "reg_date";
                                $orderQuery .= " ORDER BY t.$orderField $dir $limitStr";
                                $i = 1;
                                $query .= "$orderQuery";
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                                    $preview_link = "";
                                    if($row['mall_type'] == 1){
                                        $sql = "select card_short_url from Gn_Iam_Name_Card n inner join Gn_Member m on m.mem_id=n.mem_id where m.mem_code = '$row[card_idx]' order by n.req_data limit 0,1";
                                        $res1 = mysqli_query($self_con,$sql);
                                        $row1 = mysqli_fetch_array($res1);
                                        if($row['site_iam'] == "kiam")
                                            $preview_link = "http://www.kiam.kr/?";
                                        else
                                            $preview_link = "http://".$row['site_iam'].".kiam.kr/?";
                                        $preview_link .= $row1[0].$row['card_idx'];
                                    }
                                    if($row['mall_type'] == 2){
                                        $sql = "select card_short_url,mem_id from Gn_Iam_Name_Card where idx = '$row[card_idx]'";
                                        $res2 = mysqli_query($self_con,$sql);
                                        $row2 = mysqli_fetch_array($res2);
                                        $mall_url = $row2[0];
                                        $sql = "select mem_code,site_iam from Gn_Member where mem_id = '$row2[1]'";
                                        $res3 = mysqli_query($self_con,$sql);
                                        $row2 = mysqli_fetch_array($res3);
                                        if($row['site_iam'] == "kiam")
                                            $preview_link = "http://www.kiam.kr/?";
                                        else
                                            $preview_link = "http://".$row2['site_iam'].".kiam.kr/?";
                                        $preview_link .= $mall_url.$row2['mem_code'];
                                    }
                                    if($row['mall_type'] == 3 || $row['mall_type'] == 4){
                                        if($row['site_iam'] == "kiam")
                                            $preview_link = "http://www.kiam.kr/iam/contents.php?contents_idx=";
                                        else
                                            $preview_link = "http://".$row['site_iam'].".kiam.kr/iam/contents.php?contents_idx=";
                                        $preview_link .= $row['card_idx'];
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class = "num_check" name="<?='mall_'.$row['idx']?>" id = "<?='mall_'.$row['idx']?>" value="<?=$row['idx']?>">
                                        </td>
                                        <td><?=$number--?></td>
                                        <td><?=$row['site_iam']?></td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['mem_id']?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['mem_name']?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$mall_type_str[$row['mall_type'] - 1]?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="text-align: center;vertical-align: middle">
                                                <a href="<?=$preview_link?>" target="_blank" >
                                                    <img class="zoom" src="<?=$row['img']?>" style="width:80px;">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['title']?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['sub_title']?>
                                            </div>
                                        </td>
                                        <td onclick="page_view('<?=$row['idx']?>');">
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=mb_strlen($row['description']) > 10?mb_substr($row['description'],0,15,"utf-8")."..." : $row['description']?>
                                            </div>
                                            <input type="hidden" id="<?='desc_'.$row['idx']?>" value = "<?=$row['description']?>">
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['price']?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['sell_price']?>
                                            </div>
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="chkagree" name="status" id="mall_sample_<?=$row['idx'];?>"<?=$row['sample_display']!='N'?"checked":""?> >
                                                <span class="slider round" name="status_round" id="mall_sample_<?=$row['idx'];?>"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <input type = "number"  style="width:100%" id="sample_order" data-idx="<?=$row['idx']?>" min = "0" value = "<?=$row['sample_order']?>"/>
                                            </div>
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="chkagree" name="status" id="mall_idx_<?=$row['idx'];?>"<?=$row['display_status']!=0?"checked":""?> >
                                                <span class="slider round" name="status_round" id="mall_idx_<?=$row['idx'];?>"></span>
                                            </label>
                                        </td>
                                        <td><?=$row['reg_date']?></td>
                                        <td>
                                            <button class="btn btn-primary" onclick="del_iam_mall(<?=$row['idx']?>);"><i class="fa"></i> 삭제</button>
                                            <button class="btn btn-primary" onclick="edit_iam_mall(<?=$row['idx']?>);"><i class="fa"></i> 수정</button>
                                        </td>
                                    </tr>
                                    <?
                                    $i++;
                                }
                                if($i == 1) {?>
                                    <tr>
                                        <td colspan="17" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->

            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                    <?
                    echo drawPagingAdminNavi($totalCnt, $nowPage,$pageCnt);
                    ?>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>    
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div>
<script language="javascript">
$(function(){
    $('.chkagree').change(function() {
        var id = $(this)[0].id;
        var display_status = $(this)[0].checked;
        var method = "update_display_status";
        if($(this)[0].checked){
            display_status = 1;
        }else{
            display_status = 0;
        }
        if(id.search("mall_sample") >= 0){
            method = "update_sample_display";
            display_status = display_status == 1 ? "Y" : "N";
        }
        var mall_idx = id.split("_")[2];
        $.ajax({
            type:"POST",
            url:"/iam/ajax/mall.proc.php",
            data:{
                iam_mall_method:method,
                iam_mall_display:display_status,
                iam_mall_idx:mall_idx
            },
            success:function(data){
                //location.reload();
            },
            error: function(){
                alert('변경 실패');
            }
        });
    });
    $("input[type=number]").change(function(){
        var mall_idx = $(this).data("idx");
        var sample_order = $(this).val();
        $.ajax({
            type:"POST",
            url:"/iam/ajax/mall.proc.php",
            data:{
                iam_mall_method:"change_sample_order",
                iam_mall_display:sample_order,
                iam_mall_idx:mall_idx
            },
            success:function(data){
                //location.reload();
            },
            error: function(){
                alert('변경 실패');
            }
        });
    });
    $(function(){
        $('#check_all').on("click",function(){
            $('.num_check').prop("checked",$(this).prop("checked"));
        });
    })
});

function del_iam_mall(val){
    if(confirm("삭제하시겠습니까?")){
        $.ajax({
            type:"POST",
            url:"/iam/ajax/mall.proc.php",
            dataType:"json",
            data:{iam_mall_method:'delete_mall', iam_mall_idx:val},
            success:function(data){
                alert(data.result);
                location.reload();
            }
        })
    }
}
function multi_post_delete(){
    if(confirm('모두 삭제하시겠습니까?')) {
        var mall_idx = [];
        var index = 0;
        $('.num_check').each(function(){
            if($(this).prop("checked")) {
                mall_idx[index++] = $(this).val();
            }
        });
        var idx = mall_idx.toString();
        $.ajax({
            type:"POST",
            url:"/iam/ajax/mall.proc.php",
            dataType:"json",
            data:{iam_mall_method:'delete_mall', iam_mall_idx:idx},
            success:function(data){
                alert(data.result);
                location.reload();
            }
        })
    }
}

function edit_iam_mall(idx){
    location.href="edit_iam_mall.php?idx="+idx;
}
</script>
  