<?php
session_start();
ini_set('log_errors', 'On');
ini_set('error_reporting', E_ALL);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
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
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/png" href="css/images/FavIcon.png">
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
        <div class="main-contentProfile">
            <script src="js/insertUserLanguages.js"></script>
            <?php
            //daca user-ul nu are niciun CV incarcat, ii va aparea form-ul de incarcare a CV-ului
            if ($accountdata->getAccountCV() == NULL) {
            ?>
                <div class="profile_div">
                    <form method="POST" enctype="multipart/form-data" action="uploadCV.php">
                        <p>Nu aveti un CV incarcat, incarcati unul chiar acum !</p>
                        <div style="display: flex; justify-content: center; flex-direction: column;">
                            <div>
                                <label for="cv" style="font-size: 20px; color: rgb(172, 165, 165); display: flex; flex-direction: column; align-items: flex-start;">
                                    Alege CV <br />
                                    <input class="addCV" type="file" name="file" id="cv" style="width: fit-content;" onchange="updateInput1(this.value)">
                                    <br />
                                    <span id="cvName" style="font-size: 15px; color: black; margin-top: 10px;"></span>
                                </label>
                                <script>
                                    let input = document.getElementById("cv");
                                    let cvName = document.getElementById("cvName")

                                    input.addEventListener("change", () => {
                                        let inputCV = document.querySelector("input[type=file]").files[0];

                                        cvName.innerText = inputCV.name;
                                    })
                                </script>
                                <script>
                                    function updateInput1(newvalue) {
                                        document.getElementById("cv").value = newvalue;
                                    }
                                </script>
                            </div>
                            <div>
                                <input class="button_CV" type="submit" value="Încarcă CV">
                            </div>
                        </div>
                    </form>
                </div>
                <?php
            }
                ?>
                <form method="post" name="accountDetails" id="accDetails" action="AccountInfoPage.php">
                    <div class="profile_div">
                        <div>
                            <p class="p_profile">Nume</p>
                            <p class="p_profile1"><?php echo $accountdata->getAccountLastName(); ?></p>
                        </div>
                        <p class="p_profile">Prenume</p>
                        <p class="p_profile1"><?php echo $accountdata->getAccountFirstName(); ?></p>
                        <p class="p_profile">Nume utilizator</p>
                        <p class="p_profile1"><?php echo $accountdata->getAccountID(); ?></p>
                        <p class="p_profile">Adresa email</p>
                        <p class="p_profile1"><?php echo $accountdata->getAccountEmail(); ?></p>
                        <p class="p_profile">Telefon</p>
                        <p class="p_profile1"><?php echo $accountdata->getAccountTelephone(); ?></p>
                        <p class="p_profile">CV</p>
                        <p>
                            <?php
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
                                    <a class="p_profile1" download="<?php echo $accountdata->getAccountCV() ?>" href="cv_folder/<?php echo $accountdata->getAccountCV() ?>"><?php echo $accountdata->getAccountCV() ?></a>
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
                        </p>
                    </div>
                    <div class="profile_div" style="display: <?php if ($checkLanguagesExistance == 1) {
                                                                    echo "none";
                                                                } else {
                                                                    echo "unset";
                                                                } ?>;">
                        <label class="label_raspunsTest">Adaugati-va limbajele cunoscute</label>
                        <button type="button" id="generate" class="button_CV" name="chooseLanguages" onclick="createLanguageField()">Adauga limbaje</button>
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
                            <button type="submit" id="saveLanguages" class="button_CV" name="saveLanguages" style="display: none;">Salveaza limbajele alese</button>
                        </div>
                    </div>

                    <div class="profile_div" style="padding-bottom: 10px;display: <?php if ($checkLanguagesExistance == 1) {
                                                                    echo "block";
                                                                } else {
                                                                    echo "none";
                                                                } ?>;">
                        <div style="padding: 5px;">
                        <label class="label_adaugaJob">Limbaje cunoscute</label>
                            <table class="table_language" style="margin-top: 20px; margin-bottom: 20px;">
                                <?php
                                $returnLanguages = $accountLanguages->returnLanguages($conn, $userid);
                                ?>
                            </table>
                        </div>
                        <div>
                            <label class="label_raspunsTest">Puteți să vă înlocuiți limbajele știute</label>
                            <button type="button" id="generate1" class="button_CV" name="chooseLanguages1" onclick="createLanguageField1()">Adauga limbaje</button>
                            <div class="select_adaugaJob" style="display: none;" id="divSelect1">
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
                            <button type="submit" id="updateLanguages" class="button_CV" name="updateLanguages" style="display: none;">Salveaza limbajele alese</button>
                        </div>
                    </div>
                    <div style="display: none;">
                        <?php
                        $string = $vizualizeUserApplications->vizualizareAplicari($conn, $userid);
                        ?>
                    </div>
                    <?php
                    if ($string == 0) {
                    ?>
                        <div class="profile_div" style="display: flex; flex-direction: column; align-items: flex-start;">
                            <label class="label_raspunsTest">Nu ai aplicat la niciun job momentan!</label>
                        <?php
                    } else if ($string == 1) {
                        ?>
                            <table class="table_app">
                                <caption style="margin-bottom: 20px; font-size: 15px; font-weight: bold;">Joburile la care ati aplicat</caption>
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
        </div>
    </div>
</body>

</html>