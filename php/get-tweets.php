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
            $empty = true;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<li class="list-group-item">' . $row['text'] . '<small class="tweet-date">' . parseDate($row['date']) . '</small></li>';
                $empty = false;
            }
            if ($empty) {
                echo '<li class="list-group-item"> No posts to show! </li>';
            }
        }
        $conn = null;
    }
} else {
    echo 'Incorrect parameter';
}


function parseDate($date) {
    $segments = explode(" ", $date);
    return $segments[0];
}