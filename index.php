<?php
include_once('backend/connect/connect.php');
$nameErr = $passErr = $msgErr = "";
session_start();
// Encrypt cookie
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
    $uname = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    if (empty($_POST["username"]) || empty($_POST["password"])) {
        if (empty($_POST["username"])) {
            $nameErr = "* Username is required";
        } else {
            $username = $_POST["username"];
        }

        if (empty($_POST["password"])) {
            $passErr = "* Password is required";
        } else {
            $password = $_POST["password"];
        }
    } else {
        $sql_query = "select * from user where username='" . $uname . "' and password='" . $password . "'";
        $result = mysqli_query($conn, $sql_query);
        $row = mysqli_fetch_array($result);

        // echo '<pre>';
        // print_r($row);
        // echo '</pre>';

        if ($row) {
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['user-img'] = $row['image_path'];
            $_SESSION['role'] = $row['role_id'];
            print_r($_SESSION);

            if (!empty($_POST['rememberme'])) {
                $days = 30;
                $value_username = encryptCookie($_POST['username']);
                $value_password = encryptCookie($_POST['password']);
                setcookie("user_login", $value_username, time() + ($days * 24 * 60 * 60 * 1000));
                setcookie("user_password", $value_password, time() + ($days * 24 * 60 * 60 * 1000));
            } else {
                if (isset($_COOKIE['user_login'])) {
                    setcookie('user_login', '');
                    if (isset($_COOKIE['user_password'])) {
                        setcookie('user_password', '');
                    }
                }
            }
            header("location: backend/dashboard.php");
            echo "<script> window.location.replace('backend/dashboard.php') </script>";
        } else {
            $msgErr = "* Invalid username or password";
        }
    }
}


$fields = array(
    "file1" => "File 1:",
);


if (isset($_POST['reg_btn'])) {

    if (!empty($_POST["firstname"]) || !empty($_POST["lastname"]) || !empty($_POST["username"]) || !empty($_POST["password"]) || !empty($_POST["role_id"])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $province = $_POST['province'];
        $password = md5($_POST['password']);
        $role_id = '2';

        $strSQL = "INSERT INTO user (firstname,lastname,username,email,province,password,role_id) VALUES ('" . $firstname . "','" .  $lastname . "',
                    '" . $username . "', ' " . $email . "',' " . $province . "' ,'" . $password . "','" . $role_id . "')";
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
                    chmod('backend/uploads/user-img/', 0777);
                    $actual_link = "http://$_SERVER[HTTP_HOST]";

                    $allowed_extension = array("jpg", "gif", "png");
                    if (in_array($extension, $allowed_extension)) {
                        move_uploaded_file($file, 'backend/uploads/user-img/' . $new_image_name);
                        $imgSql = "UPDATE user SET image_path = '$new_image_name' WHERE id = '$user_id'";
                        $imgResult = mysqli_query($conn, $imgSql);

                        if ($imgResult) {
                            echo '<script language="javascript">';
                            echo 'alert("Create user success")';
                            echo '</script>';
                            echo "<script>window.location.href='./';</script>";
                        }
                    }
                } else {
                    echo '<script language="javascript">';
                    echo 'alert("Create user success no img")';
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

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="backend/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="backend/css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-custom">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user" placeholder="Username" value="">
                                            <!-- <input type="text" name="username" class="form-control form-control-user" placeholder="Username" value="<?php if (isset($_COOKIE['user_login'])) {
                                                                                                                                                                // echo decryptCookie($_COOKIE['user_login']);
                                                                                                                                                            } ?>"> -->
                                            <span class="text-danger"><?php echo $nameErr; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user" placeholder="Password" value="">
                                            <!-- <input type="password" name="password" class="form-control form-control-user" placeholder="Password" value="<?php if (isset($_COOKIE['user_password'])) {
                                                                                                                                                                    // echo decryptCookie($_COOKIE['user_password']);
                                                                                                                                                                } ?>"> -->
                                            <span class="text-danger"><?php echo $passErr; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" name="rememberme" class="custom-control-input" id="customCheck" <?php if (isset($_COOKIE['user_login'])) {
                                                                                                                                            echo 'checked';
                                                                                                                                        } ?>>
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                            <span class="text-danger"><?php echo $msgErr; ?></span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-around">
                                            <a href="../">Back to home</a>
                                            <a href="#reg_modal" data-toggle="modal">สมัครสมาชิก</a>
                                        </div>
                                        <hr>
                                        <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Register Modal-->
    <div class="modal fade" id="reg_modal" tabindex="-1" role="dialog" aria-labelledby="regModallabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register for Login</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="user" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card shadow mb-4">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" name="firstname" class="form-control form-control-user" id="exampleFirstName" placeholder="ชื่อ">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" name="lastname" class="form-control form-control-user" id="exampleLastName" placeholder="นามสกุล">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" name="email" class="form-control form-control-user" id="exampleFirstName" placeholder="อีเมล">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" name="province" class="form-control form-control-user" id="exampleLastName" placeholder="จังหวัด">
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>User image <span class="text-danger">*</span> </label> <br>
                                            <?php
                                            foreach ($fields as $field => $value) {
                                            ?>
                                                <div class="show-file mb-3" <?php echo "id='show-$field'"; ?>>
                                                    <img class="show-image" src="backend/img/no-image.jpg" alt="">
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
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <hr>
                            <button type="submit" name="reg_btn" id="reg_btn" class="btn btn-primary btn-user">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="backend/vendor/jquery/jquery.min.js"></script>
    <script src="backend/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="backend/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="backend/js/sb-admin-2.min.js"></script>

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
            imgEl.src = "backend/img/no-image.jpg";
            imgEl.className = 'show-image';
            showImgEl.appendChild(imgEl);
        }
    </script>

</body>

</html>