<?php
include("../../connection.php");
include("fetch-data.php");

session_start();

if (!isset($_SESSION['admin_login'])) {
    header("location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <style>
        .status-btn {
            border: none;
            color: white;
            padding: 5px 10px;
            width: 100px;
            cursor: pointer;
            box-shadow: 0px 0px 15px gray
        }

        .approve {
            background-color: green;
        }

        .disapprove {
            background-color: red;
        }
    </style>
</head>

<body>

    <div class="text-center mt-5">
        <div class="container">

            <?php if (isset($_SESSION['success'])) : ?>
                <div class="alert alert-success">
                    <h3>
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </h3>
                </div>
            <?php endif ?>

            <h1>Admin Page</h1>
            <hr>

            <h3>
                <?php if (isset($_SESSION['admin_login'])) { ?>
                    Welcome, <?php echo $_SESSION['admin_login'];
                            } ?>
            </h3>

            <?php  ?>
            <!-- ===== displaying data with approval button ===== -->
            <table border="1" cellspacing="0" cellpadding="10" width="50%">
                <tr>
                    <th>S.N</th>
                    <th>Heading</th>
                    <th>Content</th>
                    <th>Status</th>
                </tr>
                <?php
                $fetchData = fetchData($conn);
                if (count($fetchData) > 0) {
                    // $i = 1;
                    foreach ($fetchData as $data) {
                ?>
                        <tr>
                            <td><?php echo $data['id']; ?></td>
                            <td><?php echo $data['heading']; ?></td>
                            <td><?php echo $data['content']; ?></td>
                            <td>
                                <button onclick="updateStatus(<?php echo $data['id']; ?>)" id="statusBtn<?php echo $data['id']; ?>" class="status-btn <?php echo $data['status'] == 0 ? 'approve' : 'disapprove'; ?>"><?php echo $data['status'] == 0 ? 'Approve' : 'Disapprove'; ?></button>
                            </td>

                        <tr>
                    <?php
                        // $i++;
                    }
                } ?>
            </table>

            <a href="../logout.php" class="btn btn-danger">Logout</a>

        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="custom-ajax.js"></script>

</body>

</html>