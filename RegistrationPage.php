<?php
ini_set('log_errors','On');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('UserAccount.php');
//functie care sterge spatiile goale ,sterge backslash-urile si converteste catre caracterele speciale html
function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
$firstnameErr = $emailErr = $passwordErr = $telephoneErr = $lastnameErr = $accountidErr = "";
$accountExists = "";
$firstname = $lastname = $accountID = $email = $password1 = $telephone = "";

if (isset($_POST['register'])) {
  if (empty($_POST["firstname"])) {
    $firstnameErr = "Camp obligatoriu!";
  } else {
    $firstname = test_input($_POST["firstname"]);
    //verificare daca numele contine doar litere si spatii
    if (!preg_match("/^[a-zA-Z-' ]*$/", $firstname)) {
      $firstnameErr = "Doar litere si spatii";
    }
  }
  if (empty($_POST["lastname"])) {
    $lastnameErr = "Camp obligatoriu!";
  } else {
    $lastname = test_input($_POST["lastname"]);
    //verificare daca prenumele contine doar litere si spatii
    if (!preg_match("/^[a-zA-Z-' ]*$/", $lastname)) {
      $lastnameErr = "Doar litere si spatii";
    }
  }
  if (empty($_POST["accountID"])) {
    $accountidErr = "Camp obligatoriu!";
  } else {
    $accountID = test_input($_POST["accountID"]);
    //verificare daca id-ul contine doar litere si cifre,intre 5 si 10
    if (!preg_match('/^[a-zA-Z0-9]{5,10}$/', $accountID)) {
      $accountidErr = "Doar litere si cifre, lungime minima 5 caractere si maxima 10caractere";
    }
  }
  if (empty($_POST["password1"])) {
    $passwordErr = "Camp obligatoriu!";
  } else {
    $password1 = test_input($_POST["password1"]);
    //verificare daca parola are intre 5 si 30 caractere
    if (strlen($password1) < 8 || strlen($password1) > 30) {
      $passwordErr = "Parola trebuie sa contina minim 8 si maxim 30 de caractere";
    }
  }
  if (empty($_POST["email"])) {
    $emailErr = "Camp obligatoriu!";
  } else {
    $email = test_input($_POST["email"]);
    //verificare daca emailul are formatul potrivit
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Format nevalid";
    }
  }
  if (empty($_POST["telephone"])) {
    $telephoneErr = "Camp obligatoriu!";
  } else {
    $telephone = test_input($_POST["telephone"]);
    //verificare daca numarul de telefon are doar cifre si exact 10 caractere
    if (!preg_match('/^[0-9]{10,10}$/', $telephone)) {
      $telephoneErr = "Format nevalid,exact 10 caractere";
    }
  }
  //adaugarea unui cont de utilizator in DB, in cazul in care toate campurile sunt completate corect si email-ul,numarul de telefon sau numele de utilizator nu sunt folosite
  $newaccount = new UserAccount($conn, $firstname, $lastname, $accountID, $password1, $email, $telephone);
  if (empty($firstnameErr) && empty($lastnameErr) && empty($emailErr) && empty($passwordErr) && empty($accountidErr) && empty($telephoneErr)) {
    if ($newaccount->checkUserAccount() == 0) {
      $accountExists = "Nume de utilizator, email si numar de telefon deja utilizate!";
    } else if ($newaccount->checkUserAccount() == 1) {
      $accountExists = "Nume de utilizator si email deja utilizate!";
    } else if ($newaccount->checkUserAccount() == 2) {
      $accountExists = "Nume de utilizator si numar de telefon deja utilizate!";
    } else if ($newaccount->checkUserAccount() == 3) {
      $accountExists = "Email si numar de telefon deja utilizate!";
    } else if ($newaccount->checkUserAccount() == 4) {
      $accountExists = "Nume de utilizator deja utilizat!";
    } else if ($newaccount->checkUserAccount() == 5) {
      $accountExists = "Email deja utilizat!";
    } else if ($newaccount->checkUserAccount() == 6) {
      $accountExists = "Numar de telefon deja utilizat!";
    } else if ($newaccount->checkUserAccount() == -1) {
      $newaccount->insertIntoDb();
      echo "<script>alert('Te-ai inregistrat cu succes')</script>";
?>
      <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/LoginPage.php">
<?php
    }
  }
}
if (isset($_POST['login'])) {
  header("location:LoginPage.php");
}
?>

<html>

<head>
  <title>Registration/sing up</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>

<body>

  <div>
    <form action="RegistrationPage.php" method="post">
      <div class="container">
        <div class="row">
          <div class="col-sm-3">

            <h1>Inregistrare cont</h1>
            <p>Completati toate campurile pentru a va inregistra pe site.</p>
            <hr class="mb-3">

            <input class="form-control" style="margin-top: 20px;" placeholder="Nume de familie" type="text" name="firstname" value="<?php echo $firstname; ?>" autofocus>
            <span class="error" style="color:red"> <?php echo $firstnameErr; ?></span>

            <input class="form-control" style="margin-top: 20px;" placeholder="Prenume" type="text" value="<?php echo $lastname; ?>" name="lastname" autofocus>
            <span class="error" style="color:red"> <?php echo $lastnameErr; ?></span>

            <input class="form-control" style="margin-top: 20px;" placeholder="Account ID" type="text" name="accountID" autocomplete="false" value="<?php echo $accountID; ?>" autofocus>
            <span class="error" style="color:red"> <?php echo $accountidErr ?></span>

            <input class="form-control" style="margin-top: 20px;" placeholder="Parola" type="password" name="password1" autocomplete="false" value="<?php echo $password1; ?>" autofocus>
            <span class="error" style="color:red"> <?php echo $passwordErr; ?></span>

            <input class="form-control" style="margin-top: 20px;" placeholder="Email" type="text" name="email" value="<?php echo $email; ?>" autofocus>
            <span class="error" style="color:red"><?php echo $emailErr ?></span>

            <input class="form-control" style="margin-top: 20px;" placeholder="Numar telefon" type="text" name="telephone" value="<?php echo $telephone; ?>" autofocus>
            <span class="error" style="color:red"><?php echo $telephoneErr ?></span>

            <hr class="mb-3">
            <span class="error" style="color:red"><?php echo $accountExists ?></span>
            <hr class="mb-3">
            <input class="btn btn-primary" type="submit" name="register" value="Inregistrare">

            <hr class="mb-3">
            <p>Aveti deja cont?</p>
            <input class="btn btn-primary" type="submit" name="login" value="Conectare">

          </div>
        </div>
      </div>
    </form>
  </div>

</body>

</html>