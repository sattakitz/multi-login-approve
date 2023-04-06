<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}
include "connect/connect.php";
include_once('functions/user-function.php');

$userFn = new userFunction();
$user = $userFn->getAllUser();
$countArts = $userFn->countUser();
$row = $countArts->fetch_assoc();

$rows = $row['COUNT(id)'];

$userRole = $_SESSION['role'];

$page_rows = 10;
$last = ceil($rows / $page_rows);

if ($last < 1) {
    $last = 1;
}

$pagenum = 1;

if (isset($_GET['pn'])) {
    $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}

if ($pagenum < 1) {
    $pagenum = 1;
} else if ($pagenum > $last) {
    $pagenum = $last;
}

$limit = 'LIMIT ' . ($pagenum - 1) * $page_rows . ',' . $page_rows;
$userPage =  $userFn->getUserPage($limit);

$paginationCtrls = '';

if ($last != 1) {

    if ($pagenum > 1) {
        $previous = $pagenum - 1;
        $paginationCtrls .= '<a href="?pn=' . $previous . '" class="">Previous</a> &nbsp; &nbsp; ';

        for ($i = $pagenum - 4; $i < $pagenum; $i++) {
            if ($i > 0) {
                $paginationCtrls .= '<a href="?pn=' . $i . '" class="">' . $i . '</a> &nbsp; ';
            }
        }
    }

    $paginationCtrls .= '<label class="active">' . $pagenum . '</label> &nbsp; ';
    // $paginationCtrls .= '' . $pagenum . ' &nbsp; ';

    for ($i = $pagenum + 1; $i <= $last; $i++) {
        $paginationCtrls .= '<a href="?pn=' . $i . '" class="">' . $i . '</a> &nbsp; ';
        if ($i >= $pagenum + 4) {
            break;
        }
    }

    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a href="?pn=' . $next . '" class="">Next</a> ';
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
                    <div class="d-flex justify-content-between">
                        <h1 class="h3 mb-4 text-gray-800">User</h1>
                        <div>
                            <?php
                            if ($userRole == '1') { ?>
                                <a href="user-create.php" class="btn btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text">Create user</span>
                                </a>
                            <?php } ?>


                        </div>
                    </div>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">Picture</th>
                                            <th>Username</th>
                                            <th>First name</th>
                                            <th>Last name</th>
                                            <th>Role</th>
                                            <!-- <th style="text-align: center;">Status</th> -->
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($user = $userPage->fetch_assoc()) {
                                        ?>
                                            <tr>
                                                <td style=" vertical-align: middle; text-align: center;">
                                                    <img class="img-profile rounded-circle" src="uploads/user-img/<?php echo $user['image_path']; ?>">
                                                </td>
                                                <td style="vertical-align: middle;"><?php echo $user['username'] ?></td>
                                                <td style="vertical-align: middle;"><?php echo $user['firstname'] ?></td>
                                                <td style="vertical-align: middle;"><?php echo $user['lastname'] ?></td>
                                                <td style="vertical-align: middle;"><?php if ($user['role_id'] == '1') {
                                                                                        echo 'Admin';
                                                                                    } else {
                                                                                        echo 'Webmaster';
                                                                                    } ?></td>
                                                <!-- <td style="vertical-align: middle; text-align: center;"><i class="fas fa-circle text-success"></i></td> -->

                                                <?php
                                                if ($userRole == '1') { ?>
                                                    <td style="vertical-align: middle; text-align: right;">
                                                        <a href="user-edit.php?id=<?php echo $user['id'] ?>" class="btn btn-warning btn-circle">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a class="btn btn-danger btn-circle trash" href="#delModal" data-id="<?php echo $user['id'] ?>" data-img="<?php echo $user['image_path'] ?>" role="button" data-toggle="modal">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <div class="pagination"><?php echo $paginationCtrls; ?></div>
                                </div>
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

    <!-- start modal -->
    <!-- modal delete -->
    <div class="modal small fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <h2 class="text-danger"><i class="fa fa-warning modal-icon"></i>Are you sure to delete?
                    </h2>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button> <a href="#" class="btn btn-danger" id="modalDelete">Delete</a>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        $('.trash').click(function() {
            var id = $(this).data('id');
            var img = $(this).data('img');
            $('#modalDelete').attr('href', 'functions/user-delete.php?id=' + id + '&img=' + img);
        })
    </script>
</body>

</html>