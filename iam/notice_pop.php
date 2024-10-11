<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>온리원아이엠</title>
<style>
.pop-layer .pop-container {
  padding: 20px 25px;
}

.pop-layer p.title {
  width: 100%;
  margin: 10px 0 20px;
  padding-top: 10px;
  border-bottom: 1px solid #DDD;
  text-align:left;
  font-size:18px; color:#404040; font-weight:bold;
}

.pop-layer p.ctxt {
  color: #666;
  line-height: 25px;
}

.pop-layer .btn-r {
  width: 100%;
  margin: 10px 0 20px;
  padding-top: 10px;
  border-top: 1px solid #DDD;
  text-align: right;
}

a.btn-layerClose {
  display: inline-block;
  height: 28px;
  padding: 0 14px 0;
  border: 1px solid #304a8a;
  background-color: #3f5a9d;
  font-size: 20px;
  color: #fff;
  line-height: 30px;
}

a.btn-layerClose:hover {
  border: 1px solid #091940;
  background-color: #1f326a;
  color: #fff;
}
</style>
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css'/>
<link href='/css/main.css' rel='stylesheet' type='text/css'/>
<script>
function Memo_setCookie( name, value, expiredays ){
    var todayDate = new Date();
    var expireTime = (expiredays *1*60*60*1000);
    todayDate.setTime( todayDate.getTime() + expireTime );
    document.cookie = name + '=' + escape( value ) + '; path=/; expires=' + todayDate.toGMTString() + ';'
}
function Memo_closeWin(id){
    Memo_setCookie( 'Memo_iam'+id, 'done' , 24); // 24=하룻동안 전체 공지창 열지 않음  -> 1=1시간
    self.close();
}
    
</script>
</head>
<body style="background-color: #fff;">
<div id="layer1" class="pop-layer">
    <div class="pop-container">
        <div class="pop-conts">
            <!--content //-->
            <?if($_GET[id]){
                    $query = "SELECT * FROM tjd_sellerboard WHERE no=$_GET[id]";
                    $res = mysqli_query($self_con,$query);
                    $row_no = mysqli_fetch_array($res);
            ?>
            <table class="view_table_1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:90%;"><?=htmlspecialchars_decode($row_no[title])?></td>
                    <td style="text-align:right;"><?=substr($row_no[date],0,10)?></td>
                </tr>
                <tr>
                    <td colspan="2"><?=htmlspecialchars_decode($row_no[content])?></td>
                </tr>
            </table>
            <?}?>
            <div class="btn-r">
                 <span style='font-size:15pt;color:#000000;font-weight:bold;'>
                    <input type='checkbox' name='Memo' onclick='javascript:Memo_closeWin(<?=$_GET[id]?>);'>&nbsp;오늘은 그만보기 &nbsp;&nbsp;&nbsp;
                    <a href= 'javascript:self.close()' class="btn-layerClose">창닫기</a>
                 </span>
            </div>
        </div>
    </div>
</div>

   


</body>
</html>