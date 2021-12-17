<?php
class Chef extends clsModel {
    private static $instance = null;
    private static function GetInstance(){
        if(is_null(Chef::$instance)){
            Chef::$instance = new Chef();
        }
        return Chef::$instance;
    }
    public static function LoadItems(){
        $instance = Chef::GetInstance();
        return $instance->LoadAll();
    }
    public static function LoadItemId($id){
        $instance = Chef::GetInstance();
        return $instance->LoadById($id);
    }
    public static function LoadUserId($user_id){
        $instance = Chef::GetInstance();
        return $instance->LoadWhere(["user_id"=>$user_id]);
    }
    public static function SaveItem($data){
        $recipes = Chef::GetInstance();
        $data = $recipes->CleanData($data);
        if(isset($data['id']) && !is_null($recipes->LoadById($data['id']))){
            return $recipes->Save($data,['id'=>$data['id']]);
        }
        return $recipes->Save($data);
    }


    public $table_name = "Chef";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"user_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"name",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"face",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"hat",
            'Type'=>"varchar(50)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Chef();
}
?>