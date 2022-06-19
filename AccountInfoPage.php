<?php
session_start();
ini_set('log_errors', 'On');
ini_set('error_reporting', E_ALL);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
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

$accountLanguages = new AccountDetails();
$checkLanguagesExistance = $accountLanguages->checkLanguagesExistance($conn, $userid);

if (isset($_POST['saveLanguages'])) {
    $stringLanguages = "";
    for ($i = 1; $i < 4; $i++) {
        if (isset($_POST['language' . $i])) {
            $stringLanguages = $stringLanguages . $_POST['language' . $i];
        }
    }
    $updateLanguages = $accountLanguages->updateLanguages($conn, $userid, $stringLanguages);
    if ($updateLanguages == true) {
        echo "<script>alert('Limbaje adaugate cu succes!')</script>";
?>
        <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AccountInfoPage.php">
    <?php
    } else {
        echo "<script>alert('Ceva nu a mers bine, reincearca!')</script>";
    }
}
if (isset($_POST['updateLanguages'])) {
    $stringLanguages = "";
    for ($i = 1; $i < 4; $i++) {
        if (isset($_POST['language' . $i])) {
            $stringLanguages = $stringLanguages . $_POST['language' . $i];
        }
    }
    $updateLanguages = $accountLanguages->updateLanguages($conn, $userid, $stringLanguages);
    if ($updateLanguages == true) {
        echo "<script>alert('Limbaje updatate cu succes!')</script>";
    ?>
        <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AccountInfoPage.php">
<?php
    } else {
        echo "<script>alert('Ceva nu a mers bine, reincearca!')</script>";
    }
}
?>
<html>

<head>
    <title>Profil</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
</head>

<body>
    <script src="js/insertUserLanguages.js"></script>
    <link rel="stylesheet" type="text/css" href="css/table_design.css">
    <form method="post" class="menu" name="accountDetails" id="accDetails" action="AccountInfoPage.php">
        <div>
            <link rel="stylesheet" type="text/css" href="css/logout-button.css">
            <button class="logoutbutton" id="logout" name="logout">Log out</button>
        </div>
        <div>
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
        </div>
        <div class="under" style="display: <?php if ($checkLanguagesExistance == 1) {
                                                echo "none";
                                            } else {
                                                echo "unset";
                                            } ?>;">
            <label>Adaugati-va limbajele cunoscute</label>
            <button type="button" id="generate" class="button_quiz" name="chooseLanguages" onclick="createLanguageField()">Adauga limbaje</button>
            <div class="selectAdd" style="display: none;" id="divSelect">
                <select name="limbaj" class="select" id="limbaj">
                    <option value="Java">Java</option>
                    <option value="JavaScript">JavaScript</option>
                    <option value="MySQL">MySQL</option>
                    <option value="Python">Python</option>
                    <option value="C++">C++</option>
                    <option value="PHP">PHP</option>
                    <option value="C#">C#</option>
                    <option value="Angular">Angular</option>
                </select>
            </div>
            <div>
                <p style="display: none;" id="maxLimit">Puteti adauga maxim 3 limbaje cunoscute!</p>
            </div>
            <div id="languages"></div>
            <div>
                <button type="submit" id="saveLanguages" class="button_quiz" name="saveLanguages" style="display: none;">Salveaza limbajele alese</button>
            </div>
        </div>

        <div class="under" style="display: <?php if ($checkLanguagesExistance == 1) {
                                                echo "unset";
                                            } else {
                                                echo "none";
                                            } ?>;">
            <div>
                <table class="table, th, td" style="margin-top: 20px;">
                    <caption>Limbaje cunoscute</caption>
                    <?php
                    $returnLanguages = $accountLanguages->returnLanguages($conn, $userid);
                    ?>
                </table>
            </div>
            <div>
                <label>Puteti sa va inlocuiti limbajele stiute</label>
                <button type="button" id="generate1" class="button_quiz" name="chooseLanguages1" onclick="createLanguageField1()">Adauga limbaje</button>
                <div class="selectAdd" style="display: none;" id="divSelect1">
                    <select name="limbaj1" class="select" id="limbaj1">
                        <option value="Java">Java</option>
                        <option value="JavaScript">JavaScript</option>
                        <option value="MySQL">MySQL</option>
                        <option value="Python">Python</option>
                        <option value="C++">C++</option>
                        <option value="PHP">PHP</option>
                        <option value="C#">C#</option>
                        <option value="Angular">Angular</option>
                    </select>
                </div>
            </div>
            <div>
                <p style="display: none;" id="maxLimit1">Puteti adauga maxim 3 limbaje cunoscute!</p>
            </div>
            <div id="languages1"></div>
            <div>
                <button type="submit" id="updateLanguages" class="button_quiz" name="updateLanguages" style="display: none;">Salveaza limbajele alese</button>
            </div>
        </div>
        <div style="display: none;">
            <?php
            $string = $vizualizeUserApplications->vizualizareAplicari($conn, $userid);
            ?>
        </div>
        <?php
        if ($string == 0) {
        ?><div>
                <label>Nu ai aplicat la niciun job momentan!</label>
            <?php
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
            </div>
        <?php
        }
        ?>
    </form>
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
</body>

</html>