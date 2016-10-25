<?php


function makeThumbnailGD($imageSTR, $imageType){
	$maxPixelSize = 200;
	$exifReadString = "data://image/$imageType;base64,".base64_encode($imageSTR);

	$image = imagecreatefromstring($imageSTR);
	$orientation = isset(exif_read_data($exifReadString)['Orientation']) ? exif_read_data($exifReadString)['Orientation']: 0;
	if ($orientation == 6){
		$image = imagerotate($image, -90, 0);
	}

	$imageWidth  = imagesx($image);
	$imageHeight = imagesy($image);

    if($imageHeight < $imageWidth){
    	$thumbnailWidth = $maxPixelSize;
    	$thumbnailHeight = $maxPixelSize*($imageHeight/$imageWidth);
    }else{
    	$thumbnailHeight = $maxPixelSize;
    	$thumbnailWidth  = $maxPixelSize*($imageWidth/$imageHeight);
    }

	$thumbnailHeight = number_format($thumbnailHeight, 0);
	$thumbnailWidth  = number_format($thumbnailWidth , 0);
	$thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);
	imagecopyresized($thumbnail, $image, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $imageWidth, $imageHeight);

	return $thumbnail;
}

function blobImage($image){
	ob_start();
	imagejpeg($image);
	$imagejpg = ob_get_contents();
	ob_end_clean();
	return base64_encode($imagejpg);
}

function makeThumbnail($image, $imageType){
	$thumbnail = makeThumbnailGD($image, $imageType);
	return blobImage($thumbnail);

}
