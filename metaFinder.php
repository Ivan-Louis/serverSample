<?php
/**
 * Created by PhpStorm.
 * User: Ivan-Louis
 * Date: 12/11/2015
 * Time: 13:21
 */
include 'metaEdit.php';
$date = "";
$tag = "";
$by = "";
$land = "";   
$album = "";
$rating = "";
$hendelse = "";

function findMeta($path, $id, $conn){
    global $date;
    global $tag;
    global $by;
    global $land;
    global $album;
    global $rating;
    global $hendelse;
    $exif = exif_read_data($path, 0, true);
    foreach ($exif as $key=> $section){
        foreach ($section as $name => $val){
            checkname($name, $val);
        }
    }
    if($date != "" || $tag != "" || $by != "" || $land != "" || $album != "" || $rating != "" || $hendelse != "") {
            editMeta($id, $tag, $by, $land, $date, $album, $rating, $hendelse, $conn);
        }else{
            echo "Ingen metadataer funnet";
        }
}
function checkname($name, $val){
    global $date;
    global $tag;
    global $by;
    global $land;
    global $album;
    global $rating;
    global $hendelse;
    if ($name == "DateTimeOriginal" || $name == "DateTime") {
        if ($name == "DateTimeOriginal" && $date == ""){
            $date = date_create_from_format('Y:m:d H:i:s',$val);
            $date = (string)date_format($date, "Y-m-d");
        }else if($name =="DateTime"){
            $date = date_create_from_format('Y:m:d H:i:s',$val);
            $date = (string)date_format($date, "Y-m-d");
        }
        return true;
    } else if ($name == "tag") {
        $tag = $val;
        return true;
    } else if ($name == "stedsby") {
        $by = $val;
        return true;
    } else if ($name == "land") {
        $land = $val;
        return true;
    } else if ($name == "album") {
        $album = $val;
        return true;
    } else if ($name == "rating") {
        $rating = $val;
        return true;
    } else if ($name == "hendelse") {
        $hendelse = $val;
        return true;
    }else{
        return false;
    }
}
