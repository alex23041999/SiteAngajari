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

    public function setApplication($conn, $userid, $jobid, $numejob, $numecandidat, $prenumecandidat, $emailcandidat, $telefoncandidat, $cvcandidat, $dataaplicare, $notatest)
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
    //adaugare candidatura noua , in caz ca nu exista
    public function insertNewApplication()
    {
        try {
            if ($this->checkExistance() == 0) {
                return false;
            } else if ($this->checkExistance() == 1) {
                $sql = "INSERT INTO applications_list(user_ID,job_ID,nume_job,nume_candidat,prenume_candidat,email_candidat,telefon_candidat,cv_candidat,data_aplicare,nota_test)
                        VALUES ('$this->userid','$this->jobid','$this->numejob','$this->numecandidat','$this->prenumecandidat','$this->emailcandidat','$this->telefoncandidat','$this->cvcandidat','$this->dataaplicare','$this->notatest')";
                mysqli_query($this->conn, $sql);
                $this->increaseNrCandidates();
                mysqli_close($this->conn);
                return true;
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //verificare daca user-ul a aplicat deja la job-ul respectiv
    public function checkExistance()
    {
        try {
            $sql = "SELECT user_ID, job_ID FROM applications_list WHERE user_ID='$this->userid' AND job_ID='$this->jobid'";
            $result = mysqli_query($this->conn, $sql);
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
    public function getAplicareTest($conn,$numejob)
    {
        try {
            $sql = "SELECT test_name FROM jobs_list WHERE Nume = '$numejob'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    return $row['test_name'];
                }
            }else{
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
    public static function afisareTest($conn,$numeTest)
    {
        try {
            $sql1 = "SELECT text_intrebare FROM questions_list as ql JOIN test_names as tn ON ql.id_test=tn.id_test WHERE nume_test='$numeTest'";
            $result1 =mysqli_query($conn,$sql1);
            $counter=0;
            if(mysqli_num_rows($result1)>0){
                while($questions = mysqli_fetch_assoc($result1)){
                    $intrebare =$questions['text_intrebare'];
                    $sql2 ="SELECT text_raspuns FROM answers_list as al JOIN questions_list as ql ON al.id_question=ql.id_question WHERE text_intrebare='$intrebare'";
                    $result2 = mysqli_query($conn,$sql2);
                    echo "
                        <div>
                         <input type=\"text\" value=\"$questions[text_intrebare]\" name=\"intrebare$counter\" readonly>
                         ";
                        if(mysqli_num_rows($result2)>0){
                            $counter1 = 0;
                            while($answers = mysqli_fetch_assoc($result2)){    
                                echo"
                                    <input type=\"radio\" name=\"checkbox$counter\" id=\"checkbox$counter$counter1\" value=\"$answers[text_raspuns]\" required>
                                    <label for=\"checkbox$counter$counter1\">".($answers['text_raspuns'])."</label>
                                    ";
                                $counter1 ++;
                            }
                            $counter ++;
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
    public function correctAnswerChecking($conn,$intrebare)
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
    public function questionNumberOfTest($conn,$numeTest)
    {
        try {
            $counter = 0;
            $sql ="SELECT text_intrebare FROM questions_list as ql JOIN test_names as tn ON ql.id_test=tn.id_test WHERE nume_test='$numeTest'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while (mysqli_fetch_assoc($result)) {
                    $counter ++;
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
}
