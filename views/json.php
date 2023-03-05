<?php
/**
 * generates json output from a data array
 * @param array $data the data array to display as json
 */
function OutputJson($data){
    if(defined("SETUP_MODE")) $data['setup'] = constant("SETUP_MODE");
    if(defined("DEBUG")) {
        $data['debug'] = Debug::$debug;
        $data['trace'] = Debug::$trace;
    }
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    echo json_encode($data, JSON_PRETTY_PRINT);
    if(json_last_error())
        echo json_last_error_msg();
    if(!is_null(clsDB::$db_g)) clsDB::$db_g->CloseDB();
}
/**
 * die but with json output instead of just text
 * @param string $message the last words
 */
function JsonDie($message){
    OutputJson(["die"=>$message]);
    die();
}
?>