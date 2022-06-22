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
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="container_table">
    <div class="table_div">
    <form action="UpdateJobPage.php" method="post">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

        <table id="boostrapTable" class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 15%;">Nume</th>
                    <th style="width: 10%;">Candidati</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 15%;">Test atribuit</th>
                    <th style="width: 10%;">Limbaj</th>
                    <th style="width: 10%;">Categorie</th>
                    <th style="width: 10%;">Durata test</th>
                    <th style="width: 10%;">Actiune</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $vizualizejob = $static::vizualizareJoburiAdmin($conn);
                ?>
            </tbody>
        </table>
        <script src="js/table_design.js"></script>
    </form>
    </div>
    </div>
</body>

</html>