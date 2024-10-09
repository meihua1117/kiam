<?
include_once "../lib/rlatjd_fun.php";

if($_SESSION['one_member_id'] != "")
{
    if($_POST['mode'] == "add")
    {
        if($_POST['mail_addr'])
        {
            $query = "insert into gn_member_emails set mem_id='$_SESSION[one_member_id]',
            email='$_POST[mail_addr]'";
            mysql_query($query);
        }
    
    }else if($_POST['mode'] == "use")
    {
        $query = "select email from gn_member_emails where idx='$_POST[idx]'";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);
        if($row != "")
            echo json_encode(array('status'=>'success', 'msg' => $row[0]));
        else
            echo json_encode(array('status'=>'fail', 'msg' => ''));
        
    }else if($_POST['mode'] == "del")
    {
        $query = "delete from gn_member_emails where idx='$_POST[idx]'";
        mysql_query($query);
    }


}

?>
