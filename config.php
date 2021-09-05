<?php

//*We connect to the database
class Connection {

   protected $conn;

   public function __construct($host,$root,$pass,$database)
   {
      $this->conn = mysqli_connect( $host,$root,$pass,$database);
   }


   public function on(){
      return $this->conn;
   }

}


$conn = new Connection('localhost','rohit','Rohit@123','shopcart');

?>
