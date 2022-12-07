/** 
Data base connection
*/
<?php

class DbConnect {
    private $server = 'localhost';
    private $dbname = 'restapi';
    private $user = 'root';
    private $pass = '';


    public function connect(){
        try{
        $conn = new PDO('mysql:host'. $this->server.';dbname='.$this->dbname, $this->user,$this->pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e) {
            echo "DAtabase Error" . $e>getMessage();
        }
    }
}

$db = new DbConnect;
$db->connect;


?>