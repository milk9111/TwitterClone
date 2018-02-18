<?php

include ("../connection.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $psw = $_POST['password'];
    $obj = new Connection();
    $pdo = $obj->connect();

    if ($pdo['status'] == 100) {
        echo $pdo['response'];
    } else {
        $conn = $pdo['conn'];

        $sql = "SELECT * FROM users WHERE username = :user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user", $user);
        $result = $stmt->execute();

        if (!$result) {
            echo json_encode(array('status'=>125)); //The initial query for checking if the user exists failed.
        } else {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($row)) { //If the query is empty, that means there isn't a user with that name
                $sql = "INSERT INTO users (username, password) VALUES (:user, :psw)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":user", $user);
                $stmt->bindParam(":psw", $psw);
                $result = $stmt->execute();
                if (!$result) {
                    echo json_encode(array('status' => 150)); //Inserting the new user failed
                } else {
                    echo json_encode(array('status' => 200)); //Inserting the new user succeeded
                }
            } else {
                echo json_encode(array('status'=>100)); //That user already exists
            }
        }
    }
    $conn = null;
} else {
    echo "Username and/or Password are empty!";
}