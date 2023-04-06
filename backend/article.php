<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
}

include "connect/connect.php";
include_once('functions/article-function.php');

$articleFn = new articleFunction();
$article = $articleFn->getAllArticle();
$countArts = $articleFn->countArticle();
$row = $countArts->fetch_assoc();
$userRole = $_SESSION['role'];
$rows = $row['COUNT(id)'];

$page_rows = 8;
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
$articlePage =  $articleFn->getArticlePage($limit);

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

include("fetch-data.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Article</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/article.css" rel="stylesheet">
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
                        <h1 class="h3 mb-4 text-gray-800">Article</h1>
                        <div>
                            <a href="article-create.php" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Create article</span>
                            </a>
                        </div>
                    </div>



                    <div class="container-card">
                        <?php
                        while ($article = $articlePage->fetch_assoc()) {
                        ?>
                            <!-- <a class="cards relative" href="#"> -->
                            <div class="cards relative">
                                <?php
                                if ($userRole == '1') { ?>
                                    <button onclick="updateStatus(<?php echo $article['id']; ?>)" id="statusBtn<?php echo $article['id']; ?>" class="status-btn <?php echo $article['status'] == 0 ? 'approve' : 'disapprove'; ?>"><?php echo $article['status'] == 0 ? 'Approve' : 'Disapprove'; ?></button>
                                <?php } else { ?>
                                    <div class="status-btn <?php echo $article['status'] == 0 ? 'approve' : 'disapprove'; ?>"><?php echo $article['status'] == 0 ? 'Approve' : 'Disapprove'; ?></div>
                                <?php } ?>
                                <div class="card__header text-center">
                                    <img src="uploads/article-img/<?php echo $article['image_banner'] ?>" alt="<?php echo $article['topic_name'] ?>" class="card__image" width="380" height="300">
                                </div>
                                <div class="card__body">
                                    <!-- <span class="tag tag-blue">Technology</span> -->
                                    <h4><?php echo $article['topic_name'] ?></h4>
                                </div>
                                <div class="card__footer">
                                    <div class="user">
                                        <div class="user__info">
                                            <small><?php echo $article['create_at'] ?></small>
                                        </div>
                                        <div class="user__info">
                                            <a href="article-edit.php?id=<?php echo $article['id']; ?>"> แก้ไข </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="text-center">
                        <div class="pagination"><?php echo $paginationCtrls; ?></div>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="js/custom-ajax.js"></script>

</body>

</html>