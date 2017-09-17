<?php
class dbConn {
  public $conn;

  public function __construct() {
    // Connect to server and select database.
    include('dbconfig.php');
    $this->host = $db['hostname'];
    $this->database = $db['database'];
    $this->username = $db['username'];
    $this->password = $db['password'];

    try {
      $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database . ';charset=utf8', $this->username , $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (\Exception $e) {
      die('Database connection error: ' . $e->getMessage());
    }
  }
  
}
