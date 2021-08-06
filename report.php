<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    session_start();
    $conn = mysqli_connect("localhost", "root", "", "rbeitest_db");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <title>Progress Report</title>
    <style>
        body {
            background: linear-gradient(120deg, #2980b9, #8e44ad);
            background-attachment: fixed;
        }

        span {
            color: #ff8080;
        }
    </style>
</head>

<body>
    <!-- Navbar start -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
        <div class="container-fluid">
            <h3>RBE <span>INSTITUTE</span></h3>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <form class="d-flex" action="dashboard.php">
                    <button class="btn btn-transparent" type="submit">Home</button>
                </form>
                <form action="login.php">
                    <button class="btn btn-transparent" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- Navbar ends -->
    <!-- Table start -->
    <div class="container my-4">
        <table class="table table-striped table-hover table-bordered" id="myTable">
            <thead class="table-dark">
                <tr style="text-align:center">
                    <th scope="col">Sr. No.</th>
                    <th scope="col">List of Test</th>
                    <th scope="col">Exam Status</th>
                    <th scope="col">Score</th>
                    <th scope="col">View Wrong Answers</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // List of Exams starts
                $uid = $_SESSION['id'];
                $ExmClss = $_SESSION['class'];
                $ExmClss = $ExmClss . '%';
                $sql = "SELECT * FROM `rb_studentexam_tb` WHERE class LIKE '$ExmClss'";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);
                $exam_id_array = array();
                $exam_marks = array();
                $exam_title = array();
                // $status_array=array();
                // $image=array();

                // ------------------------------------
                // Exam title fetch starts
                while ($TitleRow = mysqli_fetch_array($result)) {
                    $titl = $TitleRow[1] . "_" . $TitleRow[0];
                    $IdRow = substr($titl, strpos($titl, '_', 0) + 1, strlen($titl));
                    array_push($exam_id_array, $IdRow);
                    array_push($exam_title, $titl);
                }
                // Exam title fetch ends
                // ------------------------------------
                foreach ($exam_id_array as $value) {
                    $user_id = $_SESSION['id'];
                    $marks_query = "SELECT * FROM `rb_studentexamresult_tb` WHERE studentid='$user_id' AND testid='$value'";
                    $result1 = mysqli_query($conn, $marks_query);
                    $count1 = mysqli_num_rows($result1);
                    $total_marks = 0;
                    while ($row = mysqli_fetch_array($result1)) {
                        $total_marks += $row[6];
                    }
                    $percent_marks = ($total_marks * 100) / 80;
                    array_push($exam_marks, $percent_marks);
                }

                for ($i = 0; $i < count($exam_id_array); $i++) {
                    echo "<tr class='table-success' style='text-align:center'><td>$i</td>";
                    echo "<td>$exam_title[$i]</td>";
                    if ($exam_marks[$i] > 0) {
                        echo "<td> Given </td>";
                    } else {
                        echo "<td> Exam Pending </td>";
                    }
                    echo "<td>$exam_marks[$i]%</td>";
                    echo "<td><a href='solution.php'><input type='button' class='btn btn-success' id='butn' value='View Solution'></a></td></tr>";
                }
                ?>
                <!-- Score ends -->
            </tbody>
        </table>
    </div>
    <!-- Table ends -->
    <form action="solution.php" method="POST">
        <script src="jquery.min.js"></script>
        <script>
            $('.table tbody').on('click', '.btn', function() {
                var currow = $(this).closest('tr');
                var col1 = currow.find('td:eq(1)').text();
                var p_id = col1.substr(col1.search('_') + 1, col1.length);
                alert(p_id);
            })
        </script>;
    </form>
    <!-- Datatables javascript start -->
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <!-- Datatables javascript ends -->
</body>

</html>