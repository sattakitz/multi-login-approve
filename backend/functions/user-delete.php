<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}

include "../connect/connect.php";

$id = $_GET['id'];
$img = $_GET['img'];

if (@$_GET['img']) {
    $file_to_delete = '../uploads/user-img/' . $img;
    unlink($file_to_delete);
}

$strSQL = "DELETE FROM user WHERE id='$id'";
$articleResult = mysqli_query($conn, $strSQL);
if ($articleResult) {
    echo '<script language="javascript">';
    echo 'alert("Delete user success")';
    echo '</script>';
    echo "<script>window.location.href='../user.php';</script>";
}
