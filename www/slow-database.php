<?php
$servername = "mysql"; // Docker service name
$username = "user";
$password = "password";
$dbname = "mydb";

try {
    // Connect to MariaDB
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table if it doesn't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS test_table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        data VARCHAR(255)
    )");

    $conn->exec("DROP PROCEDURE IF EXISTS insert_slow");

    // Create stored procedure if it doesn't exist
    $createProcedure = "CREATE PROCEDURE insert_slow()
    BEGIN
        DECLARE i INT DEFAULT 0;
        WHILE i < 500 DO
            INSERT INTO test_table (data) VALUES (CONCAT('Test Data ', i));
            DO SLEEP(0.01);  -- Force slow execution
            SET i = i + 1;
        END WHILE;
    END;";
    
    $conn->exec($createProcedure);

    // Run the stored procedure if requested
    if (isset($_POST['run_query'])) {
        $stmt = $conn->prepare("CALL insert_slow()");
        $stmt->execute();
        $message = "Stored procedure executed successfully!";
    }
} catch (PDOException $e) {
    $message = "Connection failed: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MariaDB Slow Query Test</title>
</head>
<body>
    <h2>MariaDB Slow Query Tester</h2>
    <p>Click the button below to execute a slow stored procedure.</p>

    <form method="POST">
        <button type="submit" name="run_query">Run Slow Query</button>
    </form>

    <?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>
</body>
</html>
