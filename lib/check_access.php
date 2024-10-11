<?php

$ip = $_SERVER['REMOTE_ADDR'];
$userid_selling = $_SESSION['one_member_id'];
$userid_iam = $_SESSION['iam_member_id'];
$url = $_SERVER['REQUEST_URI'];
$curtime = date("Y-m-d H:i:s");
$pasttime = date("Y-m-d H:i:s", strtotime("-1 second"));


$query = "select ip from gn_block_ip where ip='$ip'";
$res = mysqli_query($self_con,$query);
$row = mysqli_fetch_array($res);
if($row[0] != "")
    exit;

//2초동안에 6번이상 시도하면 블럭시킨다.
$maskip = substr($ip, 0, strrpos($ip, ".")+1);
$query = "select count(*) from gn_hist_access where ip like '%$maskip%' and regdate >= '$pasttime' and regdate <='$curtime'";
$result=mysqli_query($self_con,$query);
$row = mysqli_fetch_array($result);
$count = $row[0];
if($count > 12)
{
    // 아이피 차단목록에 넣는다.
    $query = "select ip from gn_block_ip where ip='$ip'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if($row[0] == "")
    {
        $query = "insert into gn_block_ip (ip, type) values('$ip', 2)";
        $res = mysqli_query($self_con,$query);
    }
    exit;
}


$pasttime = date("Y-m-d H:i:s", strtotime("-1 minute"));

//아이피 대역을 체크해서 무작위로 호출하면 요청을 거부한다.
$maskip = substr($ip, 0, strrpos($ip, ".")+1);
$query = "select count(*) from gn_hist_access where ip like '%$maskip%' and url like '%page=%'and userid_selling='' and userid_iam='' and regdate >= '$pasttime' and regdate <='$curtime'";
$result=mysqli_query($self_con,$query);
$row = mysqli_fetch_array($result);
$count = $row[0];
if($count > 6)
{
    // 아이피 차단목록에 넣는다.
    $query = "select ip from gn_block_ip where ip='$ip'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if($row[0] == "")
    {
        $query = "insert into gn_block_ip (ip, type) values('$ip', 3)";
        $res = mysqli_query($self_con,$query);
    }
    exit;
}

    
if(strpos($url, "kiam.kr") !== false || $url[0] == '/')
{
    //접속로그에 넣는다.
    $query = "insert into gn_hist_access set 
                ip='$ip',
                userid_selling='$userid_selling',
                userid_iam='$userid_iam',
                url='$url',
                regdate='$curtime'
            ";
    mysqli_query($self_con,$query);
}


 




?>