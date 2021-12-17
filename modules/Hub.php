<?php
function GetHubUrl(){
    $url = "http://localhost/api/info/servers/?hub=1";
    $info = file_get_contents($url);
    $data = json_decode($info,true);
    return $data['hub']['url'];
}
function IsHub(){
    $url = "http://localhost/api/info/";
    $info = file_get_contents($url);
    $data = json_decode($info,true);
    return $data['info']['is_hub'];
}
function HubType(){
    $url = "http://localhost/api/info/servers/hub/";
    $info = file_get_contents($url);
    $data = json_decode($info,true);
    return $data['hub']['type'];
}
?>