<?php

session_start();

$userRole = $_SESSION['role'];

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}
include("connect/connect.php");
include_once('functions/category-function.php');

$categoryFn = new categoryFunction();
$categoryList = $categoryFn->getAllCategory();

$fields = array(
    "file1" => "File 1:"
);

if (isset($_POST['but_upload'])) {
    $maxsize = 20971520; // 20MB

    $title = $_POST['title'];
    $category = $_POST['category'];

    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        $name = $_FILES['file']['name'];
        $file = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_name_array = explode(".", $file_name);
        $extension = end($file_name_array);
        $new_image_name = rand() . '.' . $extension;
        $target_dir = "uploads/podcasts/";
        $target_file = $target_dir . $new_image_name;
        $actual_link = "http://$_SERVER[HTTP_HOST]";

        // Valid file extensions
        $extensions_arr = array("mp3", "avi", "3gp", "mov", "mpeg");

        // Check extension
        if (in_array(strtolower($extension), $extensions_arr)) {

            // Check file size
            if ($_FILES['file']['size'] >= $maxsize || $_FILES["file"]["size"] == 0) {
                $_SESSION['message'] = "File too large. File must be less than 20MB.";
            } else {
                // Upload
                if (move_uploaded_file($file, $target_file)) {
                    // Insert record                    

                    $query = "INSERT INTO podcasts (name, location, title, category_id) VALUES ('" . $name . "', '" . $new_image_name . "', '" . $title . "','" . $category . "')";
                    mysqli_query($conn, $query);
                    $_SESSION['message'] = "Upload successfully.";

                    $Podcast_id = mysqli_insert_id($conn);

                    foreach ($fields as $img => $value) {
                        if (isset($_FILES[$img]['name']) && $_FILES[$img]['name'] != '') {
                            $fileImg = $_FILES[$img]['tmp_name'];
                            $file_nameImg = $_FILES[$img]['name'];
                            $file_name_arrayImg = explode(".", $file_nameImg);
                            $extensionImg = end($file_name_arrayImg);
                            $new_image_nameImg = rand() . '.' . $extensionImg;
                            $target_dirImg = "uploads/podcast-img/";
                            $target_fileImg = $target_dirImg . $new_image_nameImg;

                            // Valid image extensions
                            $allowed_extensions = array("jpg", "gif", "png");

                            if (in_array(strtolower($extensionImg), $allowed_extensions)) {
                                if (move_uploaded_file($fileImg, $target_fileImg)) {
                                    $imgSql = "UPDATE podcasts SET image_podcast = '$new_image_nameImg' WHERE id = '$Podcast_id'";
                                    $imgResult = mysqli_query($conn, $imgSql);

                                    if ($imgResult) {
                                        echo '<script language="javascript">';
                                        echo 'alert("Create image podcast success")';
                                        echo '</script>';
                                    } else {
                                        echo '<script language="javascript">';
                                        echo 'alert("Failed to update image podcast")';
                                        echo '</script>';
                                    }
                                } else {
                                    echo '<script language="javascript">';
                                    echo 'alert("Error uploading image podcast")';
                                    echo '</script>';
                                }
                            } else {
                                echo '<script language="javascript">';
                                echo 'alert("Invalid image file extension")';
                                echo '</script>';
                            }
                        }
                    }
                } else {
                    $_SESSION['message'] = "Error uploading file.";
                }
            }
        } else {
            $_SESSION['message'] = "Invalid file extension.";
        }
    } else {
        $_SESSION['message'] = "Please select a file.";
    }

    header('location: podcast.php');
    exit;
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

    <title>Podcasts</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <style>
        .custom-file-button input[type=file] {
            margin-left: -2px !important;
        }

        .custom-file-button input[type=file]::-webkit-file-upload-button {
            display: none;
        }

        .custom-file-button input[type=file]::file-selector-button {
            display: none;
        }

        .custom-file-button:hover label {
            background-color: #dde0e3;
            cursor: pointer;
        }
    </style>
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
                    <div class="d-flex justify-content-between">
                        <h1 class="h3 mb-4 text-gray-800">Podcasts</h1>
                        <div>
                            <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Create Podcasts</span>
                            </a>
                        </div>
                    </div>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <!-- Upload response -->
                            <?php
                            if (isset($_SESSION['message'])) {
                                // echo $_SESSION['message'];
                                echo '<script language="javascript">';
                                echo 'alert("' . $_SESSION['message'] . '")';
                                echo '</script>';
                                unset($_SESSION['message']);
                            }
                            ?>
                            <div class="row align-items-center">

                                <?php
                                $fetchpodcasts = mysqli_query($conn, "SELECT * FROM podcasts ORDER BY id DESC");
                                while ($row = mysqli_fetch_assoc($fetchpodcasts)) {
                                    $location = $row['location'];
                                    $Podidname = $row['title'];
                                    $Pimg = $row['image_podcast'];
                                    $PName = $row['name'];
                                    $Podid = $row['id'];
                                ?>
                                    <div class="col-3">
                                        <div class="card text-center d-flex justify-content-center">
                                            <h4><?php echo $Podidname; ?></h4>
                                            <div class="card-body">
                                                <img src="uploads/podcast-img/<?php echo $Pimg; ?>" alt="<?php echo $Podidname; ?>" class="img-fluid card__image" width="250">
                                                <audio controls height='320px'>
                                                    <source src="uploads/podcasts/<?php echo $location; ?>" type="audio/ogg">
                                                    <source src="uploads/podcasts/<?php echo $location; ?>" type="audio/mpeg">
                                                </audio>
                                                <small><?php echo $PName ?></small>
                                            </div>
                                            <div class="card-footer text-muted">
                                                <?php if ($userRole == '1') { ?>
                                                    <a href="#delModal" class="btn btn-danger btn-circle trash" data-id="<?php echo $row['id'] ?>" role="button" data-toggle="modal" data-name="<?php echo $row['name'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>



                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
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

    <!-- modal create -->
    <div class="modal small fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- <form> -->
                <form method="post" action="" enctype='multipart/form-data'>
                    <div class="modal-header">
                        <h3>Create Podcasts</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <label for="formlabelTitle" class="form-label">Title</label>
                        <input type='text' name='title' class="form-control" placeholder="กรอกไตเติล" />
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
                        <!-- <input type='file' name='file' class="btn" /> -->
                        <div class="input-group custom-file-button">
                            <label class="input-group-text" for="inputGroupFile">เลือกไฟล์ MP3</label>
                            <input type='file' name='file' class="form-control" id="inputGroupFile" accept="audio/mp3,audio/*;capture=microphone">
                        </div>
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
                        <div id="image-error"> </div>
                    </div>
                    <div class="modal-footer">
                        <input type='submit' value='Upload' class="btn btn-outline-primary" name='but_upload'>
                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    </div>
                </form>
                <!-- </form> -->
            </div>
        </div>
    </div>


    <!-- modal delete -->
    <div class="modal small fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Delete Podcasts</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <h3><i class="fa fa-warning modal-icon"></i>Are you sure to delete - <span class="text-danger" id="modal_span"></span></h3>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    <a href="#" class="btn btn-danger" id="modalDelete">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

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

<script>
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
    $('#delModal').on('show.bs.modal', function(event) {
        let name = $(event.relatedTarget).data('name')
        $("#modal_span").html(name);
    })
    $('.trash').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');

        $('#modalDelete').attr('href', 'functions/podid-delete.php?id=' + id + '&name=' + name);
    })
</script>


</html>