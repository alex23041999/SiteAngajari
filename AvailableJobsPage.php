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

?>
<html>

<head>
    <title>Vizualizare joburi</title>
    <link rel="stylesheet" type="text/css" href="css/table_design.css">
    <link rel="stylesheet" type="text/css" href="css/href_border.css">
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
</head>

<body>
    <form method="post" action="JobApplicationPage.php">
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
                                <th style="width: 20%;">Numar candidati</th>
                                <th style="width: 30%;">Limbaj</th>
                                <th style="width: 20%;">Actiune</th>
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
                                <th style="width: 20%;">Numar candidati</th>
                                <th style="width: 30%;">Limbaj</th>
                                <th style="width: 20%;">Actiune</th>
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
                                <th style="width: 20%;">Numar candidati</th>
                                <th style="width: 30%;">Limbaj</th>
                                <th style="width: 20%;">Actiune</th>
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