<?php
/**
 * gets the hub url from device info
 * @return string hub ip address
 */
function GetHubUrl(){
    $url = "http://localhost/api/info/servers/?hub=1";
    $info = file_get_contents($url);
    $data = json_decode($info,true);
    return $data['hub']['url'];
}
/**
 * checks device info to see if this is a hub
 * @return bool returns true if this device is the hub
 */
function IsHub(){
    $url = "http://localhost/api/info/";
    $info = file_get_contents($url);
    $data = json_decode($info,true);
    return $data['info']['is_hub'];
}
/**
 * gets the type of the hub this device connects to.. because that changes where apis are located
 * @return string the hub type (hub,old_hub)
 */
function HubType(){
    $url = "http://localhost/api/info/servers/hub/";
    $info = file_get_contents($url);
    $data = json_decode($info,true);
    return $data['hub']['type'];
}
?>