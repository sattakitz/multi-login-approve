<?php

session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}
include("connect/connect.php");
include_once('functions/category-function.php');
include_once('functions/tag-function.php');

$categoryFn = new categoryFunction();
$categoryList = $categoryFn->getAllCategory();
$tagFn = new tagFunction();
$tagList = $tagFn->getAllTag();
$userRole = $_SESSION['role'];

$fields = array(
    "file1" => "File 1:",
    // "file2" => "File 2:",
);

if (isset($_POST['submit'])) {
    if (
        !empty($_POST["title"]) &&
        !empty($_POST["description"]) &&
        !empty($_POST["category"]) &&
        !empty($_POST["keyword"]) &&
        !empty($_POST["description_seo"])
    ) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $keyword = $_POST['keyword'];
        $description_seo = $_POST['description_seo'];
        $url = $_POST['title'];
        $user_id = $_SESSION['userid'];
        $status = '1';

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

        $strSQL = "INSERT INTO articles
        (topic_name,
        descripion,
        category_id,
        descripion_seo,
        keyword_seo,
        url_articles_seo,
        user_id,
        status,
        view)
        VALUES
        ('" . $title . "',
        '" .  $description . "',
        '" . $category . "',
        '" . $description_seo . "',
        '" . $keyword . "',
        '" . $url . "',
        '" . $user_id . "',
        '" . $status . "',
        '" . 0 . "')";

        $articleResult = mysqli_query($conn, $strSQL);
        $article_id = '';
        if ($articleResult) {
            $article_id = mysqli_insert_id($conn);
        }
        // $last_id = mysqli_query
        if ($article_id) {
            foreach ($fields as $img => $value) {
                if ($_FILES[$img]['name']) {
                    $file = $_FILES[$img]['tmp_name'];
                    $file_name = $_FILES[$img]['name'];
                    $file_name_array = explode(".", $file_name);
                    $extension = end($file_name_array);
                    $new_image_name = rand() . '.' . $extension;
                    chmod('uploads/article-img/', 0777);
                    $actual_link = "http://$_SERVER[HTTP_HOST]";

                    $allowed_extension = array("jpg", "gif", "png");
                    if (in_array($extension, $allowed_extension)) {
                        move_uploaded_file($file, 'uploads/article-img/' . $new_image_name);
                        $imgSql = "UPDATE articles SET image_banner = '$new_image_name' WHERE id = '$article_id'";
                        $imgResult = mysqli_query($conn, $imgSql);

                        if ($imgResult) {
                            echo '<script language="javascript">';
                            echo 'alert("Create article success")';
                            echo '</script>';
                            echo "<script>window.location.href='article.php';</script>";
                        }
                    }
                } else {
                    echo '<script language="javascript">';
                    echo 'alert("Create article success no img")';
                    echo '</script>';
                    echo "<script>window.location.href='article.php';</script>";
                }
            }

            foreach ($_POST['tag_id'] as $tag_id) {
                $save_tag = "INSERT INTO tag_log (tag_id,articles_id,create_by) VALUES ('$tag_id','$article_id ','$user_id')";
                $save_query = mysqli_query($conn, $save_tag) or die("error in query:$save_tag" . mysqli_error($conn));
            }
        }
    } else {
        echo '<script language="javascript">';
        echo 'alert("Please fill data")';
        echo '</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Create article</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <script src="vendor/ckeditor/ckeditor.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include('./components/sidebar.php') ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include('./components/topbar.php') ?>

                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Create article</h1>
                    <form class="user" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card shadow mb-4">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control form-control-user" name="title" required>
                                        </div>
                                        <div class="form-group">
                                            <textarea id="description" placeholder="Description" class="ckeditor" name="description"></textarea>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label>Keyword</label>
                                            <input type="text" class="form-control form-control-user" name="keyword" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text" class="form-control form-control-user" name="description_seo" required>
                                        </div>
                                        <button type="submit" name="submit" id="submit" class="btn btn-primary btn-user btn-block">
                                            Create article
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Image banner
                                                <span class="text-danger">*</span>
                                            </label>
                                            <br>
                                            <?php
                                            foreach ($fields as $field => $value) {
                                            ?>
                                                <div class="show-file mb-3" <?php echo "id='show-$field'"; ?>>
                                                    <img class="show-image" src="img/no-image.jpg" alt="">
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" <?php echo "name='$field' id='$field'"; ?> class="custom-file-input mb-2" required accept=".jpg, .png" onchange="readURL(this)">
                                                    <label class="custom-file-label text-ellipsis" <?php echo "for='$field' id='label-$field'"; ?>>Choose
                                                        file...</label>
                                                    <button type="button" class="btn btn-danger btn-user btn-block" <?php echo "id='btn-$field'"; ?> onclick="deleteImage(this)">Delete
                                                        image</button>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="col">
                                                <div id="image-error"> </div>
                                            </div>
                                        </div>
                                        <hr class="mt-5">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select type="text" class="form-control" name="category">
                                                <?php
                                                while ($category = $categoryList->fetch_assoc()) {
                                                ?>
                                                    <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Tag</label>
                                            <select class="form-control" multiple="multiple" name="tag_id[]" id="tag">
                                                <?php
                                                while ($tag = $tagList->fetch_assoc()) {
                                                ?>
                                                    <option value="<?php echo $tag['id'] ?>"><?php echo $tag['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; ฉันไม่สามารถหยุดเปล่งประกายได้เลย <?php echo date("Y"); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="functions/logout.php">Logout</a>

                </div>
            </div>
        </div>
    </div>

</body>


<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    // ! ckediter
    CKEDITOR.replace('description', {
        height: "500px",
        language: 'th',
        filebrowserUploadMethod: 'form',
        filebrowserUploadUrl: "functions/upload-img.php",
        extraPlugins: 'contents',
    });

    function readURL(input) {
        const allowType = ['jpg', 'jpeg', 'png'];

        const imgErrEl = document.getElementById('image-error');
        imgErrEl.innerHTML = '';

        const Element = document.getElementById('show-' + input.id);
        const lebelEl = document.getElementById('label-' + input.id);
        Element.innerHTML = '';
        lebelEl.innerHTML = 'Choose file';

        if (input.files && input.files[0]) {

            const file = input.files[0];
            const fileType = file.type;
            if (allowType.find(type => fileType.includes(type))) {
                lebelEl.innerHTML = file.name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgEl = document.createElement('img');

                    imgEl.src = e.target.result;
                    imgEl.className = 'show-image';
                    Element.appendChild(imgEl);
                }
                reader.readAsDataURL(file);

            } else {
                const errorEl = document.createElement('div');
                errorEl.className = 'alert-danger p-2 mb-3';
                errorEl.innerHTML = 'File type is not correct.';
                imgErrEl.appendChild(errorEl);
            }
        }
    }

    function deleteImage(btn) {
        const id = btn.id.split('-')[1];

        const inputFile = document.getElementById(id);
        const labelEl = document.getElementById('label-' + id);
        const showImgEl = document.getElementById('show-' + id);
        const imgEl = document.createElement('img');

        inputFile.value = '';
        labelEl.innerHTML = 'Choose file';
        showImgEl.innerHTML = '';
        imgEl.src = "img/no-image.jpg";
        imgEl.className = 'show-image';
        showImgEl.appendChild(imgEl);
    }
</script>

<script>
    $(function() {
        //Initialize Select2 Elements
        $('#tag').select2({
            placeholder: "Select Tag",
            allowClear: true,
        })

    })
</script>

</html>