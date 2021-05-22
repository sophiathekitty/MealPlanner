<?php
function GetHubUrl(){
    $url = "http://localhost/api/info/servers/?hub=1";
    $info = file_get_contents($url);
    $data = json_decode($info,true);
    return $data['hub']['url'];
}
?>