<?php
require_once('DbConnection.php');
class AccountDetails
{
    private $user_id;
    private $firstname;
    private $lastname;
    private $accountid;
    private $email;
    private $telephone;
    private $nameCV;

    public function setAccountData($conn, $user_id,$firstname, $lastname, $accountid, $email, $telephone, $nameCV)
    {
        $this->conn = $conn;
        $this->user_id = $user_id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->accountid = $accountid;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->nameCV = $nameCV;
    }
    public function getUserID()
    {
        return $this->user_id;
    }
    public function getAccountFirstName()
    {
        return $this->firstname;
    }
    public function getAccountLastName()
    {
        return $this->lastname;
    }
    public function getAccountID()
    {
        return $this->accountid;
    }
    public function getAccountEmail()
    {
        return $this->email;
    }
    public function getAccountTelephone()
    {
        return $this->telephone;
    }
    public function getAccountCV()
    {
        return $this->nameCV;
    }
    //functie preluare date cont pentru afisare in pagina de detalii cont
    public static function showAccountDetails($conn, $accountid)
    {
        try {
            $sql = "SELECT user_id,firstname,lastname,accountid,email,telephone,CV FROM accounts WHERE accountid ='$accountid'";
            $result = mysqli_query($conn, $sql);
            $userdetails = new AccountDetails();
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $userdetails->setAccountData($conn, $row["user_id"],$row["firstname"], $row["lastname"], $row["accountid"], $row["email"], $row["telephone"], $row["CV"]);
                    return $userdetails;
                }
            }
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie de update CV in baza de date, in caz ca este incarcat unul
    public static function updateCV($conn, $accountid, $numeCV)
    {
        try {
            $sql = "UPDATE accounts SET CV='$numeCV' WHERE accountid='$accountid'";
            mysqli_query($conn, $sql);
            mysqli_close($conn);
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie stergere nume CV din baza de date, in caz ca aceste nu mai exista in folder
    public static function deleteCV($conn, $accountid)
    {
        try {
            $sql = "UPDATE accounts SET CV=NULL WHERE accountid='$accountid'";
            mysqli_query($conn, $sql);
            mysqli_close($conn);
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //verificare daca exista un CV cu acelasi nume in folder
    public static function verifyCvExistance($conn, $nameCV)
    {
        try {
            $sql = "SELECT * FROM accounts WHERE CV='$nameCV'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                return 0;
            }else {
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
