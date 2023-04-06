<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}
include "../connect/connect.php";

if (!empty($_POST["name"])) {
    $name = $_POST['name'];

    $strSQL = "INSERT INTO category (name) VALUES ('" . $name . "')";
    $categoryResult = mysqli_query($conn, $strSQL);
    $category_id = '';
    if ($categoryResult) {
        $category_id = mysqli_insert_id($conn);
    }

    if ($category_id) {
        echo '<script language="javascript">';
        echo 'alert("Create category success")';
        echo '</script>';
        echo "<script>window.location.href='../category.php';</script>";
    }
} else {
    echo '<script language="javascript">';
    echo 'alert("Please fill data")';
    echo '</script>';
    echo "<script>window.location.href='../category.php';</script>";
}
