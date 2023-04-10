<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}

include "connect/connect.php";
include_once('functions/tag-function.php');
$userRole = $_SESSION['role'];
$tagFn = new tagFunction();
$tag = $tagFn->getAllTag();
$countArts = $tagFn->countTag();
$row = $countArts->fetch_assoc();

$rows = $row['COUNT(id)'];

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
$tagPage =  $tagFn->getTagPage($limit);

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

    <title>Tag</title>

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
                        <h1 class="h3 mb-4 text-gray-800">Tag</h1>
                        <div>
                            <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Create tag</span>
                            </a>
                        </div>
                    </div>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width: 80%">Name</th>
                                            <th style="width: 20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($tag = $tagPage->fetch_assoc()) {
                                        ?>
                                            <tr>
                                                <td style="vertical-align: middle;"><?php echo $tag['name'] ?></td>
                                                <td>
                                                    <?php if ($userRole == '1') { ?>
                                                        <a href="#editModal" class="btn btn-warning btn-circle edit" data-id="<?php echo $tag['id'] ?>" role="button" data-toggle="modal" data-name="<?php echo $tag['name'] ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="#delModal" class="btn btn-danger btn-circle trash" data-id="<?php echo $tag['id'] ?>" role="button" data-toggle="modal" data-name="<?php echo $tag['name'] ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <i class="fas fa-circle text-success"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center">
                                <div class="pagination"><?php echo $paginationCtrls; ?></div>
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
    <!-- modal create -->
    <div class="modal small fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="functions/tag-create.php" method="post">
                    <div class="modal-header">
                        <h3>Create Tag</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <label for="">Name</label>
                        <input name="name" type="text" class="form-control form-control-user" />
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        <button class="btn btn-warning" type="submit">Create</button>
                        <!-- <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button> <a href="#" class="btn btn-warning" id="modalCreate">Create</a> -->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal edit -->
    <div class="modal small fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="functions/tag-edit.php">
                    <div class="modal-header">
                        <h3>Edit Tag</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" id="name" class="form-control">
                        <input type="hidden" name="id" id="id" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        <button class="btn btn-warning" type="submit">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal delete -->
    <div class="modal small fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Delete Tag</h2>
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
        $('#editModal').on('show.bs.modal', function(event) {
            let name = $(event.relatedTarget).data('name')
            $('#name').val(name)
            let id = $(event.relatedTarget).data('id')
            $('#id').val(id)
        })
        $('.trash').click(function() {
            var id = $(this).data('id');
            $('#modalDelete').attr('href', 'functions/tag-delete.php?id=' + id);
        })
    </script>
</body>

</html>