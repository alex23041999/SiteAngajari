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
$numejob = $descriere = $status = $cerinte = $durataTest = $limbaj="";
$numejobErr = $descriereErr = $cerinteErr = $durataTestErr= "";
$numeTest = $numeTestErr = $raspunsErr = "";
if (isset($_POST['creareTest'])) {
    //verificam daca toate $POST au valori
    $flag = false;
    foreach ($_POST as $val) {
        if (empty($val)) {
            $flag = true;
        }
    }
    //in caz ca toate $POST-urile au valori, facem inserarea in DB
    if (!$flag) {
        //de modificat aici
        //inseram detalii job in baza de date
        if (empty($_POST["numejob"])) {
            $numejobErr = "Camp obligatoriu!";
        } else {
            $numejob = test_input($_POST["numejob"]);
        }
        if (empty($_POST["descriere"])) {
            $descriereErr = "Camp obligatoriu!";
        } else {
            $descriere = test_input($_POST["descriere"]);
        }
        if (empty($_POST["cerinte"])) {
            $cerinteErr = "Camp obligatoriu!";
        } else {
            $cerinte = test_input($_POST["cerinte"]);
        }
        if (empty($_POST["timpTest"])) {
            $durataTestErr = "Camp obligatoriu!";
        } else {
            $durataTest = test_input($_POST["timpTest"]);
        }
        $status = $_POST['status'];
        $limbaj = $_POST['limbaj'];
        //insert nume test in baza de date
        $numeTest = test_input($_POST["numeTest"]);
        $newTest = new Test();
        if ($newTest->checkTestNameExistance($conn, $numeTest) == 1) {
            $numeTestErr = "Exista deja un test cu acest nume !";
        } else {
            $newTest->setTest($conn, $numeTest);
            if ($newTest != NULL) {
                $newTest->insertNumeTestIntoDB();
                $nume = $newTest->getNumeTest();
                //insert intrebari in baza de date
                $idTest = $newTest->selectIDTestFromDB($conn, $nume);
                $array_intrebari = array();
                for ($i = 0; $i < $_POST["quiznumber"]; $i++) {
                    $array_intrebari[$i] = $_POST["intrebare" . ($i + 1)];
                }
                $setIntrebari = new Intrebare();
                $setIntrebari->insertIntrebareIntoDB($conn, $idTest, $array_intrebari);
                //insert raspunsuri in baza de date
                $raspunsuri = array();
                $is_correct = 0;
                for ($i = 0; $i < $_POST["quiznumber"]; $i++) {
                    for ($x = 0; $x < 4; $x++) {
                        $checkbox_return = $_POST["raspuns_checkbox" . $i];
                        if ($_POST[$checkbox_return] == $_POST["raspuns" . $i . $x]) {
                            $is_correct = 1;
                        } else {
                            $is_correct = 0;
                        }
                        $raspunsuri[$x] = new Raspuns();
                        $raspunsuri[$x]->setRaspuns($conn, $_POST["raspuns" . $i . $x], $is_correct);
                    }
                    $idIntrebare = $setIntrebari->selectIDQuestionFromDB($conn, $_POST["intrebare" . ($i + 1)]);
                    $raspunsuri_final = new Raspuns();
                    $raspunsuri_final->insertRaspunsuriIntoDB($conn, $idIntrebare, $idTest, $raspunsuri);
                }
                $newjob = new Jobs();
                $newjob->setJob($conn, $numejob, $descriere, $cerinte, $status, $numeTest,$durataTest,$limbaj);
                if (empty($numejobErr) && empty($descriereErr) && empty($cerinteErr) && empty($durataTestErr)) {
                    $result = $newjob->insertJobs();
                    echo "<script>alert('Job adaugat cu succes')</script>";
?>
                    <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/NewJobByAdmin.php">
<?php
                }
            }
        }
    } else {
        echo "<script>alert('Ceva nu a mers bine,reincearca !')</script>";
    }
}
?>
<html>

<head>
    <title>Add Jobs</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/png" href="FavIcon.png"/>
</head>

<body>
    <div class="page-container m-0">
        <div class="sidebar m-0">
            <div class="title">Sobolaneii SRL</div>
            <div class="sidebar-button"> <i class="fa fa-envelope-open" aria-hidden="true"></i> Buton 1</div>
            <div class="sidebar-button">Buton 2</div>
            <div class="sidebar-button">Buton 3</div>
            <div class="sidebar-button">Buton 4</div>
        </div>
        <div class="main-content">
        <div class="navbar"></div>
            <form method="post" class="menu" name="Quiz" id="form">
                <div>
                    <header>
                        <div class="container">
                            <h2>Adaugare detalii job</h2>
                            <p>Completati campurile pentru a adauga un job nou</p>
                        </div>
                    </header>
                    <div class="form-group" style="margin-top: 10px;">
                        <input type="text" name="numejob" class="form-control" placeholder="Nume job..." value="<?php echo $numejob; ?>" required>
                        <span class="error" style="color:red"> <?php echo $numejobErr; ?></span>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <textarea class="form-control" rows="10" cols="50" style="width: 500px;" placeholder="Descriere..." name="descriere" value="<?php echo $descriere; ?>" required></textarea>
                        <span class="error" style="color:red"> <?php echo $descriereErr; ?></span>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <textarea class="form-control" rows="10" cols="50" style="width: 500px;" placeholder="Cerinte..." name="cerinte" value="<?php echo $cerinte; ?>" required></textarea>
                        <span class="error" style="color:red"> <?php echo $cerinteErr; ?></span>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label>Status</label>
                        <select name="status" class="select">
                            <option value="Activ">Activ</option>
                            <option value="Inactiv">Inactiv</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label>Limbaj</label>
                        <select name="limbaj" class="select">
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
                </div>
                <hr class="mb-3">
                <div>
                    <header>
                        <div class="container">
                            <h2>Adaugare quiz pentru job</h2>
                            <p>Completati campurile pentru a adauga quiz-ul</p>
                        </div>
                    </header>
                    <script src="js/inserare_Quiz_ByAdmin.js">
                    </script>
                    <input type="text" placeholder="Nume test" id="numeTest" name="numeTest" onclick="updateNumeTest(this.value)" value="<?php echo $numeTest; ?>" autocomplete="FALSE" required>
                    <span class="error" style="color:red"><?php echo $numeTestErr ?></span>
                    <p>Selectati numarul de intrebari ale quiz-ului:</p>
                    <select name="quiznumber" class="select" id="quiznr" onchange="changeNumber(this.value)">
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                    <button type="button" id="generare" class="button_quiz" name="alegeNR" onclick="createIntrebari()">Genereaza intrebari</button>
                    <div id="intrebari" class="intrebari"></div>
                    <hr class="mb-3">
                    <button type="button" id="adaugare" class="button_quiz" name="adaugareraspunsuri" onclick="createRaspunsuri()" style="display: none;">Adauga raspunsuri</button>
                    <div id="raspunsuri"></div>
                    <div id="timpTestare"></div>
                    <span class="error" style="color:red"> <?php echo $raspunsErr; ?></span>
                    <button type="submit" class="button_quiz" name="creareTest" id="creareTest" style="display: none;" value="submit">Salveaza testul</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<script>

</script>