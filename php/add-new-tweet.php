<?php

include("../connection.php");

//Check if the parameters are set.
if (isset($_GET['user']) && isset($_GET['text'])) {
    $obj = new Connection();
    $pdo = $obj->connect(); //connect to the db

    if ($pdo['status'] == 100) { //check if the connection failed
        echo $pdo['response'];
    } else {
        $conn = $pdo['conn'];
        $sql = "INSERT INTO tweets (username, text) VALUES (:user, :text)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { //check that the prepared statment failed
            echo "\nPDO::errorInfo():\n";
            print_r($conn->errorInfo());
        }

        $stmt->bindValue(':user', $_GET['user']);
        $stmt->bindValue(':text', $_GET['text']);
        $result = $stmt->execute();
        if ($result == false) { //check if the execute failed
            echo "Failed to add tweet";
        } else {
            echo "Successfully added tweet";
        }
        $conn = null;
    }
} else {
    echo 'Incorrect parameter(s)';
}
