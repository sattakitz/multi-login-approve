<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}
include "../connect/connect.php";

$id = $_GET['id'];

$strSQL = "DELETE FROM category WHERE id='$id'";
$categoryResult = mysqli_query($conn, $strSQL);
if ($categoryResult) {
    echo '<script language="javascript">';
    echo 'alert("Delete category success")';
    echo '</script>';
    echo "<script>window.location.href='../category.php';</script>";
}
