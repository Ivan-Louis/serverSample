<?php
/**
 * Created by PhpStorm.
 * User: Louis
 * Date: 15.10.2015
 * Time: 19:05
 */
require("mysqlConfig.php");

$conn = new mysqli_connect($db_server, $db_username, $db_password, $db_database);

if (!$conn) {  # Sjekker om du klarer og koble deg til serveren. Viss ikke gis feilmelding
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully <br>";

#Post for å hente evt finne verdier som trengs
$search = isset($_POST['search']) ? $_POST['search'] : "";

# Dette er et søk, men kan brukes til f.eks til å lagre istede for
$sql = "SELECT * FROM Informasjon WHERE noe = " . "'$search'"; 

#selve utførselen av søket og returnerer funn
$stmt = $conn->prepare($sql);
$stmt->bind_result($id,$tag,$image);#bind parametre du får ut. Ved * bør du binde alle parametre.
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
   	echo "Foelgende treff paa " . $search . ": <br>";
   	while($row = mysqli_fetch_assoc($result)) {
       	echo $row['rowname'] . "<br>";
   	}
} else {
   	echo " <br> Ingen treff paa " . $search . "<br>";
   	$row = mysqli_fetch_assoc($result); 
   	echo $row['rowname'];
}

#hente verdier til innsetning
$tag = isset($_POST['tag']) ? $_POST['tag'] : "";
$stedsby = isset($_POST['stedsby']) ? $_POST['stedsby'] : "";
$land = isset($_POST['land']) ? $_POST['land'] : "";
$dato = isset($_POST['dato']) ? $_POST['dato'] : "";
$album = isset($_POST['album']) ? $_POST['album'] : "";
$rating = isset($_POST['rating']) ? $_POST['rating'] : "";
$hendelse = isset($_POST['hendelse']) ? $_POST['hendelse'] : "";
$thumbnail = isset($_POST['thumbnail']) ? $_POST['thumbnail'] : "";
$image = isset($_POST['image']) ? $_POST['image'] : "";
$imagetype = isset($_POST['imagetype']) ? $_POST['imagetype'] : "";
#Dette er en insetting/lagring av en fil der alle verdier er med
$sql = "INSERT INTO informasjon(`image`, `thumbnail`, `album`,`tag`, `stedsby`, `land`, `dato`, `rating`, `hendelse`, `imagetype`) 
    VALUES ('$image' , '$thumbnail', '$album', '$tag', '$stedsby', '$land', '$dato', '$rating', '$hendelse', '$imagetype')";
    if (mysqli_query($conn, $sql) === TRUE) {
        echo " <br> New record created successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$stmt->store_result();
#Dette er en oppdatering av innslag i DB.
$id = isset($_POST['ID']) ? $_POST['ID'] : "";
$sql = "UPDATE informasjon SET image=$image, thumbnail=$thumbnail, album=$album, tag=$tag, stedsby=$stedsby, land=$land,
                            dato=$dato, rating=$rating, hendelse=$hendelse, imagetype=$imagetype
                            WHERE ID=$id";
if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$stmt->store_result();    
    
#Lukker tilkoblingen
mysqli_close($conn);
