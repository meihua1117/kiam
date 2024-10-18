<?
include_once "../lib/rlatjd_fun.php";
$_REQUEST['status'] = 1;
extract($_POST);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>온리원문자</title>
<meta name="description" content="인간능력향상프로젝트 온리원교육주도력 인지력 관계력 인공지능대비 학습능력 업무능력 10배향상프로젝트 천재의일반화" />
<meta name="keywords" content="인간능력향상프로젝트 온리원교육주도력 인지력 관계력 인공지능대비 학습능력 업무능력 10배향상프로젝트 천재의일반화" />
<link href='../css/nanumgothic.css' rel='stylesheet' type='text/css'/>
<link href='../css/main.css' rel='stylesheet' type='text/css'/>
<script language="javascript" src="../js/jquery-1.7.1.min.js"></script>
<script language="javascript" src="../js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script language="javascript">
function choiceId(str) {
    opener.$('#recommend_id').val(str);
    window.close();
}
</script>
<style>
.m_div {width:600px;}
</style>
</head>
<body>
<?
$left_str="팀장검색";
?>
<div class="big_sub">
    <div class="m_div sub_4c">
         <form name="sub_4_form" action="" method="post"  >            
                        <div class="sub_4_1_t7">
                        	<div style="float:left">
                            </div>
                            <div style="float:right;">                        
                            이름
                            <input type="text" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
                            전화번호
                            <input type="text" name="search_cell" value="<?=$_REQUEST['search_cell']?>" />
                            <a href="javascript:void(0)" onclick="sub_4_form.submit();"><img src="../images/sub_button_703.jpg" /></a>
                            </div>
                            <p style="clear:both;"></p>
                        </div>
                            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="text-align:left"><label>번호</label></td>
                                    <td>이름</td>
                                    <td>선택</td>
                                </tr>
                                <?
                                //$sql_search=" AND mem_leb =50  ";
                                $search_text = $_REQUEST['search_text'];
                                $search_key = $_REQUEST['search_key'];
                                
                                $sql_search .= " AND (mem_name ='$search_name' and REPLACE(mem_phone,'-', '') =REPLACE('$search_cell','-',''))";
                                
								$sql="select count(mem_code) as cnt from Gn_Member where 1=1 $sql_search ";
								$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
								$row=mysqli_fetch_array($result);
								$intRowCount=$row['cnt'];
								if (!$_POST['lno']) 
									$intPageSize =15;
								else 
								   $intPageSize = $_POST['lno'];				
								$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
								   
								if($_POST['page'])
								{
								  $page=(int)$_POST['page'];
								  $sort_no=$intRowCount-($intPageSize*$page-$intPageSize); 
								}
								else
								{
								  $page=1;
								  $sort_no=$intRowCount;
								}
								if($_POST['page2'])
								    $page2=(int)$_POST['page2'];
								else
								    $page2=1;								
									                                
    				            $sql="select * from Gn_Member where 1=1 $sql_search ";
    				            $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));                                
                                if($intRowCount)
    							{
    								while($row=mysqli_fetch_array($result))
    								{
    								?>
                                    <tr>
                                        <td style="width:6%;text-align:left;"><label><?=$sort_no?></label></td>
                                        <td style="width:80%;"><?=$row['mem_name']?></td>
                                        <td style="width:14%;"><a href="javascript:;;" onclick="choiceId('<? echo $row['mem_id']?>')" style="border:1px solid #000;padding:5px">선택</a></td>
                                    </tr>
                                    <?
    								$sort_no--;
    								}
    								?>
    								<tr>
                                    	<td colspan="9">
                                  		<?
    									page_f($page,$page2,$intPageCount,"sub_4_form");
    									?>
                                        </td>
                                    </tr>                                
                                    <?
    							}
    							else
    							{
    								?>
                                    <tr>
                                    	<td colspan="9">등록된 내용이 없습니다.</td>
                                    </tr>
                                    <?								
    							}
    							?>
                            </table>
                    </div>                    
        </form>
    </div>
</div> 
<?
mysqli_close($self_con);
?>
