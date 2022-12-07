<?php
class DbConnect {
    private $server = 'db';
    private $dbname = 'restapi';
    private $user = 'root';
    private $pass = 'example';


    public function connect(){
        try{
        $conn = new PDO('mysql:host='. $this->server.';dbname='.$this->dbname, $this->user,$this->pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
        } catch(Exception $e) {
            echo "DAtabase Error " . $e->getMessage();
        }
    }
}
?>