<?php
session_start();
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('Jobs.php');
require_once('Test.php');
require_once('Applications.php');
//functie care sterge spatiile goale ,sterge backslash-urile si converteste catre caracterele speciale html
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$vizualizeApplication= new Applications();
$static = 'Jobs';
if(isset($_POST["modifica"])){
    foreach($_POST["statusAplicare"] as $id_app=>$k){
        echo $id_app;
    }
}
?>
<html>

<head>
    <title>Aplicari joburi</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
</head>

<body>
    <form action="UpdateApplications.php" method="post">
        <link rel="stylesheet" type="text/css" href="css/table_design.css">
        <link rel="stylesheet" type="text/css" href="css/href_border.css">
        <table class="table, th, td" style="width: 1500px;">
            <thead>
                <tr>
                    <th style="width: 10%;">Job</th>
                    <th style="width: 10%;">Nume candidat</th>
                    <th style="width: 10%;">Prenume candidat</th>
                    <th style="width: 10%;">Email candidat</th>
                    <th style="width: 10%;">Numar telefon</th>
                    <th style="width: 15%;">CV candidat</th>
                    <th style="width: 10%;">Data aplicarii</th>
                    <th style="width: 10%;">Nota test</th>
                    <th style="width: 15%;">Status aplicare</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $vizualizeApplication = $static::vizualizareAplicariAdmin($conn);
                ?>
            </tbody>
        </table>
        <input type="submit" class="button_quiz" value="Butonel" name="modifica">
    </form>
</body>

</html>