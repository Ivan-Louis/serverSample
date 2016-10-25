<?php

function sorter($inn, $dir, $conn){

	if (!$conn) {  # Sjekker om du klarer og koble deg til serveren. Viss ikke gis feilmelding
    	die("Connection failed: " . mysqli_connect_error());
    }

	if ($dir == 0) {
		$sql = "SELECT ID, tag, stedsby, land, dato, album, rating, hendelse FROM informasjon ORDER BY $inn ASC";
	} else {
		$sql = "SELECT ID, tag, stedsby, land, dato, album, rating, hendelse FROM informasjon ORDER BY $inn DESC";
	}
    $ID = 0; $tag = ""; $stedsby = ""; $land= ""; $dato=""; $album=""; $rating  =0; $hendelse="";


    if (!$stmt = $conn->prepare($sql)) {
        echo "Error: " . $conn->error;
    }

    $stmt->execute();

    $stmt->bind_result($ID, $tag, $stedsby, $land,$dato , $album, $rating, $hendelse);
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
        #echo "Result <br>";
        #echo $page;
    }
    echo json_encode($result);
    $stmt->close();
}
