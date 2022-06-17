<?php
session_start();
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('Jobs.php');
require_once('Test.php');
if (isset($_SESSION["idUpdateJob"])) {
    $idjob = $_SESSION["idUpdateJob"];
}

if (isset($_POST['updateJob'])) {
    $numejobNou = $_POST["numejobNou"];
    $descrierejobNou = $_POST["descrierejobNou"];
    $cerintejobNou = $_POST["cerintejobNou"];
    $numetestNou = $_POST["numeTestNou"];
    $durataTestNoua = $_POST["durataTestNoua"];
    $statusjobNou = $_POST['statusnouJob'];
    $updateJob = new Jobs();
    $updateJob->updateJobs($conn, $idjob, $numejobNou, $descrierejobNou, $cerintejobNou, $statusjobNou, $numetestNou, $durataTestNoua);
    echo "<script>alert('Job modificat cu succes')</script>";
}
?>
<html>

<head>
    <p>Admin mainpage</p>
</head>

</html>