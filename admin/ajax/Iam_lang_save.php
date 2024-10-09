<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
$idx = $_POST["idx"];
if($_POST['mode'] == "inser") {
    $sql="
insert into        Gn_Iam_lang set kr ='$kr',
                                        en = '$en',
                                        cn = '$cn',
                                        jp = '$jp',
                                        id = '$id',
                                        fr = '$fr',
                                        menu = '$menu',
                                        pos = '$pos',
                                        sort_order='9999'
                                        
    ";
    mysql_query($sql) or die(mysql_error());
}
else if($_POST['mode'] == "updat") {
    $query="update  Gn_Iam_lang set kr ='$kr',
                                        en = '$en',
                                        cn = '$cn',
                                        jp = '$jp',
                                        id = '$id',
                                        fr = '$fr',
                                        menu = '$menu',
                                        pos = '$pos'
                         WHERE no='$no'
                                 ";                    
    mysql_query($query);	
} else if($_POST['mode'] == "del") {
    $query="delete  from Gn_Iam_lang WHERE no='$no'";
    mysql_query($query);	
} else if($_POST['mode'] == "sort_order") {
    print_r($_POST);
    $no = $_POST['no'];
    $sort_order = $_POST['sort_order'];
    
    for($i=0;$i<=count($no);$i++) {
        if($no[$i] != "" ) {
            $query="update Gn_Iam_lang set sort_order='$sort_order[$i]'
                                 WHERE no='$no[$i]'
                                         ";
            //echo $query."<BR>";
            mysql_query($query);	
        }
   }
}
echo "<script>alert('저장되었습니다.');location='/admin/iam_auto_ilang.php';</script>";
exit;
?>