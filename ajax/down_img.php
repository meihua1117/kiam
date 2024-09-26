<?
include_once "rlatjd_fun.php";

$contents_img = $_POST['contents_img'];
$img_name = $_POST['img_name'];

$path = "../upload/".$img_name;
$path_mms = "../adjunct/mms/thum/".$img_name;

downloadFile($contents_img, $path_mms);
echo json_encode(downloadFile($contents_img, $path));

function downloadFile($url, $path)
{
    ini_set('allow_url_fopen', 1);

    $start = microtime(true);

    $result = file_put_contents($path, fopen($url, 'r'));
    $end = microtime(true);
    $elapsed = $end-$start;
    if($result === false) {
        $data = array('error'=>'UNKNOW_ERROR');
    } else {
        $data = array('error'=>'0', 'elpased'=>$elapsed);
    }
    return $data;

    $newfname = $path;
    $file = fopen ($url, 'rb');
    if ($file) {
        $newf = fopen ($newfname, 'wb');
        if ($newf) {
            while(!feof($file)) {
                fwrite($newf, fread($file, 1024 * 64), 1024 * 64);
            }
        }
    }

    if(!$file || !$newf) {
        return array('error'=>'ERROR_FILE');
    }

    if ($file) {
        fclose($file);
    }
    if ($newf) {
        fclose($newf);
    }

    $end = microtime(true);
    $elapsed = $end-$start;

    return array('error'=>'0', 'elpased'=>$elapsed);
}
?>