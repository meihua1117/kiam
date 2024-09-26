<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

if($term == "")
    $term = date("Y-m-d",strtotime("-7 day"));
if($search_email_date == "")
    $search_email_date = date("Y-m-d",strtotime("-7 day"));
if($shopping_end_date == "")
    $shopping_end_date = date("Y-m-d",strtotime("-7 day"));
    
$cmid = $_POST["cmid"]; 
if($_POST['mode'] == "reset") {
    $cmid = $_POST['cmid'];
    $query = "update crawler_member_real set total_cnt = total_cnt+monthly_cnt, search_email_total_cnt = search_email_total_cnt+search_email_use_cnt where cmid='$cmid'";
    mysqli_query($self_con, $query);
    
    $query = "update crawler_member_real set monthly_cnt=0,search_email_use_cnt=0 where cmid='$cmid'";
    mysqli_query($self_con, $query);
} else {
    if($_POST['cmid']) {
        // PC 비밀번호가 있는경우
        if($password) {
            $addSql .= " ,`password`=md5('$password')";
        }
        $sql="update crawler_member_real set user_id='$user_id', 
                                       user_name='$user_name',
                                       cell='$cell',
                                       email='$email',
                                       address='$address',
                                       term='$term',
                                       price='$price',
                                       status='$status',
                                       use_cnt='$use_cnt',
                                       search_email_yn='$search_email_yn',
                                       search_email_date='$search_email_date',
                                       search_email_cnt='$search_email_cnt',
                                       shopping_yn='$shopping_yn',
                                       shopping_cnt='$shopping_cnt',
                                       shopping_use_cnt='$shopping_use_cnt',
                                       shopping_end_date='$shopping_end_date',
                                       extra_db_cnt='$extra_db_cnt',
                                       extra_email_cnt='$extra_email_cnt',
                                       extra_shopping_cnt='$extra_shopping_cnt',
                                       serial='$serial'
                                       $addSql 
                                     where cmid='$cmid'";
        mysqli_query($self_con, $sql);	
    } else {
        $sql="select * from crawler_member_real where user_id='$user_id'";
        $resul=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $row=mysqli_fetch_array($resul);   
        if($row['user_id'] != "") {
            echo json_encode(array("result"=>$result,"msg"=>"이미 있는 회원 아이디 입니다."));
            exit;
        }
        $addSql .= " ,`password`=md5('$password')";
        $sql="insert into crawler_member_real set user_id='$user_id', 
                                    user_name='$user_name',
                                    cell='$cell',
                                    email='$email',
                                    address='$address',
                                    term='$term',
                                    price='$price',
                                    serial='$serial',
                                    use_cnt='$use_cnt',
                                    status='$status',
                                    search_email_yn='$search_email_yn',
                                    search_email_date='$search_email_date',    
                                    search_email_cnt='$search_email_cnt',                                   
                                    shopping_yn='$shopping_yn',
                                    shopping_cnt='$shopping_cnt',
                                    shopping_use_cnt='$shopping_use_cnt',
                                    shopping_end_date='$shopping_end_date', 
                                    extra_db_cnt='$extra_db_cnt',
                                    extra_email_cnt='$extra_email_cnt',
                                    extra_shopping_cnt='$extra_shopping_cnt',                                      
                                    regdate=NOW()
                                    $addSql 
                                    ";
                                    //echo $sql;
        mysqli_query($self_con, $sql);	    
    }
}
echo json_encode(array("result"=>$result));
?>