<?php
session_start();
ini_set('log_errors','On');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

use PHPMailer\PHPMailer\SMTP;

ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('Jobs.php');
require_once('Test.php');
require_once('Applications.php');
require('PHPMailer/PHPMailerAutoload.php');
require_once('PHPMailer/class.smtp.php');

if (isset($_POST['Back'])) {
    header("location:UpdateApplicationsPage.php");
}

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

        $mail->setFrom('licentafirm2022@gmail.com', 'SL.Tech');
        $mail->addAddress($emailCandidat, $numeCompletCandidat);
        $mail->addReplyTo('licentafirm2022@gmail.com', 'SL.Tech');

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
    <div class="registration_div" style="margin-top: 50px; margin-left: 50px;">
    <form method="post">
        <div>
            <label class="label_AdaugaJob">Catre:</label>
            <input class="input_AdaugaJob" style="font-weight: bold;" type="text" name="numeCandidat" value="<?php echo $numeCompletCandidat;?>" readonly>
        </div>
        <div>
            <label class="label_AdaugaJob">Email:</label>
            <input class="input_mail" type="text" name="numeCandidat" value="<?php echo $emailCandidat;?>" readonly>
        </div>
        <div class="div_mail" style="margin-top: 10px;">
        <div class="registration_form">
            <label class="label_AdaugaJob">Status nou job:</label>
            <select class="select_adaugaJob" name="statusCandidatura" style="margin-top: 10px;">
                <option value="Admis" >Admis</option>
                <option value="Respins">Respins</option>
            </select>
            </div>
            <div style="margin-left: 50px; display: flex; align-items: center; padding-top: 35px;">
            <input class="button_adaugaJob" type="submit" name="trimite" value="Trimite email">
            </div>
            </div>
            <input type="submit" class="button_backMail" name="Back" value="Inapoi" style="margin-left: 120px;">
    </form>
    </div>
</body>

</html>