<?php
session_start();
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
if (isset($_POST['Back'])) {
    header("location:RegistrationPage.php");
}
$accountidErr2 = $passwordErr2 = $loginpassErr = $loginidErr = "";
$accountID2 = $password2 = "";
if (isset($_POST['Connect'])) {
    if (empty($_POST["accountID2"])) {
        $accountidErr2 = "Camp obligatoriu!";
    } else {
        $accountID2 = test_input($_POST["accountID2"]);
    }
    if (empty($_POST['password2'])) {
        $passwordErr2 = "Camp obligatoriu!";
    } else {
        $password2 = test_input($_POST["password2"]);
    }
    if (empty($accountidErr2) && empty($passwordErr2)) {
        $result = new AccountLogin($conn, $accountID2, $password2);
        if ($result->checkAccountForLoging() == 2 || $result->checkAccountForLoging() == 5) {
            $loginpassErr = "Parola introdusa este incorecta!";
            $password2 = "";
        } else if ($result->checkAccountForLoging() == 3 || $result->checkAccountForLoging() == 6) {
            $loginidErr = "Contul dumneavoastra nu a fost gasit!";
            $accountID2 = "";
            $password2 = "";
        } else if ($result->checkAccountForLoging() == 1) {
            $_SESSION['accountid'] = $result->getAccountID();
            $_SESSION['role'] = $result->checkAccountRole();
            echo "<script>alert('Te-ai logat cu succes')</script>";

?>
            <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/MainPage.php">
        <?php
        } else if ($result->checkAccountForLoging() == 4) {
            $_SESSION['accountid'] = $result->getAccountID();
            $_SESSION['role'] = $result->checkAccountRole();
            echo "<script>alert('Te-ai logat cu succes')</script>";
        ?>
            <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/MainPage.php">
<?php
        }
    }
}
?>
<html>

<body>
    <div>
        <form action="LoginPage.php" method="post">
            <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <h1>Intrati in contul dumneavoastra</h1>
                        <p>Completati toate campurile pentru logarea cu succes.</p>
                        <hr class="mb-3">

                        <input class="form-control" style="margin-top: 20px;" placeholder="Account ID" type="text" name="accountID2" value="<?php echo $accountID2; ?>" autocomplete="false">
                        <span class="error" style="color:red"> <?php echo $accountidErr2; ?></span>

                        <input class="form-control" style="margin-top: 20px;" placeholder="Parola" type="password" name="password2" value="<?php echo $password2; ?>" autocomplete="false">
                        <span class="error" style="color:red"> <?php echo $passwordErr2; ?></span>

                        <hr class="mb-3">
                        <span class="error" style="color:red"><?php echo $loginidErr ?></span>
                        <span class="error" style="color:red"><?php echo $loginpassErr ?></span>
                        <hr class="mb-3">
                        <input type="submit" class="btn btn-primary" name="Connect" value="Conectare">
                        <hr class="mb-3">
                        <input type="submit" class="btn btn-primary" name="Back" value="Inapoi la inregistrare">
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>