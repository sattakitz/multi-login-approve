<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}

include("connect/connect.php");
$userRole = $_SESSION['role'];
$fields = array(
    "file1" => "File 1:",
);


if (isset($_POST['submit'])) {

    if (!empty($_POST["firstname"]) || !empty($_POST["lastname"]) || !empty($_POST["username"]) || !empty($_POST["password"]) || !empty($_POST["role_id"])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $role_id = $_POST['role_id'];

        $strSQL = "INSERT INTO user (firstname,lastname,username,password,role_id) VALUES ('" . $firstname . "','" .  $lastname . "',
                    '" . $username . "','" . $password . "','" . $role_id . "')";
        $userResult = mysqli_query($conn, $strSQL);
        $user_id = '';
        if ($userResult) {
            $user_id = mysqli_insert_id($conn);
        }

        // $last_id = mysqli_query

        if ($user_id) {
            foreach ($fields as $img => $value) {
                if ($_FILES[$img]['name']) {
                    $file = $_FILES[$img]['tmp_name'];
                    $file_name = $_FILES[$img]['name'];
                    $file_name_array = explode(".", $file_name);
                    $extension = end($file_name_array);
                    $new_image_name = rand() . '.' . $extension;
                    chmod('uploads/user-img/', 0777);
                    $actual_link = "http://$_SERVER[HTTP_HOST]";

                    $allowed_extension = array("jpg", "gif", "png");
                    if (in_array($extension, $allowed_extension)) {
                        move_uploaded_file($file, 'uploads/user-img/' . $new_image_name);
                        $imgSql = "UPDATE user SET image_path = '$new_image_name' WHERE id = '$user_id'";
                        $imgResult = mysqli_query($conn, $imgSql);

                        if ($imgResult) {
                            echo '<script language="javascript">';
                            echo 'alert("Create user success")';
                            echo '</script>';
                            echo "<script>window.location.href='user.php';</script>";
                        }
                    }
                } else {
                    echo '<script language="javascript">';
                    echo 'alert("Create user success no img")';
                    echo '</script>';
                    echo "<script>window.location.href='user.php';</script>";
                }
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

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
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
                    <h1 class="h3 mb-4 text-gray-800">Create user</h1>
                    <form class="user" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card shadow mb-4">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" name="firstname" class="form-control form-control-user" id="exampleFirstName" placeholder="First Name">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" name="lastname" class="form-control form-control-user" id="exampleLastName" placeholder="Last Name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" name="username" class="form-control form-control-user" id="exampleFirstName" placeholder="Username">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="password" name="password" class="form-control form-control-user" id="exampleLastName" placeholder="Password">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="role_id" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1">
                                            <label class="form-check-label" for="inlineRadio1">Admin</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="role_id" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2">
                                            <label class="form-check-label" for="inlineRadio2">Webmaster</label>
                                        </div>
                                        <hr>
                                        <button type="submit" name="submit" id="submit" class="btn btn-primary btn-user btn-block">
                                            Create User
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>User image
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
                                                    <label class="custom-file-label text-ellipsis" <?php echo "for='$field' id='label-$field'"; ?>>Choose file...</label>
                                                    <button type="button" class="btn btn-danger btn-user btn-block" <?php echo "id='btn-$field'"; ?> onclick="deleteImage(this)">Delete image</button>
                                                </div>
                                                <div class="col">
                                                    <div id="image-error"> </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>