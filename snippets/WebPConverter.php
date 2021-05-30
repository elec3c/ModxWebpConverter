<?php
$outFilePath=substr(MODX_BASE_PATH,0,-1).'/'.ltrim($url, '/');
	$outFile=pathinfo($outFilePath);
	$webpFile=$outFile['dirname'] . '/' . $outFile['basename'] . '.webp';

	if (!file_exists($webpFile)){

	    $ext=$outFile['extension'];
	    if ($ext=="jpg" || $ext=="jpeg"){
		    $img = imageCreateFromJpeg($outFilePath);
	    }
	    if ($ext=="png"){
	        $img = imageCreateFromPng($outFilePath);
	    }
        /*if (!$img) return;*/
        imagepalettetotruecolor($img);
        imagealphablending($img, true);
        imagesavealpha($img, true);
        imagewebp($img, $webpFile, 100);
        imagedestroy($img);
        return $webpFile;
	}
