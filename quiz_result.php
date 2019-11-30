<?php

try {
    $servername = "sql290.main-hosting.eu";
    $username = "u117204720_ebenezerv99";
    $password = "45ebi1999";
    $dbname = "u117204720_ebenezerv99";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT name, score FROM `quiz` order by score desc;";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<div align='center' class='table-responsive-xl ' ><table width = '70%' class='table table-bordered' style='text-align:center;'><thead class='thead-dark'><tr><th>Name</th><th>Score</th></tr></thead><tbody>";
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $count = $count + 1;
            $highligter = "<tr>";
            if ($count < 4) {
                switch ($count) {
                    case 1: $highligter = "<tr class = 'table-success'>";
                        break;
                    case 2: $highligter = "<tr class = 'table-warning'>";
                        break;
                    case 3: $highligter = "<tr class = 'table-warning'>";
                        break;
                }
            }
            echo $highligter."<td>" . $row["name"] . "</td><td>" . $row["score"] . "</td></tr>";
        }
        echo "</tbody></table><div>";
    } else {
        echo "No Data Found";
    }
    $conn->close();
} catch (Exception $err) {
    echo $err;
}