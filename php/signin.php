<?php
include("../connection.php");

if ((isset($_POST['username']) && isset($_POST['password'])) || (isset($_GET['username']) && isset($_GET['password']))) {

    if (isset($_POST['username'])) {
        $user = $_POST['username'];
        $psw = $_POST['password'];
    } else {
        $user = $_GET['username'];
        $psw = $_GET['password'];
    }
    $obj = new Connection();
    $pdo = $obj->connect();

    if ($pdo['status'] == 100) {
        echo $pdo['response'];
    } else {
        $conn = $pdo['conn'];
        $sql = "SELECT password FROM users WHERE username = :user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user', $user);
        $result = $stmt->execute();

        if (!$result) {
            echo json_encode(array("status"=>125));
        } else {
            $empty = true;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if($row['password'] == $psw){
                    echo json_encode(array("status"=>200));
                    exit();
                }else {
                    echo json_encode(array("status"=>100));
                }
                $empty = false;
            }
            if ($empty) {
                echo json_encode(array("status"=>150));
            }
        }
        $conn = null;
    }
} else {
    echo "Username or Password is empty!";
}


