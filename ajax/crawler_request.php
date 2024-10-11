<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$term = date("Y-m-d", strtotime("next Year"));

        $sql="select * from crawler_request where mem_id='{$_SESSION['one_member_id']}'";
        $resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $row=mysqli_fetch_array($resul);   
        if($row['mem_id'] != "") {
            $result = "fail";
            echo "{\"result\":\"$result\",\"msg\":\"이미 무료 체험을 하신 아이디 입니다..\"}";
            exit;            
        }
        
        $sql="insert into crawler_request set mem_id='{$_SESSION['one_member_id']}', 
                                              regdate=NOW()";
        mysqli_query($self_con,$sql);
        
        $sql="select * from crawler_member where user_id='{$_SESSION['one_member_id']}'";
        $resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $row=mysqli_fetch_array($resul);   
        if($row[user_id] != "") {
            $result = "fail";
            echo "{\"result\":\"$result\",\"msg\":\"이미 있는 회원 아이디 입니다.\"}";
            exit;
        }

        $sql="select * from Gn_Member where mem_id='{$_SESSION['one_member_id']}'";
        $resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $row=mysqli_fetch_array($resul);   
        if($row['mem_id'] == "") {
            $result = "fail";
            echo "{\"result\":\"$result\":\"msg\":\"없는 회원 아이디 입니다.\"}";
            exit;            
        }
        
    
        //$addSql .= " ,`password`='$row['mem_pass']'";
        
        $addSql .= " ,`password`=md5('1234')";
    
        
        
        $sql="insert into crawler_member set user_id='{$_SESSION['one_member_id']}', 
                                   user_name='{$row['mem_name']}',
                                   cell='{$row['mem_phone']}',
                                   email='{$row['mem_email']}',
                                   address='$row[mem_add1]',
                                   term='$term',
                                   price='0',
                                   serial='',
                                   use_cnt='300',
                                   status='Y',
                                   search_email_yn='N',
                                   search_email_date=NOW(),
                                   search_email_cnt=0,                                   
                                   regdate=NOW()
                                   $addSql 
                                 ";
                                 //echo $sql;
        mysqli_query($self_con,$sql);	 
        $result = "success";   

echo "{\"result\":\"$result\"}";
?>