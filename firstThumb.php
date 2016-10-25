<?php

/**
 * @param $image_path
 * @return $image_byte_stream
 */



require("mysqlConfig.php");

$thumbnail = make_thumbnail("D:\Pictures\Backgrounds\1419743609085", "jpeg");

	 $sql = "INSERT INTO informasjon(`thumbnail`) VALUES(" + $thumbnail + ")";
    if (!$stmt = $conn->prepare($sql)) {
        echo 'Error: ' . $conn->error;
        die();
    }
    $stmt->bind_param("sss", $img, $imgtype, $thumbnail);
    #$stmt->bind_param("bs", $img, $imgtype); -> fix this into a completely prepared statement
    $stmt->execute();
	$stmt->close();

function make_thumbnail_gd($image_path, $image_type){
	$max_pixel_size = 200;
	$exif_read_string = "data://image/$image_type;base64,".base64_encode($image_path);

    //TODO implement switch to treat different image types
    //for now just jpg compatible

    $image = imagecreatefromstring($image_path);
	$orientation = isset(exif_read_data($exif_read_string)['Orientation']) ? exif_read_data($exif_read_string)['Orientation']: 0;
	if ($orientation == 6){
		$image = imagerotate($image, -90, 0);
	}



	$image_width  = imagesx($image);
	$image_height = imagesy($image);
    //echo ("Image Width: $image_width, Image Height: $image_height<br>");


    if($image_height < $image_width){
    	$thumbnail_width = $max_pixel_size;
    	$thumbnail_height = $max_pixel_size*($image_height/$image_width);
    }else{
    	$thumbnail_height = $max_pixel_size;
    	$thumbnail_width  = $max_pixel_size*($image_width/$image_height);
    }

	$thumbnail_height = number_format($thumbnail_height, 0);
	$thumbnail_width  = number_format($thumbnail_width , 0);


	//echo ("Image Width: $image_width, Image Height: $image_height<br>");
    //echo("Thumbnail Width: $thumbnail_width, Thumbnail Height: $thumbnail_height<br>");

	$thumbnail = imagecreatetruecolor($thumbnail_width, $thumbnail_height);



   // echo("Thumbnail Width: $thumbnail_width, Thumbnail Height: $thumbnail_height<br>");
	imagecopyresized($thumbnail, $image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $image_width, $image_height);




	return $thumbnail;

}

function blob_image($image){
	ob_start();
	imagejpeg($image);
	$imagejpg = ob_get_contents();
	ob_end_clean();
	return base64_encode($imagejpg);
}

function make_thumbnail($image, $image_path){
	$image_base_64 = make_thumbnail_gd($image, $image_path);
	return blob_image($image_base_64);

}


/* Testkode
$test_image = "FullMoon.jpg";
$handle = fopen($test_image, "rb");
$img = fread($handle, filesize($test_image));
$image_base_64 =  make_thumbnail($img, "jpeg");

$file = fopen("testifsavingworks.jpg", "w");
fwrite($file, base64_decode($image_base_64));

*/

?>