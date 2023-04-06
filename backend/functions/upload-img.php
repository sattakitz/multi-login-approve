<?php
if (isset($_FILES['upload']['name'])) {
    if ($_FILES["upload"]["size"] > 2048000) {
        echo '<script language="javascript">';
        echo 'alert("Sorry, your image is too large.")';
        echo '</script>';
    } else {

        $file = $_FILES['upload']['tmp_name'];
        $file_name = $_FILES['upload']['name'];
        $file_name_array = explode(".", $file_name);
        $extension = end($file_name_array);
        $new_image_name = rand() . '.' . $extension;
        chmod('upload', 0777);
        $actual_link = "http://$_SERVER[HTTP_HOST]";

        $allowed_extension = array("jpg", "gif", "png");
        if (in_array($extension, $allowed_extension)) {
            move_uploaded_file($file, '../uploads/article-img/' . $new_image_name);
            $function_number = $_GET['CKEditorFuncNum'];
            $url = $actual_link . '/backend/uploads/article-img/' . $new_image_name;
            // $url = $actual_link . '/backend/backend/uploads/article-img/' . $new_image_name;
            $message = '';
            echo '<script>';
            echo 'window.parent.CKEDITOR.tools.callFunction("' . $function_number . '","' . $url . '","' . $message . '")';
            echo '</script>';
        }
    }
}
