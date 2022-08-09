<?php
session_start();
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('Jobs.php');
require_once('Test.php');
ini_set('log_errors','On');
error_reporting(E_ALL ^ E_WARNING); 
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
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
    $limbajJobNou = $_POST['limbajNou'];
    $categorieJobNoua = $_POST['categorieNoua'];
    $updateJob = new Jobs();
    $updateJob->updateJobs($conn, $idjob, $numejobNou, $descrierejobNou, $cerintejobNou, $statusjobNou, $numetestNou, $durataTestNoua,$limbajJobNou,$categorieJobNoua);
    echo "<script>alert('Job modificat cu succes')</script>";
    ?>
    <html>
    <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/Project/AdminJobModifierPage.php">
<?php
}
?>