<?php 
#This is the main entry point of the HTTP requests
require("mysqlConfig.php");

/** All interaction with server goes through this Handler. Depending on the request different things will be initialized
 *  almost all the methods does require the id of the picture in question
 * 	Very important that all the parameters are named correctly, as they are case-sensitive
 *  Use by sending a http-request to:
 * 	http://localhost/dat210-gruppeb/server/requestHandler.php?operation="choose Operation Here"
 * 	remember to add any extra parameters if they are needed f.eks:
 * 	http://localhost/dat210-gruppeb/server/requestHandler.php?operation=getImage&id="the picture you want"
 */
$operation = isset($_GET['operation']) ? $_GET['operation'] :"";
$id = isset($_GET['id'])? $_GET['id'] :"";

switch ($operation) {
	case 'getMeta': // Returns n rows of data where amount = n
		include 'metaFetcher.php';
		$amount = isset($_GET['amount'])? $_GET['amount'] : "";
		$page = isset($_GET['page'])? $_GET['page']: ""; #The id of last picture recieved
		if ($page == "") $page = 0;
		sendMeta($amount, $page, $conn);
		break;
	case 'getImage': // needs the id of the picture and the mySqli from mysqlConfig
		include 'imageFetcher.php';
		sendImage("image", $id, $conn);
		break;
	case 'getThumb'; // need the id of the picture and the mySqli from mysqlConfig
		include "imageFetcher.php";
		sendImage("thumb", $id, $conn);
		break;
	case 'import'; // will need a picture to insert into the db
		include 'imageHandler.php'; // will use the methods made at standard db_interaction
		include 'thumbnail.php';
		//include 'metaFinder.php';
		uploadImage();
		// call the method to insert picture and maybe return true when completed?
		break;
	case 'editImage': // needs the edited image and the ID

		break;
	case 'editMeta': // needs new Metadata and the ID
		include 'metaEdit.php';
		$tag = isset($_GET['tag']) ? $_GET['tag'] : "";
		$stedsby = isset($_GET['stedsby']) ? $_GET['stedsby'] : "";
		$land = isset($_GET['land']) ? $_GET['land'] : "";
		$dato = isset($_GET['dato']) ? $_GET['dato'] : "";
		$album = isset($_GET['album']) ? $_GET['album'] : "";
		$rating = isset($_GET['rating']) ? $_GET['rating'] : "";
		$hendelse = isset($_GET['hendelse']) ? $_GET['hendelse'] : "";
		editMeta($id, $tag, $stedsby, $land, $dato, $album, $rating, $hendelse, $conn);
		break;
	case 'search':
		include 'searchDB.php';
		$soek = isset($_GET['soek']) ? $_GET['soek'] : "";
		searchDB2($conn, $soek);
		break;
	case 'image_rotate':
		include 'imageEdit.php';
		$direction = isset($_GET['direction']) ? $_GET['direction'] : "";
		rotateImage($id, $conn, $direction);
		break;
	case 'image_crop':
		include 'imageEdit.php';
		$newx = isset($_GET['newx']) ? $_GET['newx'] : "";
		$newy = isset($_GET['newy']) ? $_GET['newy'] : "";
		$newheight = isset($_GET['newheight']) ? $_GET['newheight'] : "";
		$newwidth = isset($_GET['newwidth']) ? $_GET['newwidth'] : "";
		cropImage($id, $conn, $newx, $newy, $newwidth, $newheight);
		break;
	case 'sort':
		include 'sort.php';
		$sortby = isset($_GET['sortby']) ? $_GET['sortby'] : "";
		$direction = isset($_GET['direction']) ? $_GET['direction'] : 0;
		sorter($sortby, $direction, $conn);
		break;
	case 'delete': // works like search, use ":" as a delimiter between image IDs in input
		include 'delete.php';
		deleteImage($id, $conn);
		break;
	case 'count':
		include 'delete.php';
		countImages($conn);
		break;
	default:
		# code...
		echo "Wrong operation, use getMeta,getImage,getThumb, import, editImage and editMeta";
		break;
}

mysqli_close($conn);
