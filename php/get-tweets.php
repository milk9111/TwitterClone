<?php

include("../connection.php");

if (isset($_GET['user'])) {
    $user = $_GET['user'];

    $obj = new Connection();
    $pdo = $obj->connect();

    if ($pdo['status'] == 100) {
        echo $pdo['response'];
    } else {
        $conn = $pdo['conn'];
        $sql = "SELECT * FROM tweets WHERE username = :user ORDER BY date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user', $user);
        $result = $stmt->execute();
        if ($result == false) {
            echo "Failed to query";
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<li class="list-group-item">' . $row['username'] . ': ' . $row['text'] . " @ " . $row['date'] . '</li>';
            }
        }
        $conn = null;
    }
} else {
    echo 'Incorrect parameter';
}


/*try {
    $conn = new PDO("pgsql:host=$hostname;port=5432;dbname=$dbname2;user=$username;password=$password");
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM tweets WHERE username = :user";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user', $user);
    $result = $stmt->execute();
    if ($result == false) {
        echo "Failed to query";
    } else {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<li class="list-group-item">' . $row['username'] . ': ' . $row['text'] . " @ " . $row['date'] . '</li>';
        }
    }
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}*/