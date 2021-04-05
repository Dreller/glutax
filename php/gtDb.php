<?php
include('MysqliDb.php');
Class gtDb extends MysqliDb{

    public function __construct(){
        #Get db connection infos
        $info = parse_ini_file('../.login.config', TRUE);
        
        $dbHost = $info["Database"]["Host"];
        $dbUser = $info["Database"]["User"];
        $dbPass = $info["Database"]["Password"];
        $dbName = $info["Database"]["Database"];
        $dbPort = $info["Database"]["Port"];
        if($dbPort != ""){
            $dbHost = $dbHost . ':' . $dbPort;
        }
        parent::__construct($dbHost, $dbUser, $dbPass, $dbName);
    }

  
}


?>