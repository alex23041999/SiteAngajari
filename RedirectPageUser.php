<?php
session_start();
error_reporting(E_ALL ^ E_WARNING); 
ini_set('log_errors','On');
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('AccountDetails.php');
require_once('Applications.php');
require_once('Jobs.php');
$userid = $_SESSION['accountid'];
$role = $_SESSION['role'];

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
if(isset($_SESSION["jobid"])){
$idjob = $_SESSION["jobid"];
$job = new Jobs();
$rr =$job->returnJobDetails($conn,$idjob);
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
    $aplicareCandidat->setApplication($conn, $userIdCandidat, $idjob, $numejob, $numeCandidat, $prenumeCandidat, $emailCandidat, $telefonCandidat, $cvCandidat, $dataAplicareCandidat, $notaFinala,$statusAplicare);
    if ($aplicareCandidat->insertNewApplication()) {
        echo "<script>alert('Ai aplicat cu succes !')</script>";
        ?>
        <html>
        <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AvailableJobsPage.php">
    <?php
    }
}else if(isset($_POST["numejob"])){
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
    $aplicareCandidat->setApplication($conn, $userIdCandidat, $idjob, $numejob, $numeCandidat, $prenumeCandidat, $emailCandidat, $telefonCandidat, $cvCandidat, $dataAplicareCandidat, $notaFinala,$statusAplicare);
    if ($aplicareCandidat->insertNewApplication()) {
        echo "<script>alert('Timpul a expirat, aplicarea ta a fost salvata !')</script>" ;
        ?>
        <html>
        <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AccountInfoPage.php">
    <?php
    }
}
?>