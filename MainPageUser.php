<?php
session_start();
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('AccountDetails.php');
$userid = $_SESSION['accountid'];
$role = $_SESSION['role'];
//in cazul in care utilizatorul/admin-ul se deconecteaza , se sterg datele cache din sesiune
if (isset($_POST['logout'])) {
    session_destroy();
    header("location:LoginPage.php");
}
//buton care duce la pagina de profil a contului
if (isset($_POST['myaccount'])) {
    header("location:AccountInfoPage.php");
}
//buton care duce la pagina de prezentare a firmei
if (isset($_POST['companyinfo'])) {
    header("location:CompanyInfoPage.php");
}
//buton care duce la pagina de adaugare/stergere job-uri(doar pentru admin)
if (isset($_POST['addjobs'])) {
    header("location:NewJobByAdmin.php");
}
//buton care duce la pagina de vizualizare job-uri si aplicare pt user
if (isset($_POST['availablejobs'])) {
    header("location:AvailableJobsPage.php");
}
//buton care duce la pagina de vizualizare job-uri si aplicare pt user
if (isset($_POST['updatejobs'])) {
    header("location:AdminJobModifierPage.php");
}

?>

<html>

<body>
    <form method="post">
        <link rel="stylesheet" type="text/css" href="css/logout-button.css">
        <div>
            <button class="logoutbutton" id="logout" name="logout">Log out</button>
        </div>
        <div>
            <link rel="stylesheet" type="text/css" href="css/myaccount_button.css">
            <button class="myaccountButton" type="submit" name="myaccount" <?php if ($role == "admin") { ?> style="display: none;" <?php } ?>>Contul meu</button>
        </div>
        <div>
            <button class="button" id="info" name="companyinfo">Informatii firma</button>
        </div>
        <div>
            <button class="button" id="jobs" name="availablejobs" <?php if ($role == "admin") { ?> style="display: none;" <?php } else {?> style="margin-top: 10px;" <?php } ?>>Vedeti job-uri disponibile</button>
        </div>
        <div>
            <button class="button" id="addjobs" name="addjobs" <?php if ($role == "user") { ?> style="margin-top: 20px; display: none;" <?php } else {?> style="margin-top: 10px;" <?php } ?>>Adauga joburi</button>
        </div>
        <div>
            <button class="button" id="updatejobs" name="updatejobs" <?php if ($role == "user") { ?> style="margin-top: 20px; display: none;" <?php } else {?> style="margin-top: 10px;" <?php } ?>>Modifica joburi</button>
        </div>
    </form>
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <div class="footer">
        <div class="row">
            <div class="column">
                <p>Contacte</p>
                <p>Numar telefon: 0351 444 188</p>s
            </div>
            <div class="column">
                <p>Adresa email:licenceproject@gmail.com</p>
                <p>Adresa:Str.Bucuriei , Nr.23</p>
            </div>
        </div>
    </div>
    </head>

</body>

</html>