<?php
session_start();
ini_set('log_errors', 'On');
error_reporting(E_ALL ^ E_WARNING);
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
$categorieJobVeche = $rr->getJobCategory();
$idjob = $_SESSION["idUpdateJob"];
$updateJob = new Jobs();
$numejobNou = $descrierejobNou = $cerintejobNou = $statusjobNou = $numetestNou = $durataTestNoua = $limbajTestNou = $categorieJobNoua = "";

if (isset($_POST['Back'])) {
    header("location:AdminJobModifierPage.php");
}

?>
<html>

<head>
    <title>Job Update</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/png" href="css/images/FavIcon.png">
</head>

<body style="display: flex;align-items: center; padding-left: 250px;">
    <div class="updateJob_div" style="margin-top: 50px; margin-left: 50px;">
        <form method="post" action="RedirectPageAdmin.php">
            <div class="div_princUpdate">
                <div class="div_secUpdate div_shadow">
                    <h1>Confirmare valori noi pentru job-ul selectat</h1>
                    <label class="label_AdaugaJob">Nume nou</label>
                    <div class="form-control" style="margin-top: 10px;">
                        <input class="input_mail" type="text" id="numeJobNou" name="numejobNou" style="height: 30px;" value="<?php echo $numejobVechi; ?>" onchange="updateInput(this.value)" required>
                        <script>
                            function updateInput(newvalue) {
                                document.getElementById("numeJobNou").value = newvalue;
                            }
                        </script>
                    </div>
                    <div class="form-control" style="margin-top: 10px;">
                        <label class="label_AdaugaJob">Descriere nouă</label>
                        <textarea class="textarea_adaugaJob" id="descriereJobNou" rows="10" cols="50" style="width: 700px;" name="descrierejobNou" onchange="updateInput1(this.value)" required><?php echo $descrierejobVeche; ?></textarea>
                        <script>
                            function updateInput1(newvalue) {
                                document.getElementById("descriereJobNou").value = newvalue;
                            }
                        </script>
                    </div>
                    <div class="form-control" style="margin-top: 10px;">
                        <label class="label_AdaugaJob">Cerințe noi</label>
                        <textarea class="textarea_adaugaJob" id="cerinteJobNou" rows="10" cols="50" style="width: 500px;" name="cerintejobNou" onchange="updateInput2(this.value)" required><?php echo $cerintejobVechi; ?></textarea>
                        <script>
                            function updateInput2(newvalue) {
                                document.getElementById("cerinteJobNou").value = newvalue;
                            }
                        </script>
                    </div>
                </div>
                <div class="div_secUpdate" style="margin-bottom: 100px;">
                    <div class="form-group" style="margin-top: 10px;">
                        <label class="label_AdaugaJob">Status nou</label>
                        <select class="select_adaugaJob" name="statusnouJob">
                            <option value="Activ" <?php if ($statusjobVechi == "Activ") : ?>selected="selected" <?php endif; ?>>Activ</option>
                            <option value="Inactiv" <?php if ($statusjobVechi == "Inactiv") : ?>selected="selected" <?php endif; ?>>Inactiv</option>
                        </select>
                    </div>
                    <div class="form-control" style="margin-top: 10px;">
                        <label class="label_AdaugaJob">Test atribuit</label>
                        <select class="select_adaugaJob" name="numeTestNou" onchange="updateInput3(this.value)">
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
                        <p style="font-size: 17px;">Durata timpului aleasă va fi considerată a fi în minute(doar valori întregi):</p>
                        <input type="number" id="durataTestNoua" name="durataTestNoua" style="height: 30px;" value="<?php echo $durataTestVeche; ?>" onchange="updateInput4(this.value)" required>
                        <script>
                            function updateInput4(newvalue) {
                                document.getElementById("durataTestNoua").value = newvalue;
                            }
                        </script>
                    </div>
                    <div style="display: flex; align-items: center;">
                    <div class="form-group" style="margin-top: 10px;">
                        <label class="label_AdaugaJob">Limbaj nou</label>
                        <select name="limbajNou" class="select_adaugaJob">
                            <option value="Java">Java</option>
                            <option value="JavaScript">JavaScript</option>
                            <option value="MySQL">MySQL</option>
                            <option value="Python">Python</option>
                            <option value="C++">C++</option>
                            <option value="PHP">PHP</option>
                            <option value="C">C</option>
                            <option value="Angular">Angular</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label class="label_AdaugaJob">Categorie nouă</label>
                        <select name="categorieNoua" class="select_adaugaJob">
                            <option value="Backend Internship">Backend Internship</option>
                            <option value="Frontend Internship">Frontend Internship</option>
                            <option value="Fullstack Internship">Fullstack Internship</option>
                            <option value="MySQL Internship">MySQL Internship</option>
                            <option value="QA Internship">QA Internship</option>
                            <option value="Backend Junior">Backend Junior</option>
                            <option value="Frontend Junior">Frontend Junior</option>
                            <option value="Fullstack Junior">Fullstack Junior</option>
                            <option value="MySQL Junior">MySQL Junior</option>
                            <option value="QA Junior">QA Junior</option>
                            <option value="Backend Senior">Backend Senior</option>
                            <option value="Frontend Senior">Frontend Senior</option>
                            <option value="Fullstack Senior">Fullstack Senior</option>
                            <option value="MySQL Senior">MySQL Senior</option>
                            <option value="QA Senior">QA Senior</option>
                        </select>
                    </div>
                    </div>
                    <div>
                        <input type="submit" class="button_updateJob" name="updateJob" value="Update job cu valorile noi">
                    </div>
                </div>
            </div>
    </form>
    <form method="POST" style="margin-top: 700px; margin-right: 20px;">
    <input type="submit" class="button_updateJob" name="Back" value="Inapoi">
    </form>
    </div>    
</body>

</html>