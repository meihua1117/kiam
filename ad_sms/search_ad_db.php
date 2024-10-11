<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

ini_set('memory_limit','4500M');
$addr = $_REQUEST['addr'];
$com_type = $_REQUEST['com_type'];
$rank = $_REQUEST['rank'];
$from_age = $_REQUEST['from_age'];
$to_age = $_REQUEST['to_age'];
$gen = $_REQUEST['gen'];
$school = $_REQUEST['school'];
$marrage = $_REQUEST['marrage'];
$only_count = $_REQUEST['only_count'];

$phones = array();
if($only_count == '0'){
    $query = "SELECT phone,name FROM sm_data_one";
}
else {
    $query = "SELECT count(phone) total_count from sm_data_one";
}


$addrs = explode(',', $addr);
$where_query = '';
$cost = 0;
$cost_addr = 5;
$cost_com_type = 5;
$cost_age = 10;
$cost_gen = 3;
$cost_rank = 20;
$cost_school = 5;
$cost_marrage = 5;

if(count($addrs) > 0 && $addr != '')
{
    if(count($addrs) > 1)
    {
        $where_query = '(';
    }
    for($i=0;$i<count($addrs);$i++)
    {
        if($i == 0)
        {
            $where_query = $where_query.'addr like "%'.$addrs[0].'%"';
        }
        else {
            $where_query = $where_query.' OR addr like "%'.$addrs[$i].'%"';
        }
        
    }
    if(count($addrs) > 1)
    {
        $where_query = $where_query.')';
    }
    $cost += $cost_addr;
}

$com_types = explode(',', $com_type);
if(count($com_types) > 0 && $com_type != '')
{
    
    if($where_query !== '')
    {
        $where_query = $where_query.' AND ';
    }

    if(count($com_types) > 1)
    {
        $where_query = $where_query.'(';
    }
    for($i=0;$i<count($com_types);$i++)
    {
        if($i == 0)
        {
            $where_query = $where_query.'com_type like "%'.$com_types[0].'%"';
        }
        else {
            $where_query = $where_query.' OR com_type like "%'.$com_types[$i].'%"';
        }
        
    }
    if(count($com_types) > 1)
    {
        $where_query = $where_query.')';
    }
    $cost += $cost_com_type;
}

$ranks = explode(',', $rank);
if(count($ranks) > 0 && $rank != '')
{
    if($where_query !== '')
    {
        $where_query = $where_query.' AND ';
    }
    if(count($ranks) > 1)
    {
        $where_query = $where_query.'(';
    }
    for($i=0;$i<count($ranks);$i++)
    {
        if($i == 0)
        {
            $where_query = $where_query.'rank like "%'.$ranks[0].'%"';
        }
        else {
            $where_query = $where_query.' OR rank like "%'.$ranks[$i].'%"';
        }
        
    }
    if(count($ranks) > 1)
    {
        $where_query = $where_query.')';
    }
    $cost += $cost_rank;
}

$year = date("Y");
if($from_age != '')
{
    if($where_query !== '')
    {
        $where_query = $where_query.' AND ';
    }
    $from_age = $year - $from_age;
    $from_age = $from_age.'년';
    $where_query = $where_query.'age <= '."'".$from_age."'";
}


if($to_age != '')
{
    if($where_query !== '')
    {
        $where_query = $where_query.' AND ';
    }
    $to_age = $year - $to_age;
    $to_age = $to_age.'년';
    $where_query = $where_query.'age >= '."'".$to_age."'";
}

if($from_age != '' || $to_age != ''){
    $cost += $cost_age;
}

if($gen != '')
{
    if($where_query !== '')
    {
        $where_query = $where_query.' AND ';
    }
    $where_query = $where_query.'gen = '."'".$gen."'";
    $cost += $cost_gen;
}

// if($school != '')
// {
//     if($where_query !== '')
//     {
//         $where_query = $where_query.' AND ';
//     }
//     $where_query = $where_query.'school = '."'".$school."'";
//     $cost += $cost_school;
// }

$schools = explode(',', $school);
if(count($schools) > 0 && $school != '')
{
    if($where_query !== '')
    {
        $where_query = $where_query.' AND ';
    }
    if(count($schools) > 1)
    {
        $where_query = $where_query.'(';
    }
    for($i=0;$i<count($schools);$i++)
    {
        if($i == 0)
        {
            $where_query = $where_query.'school = "'.$schools[0].'"';
        }
        else {
            $where_query = $where_query.' OR school = "'.$schools[$i].'"';
        }
        
    }
    if(count($schools) > 1)
    {
        $where_query = $where_query.')';
    }
    $cost += $cost_school;
}

if($marrage != '')
{
    if($where_query !== '')
    {
        $where_query = $where_query.' AND ';
    }
    $where_query = $where_query.'marrige = '."'".$marrage."'";
    $cost += $cost_marrage;
}

if($where_query != '')
{
    try{
        $query = $query.' WHERE '.$where_query;
        $result = mysqli_query($self_con,$query);

        if($only_count == '0')
        {
            $i=0;
            while($row = mysqli_fetch_array($result)) {
                $phone_no = str_replace(' ', '',$row['phone']);
                if($phone_no == '')
                {
                    continue;
                }
                $i++;
                $enc_no = substr($phone_no,0,3).'-'.substr($phone_no,3,4).'-'.'****';
                //array_push($phones, array('no'=>$i,'phone'=>$phone_no,'enc_no'=>$enc_no,'name'=>$row['name']));
                array_push($phones, array('phone'=>$phone_no));
            }
            $cost = $cost * count($phones);
            echo json_encode(array('status'=>'1','phones'=>$phones, 'cost'=>$cost));
        }
        else {
            while($row = mysqli_fetch_array($result)) {
                $count = $row['total_count'];
                echo json_encode(array('status'=>'1','count'=>$count));
            }
        }
        
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
}
else {
    echo json_encode(array('status'=>'1','phones'=>array(), 'cost'=>$cost,'count'=>0));    
}

?>