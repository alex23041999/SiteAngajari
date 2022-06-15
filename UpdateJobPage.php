<?php
session_start();
ini_set('display_errors', 1);
//functie care sterge spatiile goale ,sterge backslash-urile si converteste catre caracterele speciale html
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
require_once('DbConnection.php');
require_once('Jobs.php');
require_once('Test.php');

$idjob = $numejobVechi = $descrierejobVechi = $cerintejobVechi = $statusjobVechi = "";
$idjob = $_GET['updateID'];
$numejobVechi = $_GET['updatenumeJob'];
$descrierejobVechi = $_GET['updatedescriereJob'];
$cerintejobVechi = $_GET['updatecerinteJob'];
$statusjobVechi = $_GET['updatestatusJob'];
$numeTestVechi = $_GET['updatenumetestJob'];
$updateJob = new Jobs();
$numejobNou = $descrierejobNou = $cerintejobNou = $statusjobNou = $numetestNou = "";
$numejobErr = $descriereErr = $cerinteErr = $numeTestErr ="";

if (isset($_POST['updateJob'])) {
    if (empty($_POST["numejobNou"])) {
        $numejobErr = "Camp obligatoriu!";
    } else {
        $numejobNou = test_input($_POST["numejobNou"]);
    }
    if (empty($_POST["descrierejobNou"])) {
        $descriereErr = "Camp obligatoriu!";
    } else {
        $descrierejobNou = test_input($_POST["descrierejobNou"]);
    }
    if (empty($_POST["cerintejobNou"])) {
        $cerinteErr = "Camp obligatoriu!";
    } else {
        $cerintejobNou = test_input($_POST["cerintejobNou"]);
    }
    $newTest = new Test();
    $numeTest = $_POST["numeTestNou"];
    if (empty($_POST["numeTestNou"])) {
        $numeTestErr = "Camp obligatoriu!";
    } else if($newTest->checkTestNameExistance($conn, $numeTest) == 0){
        $numeTestErr = "Nu puteti face update cu un test care nu exista!";
    } else{
        $numetestNou = test_input($_POST["numeTestNou"]);
    }
    $statusjobNou = $_POST['statusnouJob'];
    if (empty($numejobErr) && empty($descriereErr) && empty($numeTestErr)) {
        $updateJob->updateJobs($conn, $idjob, $numejobNou, $descrierejobNou, $cerintejobNou, $statusjobNou,$numetestNou);
        echo "<script>alert('Job modificat cu succes')</script>";
?>
        <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AdminJobModifierPage.php">
<?php
    }
}
if (isset($_POST['Back'])) {
    header("location:AdminJobModifierPage.php");
}

?>
<html>
<title>Job Update</title>

<form method="post">
    <h1>Confirmare valori noi pentru job-ul selectat</h1>
    <div>
        <div class="form-control" style="margin-top: 10px;">
            <input type="text" id="numeJobNou" name="numejobNou" style="height: 30px;" value="<?php echo $numejobVechi; ?>" onchange="updateInput(this.value)">
            <span class="error" style="color:red"> <?php echo $numejobErr; ?></span>
            <script>
                function updateInput(newvalue) {
                    document.getElementById("numeJobNou").value = newvalue;
                }
            </script>
        </div>
        <div class="form-control" style="margin-top: 10px;">
            <textarea class="form-control" id="descriereJobNou" rows="10" cols="50" style="width: 500px;" name="descrierejobNou" onchange="updateInput1(this.value)"><?php echo $descrierejobVechi; ?></textarea>
            <span class="error" style="color:red"> <?php echo $descriereErr; ?></span>
            <script>
                function updateInput1(newvalue) {
                    document.getElementById("descriereJobNou").value = newvalue;
                }
            </script>
        </div>
        <div class="form-control" style="margin-top: 10px;">
            <textarea class="form-control" id="cerinteJobNou" rows="10" cols="50" style="width: 500px;" name="cerintejobNou" onchange="updateInput1(this.value)"><?php echo $cerintejobVechi; ?></textarea>
            <span class="error" style="color:red"> <?php echo $cerinteErr; ?></span>
            <script>
                function updateInput1(newvalue) {
                    document.getElementById("cerinteJobNou").value = newvalue;
                }
            </script>
        </div>
        <div class="form-group" style="margin-top: 10px;">
            <label>Status nou job:</label>
            <select name="statusnouJob">
                <option value="Activ" <?php if ($statusjobVechi == "Activ") : ?>selected="selected" <?php endif; ?>>Activ</option>
                <option value="Inactiv" <?php if ($statusjobVechi == "Inactiv") : ?>selected="selected" <?php endif; ?>>Inactiv</option>
            </select>
        </div>
        <div class="form-control" style="margin-top: 10px;">
            <input type="text" id="numeTestNou" name="numeTestNou" style="height: 30px;" value="<?php echo $numeTestVechi; ?>" onchange="updateInput(this.value)">
            <span class="error" style="color:red"> <?php echo $numeTestErr; ?></span>
            <script>
                function updateInput(newvalue) {
                    document.getElementById("numeTestNou").value = newvalue;
                }
            </script>
        </div>
        <hr class="mb-3">
        <input type="submit" class="btn btn-primary" name="updateJob" value="Update job cu valorile noi">
        <hr class="mb-3">
        <input type="submit" class="btn btn-primary" name="Back" value="Inapoi">
    </div>
</form>

</html>