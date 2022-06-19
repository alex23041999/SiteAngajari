<?php
session_start();
ini_set('log_errors', 'On');
ini_set('error_reporting', E_ALL);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('Jobs.php');
require_once('AccountDetails.php');
//functie care sterge spatiile goale ,sterge backslash-urile si converteste catre caracterele speciale html
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$vizualizeUserjob = new Jobs();
$static1 = 'Jobs';

$userid = $_SESSION['accountid'];
$vizualizeJobs = new Jobs();
$vizualizeNotRecomandedJobs = new Jobs();

$accountLanguages = new AccountDetails();
$checkLanguagesExistance = $accountLanguages->checkLanguagesExistance($conn, $userid);
$vizualizeFilteredJobs = new Jobs();


?>
<html>

<head>
    <title>Vizualizare joburi</title>
    <link rel="stylesheet" type="text/css" href="css/table_design.css">
    <link rel="stylesheet" type="text/css" href="css/href_border.css">
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
</head>

<body>
    <script src="js/insertUserLanguages.js">
    </script>
    <form method="GET" action="AvailableJobsPage.php">
        <div>
            <label>Sorteaza job-uri</label>
            <select class="select" id="categorieNoua" name="category">
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
        <div>
            <button type="submit" name="sorteaza" value="sort">Sorteaza</button>
        </div>
        <div>
            <button type="button" name="stergeFiltru" onclick="buttonWithoutFilter()">Sterge filtrul</button>
        </div>
        <script>
            function updateInput(newvalue) {
                document.getElementById("option").value = newvalue;
            }
        </script>
    </form>
    <form method="post" action="JobApplicationPage.php" id="formWithFilter">
        <input type="text" id="option" name="optiune" style="display: none;" onchange="updateInput(this.value)">
        <label style="display: none;" id="filterLabel">Nu exista job-uri care corespund filtrului!</label>
        <div id="withFilter" style="display: none;">
            <label>Cele mai potrivite cautari</label>
            <table class="table, th, td" style="width: 1000px;">
                <thead>
                    <tr>
                        <th style="width: 30%;">Nume job</th>
                        <th style="width: 10%;">Numar candidati</th>
                        <th style="width: 25%;">Categorie</th>
                        <th style="width: 10%;">Limbaj</th>
                        <th style="width: 25%;">Actiune</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_GET['sorteaza'])) {
                        $filter = $_GET['category'];
                    ?>
                        <div style="display: none;">
                            <?php
                            $s1 = $vizualizeFilteredJobs->returnFilteredJobs($conn, $filter);
                            ?>
                        </div>
                        <?php
                        if ($s1 == 1) {
                        ?>
                            <script>
                                document.getElementById('withFilter').style.display = "block";
                            </script>
                        <?php
                        } else if ($s1 == 0) {
                        ?>
                            <script>
                                document.getElementById('filterLabel').style.display = "unset";
                                document.getElementById('withFilter').style.display = "none";
                            </script>
                    <?php
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </form>

    <form method="post" action="JobApplicationPage.php" id="formNoFilter">
        <div style="display: none;">
            <?php
            $string = $vizualizeUserjob->vizualizareJoburiUser($conn);
            ?>
        </div>

        <?php
        if ($string == 0) {
            echo "Niciun job disponibil momentan!";
        } else if ($string == 1) {
            if ($checkLanguagesExistance == 1) {
        ?>
                <div>
                    <label>Job-uri recomandate pentru tine</label>
                    <table class="table, th, td" style="width: 1000px;">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Nume job</th>
                                <th style="width: 10%;">Numar candidati</th>
                                <th style="width: 25%;">Categorie</th>
                                <th style="width: 10%;">Limbaj</th>
                                <th style="width: 25%;">Actiune</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $vizualizeJobs->returnRecomandedJobs($conn, $userid);
                            ?>
                        </tbody>
                    </table>
                </div>
                <div>
                    <label>Alte joburi</label>
                    <table class="table, th, td" style="width: 1000px;">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Nume job</th>
                                <th style="width: 10%;">Numar candidati</th>
                                <th style="width: 25%;">Categorie</th>
                                <th style="width: 10%;">Limbaj</th>
                                <th style="width: 25%;">Actiune</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $vizualizeNotRecomandedJobs->returnNotRecomandedJobs($conn, $userid);
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else if ($checkLanguagesExistance == 0) {
            ?>
                <div>
                    <label>Aplica acum la oricare dintre joburile disponibile</label>
                    <table class="table, th, td" style="width: 1000px;">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Nume job</th>
                                <th style="width: 10%;">Numar candidati</th>
                                <th style="width: 25%;">Categorie</th>
                                <th style="width: 10%;">Limbaj</th>
                                <th style="width: 25%;">Actiune</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $vizualizeUserjob->vizualizareJoburiUser($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            ?>
    </form>
<?php
        }
?>
</body>

</html>