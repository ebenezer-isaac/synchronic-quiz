<?php

try {
    $score = htmlspecialchars($_REQUEST["score"]);
    $serial = htmlspecialchars($_REQUEST["serial"]);
    $servername = "sql290.main-hosting.eu";
    $username = "u117204720_ebenezerv99";
    $password = "45ebi1999";
    $dbname = "u117204720_ebenezerv99";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "Update quiz set score = score + " . $score . " where serial = " . $serial . ";";
    if ($conn->query($sql) === TRUE) {
        echo "1";
    } else {
        echo "0";
    }
    $conn->close();
} catch (Exception $err) {
    echo $err;
}