<?php
include_once('connect/connect.php');
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
            header("location: dashboard.php");
            echo "<script> window.location.replace('dashboard.php') </script>";
        } else {
            $msgErr = "* Invalid username or password";
        }
    }
}

// $actual_link = "http://$_SERVER[HTTP_HOST]";

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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">

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
                                        <a href="../">Back to home</a>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>