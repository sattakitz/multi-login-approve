<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}

include "../connect/connect.php";

if (!empty($_POST["name"])) {
    $name = $_POST['name'];
    $id = $_POST['id'];
    $url = $_POST['name'];

    function to_pretty_url($url)
    {
        if ($url !== mb_convert_encoding(mb_convert_encoding($url, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
            $url  = mb_convert_encoding($url, 'UTF-8', mb_detect_encoding($url));
        $url  = htmlentities($url, ENT_NOQUOTES, 'UTF-8');
        $url  = preg_replace('`&([ก-เ][a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $url);
        $url  = html_entity_decode($url, ENT_NOQUOTES, 'UTF-8');
        $url  = preg_replace(array('`[^a-z0-9ก-เ]`i', '`[-]+`'), '-', $url);
        $url  = strtolower(trim($url, '-'));
        return $url;
    }

    $url = to_pretty_url($url);

    $strSQL = "UPDATE tag SET name='$name',tag_url='$url' WHERE id='$id'";

    $tagResult = mysqli_query($conn, $strSQL);
    if ($tagResult) {
        echo '<script language="javascript">';
        echo 'alert("Edit tag success")';
        echo '</script>';
        echo "<script>window.location.href='../tag.php';</script>";
    }
} else {
    echo '<script language="javascript">';
    echo 'alert("Please fill data")';
    echo '</script>';
    echo "<script>window.location.href='../tag.php';</script>";
}
