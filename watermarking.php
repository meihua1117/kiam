<?
header("Content-type:text/html;charset=utf-8");
include_once "lib/db_config.php";
$sql = "select idx,img,card_idx from Gn_Iam_Mall where mall_type > 10 order by idx";
$res = mysql_query($sql);
while ($row = mysql_fetch_assoc($res)) {
    if ($_GET["content"] == "Y") {
        $sql = "update Gn_Iam_Contents set contents_img = '{$row['img']}' where idx = {$row['card_idx']}";
        mysql_query($sql);
        echo $row['idx'] . " changing...";
    } else {
        $srcFile = $row['img'];
        if (strpos($srcFile, "/") == 0)
            $srcFile = substr($srcFile, 1);
        $dstFile = addWatermark($srcFile, "iamgallery", "iam/img/common/mark.png");
        if ($dstFile != false) {
            $sql = "update Gn_Iam_Mall set img = '{$dstFile}' where idx={$row['idx']}";
            mysql_query($sql);
            $sql = "update Gn_Iam_Contents set contents_img = '{$dstFile}' where idx = {$row['card_idx']}";
            mysql_query($sql);
            echo $row['idx'] . " changing...";
        } else {
            echo $row['idx'] . " have no img";
        }
    }
    echo "<br>";
}
echo "add water mark completed!";
function addWatermark($sourceFile, $watermarkText = NULL, $watermarkImage = NULL)
{
    if ($sourceFile == "")
        return false;
    // 원본 이미지 로드
    $dstFile = mb_substr($sourceFile, 0, mb_strrpos($sourceFile, "."));
    $ext = mb_substr($sourceFile, mb_strrpos($sourceFile, "."));
    $dstFile .= "wm" . $ext;
    $info = getimagesize($sourceFile);
    $type = $info[2];
    switch ($type) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($sourceFile);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($sourceFile);
            break;
        default:
            die("Unsupported image type.");
    }

    // 이미지 워터마크 추가
    if ($watermarkImage && file_exists($watermarkImage)) {
        $watermark = imagecreatefrompng($watermarkImage);
        $image_width = imagesx($image);
        $image_height = imagesy($image);
        $wx = imagesx($watermark);
        $wy = imagesy($watermark);
        $x_interval = 100;
        $y_interval = 100;
        for ($x = 0; $x < $image_width; $x += $x_interval) {
            for ($y = 0; $y < $image_height; $y += $y_interval) {
                imagecopy($image, $watermark, $x, $y, 0, 0, $wx, $wy);
            }
        }
    }
    // 텍스트 워터마크 추가
    if ($watermarkText) {
        $text_height = imagefontheight(5);
        $textColor = imagecolorallocatealpha($image, 13, 13, 13, 100); // 백색, 반투명
        $fontFile = 'fonts/KoPubDotumLight.ttf'; // 글꼴 파일 경로 지정 필요
        $image_width = imagesx($image);
        $image_height = imagesy($image);
        $x_interval = 100;
        $y_interval = 100;
        for ($x = 0; $x < $image_width; $x += $x_interval) {
            for ($y = 0; $y < $image_height; $y += $y_interval) {
                imagettftext($image, 12, 0, $x + 10, $y + 5, $textColor, $fontFile, $watermarkText);
            }
        }
    }
    // 이미지 저장
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($image, $dstFile);
            break;
        case IMAGETYPE_PNG:
            imagepng($image, $dstFile);
            break;
    }

    // 리소스 해제
    imagedestroy($image);
    if (isset($watermark)) {
        imagedestroy($watermark);
    }
    return $dstFile;
}
