<?php
/**
 * an extension version of the model from the NullHub base app.
 * designed so it can be copied into other extensions as is.
 * @note uses constant("EXTENSION_PATH") to point to extension folder
 */
class ServerRequests {
    private static function GetData($url){
        if(defined("TEST_MODE") && strpos($url,'localhost') > -1){
            if(strpos($url,"?") > -1) $url .= "&TEST_MODE=".constant("TEST_MODE");
            else $url .= "?TEST_MODE=".constant("TEST_MODE");
        } 
        if(defined("DEBUG") && strpos($url,'localhost') > -1){
            if(strpos($url,"?") > -1) $url .= "&DEBUG=".constant("DEBUG");
            else $url .= "?DEBUG=".constant("DEBUG");
        }
        $content=@file_get_contents($url);
        $json = json_decode($content,true);
        Debug::LogGroup("ServerRequests::GetData",$url,$json);
        if(is_null($json)) return ['content'=>$content];
        return $json;
    }
    /**
     * loads json data from a meal planner api
     * @param string $host the ip address
     * @param string $api the api path "/api/info/"
     * @return array associated array of json data
     * @note uses constant("EXTENSION_PATH") to point to extension folder
     */
    public static function LoadHostJSON($host,$api){
        $url = "http://".$host.constant("EXTENSION_PATH").$api;
        return ServerRequests::GetData($url);
        /*
        if(defined("TEST_MODE") && $host == 'localhost'){
            if(strpos($url,"?") > -1) $url .= "&TEST_MODE=".constant("TEST_MODE");
            else $url .= "?TEST_MODE=".constant("TEST_MODE");
        } 
        if(defined("DEBUG") && $host == 'localhost'){
            if(strpos($url,"?") > -1) $url .= "&DEBUG=".constant("DEBUG");
            else $url .= "?DEBUG=".constant("DEBUG");
        } 
        $content=@file_get_contents($url);
        $json = json_decode($content,true);
        if(is_null($json)) return ['content'=>$content];
        return $json;
        */
    }
    /**
     * loads json data from meal planner api on local host
     * @param string $api the api path "/api/info/"
     * @return array associated array of json data
     * @note uses constant("EXTENSION_PATH") to point to extension folder
     */
    public static function LoadLocalhostJSON($api){
        return ServerRequests::LoadHostJSON('localhost',$api);
    }
    /**
     * loads json data from meal planner api on local host
     * @param string $api the api path "/api/info/"
     * @return array associated array of json data
     * @note uses constant("EXTENSION_PATH") to point to extension folder
     */
    public static function LoadRemoteJSON($main,$api){
        $url = "http://localhost/api/requests/".$main."/?api=".$api;
        if(strpos($api,"?") > -1){
            $url = "http://".NullHub::GetHubUrl().$api;
        }
        return ServerRequests::GetData($url);
        /*
        $content=@file_get_contents($url);
        $json = json_decode($content,true);
        if(is_null($json)) return ['content'=>$content];
        return $json;
        */
    }
    /**
     * loads api data from the hub
     * @param string $api the api path "/api/info/"
     * @return array associated array of json data
     */
    public static function LoadHubJSON($api){
        return ServerRequests::LoadRemoteJSON("hub",$api);
    }
    /**
     * loads api data from the main hub
     * @param string $api the api path "/api/info/"
     * @return array associated array of json data
     */
    public static function LoadMainJSON($api){
        return ServerRequests::LoadRemoteJSON("main",$api);
    }
}
?>