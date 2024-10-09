#!/opt/php/bin/php
<?php
set_time_limit(0);
ini_set('memory_limit','-1');
getDirContents('/home/kiam/www/naver_editor/upload/');
 //$handle = new Image("/naver_editor/upload/test.jpg", 800);
// $handle = new Image("/home/kiam/www/upload_month/upload_2024_04/020420241651170.JPG", 800);
// $handle->resize();
// echo $handle->log;
echo "Done!!!";

function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            //echo $path;
            $handle = new Image($path, 800);
            $handle->resize();
            echo $handle->log;
        } else if ($value != "." && $value != "..") {
            //getDirContents($path, $results);
            //$results[] = $path;
        }
    }
    return $results;
}

Class Image{

    var $file_is_image ;
    var $image_auto_rotate;
    var $image_is_transparent;
    var $image_is_palette; 
    var $image_background_color;
    var $image_transparent_color;
    var $image_default_color ;
    var $image_resize;
    var $png_compression;

    var $log;

    var $image_src_x;
    var $image_src_type;
    var $image_src_y;
    var $image_dst_x;
    var $image_dst_y;
    var $jpeg_quality;
    var $image_convert;

    var $file_src_pathname;
    var $file_dst_pathname;
    var $base_pixel;
    var $processed;
    var $start_time;
    var $step;
    var $step_logs;
    



    function  __construct($file, $basepixel)
    {
	$this->log = '<br>log:<br>';
        try{
            $this->file_is_image = false;
            $this->file_src_pathname = $file;
            $this->file_dst_pathname = $file . "_";
            $this->image_src_type = strtolower(pathinfo($file, PATHINFO_EXTENSION));  

            if($this->image_src_type != "jpg" && $this->image_src_type != "jpeg" && $this->image_src_type != "gif" && $this->image_src_type != "png")
                return;

            $size = filesize($this->file_src_pathname);
            $this->log .= $this->file_src_pathname."=".$size."<br>";
            if($size > 100 * 1024 * 1024 || $size < 1000 * 1024){
        	$this->log .= "size error<br>";
                return;    
            }
$this->log .= "before img size<br>";
            list($origW, $origH) = getimagesize($file);
$this->log .= "after img size<br>";    
            $this->image_src_x = $w = $origW;
            $this->image_src_y = $h = $origH; 
            $this->log .= "x=".$w.",y=".$h."<br>";
            $maxPixel = ($origW > $origH) ?$origW: $origH; 
            if($maxPixel <= $basepixel)
                return ;

            $this->base_pixel = $basepixel;
            
            //initialize class variables
            $this->image_is_transparent = false;
            $this->image_transparent_color  = null;
            $this->image_background_color   = null;
            $this->image_is_palette  = false;
            //$this->log = 'log:';
            $this->image_auto_rotate = true;
            $this->image_default_color      = '#ffffff';
            $this->image_resize = true;
            $this->jpeg_quality             = 85;
            $this->image_convert = '';
            $this->png_compression          = null;

            $this->processed = true;
            $this->file_is_image = true;
            $this->step = 0;
            $this->step_logs = false;


        }
        catch(Exception $e){
    	    $this->log .= $e->getMessage();    
        }

    }

    function resize()
    {
        if(!$this->file_is_image)
            return;

        $this->log .= 'file path => '.$this->file_src_pathname . '<br />';
        $size = filesize($this->file_src_pathname);
        $this->log .= '- file size : '. round($size/256)/4 . 'KB <br />'; 

        $this->statusLog();
        $auto_flip = false;
        $auto_rotate = 0;
        if ( ($this->image_src_type == 'jpg' || $this->image_src_type == 'jpeg') && $this->function_enabled('exif_read_data')) {
            $exif = @exif_read_data($this->file_src_pathname);
            if (is_array($exif) && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                switch($orientation) {
                  case 1:
                    $this->log .= '- EXIF orientation = 1 : default<br />';
                    break;
                  case 2:
                    $auto_flip = 'v';
                    $this->log .= '- EXIF orientation = 2 : vertical flip<br />';
                    break;
                  case 3:
                    $auto_rotate = 180;
                    $this->log .= '- EXIF orientation = 3 : 180 rotate left<br />';
                    break;
                  case 4:
                    $auto_flip = 'h';
                    $this->log .= '- EXIF orientation = 4 : horizontal flip<br />';
                    break;
                  case 5:
                    $auto_flip = 'h';
                    $auto_rotate = 90;
                    $this->log .= '- EXIF orientation = 5 : horizontal flip + 90 rotate right<br />';
                    break;
                  case 6:
                    $auto_rotate = 90;
                    $this->log .= '- EXIF orientation = 6 : 90 rotate right<br />';
                    break;
                  case 7:
                    $auto_flip = 'v';
                    $auto_rotate = 90;
                    $this->log .= '- EXIF orientation = 7 : vertical flip + 90 rotate right<br />';
                    break;
                  case 8:
                    $auto_rotate = 270;
                    $this->log .= '- EXIF orientation = 8 : 90 rotate left<br />';
                    break;
                  default:
                    $this->log .= '- EXIF orientation = '.$orientation.' : unknown<br />';
                    break;
                }
            } else {
                $this->log .= '- EXIF data is invalid or missing<br />';
            }
        } else {
            if (!$this->image_auto_rotate) {
                $this->log .= '- auto-rotate deactivated<br />';
            } else if ($this->image_src_type != 'jpg' && $this->image_src_type != 'jpeg') {
                //$this->log .= '- auto-rotate applies only to JPEG images<br />';
            } else if (!$this->function_enabled('exif_read_data')) {
                $this->log .= '- auto-rotate requires function exif_read_data to be enabled<br />';
            }
        }

        // make sure GD doesn't complain too much
        @ini_set("gd.jpeg_ignore_warning", 1);

        // checks if the source file is readable
        if ($this->processed && !($f = @fopen($this->file_src_pathname, 'r'))) {
            $this->processed = false;
            return;
        } else {
            @fclose($f);
        }

        if ($this->gdversion()) {
            switch($this->image_src_type) {
                case 'jpg':
                case 'jpeg':
                    if (!$this->function_enabled('imagecreatefromjpeg')) {
                        $this->processed = false;
                    } else {
                        $image_src = @imagecreatefromjpeg($this->file_src_pathname);
                        if (!$image_src) {
                            $this->processed = false;
                        } else {
                            $this->log .= '- source image is JPEG<br />';
                        }
                    }
                    break;
                case 'png':
                    if (!$this->function_enabled('imagecreatefrompng')) {
                        $this->processed = false;
                    } else {
                        $image_src = @imagecreatefrompng($this->file_src_pathname);
                        if (!$image_src) {
                            $this->processed = false;
                        } else {
                            $this->log .= '- source image is PNG<br />';
                        }
                    }
                    break;
                case 'gif':
                    if (!$this->function_enabled('imagecreatefromgif')) {
                        $this->processed = false;
                    } else {
                        $image_src = @imagecreatefromgif($this->file_src_pathname);
                        if (!$image_src) {
                            $this->processed = false;
                        } else {
                            $this->log .= '- source image is GIF<br />';
                        }
                    }
                    break;
                default:
                    $this->processed = false;
            }
        } else {
            $this->processed = false;
        }

        if ($this->processed && $image_src) {

            // we have to set image_convert if it is not already
            if (empty($this->image_convert)) {
                $this->log .= '- destination image is ' . $this->image_src_type . '<br />';
                $this->image_convert = $this->image_src_type;
            } else {
                $this->log .= '- destination image is ' . $this->image_convert . '<br />';
            }

            $this->statusLog('image initiation');

            if ($this->image_convert != 'png' && $this->image_convert != 'gif' && !empty($this->image_default_color) && empty($this->image_background_color)) $this->image_background_color = $this->image_default_color;
            if (!empty($this->image_background_color)) $this->image_default_color = $this->image_background_color;
            if (empty($this->image_default_color)) $this->image_default_color = '#FFFFFF';

            $this->image_src_x = imagesx($image_src);
            $this->image_src_y = imagesy($image_src);
            $gd_version = $this->gdversion();

            if (!imageistruecolor($image_src)) {  // $this->image_src_type == 'gif'
                $this->log .= '- image is detected as having a palette<br />';
                $this->image_is_palette = true;
                $this->image_transparent_color = imagecolortransparent($image_src);
                if ($this->image_transparent_color >= 0 && imagecolorstotal($image_src) > $this->image_transparent_color) {
                    $this->image_is_transparent = true;
                    $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;palette image is detected as transparent<br />';
                }
                // if the image has a palette (GIF), we convert it to true color, preserving transparency
                $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;convert palette image to true color<br />';
                $true_color = imagecreatetruecolor($this->image_src_x, $this->image_src_y);
                imagealphablending($true_color, false);
                imagesavealpha($true_color, true);
                for ($x = 0; $x < $this->image_src_x; $x++) {
                    for ($y = 0; $y < $this->image_src_y; $y++) {
                        if ($this->image_transparent_color >= 0 && imagecolorat($image_src, $x, $y) == $this->image_transparent_color) {
                            imagesetpixel($true_color, $x, $y, 127 << 24);
                        } else {
                            $rgb = imagecolorsforindex($image_src, imagecolorat($image_src, $x, $y));
                            imagesetpixel($true_color, $x, $y, ($rgb['alpha'] << 24) | ($rgb['red'] << 16) | ($rgb['green'] << 8) | $rgb['blue']);
                        }
                    }
                }
                $image_src = $this->imagetransfer($true_color, $image_src);
                imagealphablending($image_src, false);
                imagesavealpha($image_src, true);
                $this->image_is_palette = false;
            }

            $image_dst = & $image_src;

            // auto-flip image, according to EXIF data (JPEG only)
            if ($gd_version >= 2 && !empty($auto_flip)) {
                $this->log .= '- auto-flip image : ' . ($auto_flip == 'v' ? 'vertical' : 'horizontal') . '<br />';
                $tmp = $this->imagecreatenew($this->image_src_x, $this->image_src_y);
                for ($x = 0; $x < $this->image_src_x; $x++) {
                    for ($y = 0; $y < $this->image_src_y; $y++){
                        if (strpos($auto_flip, 'v') !== false) {
                            imagecopy($tmp, $image_dst, $this->image_src_x - $x - 1, $y, $x, $y, 1, 1);
                        } else {
                            imagecopy($tmp, $image_dst, $x, $this->image_src_y - $y - 1, $x, $y, 1, 1);
                        }
                    }
                }
                // we transfert tmp into image_dst
                $image_dst = $this->imagetransfer($tmp, $image_dst);
            }


            // auto-rotate image, according to EXIF data (JPEG only)
            if ($gd_version >= 2 && is_numeric($auto_rotate)) {
                if (!in_array($auto_rotate, array(0, 90, 180, 270))) $auto_rotate = 0;
                if ($auto_rotate != 0) {
                    if ($auto_rotate == 90 || $auto_rotate == 270) {
                        $tmp = $this->imagecreatenew($this->image_src_y, $this->image_src_x);
                    } else {
                        $tmp = $this->imagecreatenew($this->image_src_x, $this->image_src_y);
                    }
                    $this->log .= '- auto-rotate image : ' . $auto_rotate . '<br />';
                    for ($x = 0; $x < $this->image_src_x; $x++) {
                        for ($y = 0; $y < $this->image_src_y; $y++){
                            if ($auto_rotate == 90) {
                                imagecopy($tmp, $image_dst, $y, $x, $x, $this->image_src_y - $y - 1, 1, 1);
                            } else if ($auto_rotate == 180) {
                                imagecopy($tmp, $image_dst, $x, $y, $this->image_src_x - $x - 1, $this->image_src_y - $y - 1, 1, 1);
                            } else if ($auto_rotate == 270) {
                                imagecopy($tmp, $image_dst, $y, $x, $this->image_src_x - $x - 1, $y, 1, 1);
                            } else {
                                imagecopy($tmp, $image_dst, $x, $y, $x, $y, 1, 1);
                            }
                        }
                    }
                    if ($auto_rotate == 90 || $auto_rotate == 270) {
                        $t = $this->image_src_y;
                        $this->image_src_y = $this->image_src_x;
                        $this->image_src_x = $t;
                    }
                    // we transfert tmp into image_dst
                    $image_dst = $this->imagetransfer($tmp, $image_dst);
                }
            }

            $this->statusLog("processing gif and jpeg spec");

            // resize image (and move image_src_x, image_src_y dimensions into image_dst_x, image_dst_y)
            if ($this->image_resize) {  
                
                $origW = $this->image_src_x;
                $origH = $this->image_src_y;
                $basepixel = $this->base_pixel;
                $maxPixel = ($origW > $origH) ?$origW: $origH; 
                
                if($maxPixel == $origW){ //landscape image
                    $w = $basepixel;
                    $h = round( ($origH / $origW) * $basepixel);
                }else{ //portrait image
                    $h = $basepixel;
                    $w = round(($origW / $origH) * $basepixel);
                }  
    
                $this->image_dst_x = $w;
                $this->image_dst_y = $h;

                // resize the image
                if ($this->image_dst_x != $this->image_src_x || $this->image_dst_y != $this->image_src_y) {
                    $tmp = $this->imagecreatenew($this->image_dst_x, $this->image_dst_y);

                    if ($gd_version >= 2) {
                        $res = imagecopyresampled($tmp, $image_src, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y, $this->image_src_x, $this->image_src_y);
                    } else {
                        $res = imagecopyresized($tmp, $image_src, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y, $this->image_src_x, $this->image_src_y);
                    }

                    $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;resized image object created<br />';
                     // we transfert tmp into image_dst
                    $image_dst = $this->imagetransfer($tmp, $image_dst);
                }

            } else {
                $this->image_dst_x = $this->image_src_x;
                $this->image_dst_y = $this->image_src_y;
            }

            $this->statusLog("resizing image");

            // converts image from true color, and fix transparency if needed
            $this->log .= "- original size: $this->image_src_x X $this->image_src_y, converted size: $this->image_dst_x X $this->image_dst_y <br />";
            switch($this->image_convert) {
                case 'gif':
                    // if the image is true color, we convert it to a palette
                    if (imageistruecolor($image_dst)) {
                        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;true color to palette<br />';
                        // creates a black and white mask
                        $mask = array(array());
                        for ($x = 0; $x < $this->image_dst_x; $x++) {
                            for ($y = 0; $y < $this->image_dst_y; $y++) {
                                $pixel = imagecolorsforindex($image_dst, imagecolorat($image_dst, $x, $y));
                                $mask[$x][$y] = $pixel['alpha'];
                            }
                        }
                        list($red, $green, $blue) = $this->getcolors($this->image_default_color);
                        // first, we merge the image with the background color, so we know which colors we will have
                        for ($x = 0; $x < $this->image_dst_x; $x++) {
                            for ($y = 0; $y < $this->image_dst_y; $y++) {
                                if ($mask[$x][$y] > 0){
                                    // we have some transparency. we combine the color with the default color
                                    $pixel = imagecolorsforindex($image_dst, imagecolorat($image_dst, $x, $y));
                                    $alpha = ($mask[$x][$y] / 127);
                                    $pixel['red'] = round(($pixel['red'] * (1 -$alpha) + $red * ($alpha)));
                                    $pixel['green'] = round(($pixel['green'] * (1 -$alpha) + $green * ($alpha)));
                                    $pixel['blue'] = round(($pixel['blue'] * (1 -$alpha) + $blue * ($alpha)));
                                    $color = imagecolorallocate($image_dst, $pixel['red'], $pixel['green'], $pixel['blue']);
                                    imagesetpixel($image_dst, $x, $y, $color);
                                }
                            }
                        }
                        // transforms the true color image into palette, with its merged default color
                        if (empty($this->image_background_color)) {
                            imagetruecolortopalette($image_dst, true, 255);
                            $transparency = imagecolorallocate($image_dst, 254, 1, 253);
                            imagecolortransparent($image_dst, $transparency);
                            // make the transparent areas transparent
                            for ($x = 0; $x < $this->image_dst_x; $x++) {
                                for ($y = 0; $y < $this->image_dst_y; $y++) {
                                    // we test wether we have enough opacity to justify keeping the color
                                    if ($mask[$x][$y] > 120) imagesetpixel($image_dst, $x, $y, $transparency);
                                }
                            }
                        }                        
                        unset($mask);
                    }
                    break;
                case 'jpg':
                case 'jpeg':
                    // if the image doesn't support any transparency, then we merge it with the default color
                    /*$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;fills in transparency with default color<br />';
                    list($red, $green, $blue) = $this->getcolors($this->image_default_color);
                    $transparency = imagecolorallocate($image_dst, $red, $green, $blue);
                    // make the transaparent areas transparent
                    for ($x = 0; $x < $this->image_dst_x; $x++) {
                        for ($y = 0; $y < $this->image_dst_y; $y++) {
                            // we test wether we have some transparency, in which case we will merge the colors
                            if (imageistruecolor($image_dst)) {
                                $rgba = imagecolorat($image_dst, $x, $y);
                                $pixel = array('red' => ($rgba >> 16) & 0xFF,
                                                'green' => ($rgba >> 8) & 0xFF,
                                                'blue' => $rgba & 0xFF,
                                                'alpha' => ($rgba & 0x7F000000) >> 24);
                            } else {
                                $pixel = imagecolorsforindex($image_dst, imagecolorat($image_dst, $x, $y));
                            }
                            if ($pixel['alpha'] == 127) {
                                // we have full transparency. we make the pixel transparent
                                imagesetpixel($image_dst, $x, $y, $transparency);
                            } else if ($pixel['alpha'] > 0) {
                                // we have some transparency. we combine the color with the default color
                                $alpha = ($pixel['alpha'] / 127);
                                $pixel['red'] = round(($pixel['red'] * (1 -$alpha) + $red * ($alpha)));
                                $pixel['green'] = round(($pixel['green'] * (1 -$alpha) + $green * ($alpha)));
                                $pixel['blue'] = round(($pixel['blue'] * (1 -$alpha) + $blue * ($alpha)));
                                $color = imagecolorclosest($image_dst, $pixel['red'], $pixel['green'], $pixel['blue']);
                                imagesetpixel($image_dst, $x, $y, $color);
                            }
                        }
                    }*/
                    break;
                default:
                    break;
            }

            $this->statusLog("converted");

            // outputs image
            $this->log .= '- saving image...<br />';
            switch($this->image_convert) {
                case 'jpeg':
                case 'jpg':
                    $result = @imagejpeg($image_dst, $this->file_dst_pathname, $this->jpeg_quality);
                    if (!$result) {
                        $this->processed = false;
                    } else {
                        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;JPEG image created<br />';
                    }
                    break;
                case 'png':
                    imagealphablending( $image_dst, false );
                    imagesavealpha( $image_dst, true );
                    
                    if (is_numeric($this->png_compression) && version_compare(PHP_VERSION, '5.1.2') >= 0) {
                        $result = @imagepng($image_dst, $this->file_dst_pathname, $this->png_compression);
                    } else {
                        $result = @imagepng($image_dst, $this->file_dst_pathname);
                    }
                    
                    if (!$result) {
                        $this->processed = false;
                    } else {
                        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;PNG image created<br />';
                    }
                    break;
                case 'gif':
                    $result = @imagegif($image_dst, $this->file_dst_pathname);

                    if (!$result) {
                        $this->processed = false;
                    } else {
                        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;GIF image created<br />';
                    }
                    break;

                default:
                    $this->processed = false;
            }

            if ($this->processed) {
                $this->imageunset($image_src);
                $this->imageunset($image_dst);
                $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image objects destroyed<br />';


                $newsize = filesize($this->file_dst_pathname);
                if($newsize < $size)
                {
                    unlink($this->file_src_pathname);
                    rename($this->file_dst_pathname, $this->file_src_pathname);
                    $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;saving resized image<br />';
                }
                else
                {
                    unlink($this->file_dst_pathname);
                    $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;keeping original image<br />';
                }

            }
            $this->statusLog("completed..");

        }
    }

    function statusLog($mark='')
    {
        if($this->step_logs)
        {
            if($this->step == 0)
            {
                $this->start_time = $this->microtime_float();
                $this->log .= 'memory usage: '. memory_get_usage() . '<br />';    
            }
            else
            {
                $time_end = $this->microtime_float();
                $time = $time_end - $this->start_time;
                $this->log .= 'memory usage: '. memory_get_usage() . ', elapsed time: ' . $time . ',step: '. $mark .' <br />'; 
            }
            $this->step++;
        }

    }

    function imagecreatenew($x, $y, $fill = true, $trsp = false) {
        if ($x < 1) $x = 1; if ($y < 1) $y = 1;
        if ($this->gdversion() >= 2 && !$this->image_is_palette) {
            // create a true color image
            $dst_im = imagecreatetruecolor($x, $y);
            // this preserves transparency in PNG and WEBP, in true color
            if (empty($this->image_background_color) || $trsp) {
                imagealphablending($dst_im, false );
                imagefilledrectangle($dst_im, 0, 0, $x, $y, imagecolorallocatealpha($dst_im, 0, 0, 0, 127));
            }
        } else {
            // creates a palette image
            $dst_im = imagecreate($x, $y);
            // preserves transparency for palette images, if the original image has transparency
            if (($fill && $this->image_is_transparent && empty($this->image_background_color)) || $trsp) {
                imagefilledrectangle($dst_im, 0, 0, $x, $y, $this->image_transparent_color);
                imagecolortransparent($dst_im, $this->image_transparent_color);
            }
        }
        // fills with background color if any is set
        if ($fill && !empty($this->image_background_color) && !$trsp) {
            list($red, $green, $blue) = $this->getcolors($this->image_background_color);
            $background_color = imagecolorallocate($dst_im, $red, $green, $blue);
            imagefilledrectangle($dst_im, 0, 0, $x, $y, $background_color);
        }
        return $dst_im;
    }

    function imagetransfer($src_im, $dst_im) {
        $this->imageunset($dst_im);
        $dst_im = & $src_im;
        return $dst_im;
    }

    function imageunset($im) {
        if (is_resource($im)) {
            imagedestroy($im);
        } else if (is_object($im) && $im instanceOf GdImage) {
            unset($im);
        }
    }

    function getcolors($color) {
        $color = str_replace('#', '', $color);
        if (strlen($color) == 3) $color = str_repeat(substr($color, 0, 1), 2) . str_repeat(substr($color, 1, 1), 2) . str_repeat(substr($color, 2, 1), 2);
        $r = sscanf($color, "%2x%2x%2x");
        $red   = (is_array($r) && array_key_exists(0, $r) && is_numeric($r[0]) ? $r[0] : 0);
        $green = (is_array($r) && array_key_exists(1, $r) && is_numeric($r[1]) ? $r[1] : 0);
        $blue  = (is_array($r) && array_key_exists(2, $r) && is_numeric($r[2]) ? $r[2] : 0);
        return array($red, $green, $blue);
    }

    
    function gdversion($full = false) {
        static $gd_version = null;
        static $gd_full_version = null;
        if ($gd_version === null) {
            if ($this->function_enabled('gd_info')) {
                $gd = gd_info();
                $gd = $gd["GD Version"];
                $regex = "/([\d\.]+)/i";
            } else {
                ob_start();
                phpinfo(8);
                $gd = ob_get_contents();
                ob_end_clean();
                $regex = "/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i";
            }
            if (preg_match($regex, $gd, $m)) {
                $gd_full_version = (string) $m[1];
                $gd_version = (float) $m[1];
            } else {
                $gd_full_version = 'none';
                $gd_version = 0;
            }
        }
        if ($full) {
            return $gd_full_version;
        } else {
            return $gd_version;
        }
    }
    
    function function_enabled($func) {
        // cache the list of disabled functions
        static $disabled = null;
        if ($disabled === null) $disabled = array_map('trim', array_map('strtolower', explode(',', ini_get('disable_functions'))));
        // cache the list of functions blacklisted by suhosin
        static $blacklist = null;
        if ($blacklist === null) $blacklist = extension_loaded('suhosin') ? array_map('trim', array_map('strtolower', explode(',', ini_get('  suhosin.executor.func.blacklist')))) : array();
        // checks if the function is really enabled
        return (function_exists($func) && !in_array($func, $disabled) && !in_array($func, $blacklist));
    }

    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

}
?>