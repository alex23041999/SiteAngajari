<?php
session_start();
ini_set('log_errors', 'On');
ini_set('error_reporting', E_ALL);
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


$app = new Applications();
$application = $app->returnApplicationDetails($conn, $_GET['applicationID']);
$emailCandidat = $application->getApplicationEmailCandidat();
$numeJobAplicatie = $application->getApplicationNumeJob();
$numeCandidat = $application->getApplicationNumeCandidat();
$prenumeCandidat = $application->getApplicationPrenumeCandidat();
$numeCompletCandidat = $numeCandidat . " " . $prenumeCandidat;

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
$appointmentErr = "";

if (isset($_POST['trimite'])) {
    $result = 0;
    if (isset($_POST['dateSelected']) && isset($_POST['hourSelected'])) {
        $date = $_POST['dateSelected'];
        $hour = $_POST['hourSelected'];
        $sql = "SELECT date_appointment,hour_appointment FROM appointments WHERE DATE(date_appointment) = '$date' AND hour_appointment = '$hour'";
        $result = mysqli_query($conn, $sql);
    }
    $subject = "Raspuns aplicare";
    $body1 = nl2br("Salut,$numeCompletCandidat!\n În urma revizuirii aplicației tale pentru job-ul: $numeJobAplicatie ,dorim să te invităm la următoarea etapă a procesului de recrutare ce va consta într-un interviu fizic la sediul firmei noastre, pe Str.Politehnica, Nr.7, etaj 3, în data de $date, ora $hour.\n
    Toate cele bune!");
    $body2 = nl2br("Salut,$numeCompletCandidat!\n În urma revizuirii aplicației tale pentru job-ul: $numeJobAplicatie ,dorim să te informăm ca am decis să mergem mai departe cu alți candidați.\nÎți mulțumim pentru interesul acordat și îți urăm succes pe mai departe.\n
                Toate cele bune!");
    if (strcmp($_POST['statusCandidatura'], "Admis") == 0) {
        if (mysqli_num_rows($result) > 0) {
            $appointmentErr = "Există deja o programare la această oră!";
        } else if (date($date) < date("Y-m-d")) {
            $appointmentErr = "Nu poți programa un interviu în trecut!";
        } else         if (mysqli_num_rows($result) == 0) {
            if (sendMail($emailCandidat, $numeCompletCandidat, $subject, $body1) == true) {
                $sql1 = "INSERT INTO appointments(date_appointment,hour_appointment,candidate,email_candidate) VALUES ('$date','$hour','$numeCompletCandidat','$emailCandidat')";
                mysqli_query($conn, $sql1);
                echo "<script>alert('Mail trimis cu succes!')</script>";

?>
                <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/UpdateApplicationsPage.php">
            <?php
            } else {
                echo "<script>alert('Mail-ul nu a fost trimis, ceva a mers prost!')</script>";

            ?>
                <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/UpdateApplicationsPage.php">
            <?php
            }
        }
    } else {
        if (sendMail($emailCandidat, $numeCompletCandidat, $subject, $body2) == true) {
            echo "<script>alert('Mail trimis cu succes!')</script>";

            ?>
            <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/UpdateApplicationsPage.php">
        <?php
        } else {
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
    <title>Anunț candidat</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
    <link rel="shortcut icon" type="image/png" href="css/images/FavIcon.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div style="padding-bottom: 50px;display: flex; align-items: center; flex-direction: column;">
    <div style="display: flex; align-items: flex-start; padding-left: 50px;padding-right: 50px;">
        <form method="post" style="display: flex; flex-direction: column; align-items: flex-start; padding-left: 10px; margin-right: 50px;">
            <div class="divSendMail" style="display: flex; padding: 50px;">
                <div class="mail_div1" style="margin-top: 50px; margin-right: 50px;">

                    <div>
                        <label class="label_AdaugaJob">Catre:</label>
                        <input class="input_AdaugaJob" style="font-weight: bold;" type="text" name="numeCandidat" value="<?php echo $numeCompletCandidat; ?>" readonly>
                    </div>
                    <div>
                        <label class="label_AdaugaJob">Email:</label>
                        <input class="input_mail" type="text" name="numeCandidat" value="<?php echo $emailCandidat; ?>" readonly>
                    </div>
                    <div class="div_mail" style="margin-top: 50px;">
                        <div class="registration_form">
                            <label class="label_AdaugaJob">Status candidat:</label>
                            <select class="select_adaugaJob" name="statusCandidatura" style="margin-top: 10px;">
                                <option value="Admis">Admis</option>
                                <option value="Respins">Respins</option>
                            </select>
                        </div>
                        <div style="display: flex; align-items: flex-start; padding-top: 40px; margin-left: 100px;">
                            <input class="button_adaugaJob" type="submit" name="trimite" value="Trimite email">
                        </div>
                    </div>
                    <span class="error" style="color:red; font-size: 15px;"> <?php echo $appointmentErr ?></span>
                    <div>
                        <input type="text" id="dataSelectata" name="dateSelected" required autocomplete="off" readonly style="display: none;">
                    </div>
                </div>
                <div style="display: flex; align-items: flex-end; flex-direction: column;">
                    <div class="container" style="margin: 0%;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="calendar calendar-first" id="calendar_first" style="border: 2px solid black;">
                                    <div class="calendar_header">
                                        <button class="switch-month switch-left"> <i class="fa fa-chevron-left"></i></button>
                                        <h2></h2>
                                        <button class="switch-month switch-right"> <i class="fa fa-chevron-right"></i></button>
                                    </div>
                                    <div class="calendar_weekdays"></div>
                                    <div class="calendar_content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-left: 55px;">
                        <script>
                            function updateInput(newvalue) {
                                document.getElementById("numeJobNou").value = newvalue;
                            }
                        </script>
                        <label for="oraSelectata" class="label_adaugaJob">Alege ora interviului:</label>
                        <input type="time" id="oraSelectata" name="hourSelected" min="13:00" max="19:00" onchange="updateInput(this.value)" required>
                        <small style="font-size: 15px;font-weight: bold;">Interviurile au loc între 13:00-19:00</small>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div style="display: flex; flex-direction: column; align-items: center;">
        <div class="table_divSendMail">
            <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
            <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
            <table id="boostrapTable1" class="table table-striped" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 25%;">Candidat</th>
                        <th style="width: 25%;">Email candidat</th>
                        <th style="width: 25%;">Dată interviu</th>
                        <th style="width: 25%;">Oră interviu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $currentDate = date("Y-m-d");
                    $sql2 = "SELECT candidate,email_candidate,date_appointment,hour_appointment FROM appointments WHERE DATE(date_appointment) > '$currentDate'";
                    $result = mysqli_query($conn, $sql2);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                                            <tr>
                                                <td class=\"td_buttonAvbJob\">" . ($row['candidate']) . "</td>
                                                <td class=\"td_buttonAvbJob\">" . ($row['email_candidate']) . "</td>
                                                <td class=\"td_buttonAvbJob\">" . ($row['date_appointment']) . "</td>
                                                <td class=\"td_buttonAvbJob\">" . ($row['hour_appointment']) . "</td>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <script src="js/table_design1.js"></script>
        </div>
        <a href="UpdateApplicationsPage.php"><button class="button_backMail" style="margin-top: 20px;">Înapoi</button></a>
    </div>
    </div>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>