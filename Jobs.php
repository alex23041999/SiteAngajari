<?php
require_once('DbConnection.php');
require_once('Applications.php');
require_once('AccountDetails.php');
class Jobs extends Applications
{
    private $name;
    private $descriere;
    private $cerinte;
    private $status;
    private $numeTest;
    private $durataTest;
    private $limbaj;
    private $categorie;

    public function setJob($conn, $name, $descriere, $cerinte, $status, $numeTest, $durataTest, $limbaj,$categorie)
    {
        $this->conn = $conn;
        $this->name = $name;
        $this->descriere = $descriere;
        $this->cerinte = $cerinte;
        $this->status = $status;
        $this->numeTest = $numeTest;
        $this->durataTest = $durataTest;
        $this->limbaj = $limbaj;
        $this->categorie = $categorie;
    }
    public function getJobName()
    {
        return $this->name;
    }
    public function getJobDescriere()
    {
        return $this->descriere;
    }
    public function getJobCerinte()
    {
        return $this->cerinte;
    }
    public function getJobStatus()
    {
        return $this->status;
    }
    public function getJobTest()
    {
        return $this->numeTest;
    }
    public function getJobDurataTest()
    {
        return $this->durataTest;
    }
    public function getJobLimbaj()
    {
        return $this->limbaj;
    }
    public function getJobCategory()
    {
        return $this->categorie;
    }
    //functie vizualizare job-uri pentru admin
    public static function vizualizareJoburiAdmin($conn)
    {
        try {
            $sql = "SELECT * FROM jobs_list";
            $result = mysqli_query($conn, $sql);
            $varJob = new Jobs();
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                <tr>
                    <td>" . ($row['job_ID']) . "</td>
                    <td>" . ($row['Nume']) . "</td>
                    <td>" . ($row['Descriere']) . "</td>
                    <td>" . ($row['Cerinte']) . "</td>
                    <td>" . ($row['NumarCandidati']) . "</td>
                    <td>" . ($row['Status']) . "</td>
                    <td>" . ($row['test_name']) . "</td>
                    <td>" . ($row['limbaj']) . "</td>
                    <td>" . ($row['categorie']) . "</td>
                    <td>" . ($row['durata_test']) . "</td>
                    <td><button type=\"submit\" name=\"deleteJob\" value=\"$row[job_ID]\" style=\"margin-bottom: 10px;\">Sterge job</button>
                    <button type=\"submit\" name=\"updateJobID\" value=\"$row[job_ID]\" style=\"margin-bottom: 10px;\">Update job</button>
                    </td>";
                    if (isset($_POST['deleteJob'])) {
                        $varJob->deleteJobs($conn, $_POST['deleteJob']);
?>
                        </tr>
                        <?php
                        echo "<script>alert('Job sters cu succes')</script>";
                        ?>
                        <META http-equiv="Refresh" content="0; URL=http://localhost/ProiectLicenta/AdminJobModifierPage.php">
                        <?php
                    }
                }
            }
            mysqli_close($conn);
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie adaugare job-uri de catre admin
    public function insertJobs()
    {
        try {
            $sql = "INSERT INTO jobs_list(Nume,Descriere,Cerinte,Status,test_name,durata_test,limbaj,categorie) VALUES ('$this->name','$this->descriere','$this->cerinte','$this->status','$this->numeTest','$this->durataTest','$this->limbaj','$this->categorie')";
            mysqli_query($this->conn, $sql);
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie stergere job din lista de catre admin, se utilizeaza in interiorul functiei de vizualizare
    public static function deleteJobs($conn, $jobID)
    {
        try {
            $sql = "DELETE FROM jobs_list WHERE job_ID='$jobID'";
            mysqli_query($conn, $sql);
            mysqli_close($conn);
            return true;
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie update job de catre admin
    public function updateJobs($conn, $jobID, $numeNou, $descriereNoua, $cerinteNoi, $statusNou, $testNou, $durataTestNoua, $limbajNou,$categorieNoua)
    {
        try {
            $sql = "UPDATE jobs_list SET Nume='$numeNou', Descriere='$descriereNoua',Cerinte='$cerinteNoi',Status='$statusNou',test_name='$testNou',durata_test='$durataTestNoua',limbaj='$limbajNou',categorie='$categorieNoua' WHERE job_ID='$jobID'";
            mysqli_query($conn, $sql);
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie vizualizare job-uri pentru user
    public static function vizualizareJoburiUser($conn)
    {
        try {
            $numeUtilizatorCandidat = $_SESSION['accountid'];
            $candidatnou = new AccountDetails();
            $static = 'AccountDetails';
            $candidatnou = $static::showAccountDetails($conn, $numeUtilizatorCandidat);
            $userIdCandidat = $candidatnou->getUserID();
            $sql_nrjobs = "SELECT job_ID FROM jobs_list WHERE Status='Activ'";
            $nrjobs = mysqli_query($conn, $sql_nrjobs);
            $sql = "SELECT job_ID,Nume,Descriere,Cerinte,NumarCandidati,Status,limbaj,categorie FROM jobs_list";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($nrjobs) > 0) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['Status'] == "Activ") {
                            $n = new Applications();
                            $alreadyCandidate = $n->checkExistance($conn, $userIdCandidat, $row['job_ID']);
                            if ($alreadyCandidate == 0) {
                                //afisare joburi disponibile pentru aplicare si pune un text la cele la care a aplicat
                                echo "
                            <tr>
                                <td>" . ($row['Nume']) . "</td>
                                <td>" . ($row['NumarCandidati']) . "</td>
                                <td>" . ($row['categorie']) . "</td>
                                <td>" . ($row['limbaj']) . "</td>
                                <td>
                                Ai aplicat deja la acest job!
                                </td>";
                        ?>
                                </tr>
                            <?php
                            } else if ($alreadyCandidate == 1) {
                                //afisare joburi disponibile pentru aplicare si pune button la cele care nu a aplicat
                                echo "
                            <tr>
                                <td>" . ($row['Nume']) . "</td>
                                <td>" . ($row['NumarCandidati']) . "</td>
                                <td>" . ($row['categorie']) . "</td>
                                <td>" . ($row['limbaj']) . "</td>
                                <td>
                                <button type=\"submit\" name=\"aplicarejobid\" value=\"$row[job_ID]\">Aplica acum</button>
                                </td>";
                            ?>
                                </tr>
<?php
                            }
                        }
                    }
                    return 1;
                }
            } else {
                return 0;
            }
            mysqli_close($conn);
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //afisare joburile la care a aplicat user-ul in fereastra de Info Account
    public static function vizualizareAplicari($conn, $userid)
    {
        try {
            $sql = "SELECT apl.nume_job,apl.data_aplicare,apl.status_aplicare FROM applications_list AS apl
            JOIN accounts as act ON apl.user_ID=act.user_ID
            WHERE act.accountid='$userid'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                        <tr>
                            <td>" . ($row['nume_job']) . "</td>
                            <td>" . ($row['data_aplicare']) . "</td>
                            <td>" . ($row['status_aplicare']) . "</td>";
                }
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //preluare date despre un job
    public function returnJobDetails($conn, $jobid)
    {
        try {
            $job = new Jobs();
            $sql = "SELECT Nume,Descriere,Cerinte,Status,test_name,durata_test,limbaj,categorie FROM jobs_list where job_ID='$jobid'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $job->setJob($conn, $row['Nume'], $row['Descriere'], $row['Cerinte'], $row['Status'], $row['test_name'], $row['durata_test'], $row['limbaj'],$row['categorie']);
                }
            }
            return $job;
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //vizualizare job-uri recomandate
    public function returnRecomandedJobs($conn, $accountid)
    {
        try {
            $counterJobs = 0;
            $sql = "SELECT limbaj FROM accounts where accountid='$accountid'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $everyLanguage = explode(".", $row['limbaj']);
                    foreach ($everyLanguage as $language) {
                        $numeUtilizatorCandidat = $_SESSION['accountid'];
                        $candidatnou = new AccountDetails();
                        $static = 'AccountDetails';
                        $candidatnou = $static::showAccountDetails($conn, $numeUtilizatorCandidat);
                        $userIdCandidat = $candidatnou->getUserID();
                        $sql_nrjobs = "SELECT job_ID FROM jobs_list WHERE Status='Activ'";
                        $nrjobs = mysqli_query($conn, $sql_nrjobs);
                        $sql = "SELECT job_ID,Nume,Descriere,Cerinte,NumarCandidati,Status,limbaj,categorie FROM jobs_list";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($nrjobs) > 0) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row1 = mysqli_fetch_assoc($result)) {
                                    if ($row1['Status'] == "Activ" && strcmp($row1['limbaj'],$language) == 0) {
                                        $counterJobs ++;
                                        $n = new Applications();
                                        $alreadyCandidate = $n->checkExistance($conn, $userIdCandidat, $row1['job_ID']);
                                        if ($alreadyCandidate == 0) {
                                            //afisare joburi disponibile pentru aplicare si pune un text la cele la care a aplicat
                                            echo "
                                        <tr>
                                            <td>" . ($row1['Nume']) . "</td>
                                            <td>" . ($row1['NumarCandidati']) . "</td>
                                            <td>" . ($row1['categorie']) . "</td>
                                            <td>" . ($row1['limbaj']) . "</td>
                                            <td>
                                            Ai aplicat deja la acest job!
                                            </td>";
                                    ?>
                                            </tr>
                                        <?php
                                        } else if ($alreadyCandidate == 1) {
                                            //afisare joburi disponibile pentru aplicare si pune button la cele care nu a aplicat
                                            echo "
                                        <tr>
                                            <td>" . ($row1['Nume']) . "</td>
                                            <td>" . ($row1['NumarCandidati']) . "</td>
                                            <td>" . ($row1['categorie']) . "</td>
                                            <td>" . ($row1['limbaj']) . "</td>
                                            <td>
                                            <button type=\"submit\" name=\"aplicarejobid\" value=\"$row1[job_ID]\">Aplica acum</button>
                                            </td>";
                                        ?>
                                            </tr>
            <?php
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(mysqli_num_rows($nrjobs) == $counterJobs){
                return 0;
            }else if(mysqli_num_rows($nrjobs) < $counterJobs){
                return 1;
            }else if($counterJobs == 0){
                return 2;
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //vizualizare job-uri nerecomandate
    public function returnNotRecomandedJobs($conn, $accountid)
    {
        try {
            $sql = "SELECT limbaj FROM accounts where accountid='$accountid'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                    $everyLanguage = explode(".", $row['limbaj']);
                    $counter = count($everyLanguage);
                        $numeUtilizatorCandidat = $_SESSION['accountid'];
                        $candidatnou = new AccountDetails();
                        $static = 'AccountDetails';
                        $candidatnou = $static::showAccountDetails($conn, $numeUtilizatorCandidat);
                        $userIdCandidat = $candidatnou->getUserID();
                        $sql_nrjobs = "SELECT job_ID FROM jobs_list WHERE Status='Activ'";
                        $nrjobs = mysqli_query($conn, $sql_nrjobs);
                        if($counter == 4){
                            $sql1 = "SELECT job_ID,Nume,Descriere,Cerinte,NumarCandidati,Status,limbaj,categorie FROM jobs_list WHERE limbaj !='$everyLanguage[0]' AND limbaj !='$everyLanguage[1]' AND limbaj !='$everyLanguage[2]'";
                        }else if($counter == 3){
                            $sql1 = "SELECT job_ID,Nume,Descriere,Cerinte,NumarCandidati,Status,limbaj,categorie FROM jobs_list WHERE limbaj !='$everyLanguage[0]' AND limbaj !='$everyLanguage[1]'";
                        }else if($counter == 2){
                            $sql1 = "SELECT job_ID,Nume,Descriere,Cerinte,NumarCandidati,Status,limbaj,categorie FROM jobs_list WHERE limbaj !='$everyLanguage[0]'";
                        }
                        $result = mysqli_query($conn, $sql1);
                        if (mysqli_num_rows($nrjobs) > 0) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row1 = mysqli_fetch_assoc($result)) {
                                    if ($row1['Status'] == "Activ") {
                                        $n = new Applications();
                                        $alreadyCandidate = $n->checkExistance($conn, $userIdCandidat, $row1['job_ID']);
                                        if ($alreadyCandidate == 0) {
                                            //afisare joburi disponibile pentru aplicare si pune un text la cele la care a aplicat
                                            echo "
                                        <tr>
                                            <td>" . ($row1['Nume']) . "</td>
                                            <td>" . ($row1['NumarCandidati']) . "</td>
                                            <td>" . ($row1['categorie']) . "</td>
                                            <td>" . ($row1['limbaj']) . "</td>
                                            <td>
                                            Ai aplicat deja la acest job!
                                            </td>";
                                    ?>
                                            </tr>
                                        <?php
                                        } else if ($alreadyCandidate == 1) {
                                            //afisare joburi disponibile pentru aplicare si pune button la cele care nu a aplicat
                                            echo "
                                        <tr>
                                            <td>" . ($row1['Nume']) . "</td>
                                            <td>" . ($row1['NumarCandidati']) . "</td>
                                            <td>" . ($row1['categorie']) . "</td>
                                            <td>" . ($row1['limbaj']) . "</td>
                                            <td>
                                            <button type=\"submit\" name=\"aplicarejobid\" value=\"$row1[job_ID]\">Aplica acum</button>
                                            </td>";
                                        ?>
                                            </tr>
            <?php
                                        }
                                    }
                                }
                            }
                        }
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
}
?>