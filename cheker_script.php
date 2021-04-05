<?php  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('php/MysqliDb.php');

# FUNCTIONS - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// Initialise a Result Array.
function initResults(){
    $result = Array(
        "pass" => "",
        "note" => ""
    );
    return $result;
}


function setResults(&$store, $pass, $note){
    $store['pass'] = $pass;
    $store['note'] = $note;
}

function createIcon($result){
    $icon = '';
    $color = '';
    switch($result){
        case -1:
            $icon = 'minus';
            $color = 'grey';
            break;
        case 0:
            $icon = 'check circle outline';
            $color = 'green';
            break;
        case 1:
            $icon = 'circle';
            $color = 'red';
            break;
        case 2:
            $icon = 'exclamation triangle';
            $color = 'orange';
            break;
    }
    return '<i class="large '.$icon.' middle aligned icon" style="color: '.$color.';"></i>';
}

// Print test
function printTest($title, $input){
    $icon = createIcon($input["pass"]);
    $desc = $input["note"];
    echo '<div class="item">'.$icon.'<div class="content"><div class="header">'.$title.'</div><div class="description">'.$desc.'</div></div></div>';
}


// Test to check if a setting it set
// Severity:    1 = Critical.
//              2 = Warning.
function doTest_Set($title, $section, $setting, $severity=2){
    global $config;
    $result = initResults();

    if( !isset($config[$section][$setting]) ){
        setResults($result, $severity, "Setting not set!  Section $section, Item $setting");
    }else{
        if( $config[$section][$setting] == '' ){
            setResults($result, $severity, "$section/$setting is blank.");
        }else{
            setResults($result, 0, "$section/$setting is set to <div class='ui horizontal label'>".$config[$section][$setting]."</div>");
        }
        
    }

    printTest($title, $result);
}

function dbCheckTable($table){
    $result = initResults();

    $dbase = MysqliDb::getInstance();

    if( $dbase->tableExists($table)){
        $result["pass"] = true;
        $result["note"] = "Table $table found in database.";
        // Extract fields of that table
        $fields = $dbase->rawQuery("DESCRIBE $table");
        global $fieldList;
        $fieldList = Array();
        foreach($fields as $field){
            $fieldList[$field['Field']] = $field['Type'] . "!" . $field['Default'];
        }
    }else{
        $result["pass"] = false;
        $result["note"] = "Unable to find table $table in database.";
    }
    return $result;
}


# Read config file 
    $config = parse_ini_file('.login.config', TRUE);

    # Test to validate if settings are set
    doTest_Set('Application Name', 'Application', 'Name', 2);
    doTest_Set('Redirection page after successful logon', 'Application', 'RedirectPage', 1);
    doTest_Set('Database Credentials: Host', 'Database', 'Host', 1);
    doTest_Set('Database Credentials: User', 'Database', 'User', 1);
    doTest_Set('Database Credentials: Password', 'Database', 'Password', 1);
    doTest_Set('Database Credentials: Database Name', 'Database', 'Database', 1);
    doTest_Set('Database Credentials: Port', 'Database', 'Port', 2);
    // doTest_Set('', '', '');

    # Test the database connection
    $dbHost = $config['Database']['Host'];
    $dbUser = $config['Database']['User'];
    $dbPass = $config['Database']['Password'];
    $dbName = $config['Database']['Database'];
    $dbPort = $config['Database']['Port'];

    $db = new MysqliDb (Array (
        'host' => $dbHost,
        'username' => $dbUser, 
        'password' => $dbPass,
        'db'=> $dbName,
        'port' => $dbPort
    ));

    $res = initResults();
    if($db->getLastErrno() != 0 ){
        setResults($res, 1, "Unable to connect to database: " . $db->getLastError());
    }else{
        setResults($res, 0, "Connected to $dbHost/$dbName as $dbUser.");
    }
    printTest('Database connexion', $res);

    // Check if user table exists
    $tableName = $config['Database']['UserTableName'];
    $res = initResults();
    if( $db->tableExists($tableName)){
        setResults($res, 0, "Table $tableName found.");
    }else{
        setResults($res, 1, "Table $tableName NOT found!");
    }
    printTest("Looking for User Table ($tableName)", $res);

    // Read table
    $myCols = $db->rawQuery("DESCRIBE ". $tableName);
    $string = '';
    foreach($myCols as $col){
        $fieldName = $col['Field'];
        $fieldType = $col['Type'];
        $fieldDefault = $col['Default'];
        $string .= "<br><strong>$fieldName</strong> &emsp;&emsp; <strong>$fieldType</strong> &emsp; default $fieldDefault";
    }
    

    $res = initResults();
    setResults($res, 0, $string);
    printTest("Read fields in User Table", $res);

    # If the registration feature is enabled
    $registrationEnabled = (($config['Registration']['Enabled'])=="Y");
    if( $registrationEnabled ){
        doTest_Set('Registration feature: Button text', 'Registration', 'Invite');
        doTest_Set('Registration feature: Button color', 'Registration', 'Color');

        # Additional fields for the registration
        $regAddNames = substr_count($config['Registration']['Fields'], ',');
        $regAddLabels = substr_count($config['Registration']['Labels'], ',');
        $regAddTypes = substr_count($config['Registration']['Types'], ',');

        $names = explode(',', $config['Registration']['Fields']);
        $labels = explode(',', $config['Registration']['Labels']);
        $types = explode(',', $config['Registration']['Types']);
        $i = 0;
        $string = '';
        foreach( $names as $name ){
            $thisName = (isset($names[$i])?$names[$i]:'(empty)');
            $thisLabel = (isset($labels[$i])?$labels[$i]:'(empty)');
            $thisType = (isset($types[$i])?$types[$i]:'(empty)');

            $string .= "<br>Field <strong>$thisName</strong>, type <strong>$thisType</strong>, labelled as <strong>$thisLabel</strong>.";
            $i++;
        }

        $res = initResults();
        if( ($regAddNames == $regAddLabels) && ($regAddLabels == $regAddTypes) ){
            setResults($res, 0, "Additional fields are correctly set".$string);
        }else{
            setResults($res, 1, "Additional fields are NOT correctly set".$string);
        }
        printTest('Registration feature: Additional fields', $res);

    }else{
        $res = initResults();
        setResults($res, -1, 'This feature is OFF');
        printTest('Registration feature', $res);
    }


    # If the password reset feature is enabled
    $passwdEnabled = (($config['PasswordReset']['Enabled'])=="Y");
    if( $passwdEnabled ){
        doTest_Set('Password Reset feature: Button text', 'PasswordReset', 'Invite');
        doTest_Set('Password Reset feature: Button color', 'PasswordReset', 'Color');
    }else{
        $res = initResults();
        setResults($res, -1, 'This feature is OFF');
        printTest('Reset password feature', $res);
    }

