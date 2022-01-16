<?php
/**
 * scans the api directory for apis
 * @return array the list of all the found apis
 */
function LocalAPIs(){
    global $root_path;
    return APIFolder($root_path,"api/");
}
/**
 * scans the api directory for apis
 * @param string $root the root path
 * @param string $path the middle path
 * @param array $apis the list of apis that have already been found
 * @return array the list of all the found apis
 */
function APIFolder($root,$path,$apis = []){
    //echo "<br><b>$root$path</b><br>";
    $shared_models_dir = opendir($root.$path);
    // LOOP OVER ALL OF THE  FILES    
    while ($file = readdir($shared_models_dir)) { 
        //echo "$root $path $file ".is_dir($root.$path.$file)."\n";
        // IF IT IS NOT A FOLDER, AND ONLY IF IT IS A .php WE ACCESS IT
        if(is_dir($root.$path.$file) && $file != ".." && $file != "."){
            $apis[$file] = [];
            if(is_file($root.$path.$file."/params.json")){
                $apis[$file]['params'] = "http://".$_SERVER['HTTP_HOST'].Settings::LoadSettingsVar('path',"/extensions/MealPlanner/").$path.$file."/params.json";
            }
            $apis[$file]['path'] = "http://".$_SERVER['HTTP_HOST'].Settings::LoadSettingsVar('path',"/extensions/MealPlanner/").$path.$file."/";
            $apis = APIChildFolder($root,$path.$file."/",$file,$apis);
        }
    }
    // CLOSE THE DIRECTORY
    closedir($shared_models_dir);
    return $apis;
}
/**
 * scans the child of an api directory for apis
 * @param string $root the root path
 * @param string $path the middle path
 * @param string $api the top level api
 * @param array $apis the list of apis that have already been found
 * @return array the list of all the found apis
 */
function APIChildFolder($root,$path,$api,$apis){
    //echo "<br><b>$root$path</b><br>";
    $shared_models_dir = opendir($root.$path);
    // LOOP OVER ALL OF THE  FILES    
    while ($file = readdir($shared_models_dir)) { 
        //echo "<br><i>$file</i> ".is_dir($root.$path.$file)."<br>";
        // IF IT IS NOT A FOLDER, AND ONLY IF IT IS A .php WE ACCESS IT
        if(is_dir($root.$path.$file) && $file != ".." && $file != "."){
            $apis[$api][$file] = [];
            if(is_file($root.$path.$file."/params.json")){
                $apis[$api][$file]['params'] = "http://".$_SERVER['HTTP_HOST'].Settings::LoadSettingsVar('path',"/").$path.$file."/params.json";
            }
            $apis[$api][$file]['path'] = "http://".$_SERVER['HTTP_HOST'].Settings::LoadSettingsVar('path',"/").$path.$file."/";
            $apis = APIGrandChildFolder($root,$path.$file."/",$api,$file,$apis);
        }
    }
    // CLOSE THE DIRECTORY
    closedir($shared_models_dir);
    return $apis;
}
/**
 * scans the child of a child directory for apis
 * @param string $root the root path
 * @param string $path the middle path
 * @param string $api the top level api
 * @param string $parent the parent api
 * @param array $apis the list of apis that have already been found
 * @return array the list of all the found apis
 */
function APIGrandChildFolder($root,$path,$api,$parent,$apis){
    //echo "<br><b>$root$path</b><br>";
    $shared_models_dir = opendir($root.$path);
    // LOOP OVER ALL OF THE  FILES    
    while ($file = readdir($shared_models_dir)) { 
        //echo "<br><i>$file</i> ".is_dir($root.$path.$file)."<br>";
        // IF IT IS NOT A FOLDER, AND ONLY IF IT IS A .php WE ACCESS IT
        if(is_dir($root.$path.$file) && $file != ".." && $file != "."){
            $apis[$api][$parent][$file] = [];
            if(is_file($root.$path.$file."/params.json")){
                $apis[$api][$parent][$file]['params'] = "http://".$_SERVER['HTTP_HOST'].Settings::LoadSettingsVar('path',"/").$path.$file."/params.json";
            }
            $apis[$api][$parent][$file]['path'] = "http://".$_SERVER['HTTP_HOST'].Settings::LoadSettingsVar('path',"/").$path.$file."/";
            $apis = APIGrandChildFolder($root,$path.$file."/",$api,$parent,$apis);
        }
    }
    // CLOSE THE DIRECTORY
    closedir($shared_models_dir);
    return $apis;
}
?>