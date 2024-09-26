<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
if($_POST['mode'] == "creat") {
    $query = "select count(mem_code) from Gn_Member use index(mem_id) where mem_id = '$mem_id'";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);
    if($row[0] == 0){
        echo "<script>alert('아이디가 존재하지 않습니다.');history.back(-1);</script>";
    }else{
        $query = "select count(no) from gn_check_phone where mem_id='$mem_id'";
        $res = mysqli_query($self_con, $query) or die(mysqli_error($self_con));
        $row = mysqli_fetch_array($res);
        if($row[0] == 0){
            $query = "insert into gn_check_phone set mem_id='$mem_id',
                                                    company = '$company',
                                                    manager = '$manager',
                                                    domain = '$domain',
                                                    main_price = '$main_price',
                                                    sub_price = '$sub_price',
                                                    api_key = '$api_key',
                                                    reg_date = NOW()";
            mysqli_query($self_con, $query) or die(mysqli_error($self_con));
            echo "<script>alert('저장되었습니다.');location='/admin/phone_check_list.php';</script>";
        }else{
            echo "<script>alert('아이디가 이미 등록되어있습니다.');history.back(-1);</script>";
        }
    }
} else if($_POST['mode'] == "updat") {
    $query = "update gn_check_phone set mem_id='$mem_id',
                                            company = '$company',
                                            manager = '$manager',
                                            domain = '$domain',
                                            main_price = '$main_price',
                                            sub_price = '$sub_price',
                                            api_key = '$api_key',
                                            reg_date = NOW()
                                            where no = $no";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));
    echo "<script>alert('저장되었습니다.');location='/admin/phone_check_list.php';</script>";
} else if($_POST['mode'] == "del"){
    $no_arr = array();
    if(strpos($no, ",") !== false){
        $no_arr = explode(",", $no);
    }
    else{
        $no_arr[0] = $no;
    }
    
    for($i = 0; $i < count($no_arr); $i++){
        $query="delete from gn_check_phone WHERE no='$no_arr[$i]'";
        mysqli_query($self_con, $query);
    }
    echo json_encode(array("result"=>"success"));
    exit;
} else if($_POST['mode'] == "modify_phone_check_status"){
    $query = "update gn_check_phone set status='$status' where no = $idx";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));
    echo json_encode(array("result"=>"success"));
    exit;
} else if($_POST['mode'] == "modify_phone_check_type"){
    $query = "update gn_check_phone set check_type='$type' where no = $idx";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));
    echo json_encode(array("result"=>"success"));
    exit;
} 
?>