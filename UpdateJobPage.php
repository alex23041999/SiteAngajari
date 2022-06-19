<?php
session_start();
ini_set('log_errors','On');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('Jobs.php');
require_once('Test.php');
//functie care sterge spatiile goale ,sterge backslash-urile si converteste catre caracterele speciale html
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$idjob = $numejobVechi = $descrierejobVeche = $cerintejobVechi = $statusjobVechi = $numeTestVechi = $durataTestVeche = "";

if (isset($_POST["updateJobID"])) {
    $_SESSION["idUpdateJob"] = $_POST["updateJobID"];
}
$numeTeste = new Test();
$static2 = 'Test';

$idjob = $_SESSION["idUpdateJob"];
$job = new Jobs();
$rr = $job->returnJobDetails($conn, $idjob);
$numejobVechi = $rr->getJobName();
$descrierejobVeche = $rr->getJobDescriere();
$cerintejobVechi = $rr->getJobCerinte();
$statusjobVechi = $rr->getJobStatus();
$numeTestVechi = $rr->getJobTest();
$durataTestVeche = $rr->getJobDurataTest();
$limbajTestVechi = $rr->getJobLimbaj();
$idjob = $_SESSION["idUpdateJob"];
$updateJob = new Jobs();
$numejobNou = $descrierejobNou = $cerintejobNou = $statusjobNou = $numetestNou = $durataTestNoua = $limbajTestNou = "";

if (isset($_POST['Back'])) {
    header("location:AdminJobModifierPage.php");
}

?>
<html>

<head>
    <title>Job Update</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
</head>

<body>
    <form method="post" action="MainPageAdmin.php">
        <h1>Confirmare valori noi pentru job-ul selectat</h1>
        <div>
            <div class="form-control" style="margin-top: 10px;">
                <input type="text" id="numeJobNou" name="numejobNou" style="height: 30px;" value="<?php echo $numejobVechi; ?>" onchange="updateInput(this.value)" required>
                <script>
                    function updateInput(newvalue) {
                        document.getElementById("numeJobNou").value = newvalue;
                    }
                </script>
            </div>
            <div class="form-control" style="margin-top: 10px;">
                <textarea class="form-control" id="descriereJobNou" rows="10" cols="50" style="width: 500px;" name="descrierejobNou" onchange="updateInput1(this.value)" required><?php echo $descrierejobVeche; ?></textarea>
                <script>
                    function updateInput1(newvalue) {
                        document.getElementById("descriereJobNou").value = newvalue;
                    }
                </script>
            </div>
            <div class="form-control" style="margin-top: 10px;">
                <textarea class="form-control" id="cerinteJobNou" rows="10" cols="50" style="width: 500px;" name="cerintejobNou" onchange="updateInput2(this.value)" required><?php echo $cerintejobVechi; ?></textarea>
                <script>
                    function updateInput2(newvalue) {
                        document.getElementById("cerinteJobNou").value = newvalue;
                    }
                </script>
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Status nou job:</label>
                <select name="statusnouJob">
                    <option value="Activ" <?php if ($statusjobVechi == "Activ") : ?>selected="selected" <?php endif; ?>>Activ</option>
                    <option value="Inactiv" <?php if ($statusjobVechi == "Inactiv") : ?>selected="selected" <?php endif; ?>>Inactiv</option>
                </select>
            </div>
            <div class="form-control" style="margin-top: 10px;">
                <select name="numeTestNou" class="select" onchange="updateInput3(this.value)">
                    <?php
                    $numeTeste = $static2::selectNumeTestFromDB($conn);
                    ?>
                </select>
                <script>
                    function updateInput3(newvalue) {
                        document.getElementById("numeTestNou").value = newvalue;
                    }
                </script>
            </div>
            <div class="form-control" style="margin-top: 10px;">
                <p>Durata timpului aleasa va fi considerata a fi in minute(doar valori intregi):</p>
                <input type="number" id="durataTestNoua" name="durataTestNoua" style="height: 30px;" value="<?php echo $durataTestVeche; ?>" onchange="updateInput4(this.value)" required>
                <script>
                    function updateInput4(newvalue) {
                        document.getElementById("durataTestNoua").value = newvalue;
                    }
                </script>
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Limbaj</label>
                <select name="limbajNou" class="select">
                    <option value="Java">Java</option>
                    <option value="JavaScript">JavaScript</option>
                    <option value="MySQL">MySQL</option>
                    <option value="Python">Python</option>
                    <option value="C++">C++</option>
                    <option value="PHP">PHP</option>
                    <option value="C++">C++</option>
                    <option value="Angular">Angular</option>
                </select>
            </div>
            <hr class="mb-3">
            <input type="submit" class="btn btn-primary" name="updateJob" value="Update job cu valorile noi">
            <hr class="mb-3">
            <input type="submit" class="btn btn-primary" name="Back" value="Inapoi">
        </div>
    </form>
</body>

</html>