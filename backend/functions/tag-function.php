<?php
// $actual_link = "http://$_SERVER[HTTP_HOST]";
// include_once("$actual_link/conn/connect.php");
// include_once("../../conn/connect.php");
include_once("db.php");

// include_once("../conn/connect.php");
class tagFunction extends connectDB
{
    public function countTag()
    {
        $sql = "SELECT COUNT(id) FROM tag";
        return $this->conn->query($sql);
    }
    public function getAllTag()
    {
        $sql = "SELECT * FROM tag ORDER BY id ASC";
        return $this->conn->query($sql);
    }

    public function getTagPage($limit)
    {
        $sql = "SELECT * FROM tag ORDER BY id DESC $limit";
        return $this->conn->query($sql);
    }
}
