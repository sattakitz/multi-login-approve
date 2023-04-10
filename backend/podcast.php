<?php

session_start();

$userRole = $_SESSION['role'];

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}
include("connect/connect.php");


if (isset($_POST['but_upload'])) {
    $maxsize = 20971520; // 20MB

    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        $name = $_FILES['file']['name'];
        $target_dir = "uploads/podcasts/";
        $target_file = $target_dir . $_FILES["file"]["name"];


        // Select file type
        $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Valid file extensions
        $extensions_arr = array("mp3", "avi", "3gp", "mov", "mpeg");

        // Check extension
        if (in_array($extension, $extensions_arr)) {

            // Check file size
            if (($_FILES['file']['size'] >= $maxsize) || ($_FILES["file"]["size"] == 0)) {

                $_SESSION['message'] = "File too large. File must be less than 5MB.";
            } else {
                // Upload
                if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                    // Insert record
                    $query = "INSERT INTO podcasts(name,location) VALUES('" . $name . "','" . $target_file . "')";

                    mysqli_query($conn, $query);

                    $_SESSION['message'] = "Upload successfully.";
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
                            <div class="row">

                                <?php
                                $fetchpodcasts = mysqli_query($conn, "SELECT * FROM podcasts ORDER BY id DESC");
                                while ($row = mysqli_fetch_assoc($fetchpodcasts)) {
                                    $location = $row['location'];
                                    $Vidname = $row['name'];
                                    $Vid = $row['id'];
                                ?>
                                    <div class="col-3">
                                        <h5><?php echo $Vidname; ?></h5>
                                        <audio controls height='320px'>
                                            <source src="<?php echo $location; ?>" type="audio/ogg">
                                            <source src="<?php echo $location; ?>" type="audio/mpeg">
                                        </audio>
                                        <?php if ($userRole == '1') { ?>
                                            <a href="#delModal" class="btn btn-danger btn-circle trash" data-id="<?php echo $row['id'] ?>" role="button" data-toggle="modal" data-name="<?php echo $row['name'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php } ?>
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
                <div class="modal-header">
                    <h3>Create Podcasts</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" enctype='multipart/form-data'>
                        <input type='file' name='file' />
                        <input type='submit' value='Upload' name='but_upload'>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                </div>
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
    $('#delModal').on('show.bs.modal', function(event) {
        let name = $(event.relatedTarget).data('name')
        $("#modal_span").html(name);
    })
    $('.trash').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#modalDelete').attr('href', 'functions/vid-delete.php?id=' + id + '&name=' + name);
    })
</script>


</html>