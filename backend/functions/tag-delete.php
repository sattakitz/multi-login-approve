<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}

include "../connect/connect.php";

$id = $_GET['id'];

$strSQL = "DELETE FROM tag WHERE id='$id'";
$tagResult = mysqli_query($conn, $strSQL);
if ($tagResult) {
    echo '<script language="javascript">';
    echo 'alert("Delete tag success")';
    echo '</script>';
    echo "<script>window.location.href='../tag.php';</script>";
}
