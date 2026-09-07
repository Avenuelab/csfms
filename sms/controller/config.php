
<?php
	    $uname="root";
        $pass="";
        $servername="localhost";
        $dbname="DemoSMS";

//include_once 'config.php';   // As functions.php is not included
//$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);      
$conn=new mysqli($servername, $uname, $pass, $dbname)or trigger_error(mysqli_connect_error(),E_USER_ERROR);
//////// Do not Edit below /////////
try {
$dbo = new PDO('mysql:host='.$servername.';dbname='.$dbname, $uname, $pass);
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}


?>