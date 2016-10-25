<?php

$db_server = "127.0.0.1"; #disse 4 må kanskje forandres etter hvilke konfigurasjon du har localt
$db_username = "root";
$db_password = "";
$db_database = "dat210_gruppe_b";

$conn = new mysqli($db_server, $db_username, $db_password, $db_database);