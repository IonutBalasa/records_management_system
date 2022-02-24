<?php
    session_start();
    require 'configuration.php';
    $today_date = date('Y-m-d');
    $session = $pdo->prepare('SELECT * FROM staff WHERE id=:id');
    $values1=[
        'id' => $_SESSION['loggedin']
    ];
    $session->execute($values1);
    $login = $session->fetch();
    if(empty($_SESSION['loggedin']) || ($login['permissions']!="staff" && $login['permissions']!="admin")){
        header('Location: index.php');
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

    <title>WUC Records Management System</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">

</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php require 'sidebar.php' ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php 
                    require 'topbar.php';
                ?>
                <div class="container-fluid file-color mainpage d-flex row">
                    <?php
                        $repeat = 0;
                        $stmt = $pdo->prepare('SELECT * FROM timetables WHERE date >= :date ORDER BY date, time');
                        $values = [
                            'date' => $today_date
                        ];
                        $stmt->execute($values);

                        foreach($stmt as $timetable){
                            $editModule = $pdo->prepare('SELECT * FROM modules WHERE module_code = :module_id');
                            $values = [
                                'module_id' => $timetable['module_id']
                            ];
                            $editModule->execute($values);
                            $em = $editModule->fetch();

                            $editTutor = $pdo->prepare('SELECT * FROM staff WHERE uni_id = :uni_id');
                            $values = [
                                'uni_id' => $timetable['tutor_id']
                            ];
                            $editTutor->execute($values);
                            $et = $editTutor->fetch();
                            if ($repeat == $timetable['date']){
                                echo '<p class="bltext col-12 px-5">' . $em['module_title'] . ' (' . $em['module_code'] . ') in ' . $timetable['room'] . ' at ' . $timetable['time'] .  ' - ' . $et['firstname'] . ' ' . $et['lastname'] . '</p>';
                            } else {
                                echo '<h3 class="bltext col-12 pt-3 px-5"><u>'. date('l jS \of F Y', strtotime($timetable['date'])) . '</u></h3>';
                                echo '<p class="bltext col-12 px-5">' . $em['module_title'] . ' (' . $em['module_code'] . ') in ' . $timetable['room'] . ' at ' . $timetable['time'] .  ' - ' . $et['firstname'] . ' ' . $et['lastname'] . '</p>';
                                $repeat = $timetable['date'];
                            }
                        }
                    ?>
                </div>





<?php require 'footer.php' ?>

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
<i class="fas fa-angle-up"></i>
</a>

<?php require 'logoutmodal.php'; ?>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>