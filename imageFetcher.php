<?php
/**Function
 *  Takes in the value to return (image or thumbnail), the id of the return and the sqli with the params for the db
 */
function sendImage($return, $id, $conn){
    if (!$conn) {  # Sjekker om du klarer og koble deg til serveren. Viss ikke gis feilmelding
        die("Connection failed: " . mysqli_connect_error());
    }
    switch ($return) {
        case "image":
            $sql = "SELECT image, imagetype FROM informasjon WHERE ID = ?";
            break;
        case "thumb":
            $sql = "SELECT thumbnail, imagetype FROM informasjon WHERE ID = ?";
            break;
        default:
            echo "Wrong return parameter, either image or thumb";
            break;
    }
    $image = "";
    $image_type = "";

    if (!$stmt = $conn->prepare($sql)) {
        echo "Error: " . $conn->error;
    }
    $stmt->bind_param('i', $id);
    $stmt->bind_result($image, $image_type);
    $stmt->execute();
    $stmt->store_result();
    $stmt->fetch();
    if ($stmt->num_rows() > 0) {
        //$img = base64_decode($image); // denne decodingen vil mest sannsynligvis være best å gjøre på klienten, men må testes
        $stmt->close();
        header("Content-type: image/" . $image_type);
        echo $image;
    }else {
        $num = $stmt->num_rows();
        $stmt->close();
        echo $num;
    }
}
