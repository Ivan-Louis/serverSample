<?php

function uploadImage() {
    global $conn;
    $picturePath = "";
    try {
        /*
        if (
        !isset($_FILES['newPicture']['error']) ||
        is_array($_FILES['newPicture']['error'])
        ) {
            throw new RuntimeException('Invalid parameters');
        }
        */

        switch ($_FILES['newPicture']['error']) {
            case UPLOAD_ERR_OK:
                break;

            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');

            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');

            default:
                throw new RuntimeException('Unknown errors.');
        }

        if ($_FILES['newPicture']['size'] > 10000000) {
            throw new RuntimeException('Exceeded filesize limit.');
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($_FILES['newPicture']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ),
            true
        )) {
            throw new RuntimeException('Invalid file format.');
        }
        $picturePath = sprintf('./uploads/%s.%s',
            sha1_file($_FILES['newPicture']['tmp_name']),
            $ext
        );
        if (!move_uploaded_file(
        $_FILES['newPicture']['tmp_name'],
        $picturePath
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
        }
        
        insertNewImage($picturePath, $conn);
        #findMeta($picturePath);
        echo ' File is uploaded successfully.';
    } catch (RuntimeException $e) {
        echo $e->getMessage();
    }       
    //echo $picturePath;
}

function insertNewImage($path, $conn){
    if (!$conn) {  # Sjekker om du klarer og koble deg til serveren. Viss ikke gis feilmelding
        die("Connection failed: " . mysqli_connect_error());
    }
    echo "Connected successfully <br>";

    $handle = fopen($path, "rb"); #tester etter et bilde i samme mappe som koden
    $img = fread($handle, filesize($path));
    $imgtype = exif_imagetype($path);
    fclose($handle);

    switch ($imgtype) {
        case '1':
            $imgtype = "gif";
            break;
        case '2':
            $imgtype = "jpeg";
            break;
        case '3':
            $imgtype = "png";
            break;
        case '7':
            $imgtype = "tiff";
            break;
        case '8':
            $imgtype = "tiff";
            break;
    }
    $thumbnail = makeThumbnail($img, $imgtype); // lager thumbnail

    $img = base64_encode($img); // denne decodingen vil mest sannsynligvis være best å gjøre på klienten, men må testes!
    $sql = "INSERT INTO informasjon(`image`, `imagetype`, `thumbnail`)
		VALUES ('$img', '$imgtype', '$thumbnail')";

    if (!$stmt = $conn->prepare($sql)) {
        echo 'Error: ' . $conn->error;
        die();
    }
    #$stmt->bind_param("bs", $img, $imgtype); -> fix this into a completely prepared statement
    $stmt->execute();
    $stmt->store_result();

    $stmt->close();
    echo "Success! You have inserted your picture!";
}

