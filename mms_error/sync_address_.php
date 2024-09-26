<?
// header("Content-type: text/html; charset=utf-8");
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

set_time_limit(0);
ini_set('memory_limit', '-1');
$id = $_POST["id"];
//$id = $_REQUEST["id"];
$address = $_POST["address"];
$mem_id = $_POST["mem_id"];
/*
$address = '
[
  {
    "Coworkers": [
      {
        "노그룹": "0104653892"
      },
      {
        "올레 고객센터": "100"
      },
      {
        "LG전자 고객 상담실": "15447777"
      }
    ]
  },
  {
    "Family": [
      {
        "가족1": "010-6592-7799"
      }
    ]
  },
  {
    "Friends": [
      {
        "친구1": "345-6"
      }
    ]
  },
  {
    "기타": [
      {
        "테스트 번호1": "123-4567"
      },
      {
        "테스트2": "234-5689"
      }
    ]
  },
  {
    "업체": [
      {
        "업체1": "456-7"
      }
    ]
  },
  {
    "NO_GROUP": [
      {
        "노그룹": "0104653892"
      },
      {
        "올레 고객센터": "100"
      },
      {
        "LG전자 고객 상담실": "15447777"
      }
    ]
  }
]
';
*/
if($id == "") exit;

$sql_s = "select count(*) as cnt from sm_data where dest = '".$id."'";
$row_s = sql_fetch($sql_s);
$address = str_replace("\n", " ", $address);
$address = str_replace("'", " ", $address);
$addr = explode("}
    ]
  }",$address);
if($row_s[cnt] != "0")
{
	$sql_d = "delete from sm_data where dest = '".$id."'"; //기존 데이터 삭제
	$query_d = sql_query($sql_d);
}

$sql = "insert into call_app_log (api_name, mem_id, send_num, recv_num, sms, regdate) values ('sync_address', '$id', '$id', '', '$mem_id', now())";
mysql_query($sql);

$sql="select mem_id from Gn_Member where REPLACE(mem_phone,'-','') ='$id' order by mem_code desc ";
$result = mysql_query($sql) or die(mysql_error());
$member_info = $row=mysql_fetch_array($result); 
if($mem_id == "")
    $mem_id = $member_info[mem_id];

$sql = "SET NAMES utf8;";
mysql_query($sql) or die(mysql_error());

$sql="select * from Gn_MMS_Group where mem_id='$mem_id' and grp='아이엠'";
$result_ = mysql_query($sql) or die(mysql_error());
$data = mysql_fetch_array($result_);  
if($data[idx] != ""){
  $idx  = $data[idx];
}else{
  $query = "insert into Gn_MMS_Group set mem_id='$mem_id', grp='아이엠', reg_date=NOW()";
  mysql_query($query);
  $idx = mysql_insert_id();
}  


// Cooper Add 앱 콜 일어나는지 확인로그
$sql = "insert into call_app_log (api_name, mem_id, send_num, recv_num, sms, regdate) values ('sync_address', '$id', '$id', '$mem_id', '$address', now())";
mysql_query($sql);

//$query = "delete from Gn_MMS_Receive where mem_id='$mem_id' and grp_id='$idx' and iam=1";
//mysql_query($query);
$query = "delete from Gn_MMS_Receive_Iam where mem_id='$mem_id'";
mysql_query($query);
$acnt = 0;
for($i=0;$i<count($addr);$i++)
{
	$addr[$i] = str_replace("\"","",$addr[$i]);
	$addr[$i] = str_replace("\\","",$addr[$i]);
	$add = explode(",",$addr[$i]);

	$addr[$i] = str_replace('{','',$addr[$i]);
	$addr[$i] = str_replace('}','',$addr[$i]);
	$addr[$i] = str_replace('"','',$addr[$i]);

	for($t=0;$t<count($add);$t++)
	{
		$add[$t] = str_replace("{","",$add[$t]);
		$add[$t] = str_replace("}","",$add[$t]);
		$add_ex = explode(":",$add[$t]);
	
		$add_ex = str_replace("[","",$add_ex);
		
		if(count($add_ex) > 2)
		{
			$grp = trim($add_ex[0]);
			$name = trim($add_ex[1]);
			$num = trim($add_ex[2]);
			$grp_sess = $grp;
		}
		else
		{
			$grp = $grp;
			$name = trim($add_ex[0]);
			$num = trim($add_ex[1]);
		}
		if(!$grp)
		{
			$grp = $grp_sess;
		}
		if(($num) && ($name!=="]") && (substr($num,0,3)=="010"||substr($num,0,3)=="016"||substr($num,0,3)=="011"))
		{
			//$name = iconv("UTF-8","EUC-KR",$name);
			$num = str_replace("-","",$num);
			$num = str_replace("]","",$num);
			if(!$grp)
			{
			$grp = "NO_GROUP";
			}
			//$grp = iconv("UTF-8","EUC-KR",$grp);
			$sql = "insert sm_data set dest = '".$id."', msg_text = '".$name."', msg_url = '".$num."', reservation_time = now(), grp = '".$grp."'";
			$query = sql_query($sql);
			
			$iam[$num][name] = $name;
			$iam[$num][num] = $num;
		}
	}
}
foreach($iam as $key=>$value) {
	if($mem_id != "") {
        //$query = "insert into Gn_MMS_Receive set mem_id='$mem_id', send_num='$id', grp_id='$idx', iam=1, grp='아이엠', grp_2='아이엠', name='$value[name]', recv_num='$value[num]',reg_date=NOW()";
        //mysql_query($query);			
        $query = "insert into Gn_MMS_Receive_Iam set mem_id='$mem_id', send_num='$id', grp_id='$idx', iam=1, grp='아이엠', grp_2='아이엠', name='$value[name]', recv_num='$value[num]',reg_date=NOW()";
        mysql_query($query);
        $acnt++;
    }
}
	
if($query)
{
  $sql="update Gn_MMS_Group set count='$acnt' where idx='$idx' and grp='아이엠'";
  mysql_query($sql) or die(mysql_error());    
	$result = 0;
}
else
{
	$result = 1;
}

$phone_num = $id;
if(strlen($phone_num) > 0)
{
	$time = date("Y-m-d H:i:s");
	$sql="select idx from call_api_log where phone_num='$phone_num'";
	$res=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($res);
	if($row['idx'] != "") {
		$sql="update call_api_log set sync_address='$time' where idx='$row[idx]'";
		mysql_query($sql);	
	}
	else{
		$sql ="insert into call_api_log set sync_address='$time', phone_num='$phone_num'";
		mysql_query($sql);	
	}
}
echo "{\"result\":\"$result\"}";
?>