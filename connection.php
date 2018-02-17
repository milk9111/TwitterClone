<?php

class Connection{

    private $hostname = "ec2-174-129-221-240.compute-1.amazonaws.com";
    private $dbname = "public";
    private $dbname2 = "d75ohve8ql27th";
    private $username = "axmmwxhxbxaqfs";
    private $password = "2a721916a0cb42a96a4e7b6f9e8557ee33aa97ba19e9713e883d900d0d1e9ed6";

    public function connect () {
        try {
            $conn = new PDO("pgsql:host=$this->hostname;port=5432;dbname=$this->dbname2;user=$this->username;password=$this->password");

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return array('status'=>200, 'conn'=>$conn);
        }
        catch(PDOException $e) {
            return array('status'=>100, 'response'=>"Connection failed: " . $e->getMessage());
        }
    }
}
