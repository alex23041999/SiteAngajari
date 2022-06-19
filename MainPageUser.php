<?php
session_start();
ini_set('log_errors','On');
ini_set('error_reporting', E_ALL );
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
//in cazul in care utilizatorul/admin-ul se deconecteaza , se sterg datele cache din sesiune
if (isset($_POST['logout'])) {
    session_destroy();
    header("location:LoginPage.php");
}
//buton care duce la pagina de profil a contului
if (isset($_POST['myaccount'])) {
    header("location:AccountInfoPage.php");
}
//buton care duce la pagina de prezentare a firmei
if (isset($_POST['companyinfo'])) {
    header("location:CompanyInfoPage.php");
}
//buton care duce la pagina de adaugare/stergere job-uri(doar pentru admin)
if (isset($_POST['addjobs'])) {
    header("location:NewJobByAdmin.php");
}
//buton care duce la pagina de vizualizare job-uri si aplicare pt user
if (isset($_POST['availablejobs'])) {
    header("location:AvailableJobsPage.php");
}
//buton care duce la pagina de vizualizare job-uri si aplicare pt user
if (isset($_POST['updatejobs'])) {
    header("location:AdminJobModifierPage.php");
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
    }
}
?>

<html>

<body>
    <form method="post">
        <link rel="stylesheet" type="text/css" href="css/logout-button.css">
        <div>
            <button class="logoutbutton" id="logout" name="logout">Log out</button>
        </div>
        <div>
            <link rel="stylesheet" type="text/css" href="css/myaccount_button.css">
            <button class="myaccountButton" type="submit" name="myaccount" <?php if ($role == "admin") { ?> style="display: none;" <?php } ?>>Contul meu</button>
        </div>
        <div>
            <button class="button" id="info" name="companyinfo">Informatii firma</button>
        </div>
        <div>
            <button class="button" id="jobs" name="availablejobs" <?php if ($role == "admin") { ?> style="display: none;" <?php } else { ?> style="margin-top: 10px;" <?php } ?>>Vedeti job-uri disponibile</button>
        </div>
        <div>
            <button class="button" id="addjobs" name="addjobs" <?php if ($role == "user") { ?> style="margin-top: 20px; display: none;" <?php } else { ?> style="margin-top: 10px;" <?php } ?>>Adauga joburi</button>
        </div>
        <div>
            <button class="button" id="updatejobs" name="updatejobs" <?php if ($role == "user") { ?> style="margin-top: 20px; display: none;" <?php } else { ?> style="margin-top: 10px;" <?php } ?>>Modifica joburi</button>
        </div>
    </form>
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <div class="footer">
        <div class="row">
            <div class="column">
                <p>Contacte</p>
                <p>Numar telefon: 0351 444 188</p>s
            </div>
            <div class="column">
                <p>Adresa email:licenceproject@gmail.com</p>
                <p>Adresa:Str.Bucuriei , Nr.23</p>
            </div>
        </div>
    </div>
    </head>

</body>

</html>