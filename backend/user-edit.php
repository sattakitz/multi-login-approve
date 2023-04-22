<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}
include("connect/connect.php");

$user_id = $_GET["id"];
$sql = "SELECT * FROM user WHERE id='$user_id'";
$userQuery = mysqli_query($conn, $sql);
$user = mysqli_fetch_array($userQuery);
$userRole = $_SESSION['role'];
$fields = array(
    "file1" => "File 1:",
);

function encryptCookie($value)
{
    $key = hex2bin(openssl_random_pseudo_bytes(4));

    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);

    $ciphertext = openssl_encrypt($value, $cipher, $key, 0, $iv);

    return (base64_encode($ciphertext . '::' . $iv . '::' . $key));
}

function decryptCookie($ciphertext)
{
    $cipher = "aes-256-cbc";

    list($encrypted_data, $iv, $key) = explode('::', base64_decode($ciphertext));
    return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
}

if (isset($_POST['submit'])) {

    if (!empty($_POST["firstname"]) || !empty($_POST["lastname"]) || !empty($_POST["username"]) || !empty($_POST["password"]) || !empty($_POST["role_id"])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $province = $_POST['province'];

        $password = md5($_POST['password']);
        $role_id = $_POST['role_id'];

        $strSQL = "UPDATE user SET
        firstname='$firstname',
        lastname='$lastname',
        username='$username',
        email='$email',
        province='$province',
        password='$password',
        role_id='$role_id'
        WHERE id='$user_id'";

        $userResult = mysqli_query($conn, $strSQL);

        // $last_id = mysqli_query
        if ($userResult && $fields) {
            foreach ($fields as $img => $value) {
                if ($_FILES[$img]['name']) {
                    $file = $_FILES[$img]['tmp_name'];
                    $file_name = $_FILES[$img]['name'];
                    $file_name_array = explode(".", $file_name);
                    $extension = end($file_name_array);
                    $new_image_name = rand() . '.' . $extension;
                    chmod('upload', 0777);
                    $actual_link = "http://$_SERVER[HTTP_HOST]";

                    $allowed_extension = array("jpg", "gif", "png");
                    if (in_array($extension, $allowed_extension)) {
                        move_uploaded_file($file, 'uploads/user-img/' . $new_image_name);
                        $imgSql = "UPDATE user SET image_path = '$new_image_name' WHERE id = '$user_id'";
                        $imgResult = mysqli_query($conn, $imgSql);
                        echo $user['image_path'];
                        if ($imgResult) {
                            unlink('uploads/user-img/' . $user['image_path']);
                            echo '<script language="javascript">';
                            echo 'alert("Edit user success")';
                            echo '</script>';
                            // echo "<script>window.location.href='user.php';</script>";
                        }
                    }
                } else {
                    echo '<script language="javascript">';
                    echo 'alert("Edit user success")';
                    echo '</script>';
                    // echo "<script>window.location.href='user.php';</script>";
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
                    <h1 class="h3 mb-4 text-gray-800">Edit user</h1>
                    <form class="user" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card shadow mb-4">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <label for="">First Name</label>
                                                    <input type="text" name="firstname" class="form-control form-control-user" id="exFirstName" placeholder="First Name" value="<?php echo $user['firstname']; ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="">Last Name</label>
                                                    <input type="text" name="lastname" class="form-control form-control-user" id="exLastName" placeholder="Last Name" value="<?php echo $user['lastname']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" name="email" class="form-control form-control-user" id="exEmail" placeholder="อีเมล" value="<?php echo $user['email']; ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" name="province" class="form-control form-control-user" id="exProvince" placeholder="จังหวัด" value="<?php echo $user['province']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <label for="">Username</label>
                                                    <input type="text" name="username" class="form-control form-control-user" id="exUsername" placeholder="Username" value="<?php echo $user['username']; ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="">Password</label>
                                                    <!-- <input type="password" name="password" class="form-control form-control-user" id="exampleLastName" placeholder="Password" value="<?php echo $user['password']; ?>"> -->
                                                    <input type="password" name="password" class="form-control form-control-user" id="exPassword" placeholder="Password" value="<?php echo $user['password']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="role_id" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" <?php if ($user['role_id']  == '1') echo "checked"; ?>>
                                            <label class="form-check-label" for="inlineRadio1">Admin</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="role_id" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2" <?php if ($user['role_id']  == '2') echo "checked"; ?>>
                                            <label class="form-check-label" for="inlineRadio2">Webmaster</label>
                                        </div>
                                        <hr>
                                        <button type="submit" name="submit" id="submit" class="btn btn-primary btn-user btn-block">
                                            Edit User
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
                                                    <!-- <img class="show-image" src="img/no-image.jpg" alt=""> -->
                                                    <img <?php if ($user['image_path']) {
                                                                echo "src='uploads/user-img/" . $user['image_path'] . "'";
                                                            } else {
                                                                echo "src='img/no-image.jpg'";
                                                            }
                                                            ?> alt="" class="show-image mx-auto">
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" <?php echo "name='$field' id='$field'"; ?> class="custom-file-input mb-2" accept=".jpg, .png" onchange="readURL(this)">
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