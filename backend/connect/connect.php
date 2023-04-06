<?php
$conn = mysqli_connect("localhost", "root", "", "project_news") or die("Error server");
// $con = mysqli_connect("localhost", "xnzwfbsi_123_bet", "123_bet", "xnzwfbsi_123_bet_org") or die("Error server");

class connectDB
{
    public $conn;
    // private $hostName = "localhost";
    // private $userName = "xnzwfbsi_123_bet";
    // private $password = "123_bet";
    // private $dbName = "xnzwfbsi_123_bet_org";
    private $hostName = "localhost";
    private $userName = "root";
    private $password = "";
    private $dbName = "project_news";

    function __construct()
    {
        $this->conn = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName);
        $this->conn->set_charset("utf8");
        if (!$this->conn) {
            die("Connection failed" . mysqli_connect_error());
        }
    }
}
