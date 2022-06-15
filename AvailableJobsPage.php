<?php
session_start();
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('Jobs.php');
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
?>
<html>

<head>
    <title>Vizualizare joburi</title>
</head>

<body>
    <div style="display: none;">
        <?php
        $string = $vizualizeUserjob->vizualizareJoburiUser($conn);
        ?>
    </div>

    <?php
    if ($string == 0) {
        echo "Niciun job disponibil momentan!";
    } else if ($string == 1) {
    ?>
        <form action="AvailableJobsPage.php" method="post">
            <link rel="stylesheet" type="text/css" href="css/table_design.css">
            <link rel="stylesheet" type="text/css" href="css/href_border.css">
            <table class="table, th, td" style="width: 1000px;">
                <thead>
                    <tr>
                        <th style="width: 40%;">Nume job</th>
                        <th style="width: 20%;">Numar candidati</th>
                        <th style="width: 20%;">Actiune</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $vizualizeUserjob = $static1::vizualizareJoburiUser($conn);
                    ?>
                </tbody>
            </table>
        </form>
    <?php
    }
    ?>
</body>

</html>