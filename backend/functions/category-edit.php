<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}
include "../connect/connect.php";

if (!empty($_POST["name"])) {
    $name = $_POST['name'];
    $id = $_POST['id'];
    $strSQL = "UPDATE category SET name='$name' WHERE id='$id'";
    $categoryResult = mysqli_query($conn, $strSQL);
    if ($categoryResult) {
        echo '<script language="javascript">';
        echo 'alert("Edit category success")';
        echo '</script>';
        echo "<script>window.location.href='../category.php';</script>";
    }
} else {
    echo '<script language="javascript">';
    echo 'alert("Please fill data")';
    echo '</script>';
    echo "<script>window.location.href='../category.php';</script>";
}
