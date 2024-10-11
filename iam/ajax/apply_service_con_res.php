<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

$date_today=date("Y-m-d H:i:s");
$date_month=date("Ym");

if(isset($_POST['seed_point'])){
    $sql_mem_data = "select * from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
    $res_mem_data = mysqli_query($self_con,$sql_mem_data);
    $row_mem_data = mysqli_fetch_array($res_mem_data);

    $cash = $row_mem_data['mem_cash'];
    $seed = $row_mem_data['mem_point'];
    $cash = $cash * 1 - $_POST['seed_point'] * 1;
    $seed = $seed * 1 + $_POST['seed_point'] * 1;

    $sql_update = "update Gn_Member set mem_point='$seed', mem_cash='$cash' where mem_id='{$_SESSION['iam_member_id']}'";
    mysqli_query($self_con,$sql_update);

    $sql_buyer = "insert into Gn_Item_Pay_Result
            set buyer_id='{$_SESSION['one_member_id']}',
                buyer_tel='$row_mem_data[mem_phone]',
                site='',
                pay_method='$_POST[payMethod]',
                item_name = '$_POST[member_type]',
                item_price=$_POST[allat_amt],
                seller_id='',
                pay_date=NOW(),
                pay_status='Y',
                pay_percent='$_POST[pay_percent]',
                order_number = '$_POST[allat_order_no]',
                VACT_InputName='{$row_mem_data['mem_name']}',
                point_val=1,
                type='buy',
                current_point='$seed',
                current_cash='$cash'";
    $res_result = mysqli_query($self_con,$sql_buyer);

    echo $res_result;
    exit;
}
else if(isset($_GET['mode'])){
    if(isset($_GET['prev']) && $_GET['prev'] == "mypage_sell"){
        $location = "iam/mypage_payment_item.php";
    }
    else if(isset($_GET['prev']) && $_GET['prev'] == "mypage_buy"){
        $location = "iam/mypage_payment.php";
    }
    else{
        $location = "iam/mypage_payment.php";
    }

    if($_GET['mode'] == "buy_card"){
        $db_idx = $_GET['residx'];
        $sql_buy_data = "select * from Gn_Item_Pay_Result where no='$db_idx'";
        $res_buy_data = mysqli_query($self_con,$sql_buy_data);
        $row_buy_data = mysqli_fetch_array($res_buy_data);

        $point_percent = $row_buy_data['point_percent'];
        $pay_percent = $row_buy_data['pay_percent'];

        if(!$point_percent){
            echo "<script>alert('설정이 잘못되었습니다.'); location.href='/".$location."';</script>";
            exit;
        }

        $reduce_arr = explode("/", $point_percent);
        $reduce1 = trim($reduce_arr[0]);
        $reduce2 = trim($reduce_arr[1]);

        if($row_buy_data['apply_buyer_date']){
            echo "<script>alert('이미 구매 확인 되었습니다.'); location.href='/".$location."';</script>";
            exit;
        }
        $add_point = $row_buy_data['item_price'] * 1 * ($reduce1 * 1 / 100) * ($reduce2 * 1 /100);
        $final_point = $row_buy_data['item_price'] * 1 - (int)$add_point;

        $sql_mem_data = "select * from Gn_Member where mem_id='$row_buy_data[buyer_id]'";
        $res_mem_data = mysqli_query($self_con,$sql_mem_data);
        $row_mem_data = mysqli_fetch_array($res_mem_data);

        $mem_cash = $row_mem_data['mem_cash'] * 1 - $final_point * 1;

        $sql_update_pay_res = "update Gn_Item_Pay_Result set current_cash='$mem_cash', apply_buyer_date='$date_today' where no='$buy_idx'";
        mysqli_query($self_con,$sql_update_pay_res);

        $sql_update_pay_res1 = "update Gn_Item_Pay_Result set apply_buyer_date='$date_today' where no='$sell_idx'";
        mysqli_query($self_con,$sql_update_pay_res1);

        $sql_update_mem = "udpate Gn_Member set mem_cash='$mem_cash' where mem_id='$row_buy_data[buyer_id]'";
        $res = mysqli_query($self_con,$sql_update_mem);

        // echo $res;
        echo "<script>alert('구매확인이 되었습니다.'); location.href='/".$location."';</script>";
        exit;
    }
    else if($_GET['mode'] == "sell_card"){
        $db_idx = $_GET['residx'];
        $sql_sell_data = "select * from Gn_Item_Pay_Result where no='$db_idx'";
        $res_sell_data = mysqli_query($self_con,$sql_sell_data);
        $row_sell_data = mysqli_fetch_array($res_sell_data);

        $point_percent = $row_sell_data['point_percent'];
        $pay_percent = $row_sell_data['pay_percent'];

        if(!$point_percent){
            echo "<script>alert('설정이 잘못되었습니다.'); location.href='/".$location."';</script>";
            exit;
        }

        $reduce_arr = explode("/", $point_percent);
        $reduce1 = trim($reduce_arr[0]);
        $reduce2 = trim($reduce_arr[1]);

        if($row_sell_data['apply_seller_date']){
            echo "<script>alert('이미 판매 확인 되었습니다.'); location.href='/".$location."';</script>";
            exit;
        }
        $add_point = $row_sell_data['item_price'] * 1 * ($pay_percent * 1 / 100);
        $final_point = $row_sell_data['item_price'] * 1 + (int)$add_point;

        $sql_mem_data = "select * from Gn_Member where mem_id='$row_sell_data[seller_id]'";
        $res_mem_data = mysqli_query($self_con,$sql_mem_data);
        $row_mem_data = mysqli_fetch_array($res_mem_data);

        $mem_cash = $row_mem_data['mem_cash'] * 1 + $final_point * 1;

        $sql_update_pay_res = "update Gn_Item_Pay_Result set current_cash='$mem_cash', apply_seller_date='$date_today' where no='$sell_idx'";
        mysqli_query($self_con,$sql_update_pay_res);

        $sql_update_pay_res1 = "update Gn_Item_Pay_Result set apply_seller_date='$date_today' where no='$buy_idx'";
        mysqli_query($self_con,$sql_update_pay_res1);

        $sql_update_mem = "udpate Gn_Member set mem_cash='$mem_cash' where mem_id='$row_sell_data[seller_id]'";
        mysqli_query($self_con,$sql_update_mem);

        $sql_amount = "insert into Gn_Item_Pay_Result_Balance set pay_no='$sell_idx', mem_id='$row_sell_data[seller_id]', seller_id='$row_sell_data[buyer_id]', item_name='$row_sell_data[item_name]', share_per='$pay_percent', price='$row_sell_data[item_price]', regdate='$date_today', balance_date='$date_month', balance_yn='Y', balance_confirm_date='$date_today', pay_date='$date_today'";
        $res = mysqli_query($self_con,$sql_amount);

        echo "<script>alert('판매확인이 되었습니다.'); location.href='/".$location."';</script>";
        exit;
    }

    $buy_idx = $_GET['residx_buy'];
    $sell_idx = $_GET['residx_sell'];

    $sql_buy_data = "select * from Gn_Item_Pay_Result where no='$buy_idx'";
    $res_buy_data = mysqli_query($self_con,$sql_buy_data);
    $row_buy_data = mysqli_fetch_array($res_buy_data);

    $point_percent = $row_buy_data['point_percent'];
    $pay_percent = $row_buy_data['pay_percent'];

    if(!$point_percent){
        echo "<script>alert('설정이 잘못되었습니다.'); location.href='/".$location."';</script>";
        exit;
    }

    $reduce_arr = explode("/", $point_percent);
    $reduce1 = trim($reduce_arr[0]);
    $reduce2 = trim($reduce_arr[1]);

    if($_GET['mode'] == "buy"){
        if($row_buy_data['apply_buyer_date']){
            echo "<script>alert('이미 구매 확인 되었습니다.'); location.href='/';</script>";
            exit;
        }
        $add_point = $row_buy_data['item_price'] * 1 * ($reduce1 * 1 / 100) * ($reduce2 * 1 /100);
        $final_point = $row_buy_data['item_price'] * 1 - (int)$add_point;

        $sql_mem_data = "select * from Gn_Member where mem_id='$row_buy_data[buyer_id]'";
        $res_mem_data = mysqli_query($self_con,$sql_mem_data);
        $row_mem_data = mysqli_fetch_array($res_mem_data);

        $mem_cash = $row_mem_data['mem_cash'] * 1 - $final_point * 1;

        $sql_update_pay_res = "update Gn_Item_Pay_Result set current_cash='$mem_cash', apply_buyer_date='$date_today' where no='$buy_idx'";
        mysqli_query($self_con,$sql_update_pay_res);

        $sql_update_pay_res1 = "update Gn_Item_Pay_Result set apply_buyer_date='$date_today' where no='$sell_idx'";
        mysqli_query($self_con,$sql_update_pay_res1);

        $sql_update_mem = "udpate Gn_Member set mem_cash='$mem_cash' where mem_id='$row_buy_data[buyer_id]'";
        $res = mysqli_query($self_con,$sql_update_mem);

        // echo $res;
        echo "<script>alert('구매확인이 되었습니다.'); location.href='/';</script>";
    }
    else if($_GET['mode'] == "sell"){
        if($row_buy_data['apply_seller_date']){
            echo "<script>alert('이미 판매 확인 되었습니다.'); location.href='/".$location."';</script>";
            exit;
        }
        $add_point = $row_buy_data['item_price'] * 1 * ($pay_percent * 1 / 100);
        $final_point = $row_buy_data['item_price'] * 1 + (int)$add_point;

        $sql_mem_data = "select * from Gn_Member where mem_id='$row_buy_data[pay_method]'";
        $res_mem_data = mysqli_query($self_con,$sql_mem_data);
        $row_mem_data = mysqli_fetch_array($res_mem_data);

        $mem_cash = $row_mem_data['mem_cash'] * 1 + $final_point * 1;

        $sql_update_pay_res = "update Gn_Item_Pay_Result set current_cash='$mem_cash', apply_seller_date='$date_today' where no='$sell_idx'";
        mysqli_query($self_con,$sql_update_pay_res);

        $sql_update_pay_res1 = "update Gn_Item_Pay_Result set apply_seller_date='$date_today' where no='$buy_idx'";
        mysqli_query($self_con,$sql_update_pay_res1);

        $sql_update_mem = "udpate Gn_Member set mem_cash='$mem_cash' where mem_id='$row_buy_data[pay_method]'";
        mysqli_query($self_con,$sql_update_mem);

        $sql_amount = "insert into Gn_Item_Pay_Result_Balance set pay_no='$sell_idx', mem_id='$row_buy_data[pay_method]', seller_id='$row_buy_data[buyer_id]', item_name='$row_buy_data[item_name]', share_per='$pay_percent', price='$row_buy_data[item_price]', regdate='$date_today', balance_date='$date_month', balance_yn='Y', balance_confirm_date='$date_today', pay_date='$date_today'";
        $res = mysqli_query($self_con,$sql_amount);

        echo "<script>alert('판매확인이 되었습니다.'); location.href='/".$location."';</script>";
    }
    exit;
}
?>