<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}

include "../connect/connect.php";

if (!empty($_POST["name"])) {
    $name = $_POST['name'];
    $url = $_POST['name'];
    $user_id = $_SESSION['userid'];

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

    $strSQL = "INSERT INTO tag (name,user_id,tag_url) VALUES ('" . $name . "','" . $user_id . "','" . $url . "')";
    $tagResult = mysqli_query($conn, $strSQL);
    $tag_id = '';
    if ($tagResult) {
        $tag_id = mysqli_insert_id($conn);
    }

    if ($tag_id) {
        echo '<script language="javascript">';
        echo 'alert("Create tag success")';
        echo '</script>';
        echo "<script>window.location.href='../tag.php';</script>";
    }
} else {
    echo '<script language="javascript">';
    echo 'alert("Please fill data")';
    echo '</script>';
    echo "<script>window.location.href='../tag.php';</script>";
}
