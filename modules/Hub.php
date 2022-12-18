<?php
/**
 * 
 */
class NullHub {
    private static $cache = [];
    private static function GetData($api){
        if(isset(NullHub::$cache[$api])) return NullHub::$cache[$api];
        $url = "http://localhost/$api";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        NullHub::$cache[$api] = $data;
        return $data;    
    }
    /**
     * gets the hub url from device info
     * @return string hub ip address
     * @note should use ServerRequest::LoadHubJSON() or ServerRequest::LoadMainJSON() for talking to the hub
     */
    public static function GetHubUrl(){
        $data = NullHub::GetData("api/info/servers/?hub=1");
        return $data['hub']['url'];
    }
    /**
     * checks device info to see if this is a hub
     * @return bool returns true if this device is the hub
     */
    public static function IsHub(){
        $data = NullHub::GetData("api/info/");
        return $data['info']['is_hub'];
    }
    /**
     * gets the type of the hub this device connects to.. because that changes where apis are located
     * @return string the hub type (hub,old_hub)
     */
    public static function HubType(){
        $data = NullHub::GetData("api/info/servers/hub/");
        return $data['hub']['type'];
    }
}
/**
 * gets the hub url from device info
 * @return string hub ip address
 * @depreciated use ServerRequest::LoadHubJSON() or ServerRequest::LoadMainJSON() for talking to the hub
 */
function GetHubUrl(){
    return NullHub::GetHubUrl();
    $url = "http://localhost/api/info/servers/?hub=1";
    $info = file_get_contents($url);
    $data = json_decode($info,true);
    return $data['hub']['url'];
}
/**
 * checks device info to see if this is a hub
 * @return bool returns true if this device is the hub
 * @depreciated use NullHub::IsHub(); instead
 */
function IsHub(){
    return NullHub::IsHub();
    $url = "http://localhost/api/info/";
    $info = file_get_contents($url);
    $data = json_decode($info,true);
    return $data['info']['is_hub'];
}
/**
 * gets the type of the hub this device connects to.. because that changes where apis are located
 * @return string the hub type (hub,old_hub)
 * @depreciated use NullHub::HubType(); instead
 */
function HubType(){
    return NullHub::HubType();
    $url = "http://localhost/api/info/servers/hub/";
    $info = file_get_contents($url);
    $data = json_decode($info,true);
    return $data['hub']['type'];
}
?>