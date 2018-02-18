<?php
include("../connection.php");

print_r($_POST);
print_r($_GET);
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
        if ($result == false) {
            echo "Failed to query";
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if($row['password'] == $psw){
                    echo "Login Successful!";
                    //header('Location: ../html/feed.html');
                    exit();
                }else {
                    echo "Incorrect Username or Password!";
                }
            }
        }
        $conn = null;
    }
} else {
    echo "Username or Password is empty!";
}
?>

