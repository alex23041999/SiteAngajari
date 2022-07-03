<?php
session_start();
ini_set('log_errors', 'On');
ini_set('error_reporting', E_ALL);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
require_once('DbConnection.php');
require_once('AccountDetails.php');
require_once('Applications.php');
require_once('Jobs.php');
$userid = $_SESSION['accountid'];
$role = $_SESSION['role'];
//in cazul in care utilizatorul/admin-ul se deconecteaza , se sterg datele cache din sesiune
if (isset($_POST['logoutButton'])) {
    session_destroy();
    header("location:LoginPage.php");
}
//verificam daca s-a transmis in sesiune accountid-ul si daca rolul este de user
if ($_SESSION['accountid'] != NULL && strcmp($_SESSION['role'], "user") == 0) {
    $numeUtilizatorCandidat = $_SESSION['accountid'];
}
$candidatnou = new AccountDetails();
$static = 'AccountDetails';
$candidatnou = $static::showAccountDetails($conn, $numeUtilizatorCandidat);
$userIdCandidat = $candidatnou->getUserID();
$numeCandidat = $candidatnou->getAccountLastName();
$prenumeCandidat = $candidatnou->getAccountFirstName();
$emailCandidat = $candidatnou->getAccountEmail();
$telefonCandidat = $candidatnou->getAccountTelephone();
$cvCandidat = $candidatnou->getAccountCV();
if (isset($_SESSION["jobid"])) {
    $idjob = $_SESSION["jobid"];
    $job = new Jobs();
    $rr = $job->returnJobDetails($conn, $idjob);
    $numejob = $rr->getJobName();
}
if (isset($_POST["aplicare"])) {
    $questionsnumber = $_SESSION["questionsnumber"];
    $raspunsuriCorecte = 0;
    for ($i = 0; $i < $questionsnumber; $i++) {
        $verificare = new Applications();
        $intrebare = $_POST["intrebare" . $i];
        $raspunsCorect = $verificare->correctAnswerChecking($conn, $intrebare);
        $raspunsDat = $_POST["checkbox" . $i];
        if (!isset($_POST["checkbox" . $i])) {
        } else if (isset($_POST["checkbox" . $i])) {
            if (strcmp($raspunsCorect, $raspunsDat) == 0) {
                $raspunsuriCorecte++;
            }
        }
    }
    $notaFinala = ($raspunsuriCorecte / $questionsnumber) * 10;
    $dataAplicareCandidat = date('Y-m-d');
    $statusAplicare = "Neevaluat";
    $aplicareCandidat = new Applications();
    $aplicareCandidat->setApplication($conn, $userIdCandidat, $idjob, $numejob, $numeCandidat, $prenumeCandidat, $emailCandidat, $telefonCandidat, $cvCandidat, $dataAplicareCandidat, $notaFinala, $statusAplicare);
    if ($aplicareCandidat->insertNewApplication()) {
        echo "<script>alert('Ai aplicat cu succes !')</script>";
    }
} else if (isset($_POST["numejob"])) {
    $questionsnumber = $_SESSION["questionsnumber"];
    $raspunsuriCorecte = 0;
    for ($i = 0; $i < $questionsnumber; $i++) {
        $verificare = new Applications();
        $intrebare = $_POST["intrebare" . $i];
        $raspunsCorect = $verificare->correctAnswerChecking($conn, $intrebare);
        $raspunsDat = $_POST["checkbox" . $i];
        if (!isset($_POST["checkbox" . $i])) {
        } else if (isset($_POST["checkbox" . $i])) {
            if (strcmp($raspunsCorect, $raspunsDat) == 0) {
                $raspunsuriCorecte++;
            }
        }
    }
    $notaFinala = ($raspunsuriCorecte / $questionsnumber) * 10;
    $dataAplicareCandidat = date('Y-m-d');
    $aplicareCandidat = new Applications();
    $aplicareCandidat->setApplication($conn, $userIdCandidat, $idjob, $numejob, $numeCandidat, $prenumeCandidat, $emailCandidat, $telefonCandidat, $cvCandidat, $dataAplicareCandidat, $notaFinala, $statusAplicare);
    if ($aplicareCandidat->insertNewApplication()) {
        echo "<script>alert('Timpul a expirat, aplicarea ta a fost salvata !')</script>";
    }
}
?>

<html>

<head>
    <title>Contul meu</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/png" href="css/images/FavIcon.png">
</head>

<body>
    <div class="page-container m-0" style="overflow: hidden;">
        <div class="sidebar_mainpage m-0">
            <div class="logo-firm"></div>
            <div class="sidebar-buttons">
            <button class="sidebar-button" onclick="window.location='MainPageUser.php';"> <i class="fa fa-home" aria-hidden="true"></i>Pagină principală</button>
                <button class="sidebar-button" onclick="window.location='AccountInfoPage.php';"> <i class="fa fa-user" aria-hidden="true"></i>Contul meu</button>
                <button class="sidebar-button" onclick="window.location='AvailableJobsPage.php';"> <i class="fa fa-briefcase" aria-hidden="true"></i>Vizualizează joburi</button>
                <form method="POST" name="logout" action="LoginPage.php">
                    <button class="logout-button" type="submit" name="logoutButton"><i class="fa fa-sign-out" aria-hidden="true" style="margin-right: 10px;"></i> Logout</button>
                </form>
            </div>
        </div>
        <div class="sidebar_mainpageclear m-0">
        </div>
        <div class="mainpage_div" id="img_div">
        </div>
    </div>
</body>

</html>