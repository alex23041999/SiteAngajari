<?php
require_once('DbConnection.php');
class Applications
{
    private $userid;
    private $jobid;
    private $numejob;
    private $numecandidat;
    private $prenumecandidat;
    private $emailcandidat;
    private $telefoncandidat;
    private $cvcandidat;
    private $dataaplicare;
    private $notatest;
    private $statusAplicare;

    public function setApplication($conn, $userid, $jobid, $numejob, $numecandidat, $prenumecandidat, $emailcandidat, $telefoncandidat, $cvcandidat, $dataaplicare, $notatest, $statusAplicare)
    {
        $this->conn = $conn;
        $this->userid = $userid;
        $this->jobid = $jobid;
        $this->numejob = $numejob;
        $this->numecandidat = $numecandidat;
        $this->prenumecandidat = $prenumecandidat;
        $this->emailcandidat = $emailcandidat;
        $this->telefoncandidat = $telefoncandidat;
        $this->cvcandidat = $cvcandidat;
        $this->dataaplicare = $dataaplicare;
        $this->notatest = $notatest;
        $this->statusAplicare = $statusAplicare;
    }
    public function getApplicationUserID()
    {
        return $this->userid;
    }
    public function getApplicationJobID()
    {
        return $this->jobid;
    }
    public function getApplicationNumeJob()
    {
        return $this->numejob;
    }
    public function getApplicationNumeCandidat()
    {
        return $this->numecandidat;
    }
    public function getApplicationPrenumeCandidat()
    {
        return $this->prenumecandidat;
    }
    public function getApplicationEmailCandidat()
    {
        return $this->emailcandidat;
    }
    public function getApplicationTelefonCandidat()
    {
        return $this->telefoncandidat;
    }
    public function getApplicationCvCandidat()
    {
        return $this->cvcandidat;
    }
    public function getApplicationDataAplicare()
    {
        return $this->dataaplicare;
    }
    public function getApplicationNotaTest()
    {
        return $this->notatest;
    }
    public function getApplicationStatus()
    {
        return $this->statusAplicare;
    }
    //adaugare candidatura noua , in caz ca nu exista
    public function insertNewApplication()
    {
        try {
            $status = "Neevaluat";
            $sql = "INSERT INTO applications_list(user_ID,job_ID,nume_job,nume_candidat,prenume_candidat,email_candidat,telefon_candidat,cv_candidat,data_aplicare,nota_test,status_aplicare)
                        VALUES ('$this->userid','$this->jobid','$this->numejob','$this->numecandidat','$this->prenumecandidat','$this->emailcandidat','$this->telefoncandidat','$this->cvcandidat','$this->dataaplicare','$this->notatest','$status')";
            mysqli_query($this->conn, $sql);
            $this->increaseNrCandidates();
            mysqli_close($this->conn);
            return true;
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //verificare daca user-ul a aplicat deja la job-ul respectiv
    public function checkExistance($conn, $userid, $jobid)
    {
        try {
            $sql = "SELECT user_ID, job_ID FROM applications_list WHERE user_ID='$userid' AND job_ID='$jobid'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //crestere numar candidati cand o aplicare are loc cu succes
    public function increaseNrCandidates()
    {
        try {
            $sql = "UPDATE jobs_list SET NumarCandidati=NumarCandidati+1 WHERE job_ID='$this->jobid'";
            mysqli_query($this->conn, $sql);
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    public function getAplicareTest($conn, $numejob)
    {
        try {
            $sql = "SELECT test_name FROM jobs_list WHERE Nume = '$numejob'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    return $row['test_name'];
                }
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
    //afisare test pentru aplicare
    public static function afisareTest($conn, $numeTest)
    {
        try {
            $sql1 = "SELECT text_intrebare FROM questions_list as ql JOIN test_names as tn ON ql.id_test=tn.id_test WHERE nume_test='$numeTest'";
            $result1 = mysqli_query($conn, $sql1);
            $counter = 0;
            if (mysqli_num_rows($result1) > 0) {
                while ($questions = mysqli_fetch_assoc($result1)) {
                    $intrebare = $questions['text_intrebare'];
                    $sql2 = "SELECT text_raspuns FROM answers_list as al JOIN questions_list as ql ON al.id_question=ql.id_question WHERE text_intrebare='$intrebare'";
                    $result2 = mysqli_query($conn, $sql2);
                    echo "
                        <div>
                         <input type=\"text\" value=\"$questions[text_intrebare]\" name=\"intrebare$counter\" readonly>
                         ";
                    if (mysqli_num_rows($result2) > 0) {
                        $counter1 = 0;
                        while ($answers = mysqli_fetch_assoc($result2)) {
                            echo "
                                    <input type=\"radio\" name=\"checkbox$counter\" id=\"checkbox$counter$counter1\" value=\"$answers[text_raspuns]\">
                                    <label for=\"checkbox$counter$counter1\">" . ($answers['text_raspuns']) . "</label>
                                    ";
                            $counter1++;
                        }
                        $counter++;
                    }

?>
                    </div>
<?php
                }
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie care verifica daca raspunsul ales este corect
    public function correctAnswerChecking($conn, $intrebare)
    {
        try {
            $sql = "SELECT text_raspuns FROM answers_list as al JOIN questions_list as ql ON al.id_question=ql.id_question WHERE text_intrebare='$intrebare' AND is_correct=1";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    return $row['text_raspuns'];
                }
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie care returneaza numarul de intrebari in functie de test
    public function questionNumberOfTest($conn, $numeTest)
    {
        try {
            $counter = 0;
            $sql = "SELECT text_intrebare FROM questions_list as ql JOIN test_names as tn ON ql.id_test=tn.id_test WHERE nume_test='$numeTest'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while (mysqli_fetch_assoc($result)) {
                    $counter++;
                }
            }
            return $counter;
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie vizualizare job-uri pentru admin
    public static function vizualizareAplicariAdmin($conn)
    {
        try {
            $evaluat = "Evaluat";
            $neevaluat = "Neevaluat";
            $sql = "SELECT * FROM applications_list";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id_app = $row['id_application'];
                    $var = "";
                    if (strcmp($row['status_aplicare'], $neevaluat) == 0) {
                        $var = $evaluat;
                    } else {
                        $var = $neevaluat;
                    }
                    echo "
                <tr>
                    <td>" . ($row['nume_job']) . "</td>
                    <td>" . ($row['nume_candidat']) . "</td>
                    <td>" . ($row['prenume_candidat']) . "</td>
                    <td>" . ($row['email_candidat']) . "</td>
                    <td>" . ($row['telefon_candidat']) . "</td>";

                    if ($row['cv_candidat'] != NULL) {
                        $files = scandir("cv_folder"); // genereaza o lista cu documentele din folder
                        $exist = false;
                        for ($a = 2; $a < count($files); $a++) {
                            if ($files[$a] == $row['cv_candidat']) {
                                $exist = true;
                                break;
                            }
                        }
                        //daca acesta exista ii afisam numele ca pe un link de descarcare
                        if ($exist == true) {
                            echo "
                            <td> .<a download=" . $row['cv_candidat'] . " href=\"cv_folder/" . $row['cv_candidat'] . "\">" . $row['cv_candidat'] . " </a>. </td>";
                        } else {
                            echo "<td> Niciun CV gasit</td>";
                        }
                    }
                    echo "
                    <td>" . ($row['data_aplicare']) . "</td>
                    <td>" . ($row['nota_test']) . "</td>";
                    if (strcmp($row['status_aplicare'], $evaluat) == 0) {
                        echo "<td>Evaluat</td>";
                    } else {
                        echo "
                        <td>
                        <select name=\"statusAplicare[$id_app]\" class=\"select\">
                        <option value=\"$row[status_aplicare]\">$row[status_aplicare]</option>
                        <option value=\"$var\">$var</option>
                    </select></td>";
                    }
                    echo "
                    <td>
                    <a href=\"SendEmailPage.php?applicationID=$row[id_application]\">Trimite email</a>
                    </td>";
                }
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie care verifica daca s-a modificat statusul aplicarii
    public function checkApplicationStatus($conn, $applicationID, $status_nou)
    {
        try {
            $sql = "SELECT status_aplicare FROM applications_list WHERE id_application = '$applicationID' ";
            $result = mysqli_query($conn, $sql);
            $equals = 1;
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if (strcmp($row['status_aplicare'], $status_nou) != 0) {
                    $equals = 0;
                }
            }
            return $equals;
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie care verifica daca s-a modificat statusul aplicarii
    public function returnApplicationDetails($conn, $applicationID)
    {
        try {
            $sql = "SELECT * FROM applications_list WHERE id_application = '$applicationID'";
            $result = mysqli_query($conn, $sql);
            $application = new Applications();
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $application->setApplication($conn, $row['user_ID'], $row['job_ID'], $row['nume_job'], $row['nume_candidat'], $row['prenume_candidat'], $row['email_candidat'], $row['telefon_candidat'], $row['cv_candidat'], $row['data_aplicare'], $row['nota_test'], $row['status_aplicare']);
            }
            return $application;
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie care face update la status-ul aplicatiei
    public function updateApplicationStatus($conn, $applicationID,$statusNou)
    {
        try {
            $sql = "UPDATE applications_list SET status_aplicare='$statusNou' WHERE id_application='$applicationID'";
            mysqli_query($conn, $sql);
              return true;
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
}
