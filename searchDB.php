<?php

function searchString($str) {
	$strArray = [];
	$strArray = explode(":", $str);
	return $strArray;
}

function searchDB2 ($conn, $search) {
	$search = searchString($search);

	$sql = "SELECT ID, tag, stedsby, land, dato, album, rating, hendelse
              FROM informasjon WHERE ";

	for($i = 0; $i < sizeof($search); $i++) {
		if(is_numeric($search[$i]) == true ) {
			if(strlen($search[$i]) == 1){
				$sql .= "`rating` = $search[$i] AND ";
			}
			else {
				$sql .= "`dato` LIKE '%$search[$i]%' AND ";
		}
		}else {
			$sql .= "(`tag` LIKE '%$search[$i]%' OR `stedsby` LIKE '%$search[$i]%' OR `land` LIKE
			'%$search[$i]%' OR `album` LIKE '%$search[$i]%' OR `hendelse` LIKE '%$search[$i]%' OR `dato` LIKE '%$search[$i]%') AND ";
		}
	}
	$sql = substr($sql, 0, strlen($sql) - 4);

	$ID = 0;
	if (!$stmt = $conn->prepare($sql)) {
		echo "Error: " . $conn->error;
	}
	$stmt->bind_result($ID, $tag, $stedsby, $land,$dato , $album, $rating, $hendelse);
	$stmt->execute();
	$stmt->store_result();

	$result = [];
	if ($stmt->num_rows() > 0) {
        while($stmt->fetch()){
            $arr = [
                "id" => $ID,
                "tag" => $tag,
                "by" => $stedsby,
                "land" => $land,
                "dato" => $dato,
                "album" => $album,
                "rating"=> $rating,
                "hendelse"=> $hendelse
            ];
            array_push($result, $arr);
        }
    }
    echo json_encode($result);
	$stmt->close();

}
