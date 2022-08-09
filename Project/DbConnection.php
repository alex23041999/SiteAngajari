<?php

    class DataBase_Connection {
        //Pastram instanta bazei de date
       public static $instance = null;
       public $conn;
        
       public $host = '127.0.0.1';
       public $user='root';
       public $password='230419';
       public $db_name='proiectlicenta';
         
        //Conexiunea noua se realizeaza cu ajutorul unui constructor
       public function __construct()
        {
          $this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->db_name);
        }
        
        public static function getInstance()
        {
          if(!self::$instance)
          {
            self::$instance = new DataBase_Connection();
          }
         
          return self::$instance;
        }
        
        public function getConnection()
        {
          return $this->conn;
        }
      
    }
      
    $instance=DataBase_Connection::getInstance();
    $conn=$instance->getConnection();
?>
