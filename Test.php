<?php
require_once('DbConnection.php');
class Test
{
    private $nume_test;

    public function setTest($conn, $nume_test)
    {
        $this->conn = $conn;
        $this->nume_test = $nume_test;
    }
    public function getNumeTest()
    {
        return $this->nume_test;
    }
    //functie care adauga numele testului in baza de date
    public function insertNumeTestIntoDB()
    {
        try {
            $sql = "INSERT INTO test_names(nume_test) VALUES ('$this->nume_test')";
            mysqli_query($this->conn, $sql);
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie care preia ID-ul testului in baza de date
    public static function selectIDTestFromDB($conn, $nume_test)
    {
        try {
            $sql = "SELECT id_test FROM test_names WHERE nume_test = '$nume_test'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                return $row['id_test'];
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie care verifica daca Numele testului exista in DB
    public static function checkTestNameExistance($conn, $nume_test)
    {
        try {
            $sql = "SELECT nume_test FROM test_names WHERE nume_test = '$nume_test'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                return 1;
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
}

class Intrebare
{
    private $text_intrebare;

    public function setIntrebare($conn, $text_intrebare)
    {
        $this->conn = $conn;
        $this->text_intrebare = $text_intrebare;
    }
    public function getTextIntrebare()
    {
        return $this->text_intrebare;
    }
    //functie care adauga intrebarea in baza de date
    public static function insertIntrebareIntoDB($conn, $id_test, $array_intrebari)
    {
        try {
            foreach ($array_intrebari as $intr => $intrebare) {
                $sql = "INSERT INTO questions_list(id_test,text_intrebare) VALUES ('$id_test','$intrebare')";
                mysqli_query($conn, $sql);
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie care preia ID-ul intrebarii in baza de date
    public static function selectIDQuestionFromDB($conn, $text_intrebare)
    {
        try {
            $sql = "SELECT id_question FROM questions_list WHERE text_intrebare = '$text_intrebare'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                return $row['id_question'];
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
}

class Raspuns
{
    private $text_raspuns;
    private $is_correct;

    public function setRaspuns($conn, $text_raspuns, $is_correct)
    {
        $this->conn = $conn;
        $this->text_raspuns = $text_raspuns;
        $this->is_correct = $is_correct;
    }
    public function getTextRaspuns()
    {
        return $this->text_raspuns;
    }
    public function getIsCorrectRaspuns()
    {
        return $this->is_correct;
    }
    //functie care adauga raspunsurile in baza de date
    public static function insertRaspunsuriIntoDB($conn, $id_question, $id_test, $array_raspunsuri)
    {
        try {
            foreach ($array_raspunsuri as $intr => $raspuns) {
                $text_raspuns = $raspuns->getTextRaspuns();
                $is_correct = $raspuns->getIsCorrectRaspuns();
                $sql = "INSERT INTO answers_list(id_question,id_test,text_raspuns,is_correct) VALUES ('$id_question','$id_test','$text_raspuns','$is_correct')";
                mysqli_query($conn, $sql);
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
}
