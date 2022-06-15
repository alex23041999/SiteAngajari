<?php
session_start();
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('Jobs.php');
require_once('AccountDetails.php');
require_once('Applications.php');
//functie care sterge spatiile goale ,sterge backslash-urile si converteste catre caracterele speciale html
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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

$idjob = $_GET['aplicare_jobID'];
$numejob = $_GET['aplicare_numeJob'];
$descrierejob = $_GET['aplicare_descriereJob'];
$cerintejob = $_GET['aplicare_cerinteJob'];

$afisareTest = new Applications();
$static1 = 'Applications';

/*if (isset($_POST['incepeTest'])) {
if (strcmp($cvCandidat, "") == 0) {
        echo "<script>alert('Trebuie sa ai un CV incarcat pentru a aplica la un job !')</script>";
?>
        <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AccountInfoPage.php">
        <?php
    } else {
        $dataAplicareCandidat = date('Y-m-d');
        $aplicareCandidat = new Applications();
        $aplicareCandidat->setApplication($conn, $userIdCandidat, $idjob, $numejob, $numeCandidat, $prenumeCandidat, $emailCandidat, $telefonCandidat, $cvCandidat, $dataAplicareCandidat,$notaTest);
        if ($aplicareCandidat->insertNewApplication()) {
            echo "<script>alert('Ai aplicat cu succes !')</script>";
        ?>
            <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AvailableJobsPage.php">
<?php
        } else if (($aplicareCandidat->insertNewApplication()) == false) {
            echo "<script>alert('Nu poti aplica la un job la care deja ai aplicat !')</script>";
        }
    }
}*/
$numeTest = $afisareTest->getAplicareTest($conn,$numejob);
if(isset($_POST["ptTEST"])){
    $raspunscorect =$_POST["checkbox".$i];
}
if (isset($_POST['Back'])) {
    header("location:AvailableJobsPage.php");
}
?>
<html>
<title>Aplica la jobul ales!</title>
<link rel="stylesheet" type="text/css" href="css/quiz_style.css">

<body>

    <form method="post" id="form1">
        <div>
            <div>
                <input type="text" id="numeJob" name="numejob" placeholder="Nume job" style="height: 30px;" value="<?php echo $numejob; ?>" readonly>
            </div>
            <div>
                <textarea id="descriereJob" rows="10" cols="50" style="width: 500px;" name="descrierejob" placeholder="Descriere job" readonly><?php echo $descrierejob; ?></textarea>
            </div>
            <div>
                <textarea id="cerinteJob" rows="10" cols="50" style="width: 500px;" name="cerintejob" placeholder="Cerinte job" readonly><?php echo $cerintejob; ?></textarea>
            </div>
            <div>
                <button type="button" id="incepeTest" class="button_quiz" name="incepeTest" onclick="visibleDivTest()">Incepe testul pentru aplicare!</button>
                <script>
                    function visibleDivTest() {
                        document.getElementById("divTest").style.display = "unset";
                        document.getElementById("incepeTest").style.display = "none";
                    }
                </script>
            </div>
            <div id="divTest" style="display: none;">
                <hr class="mb-3">
                <?php
                    $afisareTest = $static1::afisareTest($conn,$numeTest);
                ?>
            </div>
            <hr class="mb-3">
            <div>
                <input type="submit" class="btn btn-primary" name="ptTEST" value="Testare">
            </div>
            <hr class="mb-3">
            <div>
                <input type="submit" class="btn btn-primary" name="Back" value="Inapoi">
            </div>
        </div>
    </form>
</body>

</html>