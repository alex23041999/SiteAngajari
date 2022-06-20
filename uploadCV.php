<?php
session_start();
error_reporting(E_ALL ^ E_WARNING); 
require_once('AccountDetails.php');
$updateCV = new AccountDetails();
$static = 'AccountDetails';
$file = $_FILES["file"];
if (($updateCV = $static::verifyCvExistance($conn, $file["name"])) == 1) {
    //salvare cv in folderul "cv_folder"
    move_uploaded_file($file["tmp_name"], "cv_folder/" . $file["name"]);
    //facem update in baza de date, in funcite de user-ul care a incarcat CV-ul , cu numele CV-ului pe care il vom folosi ulterior la descarcarea lui
    $_SESSION['numeCV'] = $file["name"];
    $accountid = $_SESSION['accountid'];
    if(strcmp($file["name"],"") ==0 ){
        echo "<script>alert('Alegeti un CV valabil!')</script>";
        ?>
        <html>
        <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AccountInfoPage.php">
    <?php
    }else {
        $updateCV = $static::updateCV($conn, $accountid, $file["name"]);
        echo "<script>alert('Ai incarcat CV-ul cu succes !')</script>";
    ?>
        <html>
        <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/MainPageUser.php">
    <?php
    }
} else if(($updateCV = $static::verifyCvExistance($conn, $file["name"])) == 0){
    echo "<script>alert('Va rugam sa incarcati un CV cu alt nume, deoarece acesta este deja existent in baza noastra !')</script>";
    ?>
    <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AccountInfoPage.php">
    <?php
}   
?>

</html>