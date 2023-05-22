<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}

include "../connect/connect.php";

$podid = $_GET['id'];


if (@$_GET['id']) {

    $sql = "SELECT * FROM podcasts where id = '" . $podid . "' ";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $location = $row['location'];
        $pimg = $row['image_podcast'];

        $file_to_delete = '../uploads/podcasts/' . $location;
        $file_to_delete1 = '../uploads/podcast-img/' . $pimg;
        unlink($file_to_delete);
        unlink($file_to_delete1);
    }

    $strSQL = "DELETE FROM podcasts WHERE id='$podid'";
    $tagResult = mysqli_query($conn, $strSQL);
}

if ($tagResult) {
    echo '<script language="javascript">';
    echo 'alert("Delete podcast success")';
    echo '</script>';
    echo "<script>window.location.href='../podcast.php';</script>";
}
