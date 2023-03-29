<?php
include("database.php");
include("fetch-data.php");
?>
<!DOCTYPE html>
<html>

<head>
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

    <!-- ============displaying data with approval button========== -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="custom-ajax.js"></script>

</body>

</html>