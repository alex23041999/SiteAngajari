<?php
session_start();
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('AccountDetails.php');
require_once('Jobs.php');
$userid = $_SESSION['accountid'];
$accountdata = new AccountDetails();
$static = 'AccountDetails';
$accountdata = $static::showAccountDetails($conn, $userid);
$vizualizeUserApplications = new Jobs();
$static1 = 'Jobs';

//in cazul in care utilizatorul/admin-ul se deconecteaza , se sterg datele cache din sesiune
if (isset($_POST['logout'])) {
    session_destroy();
    header("location:LoginPage.php");
}
?>
<html>

<body>

    <div style="display: none;">
        <?php
        $string = $vizualizeUserApplications->vizualizareAplicari($conn, $userid);
        ?>
    </div>

    <div>
        <link rel="stylesheet" type="text/css" href="css/table_design.css">
        <table class="table, th, td" style="margin-top: 20px;">
            <caption>Bine ati venit in contul dumneavoastra</caption>
            <th>Informatii</th>
            <th>Detalii</th>
            <tr>
                <td>Nume</td>
                <td><?php echo $accountdata->getAccountLastName(); ?></td>
            </tr>
            <tr>
                <td>Prenume</td>
                <td><?php echo $accountdata->getAccountFirstName(); ?></td>
            </tr>
            <tr>
                <td>Nume utilizator</td>
                <td><?php echo $accountdata->getAccountID(); ?></td>
            </tr>
            <tr>
                <td>Adresa email</td>
                <td><?php echo $accountdata->getAccountEmail(); ?></td>
            </tr>
            <tr>
                <td>Telefon</td>
                <td><?php echo $accountdata->getAccountTelephone(); ?></td>
            </tr>
            <tr>
                <td>CV</td>
                <td><?php
                    //verificare daca exista vreun nume de CV incarcat in baza de date
                    if ($accountdata->getAccountCV() == NULL) {
                        echo "Niciun CV incarcat!";
                    }
                    //daca exista un nume de CV in baza de date, verificam daca fisierul propriu zis exista in folder
                    else if ($accountdata->getAccountCV() != NULL) {
                        $files = scandir("cv_folder"); // genereaza o lista cu documentele din folder
                        $exist = false;
                        for ($a = 2; $a < count($files); $a++) {
                            if ($files[$a] == $accountdata->getAccountCV()) {
                                $exist = true;
                                break;
                            }
                        }
                        //daca acesta exista ii afisam numele ca pe un link de descarcare
                        if ($exist == true) {
                    ?>
                            <a download="<?php echo $accountdata->getAccountCV() ?>" href="cv_folder/<?php echo $accountdata->getAccountCV() ?>"><?php echo $accountdata->getAccountCV() ?></a>
                        <?php
                        }
                        //daca a fost sters din folder si nu mai exista , atunci facem update si in baza de date
                        else {
                            $accountdata = $static::deleteCV($conn, $userid);
                        ?>
                            <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AccountInfoPage.php">
                    <?php
                        }
                    }
                    ?>
                </td>
            </tr>
        </table>
        <?php
        if ($string == 0) {
            echo "Nu ai aplicat la niciun job momentan!";
        } else if ($string == 1) {
        ?>
            <table class="table, th, td">
                <caption>Joburile la care ati aplicat</caption>
                <thead>
                    <tr>
                        <th>Nume job</th>
                        <th>Data aplicare</th>
                        <th>Status aplicare</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $vizualizeUserApplications = $static1::vizualizareAplicari($conn, $userid);
                ?>
                </tbody>
            </table>
        <?php
        }
        ?>
    </div>
    <?php
    //daca user-ul nu are niciun CV incarcat, ii va aparea form-ul de incarcare a CV-ului
    if ($accountdata->getAccountCV() == NULL) {
    ?>
        <form method="POST" enctype="multipart/form-data" action="uploadCV.php">
            <hr class="mb-3">
            <p>Nu aveti un CV incarcat, incarcati unul chiar acum !</p>
            <input type="file" name="file" id="cv" style="width: fit-content;" onchange="updateInput1(this.value)">
            <script>
                function updateInput1(newvalue) {
                    document.getElementById("cv").value = newvalue;
                }
            </script>
            <input type="submit" value="Upload">
        </form>
    <?php
    }
    ?>

    <form method="POST">
        <div>
            <link rel="stylesheet" type="text/css" href="css/logout-button.css">
            <button class="logoutbutton" id="logout" name="logout">Log out</button>
        </div>
    </form>
</body>

</html>