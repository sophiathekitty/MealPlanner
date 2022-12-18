<?php
require_once("../includes/main.php");

if(!is_file("../../../settings.php")) JsonDie("NullHub settings.php missing");
if(is_file("../settings.php")) JsonDie("MealPlanner settings.php already exists");

if(defined("SETUP_MODE")){
    require_once("../../../settings.php");
    if(isset($device_info)){
        // write local settings file using device info
        $device_info['database'] = "MealPlanner";
        CreateSettingsFile($device_info);
    } else if(isset($db_info)){
        // write local settings file using db_info
        $db_info['database'] = "MealPlanner";
        CreateSettingsFile($db_info);
    }
    file_get_contents("../helpers/validate_models.php");
}
/**
 * create the settings.php file
 */
function CreateSettingsFile($data){
    if(!(defined("SETUP_MODE"))) return;
    global $root_path;
    $text = "<?php\n";
    $text .= "\$device_info = [];\n";
    foreach($data as $key => $value)
        $text .= "\$device_info['$key'] = \"$value\";\n";
    $text .= "?>\n";
    $filename = "settings.php";
    if(defined("TEST_MODE")) $filename = "settings_test.php";
    Debug::Log("CreateSettingsFile",$filename,$text);
    $file = fopen($root_path.$filename, "w") or Debug::Die("Unable to open file for write!");
    fwrite($file,$text);
    fclose($file);
}
?>