<?php
require_once('DbConnection.php');
class AccountLogin
{
    private $accountid;
    private $password1;

    public function __construct($conn, $accountid, $password1)
    {
        $this->conn = $conn;
        $this->accountid = $accountid;
        $this->password1 = $password1;
    }

    public function getAccountID()
    {
        return $this->accountid;
    }
    //functie de verificare daca contul este inregistrat in baza de date 
    public function checkAccountForLoging()
    {
        try {
            $sql = "SELECT accountid,password1 FROM accounts";
            $result = mysqli_query($this->conn, $sql);
            $counter = 0;
            //pt user
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row["accountid"] == $this->accountid && $row["password1"] == $this->password1) {
                        //cont gasit cu succes
                        $counter = 1;
                        break;
                    } else if ($row["accountid"] == $this->accountid && $row["password1"] != $this->password1) {
                        //parola este gresita
                        $counter = 2;
                        break;
                    } else {
                        //contul nu exista
                        $counter = 3;
                    }
                }
            }
            //pt admin
            if ($counter == 0 || $counter == 3) {
                $sql1 = "SELECT accountid,password FROM admin_accounts";
                $result1 = mysqli_query($this->conn, $sql1);
                if (mysqli_num_rows($result1) > 0) {
                    while ($row = mysqli_fetch_assoc($result1)) {
                        if ($row["accountid"] == $this->accountid && $row["password"] == $this->password1) {
                            //cont gasit cu succes
                            $counter = 4;
                            break;
                        } else if ($row["accountid"] == $this->accountid && $row["password"] != $this->password1) {
                            //parola este gresita
                            $counter = 5;
                            break;
                        } else {
                            //contul nu exista
                            $counter = 6;
                        }
                    }
                }
            }
            return $counter;
            mysqli_close($this->conn);
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
    //functie pentru determinarea rolului contului logat
    public function checkAccountRole()
    {
        try {
            $sql = "SELECT accountid,rol FROM accounts WHERE accountid = '$this->accountid'";
            $result = mysqli_query($this->conn, $sql);
            $role = "";
            if (mysqli_num_rows($result) > 0) {
                $role = "user";
            }else {
                $sql1 = "SELECT accountid,rol FROM admin_accounts WHERE accountid ='$this->accountid'";
                $result1 = mysqli_query($this->conn,$sql1);
                if(mysqli_num_rows($result1) > 0){
                    while($row = mysqli_fetch_assoc($result1)){
                        if($row["accountid"] == $this->accountid){
                            $role = $row["rol"];
                            break;
                        }
                    }
                }
            }
            return $role;
        } catch (PDOException $e) {
            echo ("<pre>");
            var_dump($e);
            echo ("</pre>");
            return false;
        }
    }
}
