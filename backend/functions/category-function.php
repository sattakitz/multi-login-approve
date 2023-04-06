<?php
include_once("db.php");
class categoryFunction extends connectDB
{
    public function countCategory()
    {
        $sql = "SELECT COUNT(id) FROM category";
        return $this->conn->query($sql);
    }
    public function getAllCategory()
    {
        $sql = "SELECT * FROM category ORDER BY id ASC";
        return $this->conn->query($sql);
    }

    public function getCategoryPage($limit)
    {
        $sql = "SELECT * FROM category ORDER BY id DESC $limit";
        return $this->conn->query($sql);
    }
}
