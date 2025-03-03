<?php
$mysqli = new mysqli("mysql", "user", "password", "mydb");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$result = $mysqli->query("SELECT * FROM test_table");
while ($row = $result->fetch_assoc()) {
    echo "<p>" . htmlspecialchars(json_encode($row)) . "</p>";
}
$mysqli->close();
?>