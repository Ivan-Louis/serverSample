<?php
/** Method for editing metadata
 *
 */

function editMeta($id, $tag, $stedsby, $land, $dato, $album, $rating, $hendelse, $conn){
    if (!$conn) {  # Sjekker om du klarer og koble deg til serveren. Viss ikke gis feilmelding
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "UPDATE `informasjon` SET ";
    if ($tag != ""){
        $sql .= "`tag`='$tag', ";
        $last = "tag";
    }
    if ($stedsby != ""){
        $sql .= "`stedsby`='$stedsby', ";
        $last = "stedsby";
    }
    if ($land != "") {
        $sql .= "`land`='$land', ";
        $last = "land";
    }
    if ($dato != "") {
        $sql .= "`dato`='$dato', ";
        $last = "dato";
    }
    if ($album != "") {
        $sql .= "`album`='$album', ";
        $last = "album";
    }
    if ($rating != "") {
        $sql .= "`rating`=$rating, ";
        $last = "rating";
    }
    if ($hendelse != "") {
        $sql .= "`hendelse`='$hendelse' ";
        $last = "hendelse";
    }
    if($last != "hendelse"){
        $sql = substr($sql, 0, -2);
        $sql .= " ";
    }
    $sql .= "WHERE ID = $id";
    
    echo "<br>".$sql."<br>";

    if (!$stmt = $conn->prepare($sql)) {
        echo "Error: " . $conn->error;
    }

    $stmt->execute();
    $stmt->store_result();
    $stmt->fetch();

    $stmt->close();
}