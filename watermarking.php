<?
header("Content-type:text/html;charset=utf-8");
include_once "lib/db_config.php";
addWatermark("upload_month/upload_2024_04/watermark.png","iamgallery");
//watermarking("upload_month/upload_2024_04/watermark.png","upload_month/upload_2024_04/mark.jpg",50,50);
function addWatermark($sourceFile, $watermarkText = NULL, $watermarkImage = NULL)
{
	// 원본 이미지 로드
	echo $sourceFile."<br>";
    $dstFile = mb_substr($sourceFile,0,mb_strrpos($sourceFile,"."));
    $ext = mb_substr($sourceFile,mb_strrpos($sourceFile,"."));
    $dstFile .= "wm".$ext;
    echo $dstFile."<br>";
	$info = getimagesize($sourceFile);
	$type = $info[2];
	print_r($info);
	echo "<br>";
	switch ($type) {
		case IMAGETYPE_JPEG:
			$image = imagecreatefromjpeg($sourceFile);
			break;
		case IMAGETYPE_PNG:
			echo "type png:".$image = imagecreatefrompng($sourceFile)."<br>";
			break;
		default:
			die("Unsupported image type.");
	}
	// 텍스트 워터마크 추가
	if ($watermarkText) {
		$textColor = imagecolorallocatealpha($image, 255, 255, 255, 50); // 백색, 반투명
		$fontFile = 'fonts/fontawesome-webfont.ttf'; // 글꼴 파일 경로 지정 필요
		echo "color=>".$textColor.":".imagettftext($image, 200, 50, 100, 25, $textColor, $fontFile, $watermarkText);
	}
	echo imagesx($image)."/".imagesy($image)."<br>";
	// 이미지 워터마크 추가
	if ($watermarkImage && file_exists($watermarkImage)) {
		$watermark = imagecreatefrompng($watermarkImage);
		$wx = imagesx($watermark);
		$wy = imagesy($watermark);
		imagecopy($image, $watermark, imagesx($image) - $wx - 10, imagesy($image) - $wy - 10, 0, 0, $wx, $wy);
	}

	// 이미지 저장
	switch ($type) {
		case IMAGETYPE_JPEG:
			imagejpeg($image, $dstFile);
			break;
		case IMAGETYPE_PNG:
			$wres = imagepng($image, "upload_month/upload_2024_04/test.png");
			echo "png=".$wres."<br>";
			break;
	}
	
	// 리소스 해제
	imagedestroy($image);
	if (isset($watermark)) {
		imagedestroy($watermark);
	}
}
function watermarking($file, $mark, $x = 0, $y = 0, $opacity = 60)
{
	$ext = strtolower(substr(strrchr($file, "."), 1));
	$mark_size = getimagesize($mark);
    print_r($mark_size);
    echo "<br>";
	if ($ext == "jpg" || $ext == "jpeg") {
		$mark = imagecreatefromgif($mark);
		$dest = imagecreatefromjpeg($file);
		$size = getimagesize($file);

		imagecopymerge($dest, $mark, $x, $y, 0, 0, $mark_size[0], $mark_size[1], $opacity);
		imagejpeg($dest, $file);
	} elseif ($ext == "gif") {
		$mark = imagecreatefromgif($mark);
		$dest = imagecreatefromgif($file);
		$size = getimagesize($file);

		imagecopymerge($dest, $mark, $x, $y, 0, 0, $mark_size[0], $mark_size[1], $opacity);
		imagegif($dest, $file);
	} elseif ($ext == "png") {
		$mark = imagecreatefromgif($mark);
		$dest = imagecreatefrompng($file);
		$size = getimagesize($file);
        print_r($size);
        echo "<br>";
		echo "merge:".imagecopymerge($dest, $mark, $x, $y, 0, 0, $mark_size[0], $mark_size[1], $opacity);
        echo "<br>";
		echo "png:".imagepng($dest, $file);
	}
}
?>