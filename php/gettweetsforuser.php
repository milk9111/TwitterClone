<?php

include ("../credentials.php");

$user = $_GET['user'];
try {
    $conn = new PDO("pgsql:host=$hostname;port=5432;dbname=$dbname2;user=$username;password=$password");
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}