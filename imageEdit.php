<?php

function blobImage($image){
	ob_start();
	imagejpeg($image);
	$imageJPG = ob_get_contents();
	ob_end_clean();
	return base64_encode($imageJPG);
}

function getImage($id, $conn){
	include 'imageFetcher.php';
	ob_start();
	sendImage("image", $id, $conn);
	$imageSTR = ob_get_contents();
	ob_end_clean();
	return $imageSTR;
}

function updateThumbnailDB($id, $conn, $imageSTR){
	
	$image = imagecreatefromstring(base64_decode($imageSTR));

	$maxPixelSize = 200;
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

	$thumbnail = blobImage($thumbnail);
	$sql = "update informasjon set thumbnail='$thumbnail' where id='$id'";
	$conn->query($sql);
}

function updateImageDB($id, $conn, $imageSTR){
	$sql = "update informasjon set image='$imageSTR' where id='$id'";
	if ($conn->query($sql)){
		updateThumbnailDB($id, $conn, $imageSTR);
	} 

}

function rotateImage($id, $conn, $direction){

	$imageSTR = getImage($id, $conn);
	$image = imagecreatefromstring(base64_decode($imageSTR));
	$image = imagerotate($image, $direction, 0);
	return updateImageDB($id, $conn, blobImage($image));
	
}

function cropImage($id, $conn, $newx, $newy, $newwidth, $newheight){

	$image = imagecreatefromstring(base64_decode(getImage($id, $conn)));
	$newImage = imagecreatetruecolor($newwidth, $newheight);
	imagecopy($newImage, $image, 0, 0, $newx, $newy, $newwidth, $newheight);
	return updateImageDB($id, $conn, blobImage($newImage));

}