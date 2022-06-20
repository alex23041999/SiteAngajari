<?php
session_start();
error_reporting(E_ALL ^ E_WARNING); 
ini_set('log_errors', 'On');
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
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
$cvCandidat = $candidatnou->getAccountCV();

if (strcmp($cvCandidat, "") == 0) {
    echo "<script>alert('Trebuie sa ai un CV incarcat pentru a aplica la un job !')</script>";
?>
    <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AccountInfoPage.php">
<?php
}

if (isset($_POST["aplicarejobid"])) {
    $_SESSION["jobid"] = $_POST["aplicarejobid"];
    $idjob = $_SESSION["jobid"];
    $job = new Jobs();
    $jdetails = $job->returnJobDetails($conn, $idjob);
    $numejob = $jdetails->getJobName();
    $timpRezolvare = $jdetails->getJobDurataTest();

    $afisareTest = new Applications();
    $static1 = 'Applications';
    $numeTest = $afisareTest->getAplicareTest($conn, $numejob);
    $_SESSION["questionsnumber"] = $afisareTest->questionNumberOfTest($conn, $numeTest);
}
$raspunsuriCorecte = 0;

if (isset($_POST['Back'])) {
    header("location:AvailableJobsPage.php");
}
?>
<html>
<title>Aplica la jobul ales!</title>
<link rel="stylesheet" type="text/css" href="css/quiz_style.css">

<body>
    <form method="post" id="form1" action="RedirectPageUser.php">
        <div>
            <div>
                <input type="text" id="numeJob" name="numejob" placeholder="Nume job" style="height: 30px;" value="<?php echo $numejob; ?>" readonly>
            </div>
            <div>
                <textarea id="descriereJob" rows="10" cols="50" style="width: 500px;" name="descrierejob" placeholder="Descriere job" readonly><?php echo $jdetails->getJobDescriere(); ?></textarea>
            </div>
            <div>
                <textarea id="cerinteJob" rows="10" cols="50" style="width: 500px;" name="cerintejob" placeholder="Cerinte job" readonly><?php echo $jdetails->getJobCerinte(); ?></textarea>
            </div>
            <div>
                <button type="button" id="incepeTest" class="button_quiz" name="incepeTest" onclick="visibleDivTest();timeCountDown();">Incepe testul pentru aplicare!</button>
                <script>
                    function visibleDivTest() {
                        document.getElementById("divTest").style.display = "unset";
                        document.getElementById("incepeTest").style.display = "none";
                        document.getElementById("aplicare").style.display = "unset";
                    }

                    function timeCountDown() {
                        const startTime = document.getElementById("timpRezolvare").value;
                        console.log(startTime);
                        let time = startTime * 60;
                        const countDownEl = document.getElementById("timer");
                        setInterval(updateCountDown, 1000);

                        function updateCountDown() {
                            const minutes = Math.floor(time / 60);
                            let seconds = time % 60;

                            seconds = seconds < 10 ? '0' + seconds : seconds;
                            countDownEl.innerHTML = minutes + ":" + seconds;
                            time--;
                            time = time == 0 ? document.getElementById("form1").submit() : time;
                        }
                    }
                </script>
            </div>
            <div id="divTest" style="display: none;">
                <hr class="mb-3">
                <div>
                    <input type="text" id="timpRezolvare" value="<?php echo $timpRezolvare; ?>" style="display: none;">
                    <p id="timer"></p>
                </div>
                <?php
                $afisareTest = $static1::afisareTest($conn, $numeTest);
                ?>
            </div>
            <hr class="mb-3">
            <div>
                <input type="submit" class="button_quiz" name="aplicare" id="aplicare" value="Trimite aplicarea!" style="display: none;">
            </div>
            <hr class="mb-3">
            <div>
                <input type="submit" class="button_quiz" name="Back" value="Inapoi">
            </div>
        </div>
    </form>
</body>

</html>