<?php

function sendMeta($amount, $page, $conn){
    if (!$conn) {  # Sjekker om du klarer og koble deg til serveren. Viss ikke gis feilmelding
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT ID, tag, stedsby, land, dato, album, rating, hendelse
              FROM informasjon ORDER BY ID ASC LIMIT ? OFFSET ?";
    $ID = 0; $tag = ""; $stedsby = ""; $land= ""; $dato=""; $album=""; $rating  =0; $hendelse="";

    if (!$stmt = $conn->prepare($sql)) {
        echo "Error: " . $conn->error;
    }
    if ($page  === 0){
        $offset = 0;
    }else{
        $offset = ($page-1) * $amount;
    }
    $stmt->bind_param('ii', $amount, $offset);
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
    $stmt->close();
    echo json_encode($result);
}