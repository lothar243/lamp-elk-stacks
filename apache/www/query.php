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

    // Run the SELECT query
    $stmt = $conn->prepare("SELECT * FROM test_table");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch data as an associative array

    $message = "Query executed successfully!";
} catch (PDOException $e) {
    $message = "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MariaDB Query Test</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>MariaDB Query Results</h2>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <?php if (!empty($results)) : ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Data</th>
            </tr>
            <?php foreach ($results as $row) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['data']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No data found.</p>
    <?php endif; ?>
</body>
</html>
