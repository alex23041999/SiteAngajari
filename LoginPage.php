<?php
session_start();
ini_set('log_errors', 'On');
ini_set('error_reporting', E_ALL);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('UserAccount.php');
require_once('AccountLogin.php');
require_once('AccountDetails.php');
//functie care sterge spatiile goale ,sterge backslash-urile si converteste catre caracterele speciale html
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if (isset($_POST['logoutButton'])) {
    session_destroy();
    echo "<script>alert('Te-ai deconectat cu succes !')</script>";
}

if (isset($_POST['Back'])) {
    header("location:RegistrationPage.php");
}
$accountidErr2 = $passwordErr2 = $loginpassErr = $loginidErr = "";
$accountID2 = $password2 = "";
if (isset($_POST['Connect'])) {
    if (empty($_POST["accountID2"])) {
        $accountidErr2 = "Câmp obligatoriu!";
    } else {
        $accountID2 = test_input($_POST["accountID2"]);
    }
    if (empty($_POST['password2'])) {
        $passwordErr2 = "Câmp obligatoriu!";
    } else {
        $password2 = test_input($_POST["password2"]);
    }
    if (empty($accountidErr2) && empty($passwordErr2)) {
        $result = new AccountLogin($conn, $accountID2, $password2);
        if ($result->checkAccountForLoging() == 2 || $result->checkAccountForLoging() == 5) {
            $loginpassErr = "Parola introdusă este incorectă!";
            $password2 = "";
        } else if ($result->checkAccountForLoging() == 3 || $result->checkAccountForLoging() == 6) {
            $loginidErr = "Contul dumneavoastră nu a fost găsit!";
            $accountID2 = "";
            $password2 = "";
        } else if ($result->checkAccountForLoging() == 1) {
            $_SESSION['accountid'] = $result->getAccountID();
            $_SESSION['role'] = $result->checkAccountRole();
             echo "<script>alert('Te-ai logat cu succes')</script>";

 ?>
             <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/MainPageUser.php">
         <?php
        } else if ($result->checkAccountForLoging() == 4) {
            $_SESSION['accountid'] = $result->getAccountID();
            $_SESSION['role'] = $result->checkAccountRole();
            echo "<script>alert('Te-ai logat cu succes')</script>";
         ?>
             <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/MainPageAdmin.php">
 <?php
        }
    }
}
?>
<html>

<head>
    <title>Logare</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/png" href="css/images/FavIcon.png">
</head>

<body>
    <div class="registration_background">
    <div class="logo-firm"></div>
    <div class="registration_div">
        <form action="LoginPage.php" method="post" class="registration_form">
                        <p class="registration-text1">Conectați-vă la contul dumneavoastră</p>
                        <input class="input_registration" placeholder="Account ID" type="text" name="accountID2" value="<?php echo $accountID2; ?>" autocomplete="false">
                        <span style="color:red; font-size: 15px;"> <?php echo $accountidErr2; ?></span>

                        <input class="input_registration" placeholder="Parola" type="password" name="password2" value="<?php echo $password2; ?>" autocomplete="false">
                        <span style="color:red; font-size: 15px;"> <?php echo $passwordErr2; ?></span>

                        <span style="color:red; font-size: 15px;"><?php echo $loginidErr ?></span>

                        <span style="color:red; font-size: 15px;margin-bottom: 10px;"><?php echo $loginpassErr ?></span>

                        <button type="submit" class="registration_button" name="Connect" value="Conectare" style="width: 250px;margin-bottom: 10px;"><i class="fa fa-sign-in" aria-hidden="true" style="margin-right: 10px;"></i>Conectare</button>
                        <button type="submit" class="registration_button" name="Back" value="Înapoi la înregistrare" style="width: 250px;"><i class="fa fa-arrow-left" aria-hidden="true" style="margin-right: 10px;"></i>Înapoi la înregistrare</button>
        </form>
</div>
    </div>
</body>

</html>