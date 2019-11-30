<?php

try {
    $name = htmlspecialchars($_REQUEST["name"]);
    $servername = "sql290.main-hosting.eu";
    $username = "u117204720_ebenezerv99";
    $password = "45ebi1999";
    $dbname = "u117204720_ebenezerv99";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "INSERT INTO quiz VALUES (null,'" . $name . "', 0,null);";
    $conn->query($sql);
    $sql = "SELECT LAST_INSERT_ID() as serial;";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row["serial"];
        }
    }

    $conn->close();
} catch (Exception $err) {
    echo $err;
}