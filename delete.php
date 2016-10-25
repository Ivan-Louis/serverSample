<?php

function deleteImage($id, $conn){
    if (!$conn) {  # Sjekker om du klarer og koble deg til serveren. Viss ikke gis feilmelding
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "DELETE FROM `informasjon` WHERE ID =? ";

    if (!$stmt = $conn->prepare($sql)) {
        echo "Error: " . $conn->error;
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->fetch();
    if($stmt->affected_rows > 0){
        echo "Delete succeeded";
    }else{
        echo "Delete failed";
    }
    $stmt->close();
}

function countImages($conn){
    $am = 0;
    $sql = "SELECT COUNT(*) FROM informasjon";
    if (!$stmt = $conn->prepare($sql)) {
        echo "Error: " . $conn->error;
    }
    $stmt->bind_result($am);
    $stmt->execute();
    $stmt->store_result();
    $stmt->fetch();
    if($am > 0){
        echo $am;
    }else{
        echo "0";
    }
    $stmt->close();
}