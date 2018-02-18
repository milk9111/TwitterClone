<?php
include("../connection.php");

if ((isset($_POST['username']) && isset($_POST['password']))) {
    $user = $_POST['username'];
    $psw = $_POST['password'];

    $obj = new Connection();
    $pdo = $obj->connect();

    if ($pdo['status'] == 100) {
        echo $pdo['response'];
    } else {
        $conn = $pdo['conn'];
        $sql = "SELECT password FROM users WHERE username = :user"; //get the password for the user if they exist
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user', $user);
        $result = $stmt->execute();

        if (!$result) {
            echo json_encode(array("status"=>125)); //if the query failed
        } else {
            $empty = true;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if($row['password'] == $psw){
                    echo json_encode(array("status"=>200)); //if the password matched
                    exit();
                }else {
                    echo json_encode(array("status"=>100)); //if the password didn't match
                }
                $empty = false;
            }
            if ($empty) {
                echo json_encode(array("status"=>150)); //if there wasn't a user matching that username
            }
        }
        $conn = null;
    }
} else {
    echo "Username or Password is empty!";
}


