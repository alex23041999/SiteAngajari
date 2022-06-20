<?php
error_reporting(E_ALL ^ E_WARNING); 
require_once('DbConnection.php');
class UserAccount
{
    private $firstname;
    private $lastname;
    private $accountid;
    private $password1;
    private $email;
    private $telephone;

    public function __construct($conn, $firstname, $lastname, $accountid, $password1, $email, $telephone)
    {
        $this->conn = $conn;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->accountid = $accountid;
        $this->password1 = $password1;
        $this->email = $email;
        $this->telephone = $telephone;
    }
    public function insertIntoDb()
    {
        try {
            $sql = "INSERT INTO accounts (firstname,lastname,accountid,password1,email,telephone,rol) 
                VALUES('$this->firstname','$this->lastname','$this->accountid','$this->password1','$this->email','$this->telephone','user')";
            mysqli_query($this->conn, $sql);
            mysqli_close($this->conn);
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie verificare existenta cont utilizator
    public function checkUserAccount()
    {
        try {
            $sql = "SELECT accountid,email,telephone FROM accounts WHERE accountid ='$this->accountid' OR email ='$this->email' OR telephone = '$this->telephone'";
            $result = mysqli_query($this->conn, $sql);
            $counter = -1;
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)){
                    //id,email si telefon deja utilizate
                    if($row["accountid"] == $this->accountid && $row["email"] == $this->email && $row["telephone"] == $this->telephone){
                        $counter = 0;
                        break;
                    }
                    //id si email deja utilizate 
                    else if($row["accountid"] == $this->accountid && $row["email"] == $this->email && $row["telephone"] != $this->telephone){
                        $counter = 1;
                        break;
                    }
                    //id si telefon deja utilizate
                    else if($row["accountid"] == $this->accountid && $row["email"] != $this->email && $row["telephone"] == $this->telephone){
                        $counter = 2;
                        break;
                    }
                    //email si telefon deja utilizate
                    else if($row["accountid"] != $this->accountid && $row["email"] == $this->email && $row["telephone"] == $this->telephone){
                        $counter = 3;
                        break;
                    }
                    //id deja utilizat
                    else if($row["accountid"] == $this->accountid && $row["email"] != $this->email && $row["telephone"] != $this->telephone){
                        $counter = 4;
                        break;
                    }
                    //email deja utilizat
                    else if($row["accountid"] != $this->accountid && $row["email"] == $this->email && $row["telephone"] != $this->telephone){
                        $counter = 5;
                        break;
                    } 
                    //telefon deja utilizat
                    else if($row["accountid"] != $this->accountid && $row["email"] != $this->email && $row["telephone"] == $this->telephone){
                        $counter = 6;
                        break;
                    }
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
