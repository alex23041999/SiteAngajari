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
    <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/Project/AccountInfoPage.php">
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

<head>
    <title>Aplica la jobul ales!</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/png" href="css/images/FavIcon.png">
</head>

<body>
    <div class="page-container m-0">
        <form method="post" id="form1" action="RedirectPageUser.php" style="display: flex; align-items: center;padding-left: 500px;">
            <div class="test_div">
                <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 50px;padding:20px;box-shadow: 0px 2px 5px 5px rgba(0,6,241,0.6); border-radius: 5px;">
                    <input type="text" id="numeJob" name="numejob" placeholder="Nume job" class="input_testJob" value="<?php echo $numejob; ?>" readonly>
                    <div class="div_mail" style="margin-top: 50px; display: flex;justify-content: space-between;">
                        <div style="display: flex; align-items: center; flex-direction: column;">
                            <label class="label_adaugaJob">Descriere job</label>
                            <textarea id="descriereJob" rows="10" cols="50" class="textarea_adaugaJob" name="descrierejob" placeholder="Descriere job" readonly><?php echo $jdetails->getJobDescriere(); ?></textarea>
                        </div>
                        <div style="display: flex; align-items: center; flex-direction: column; margin-left: 50px;">
                            <label class="label_adaugaJob">Cerințe job</label>
                            <textarea id="cerinteJob" rows="10" cols="50" class="textarea_adaugaJob" name="cerintejob" placeholder="Cerinte job" readonly><?php echo $jdetails->getJobCerinte(); ?></textarea>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" id="incepeTest" class="registration_button" style="width: 200px;margin-bottom: 50px;" name="incepeTest" onclick="visibleDivTest();timeCountDown();">Începe testul pentru aplicare!</button>
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
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <div>
                            <input type="text" id="timpRezolvare" value="<?php echo $timpRezolvare; ?>" style="display: none;">
                            <p id="timer" class="p_timer"></p>
                        </div>
                        <?php
                        $afisareTest = $static1::afisareTest($conn, $numeTest);
                        ?>
                        <div>
                            <input type="submit" name="aplicare" id="aplicare" value="Trimite aplicarea!" class="registration_button" style="margin-top: 20px;">
                        </div>
                    </div>
                </div>
                <div>
                    <input type="submit" name="Back" value="Înapoi" class="registration_button" style="margin-top: 50px;">
                </div>
            </div>
        </form>
    </div>
</body>

</html>