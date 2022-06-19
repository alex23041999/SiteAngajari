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
$vizualizejob = new Jobs();
$static = 'Jobs';
?>
<html>

<head>
    <title>Joburi</title>
</head>
<body>
    <form action="UpdateJobPage.php" method="post">
        <link rel="stylesheet" type="text/css" href="css/table_design.css">
        <link rel="stylesheet" type="text/css" href="css/href_border.css">
        <table class="table, th, td" style="width: 1500px;">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 5%;">Nume</th>
                    <th style="width: 25%;">Descriere</th>
                    <th style="width: 25%;">Cerinte</th>
                    <th style="width: 5%;">Candidati</th>
                    <th style="width: 5%;">Status</th>
                    <th style="width: 5%;">Test atribuit</th>
                    <th style="width: 5%;">Limbaj</th>
                    <th style="width: 5;">Categorie</th>
                    <th style="width: 5%;">Durata test</th>
                    <th style="width: 5%;">Actiune</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $vizualizejob = $static::vizualizareJoburiAdmin($conn);
                ?>
            </tbody>
        </table>
    </form>
    <hr class="mb-3">
</body>
</html>