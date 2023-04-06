<?php
include_once("db.php");
class userFunction extends connectDB
{
    public function countUser()
    {
        $sql = "SELECT COUNT(id) FROM user";
        return $this->conn->query($sql);
    }
    public function getAllUser()
    {
        $sql = "SELECT * FROM user ORDER BY id ASC";
        return $this->conn->query($sql);
    }

    public function getUserPage($limit)
    {
        $sql = "SELECT * FROM user ORDER BY id DESC $limit";
        return $this->conn->query($sql);
    }
}
