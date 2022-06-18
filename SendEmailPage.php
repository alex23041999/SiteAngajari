<?php
session_start();

use PHPMailer\PHPMailer\SMTP;

ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('Jobs.php');
require_once('Test.php');
require_once('Applications.php');
require('PHPMailer/PHPMailerAutoload.php');
require_once('PHPMailer/class.smtp.php');



$app = new Applications();
$application = $app->returnApplicationDetails($conn, $_GET['applicationID']);
$emailCandidat = $application->getApplicationEmailCandidat();
$numeJobAplicatie = $application->getApplicationNumeJob();
$numeCandidat = $application->getApplicationNumeCandidat();
$prenumeCandidat = $application->getApplicationPrenumeCandidat();
$numeCompletCandidat = $numeCandidat . " " . $prenumeCandidat;

$subject = "Raspuns aplicare";
$body1 = nl2br("Salut,$numeCompletCandidat!\n In urma revizuirii aplicatiei tale pentru job-ul: $numeJobAplicatie ,dorim sa te invitam la urmatoare etapa a procesului de recrutare ce va constaintr-un interviu fizic la sediul firmei noastre.\r\n Vei primi in cel mai scurt timp posibil detaliile legate de locatia si ora interviului.\n
               Toate cele bune!");
$body2 = nl2br("Salut,$numeCompletCandidat!\n In urma revizuirii aplicatiei tale pentru job-ul: $numeJobAplicatie ,dorim sa te informam ca am decis sa mergem mai departe cu alti candidati.\nIti multumim pentru interesul acordat si iti uram succes pe mai departe.\n
                Toate cele bune!");

function sendMail($emailCandidat, $numeCompletCandidat, $subject, $body)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'licentafirm2022@gmail.com';
        $mail->Password   = 'juxqwnfxlrgswnlx';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom('licentafirm2022@gmail.com', 'Nume firma');
        $mail->addAddress($emailCandidat, $numeCompletCandidat);
        $mail->addReplyTo('licentafirm2022@gmail.com', 'Nume firma');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = 'Browser-ul tau nu suporta continutul mail-ului!';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
if(isset($_POST['trimite'])){
    if(strcmp($_POST['statusCandidatura'],"Admis") == 0){
        if(sendMail($emailCandidat,$numeCompletCandidat,$subject,$body1) == true){
            echo "<script>alert('Mail trimis cu succes!')</script>";
            ?>
             <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/UpdateApplicationsPage.php">
            <?php
        }else {
            echo "<script>alert('Mail-ul nu a fost trimis, ceva a mers prost!')</script>";
            ?>
            <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/UpdateApplicationsPage.php">
           <?php
        }
    }else{
        if(sendMail($emailCandidat,$numeCompletCandidat,$subject,$body2) == true){
            echo "<script>alert('Mail trimis cu succes!')</script>";
            ?>
             <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/UpdateApplicationsPage.php">
            <?php
        }else {
            echo "<script>alert('Mail-ul nu a fost trimis, ceva a mers prost!')</script>";
            ?>
            <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/UpdateApplicationsPage.php">
           <?php
        }
    }
}
?>
<html>

<head>
    <title>Trimite mail catre candidat</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
</head>

<body>
    <form method="post">
        <div>
            <label>Catre:</label>
            <input type="text" name="numeCandidat" value="<?php echo $numeCompletCandidat;?>" readonly>
        </div>
        <div>
            <label>Email:</label>
            <input type="text" name="numeCandidat" value="<?php echo $emailCandidat;?>" readonly>
        </div>
        <div class="form-group" style="margin-top: 10px;">
            <label>Status nou job:</label>
            <select name="statusCandidatura">
                <option value="Admis" >Admis</option>
                <option value="Respins">Respins</option>
            </select>
        </div>
        <div>
            <input type="submit" name="trimite" value="Trimite email">
            <input type="submit" name="verificare" value="verificare">
        </div>
    </form>
</body>

</html>