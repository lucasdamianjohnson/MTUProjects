<?php
header('Content-Type: image/jpeg');
//phpinfo();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);



if(isset($_GET['path'])) {
	$path = $_GET['path'];
	if(strpos($path,'http') === false) {
		$path = 'https:'.$path;
	}

} else {
	exit();
}
if(isset($_GET['width'])){
  $mwidth = $_GET['width'];
} else {
  $mwidth = false;
}
if(isset($_GET['height'])){
  $mheight = $_GET['height'];
} else {
  $mheight = false;
}

if($mwidth && $mheight) {
  $crop = true;
} else {
  $crop = false; 
}

if(isset($_GET['quality'])){
  $quality = $_GET['quality'];
} else {
  $quality = 75;
}

$file_extension = substr(basename($path), -3);
$filename = urlencode($_SERVER['QUERY_STRING']);

$file = "crop-cache/$filename.$file_extension";
$cachetime = 14400; //this is 1 day
$cacheon = false;
if (file_exists($file) && time() - $cachetime < filemtime($file)) {
    readfile($file);
    exit;
} else {
$cacheon = true;
}



function loadIMG($path,$extension)
{
	//echo $path;
	
 switch ($extension) {
  case 'png':
  $im = @imagecreatefrompng($path);
  break;
  case 'jpg':
  $im = @imagecreatefromjpeg($path);
  break;
  case 'gif':
  $im = @imagecreatefromgif($path);
  break;
  default:
  $im = false;
  break;
}
	
function imagecrop($src, array $rect)
{
    $dest = imagecreatetruecolor($rect['width'], $rect['height']);
    imagecopy(
        $dest,
        $src,
        0,
        0,
        $rect['x'],
        $rect['y'],
        $rect['width'],
        $rect['height']
    );

    return $dest;
}


if(!$im)
{
  $im  = imagecreatetruecolor(120, 20);
  $bgc = imagecolorallocate($im, 255, 255, 255);
  $tc  = imagecolorallocate($im, 0, 0, 0);

  imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

  imagestring($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
}

return $im;
}




if(($file_extension == 'jpg') || ($file_extension == 'png') || ($file_extension == 'gif')) {
  $info = getimagesize($path);
  if(isset($info[0]) || (isset($info[1]))) {

    $image = loadIMG($path,$file_extension);

//imagejpeg($image);

    $width = $info[0];
    $height = $info[1];
//new to do something with the sizes here.....

    if($crop) {
	
		if(isset($mwidth) && isset($mheight)) {
			$xaspect = $mwidth/$width;
			$yaspect = $mheight/$height;
			if($xaspect < $yaspect) {
				$zheight = $height;
				$zwidth = $mwidth/$yaspect;
				$x = ($width - $zwidth)/2;
			    $y = ($height - $zheight)/2;
			} else {
				$zwidth = $width;
				$zheight = $mheight/$xaspect;
				$x = ($width - $zwidth)/2;
			    $y = ($height - $zheight)/2;
			}
			
			
	     $new = imagecreatetruecolor($mwidth, $mheight);
		 $image = imagecrop($image,['x'=>$x,'y'=>$y,'width'=>$zwidth,'height'=>$zheight]);	
         $test = imagecopyresampled($new, $image, 0, 0, 0, 0, $mwidth, $mheight, $zwidth, $zheight);
			
		} else {
		
			if($width != $mwidth) {
				$x = ($width-$mwidth) / 2;
			} else {
				$x = 0;
			}

			if($height != $mheight) {
				$y = ($height-$mheight) / 2;
			} else {
				$y = 0;
			}
		
		  $new = imagecrop($image,['x'=>$x,'y'=>$y,'width'=>$mwidth,'height'=>$mheight]);
		  $test = true;
			
		}
    
    } else {
		
      if($mwidth) {
        $div = $width / $mwidth;
        $newwidth = $mwidth;
        $newheight = $height / $div;
      } else if($mheight) {
        $div = $height / $mheight;
        $newheight = $mheight;
        $newwidth = $width / $div;
      }

      $new = imagecreatetruecolor($newwidth, $newheight);
      $test =  imagecopyresampled($new, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		
    }
	  
    if($test){
      switch ($file_extension) {
       case 'png':
       if($cacheon){
        imagepng($new,$file,$quality);
        imagepng($new,NULL,$quality);
       } else {
       imagepng($new,NULL,$quality);
     }
       break;
       case 'jpg':
      if($cacheon){
        imagejpeg($new,$file,$quality);
        imagejpeg($new,NULL,$quality);
       } else {
       imagejpeg($new,NULL,$quality);
     }
       break;
       case 'gif':
         if($cacheon){
        imagegif($new,$file,$quality);
        imagegif($new,NULL,$quality);
       } else {
       imagegif($new,NULL,$quality);
     }
       break;
       default:
         if($cacheon){
        imagejpeg($new,$file,$quality);
        imagejpeg($new,NULL,$quality);
       } else {
       imagejpeg($new,NULL,$quality);
     }
       break;
     }


   } 

 }

}



?>