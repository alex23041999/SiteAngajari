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
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/png" href="css/images/FavIcon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="page-container m-0">
        <div class="sidebar m-0">
            <div class="logo-firm"></div>
            <div class="sidebar-buttons">
                <button class="sidebar-button" onclick="window.location='AccountInfoPage.php';"> <i class="fa fa-user" aria-hidden="true"></i>Contul meu</button>
                <button class="sidebar-button" onclick="window.location='MainPageUser.php';"> <i class="fa fa-home" aria-hidden="true"></i>Pagină principală</button>
                <button class="sidebar-button" onclick="window.location='AvailableJobsPage.php';"> <i class="fa fa-briefcase" aria-hidden="true"></i>Vizualizează joburi</button>
                <button class="sidebar-button" onclick="window.location='CompanyInfoPage.php';"> <i class="fa fa-retweet" aria-hidden="true"></i>Despre SL.Tech</button>
                <form method="POST" name="logout" action="LoginPage.php">
                    <button class="logout-button" type="submit" name="logoutButton"><i class="fa fa-sign-out" aria-hidden="true" style="margin-right: 10px;"></i> Logout</button>
                </form>
            </div>
        </div>
        <div class="main-contentJobs">

            <form method="GET" action="AvailableJobsPage.php" style="padding-left: 50px;">
                <div class="profile_div" style="justify-content: center;padding: 5px;">
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <label class="label_adaugaJob">Sorteaza job-uri</label>
                        <select class="select_adaugaJob" id="categorieNoua" name="category">
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
                    <div style="display: flex;align-items: center;">
                        <script src="js/insertUserLanguages.js">
                        </script>
                        <button class="button_filter" type="submit" name="sorteaza" value="sort">Sorteaza</button>
                        <button class="button_filter" style="margin-left: 100px;" type="button" name="stergeFiltru" onclick="buttonWithoutFilter()">Sterge filtrul</button>
                    </div>
                    <script>
                        function updateInput(newvalue) {
                            document.getElementById("option").value = newvalue;
                        }
                    </script>
                </div>
            </form>

            <form method="post" action="JobApplicationPage.php" id="formWithFilter" style="width: 1500px;">
                <input type="text" id="option" name="optiune" style="display: none;" onchange="updateInput(this.value)">
                <label style="display: none;" id="filterLabel">Nu exista job-uri care corespund filtrului!</label>
                <div id="withFilter" style="display: none;">
                    <div class="table_avbJobs">
                        <label class="label_adaugaJob">Cele mai potrivite căutări</label>
                        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
                        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
                        <table id="boostrapTable" class="table table-striped" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 30%;text-align: left;">Nume job</th>
                                    <th style="width: 10%;text-align: left;">Numar candidati</th>
                                    <th style="width: 25%;text-align: left;">Categorie</th>
                                    <th style="width: 10%;text-align: left;">Limbaj</th>
                                    <th style="width: 25%;text-align: left;">Actiune</th>
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
                        <script src="js/table_design.js"></script>
                    </div>
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
                        <div class="table_avbJobs">
                        <label class="label_adaugaJob">Job-uri recomandate pentru tine</label>
                            <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
                            <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                            <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
                            <table id="boostrapTable1" class="table table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;text-align: left;">Nume job</th>
                                        <th style="width: 10%;text-align: left;">Numar candidati</th>
                                        <th style="width: 25%;text-align: left;">Categorie</th>
                                        <th style="width: 10%;text-align: left;">Limbaj</th>
                                        <th style="width: 25%;text-align: left;">Actiune</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $vizualizeJobs->returnRecomandedJobs($conn, $userid);
                                    ?>
                                </tbody>
                            </table>
                            <script src="js/table_design1.js"></script>
                        </div>

                        <div class="table_avbJobs">
                        <label class="label_adaugaJob">Alte joburi</label>
                            <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
                            <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                            <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
                            <table id="boostrapTable2" class="table table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;text-align: left;">Nume job</th>
                                        <th style="width: 10%;text-align: left;">Numar candidati</th>
                                        <th style="width: 25%;text-align: left;">Categorie</th>
                                        <th style="width: 10%;text-align: left;">Limbaj</th>
                                        <th style="width: 25%;text-align: left;">Actiune</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $vizualizeNotRecomandedJobs->returnNotRecomandedJobs($conn, $userid);
                                    ?>
                                </tbody>
                            </table>
                            <script src="js/table_design2.js"></script>
                        </div>
                    <?php
                    } else if ($checkLanguagesExistance == 0) {
                    ?>
                            <div class="table_avbJobs">
                            <label class="label_adaugaJob">Aplică acum la oricare dintre joburile disponibile</label>
                            <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
                            <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                            <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
                            <table id="boostrapTable3" class="table table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;text-align: left;">Nume job</th>
                                        <th style="width: 10%;text-align: left;">Numar candidati</th>
                                        <th style="width: 25%;text-align: left;">Categorie</th>
                                        <th style="width: 10%;text-align: left;">Limbaj</th>
                                        <th style="width: 25%;text-align: left;">Actiune</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $vizualizeUserjob->vizualizareJoburiUser($conn);
                                    ?>
                                </tbody>
                            </table>
                            <script src="js/table_design3.js"></script>
                        </div>
                    <?php
                    }
                    ?>
            </form>
            
        <?php
                }
        ?>
            </div>
    </div>
</body>

</html>