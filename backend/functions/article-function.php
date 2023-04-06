<?php
// include_once("db.php");
include "db.php";

class articleFunction extends connectDB
{
    public function countArticle()
    {
        $sql = "SELECT COUNT(id) FROM articles";
        return $this->conn->query($sql);
    }
    public function getAllArticle()
    {
        $sql = "SELECT * FROM articles ORDER BY id ASC";
        return $this->conn->query($sql);
    }

    public function getArticlePage($limit)
    {
        $sql = "SELECT * FROM articles ORDER BY `articles`.`create_at` DESC $limit";
        return $this->conn->query($sql);
    }
}
