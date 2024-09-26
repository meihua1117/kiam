<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
$idx = $_POST["idx"];
if($_POST['mode'] == "creat") {
    $sql="insert into Gn_Iam_multilang (korean,english,china,japan,india,france,profile_menu,story_menu,contact_menu,friends_menu,manager)".
                    "values ('$korean','$english','$china','$japan','$india','$france','$profile_menu','$story_menu','$contact_menu','$friends_menu','$manager')";
    mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
}
else if($_POST['mode'] == "updat") {
    $query="update  Gn_Iam_multilang set korean ='$korean',
                                        english = '$english',
                                        china = '$china',
                                        japan = '$japan',
                                        india = '$india',
                                        france = '$france',
                                        profile_menu = '$profile_menu',
                                        story_menu = '$story_menu',
                                        contact_menu = '$contact_menu',
                                        friends_menu = '$friends_menu'
                                        manager = '$manager'
                                       
                                        
                         WHERE no='$idx'
                                 ";                    
    mysqli_query($self_con, $query);	
} else if($_POST['mode'] == "del") {
    $query="delete  from Gn_Iam_multilang WHERE no='$idx'";
    mysqli_query($self_con, $query);	
}
echo "<script>alert('저장되었습니다.');location='/admin/iam_auto_multilang.php';</script>";
exit;
?>