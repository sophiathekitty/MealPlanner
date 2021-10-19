<?php
class Stores extends clsModel {
    private static $settings = null;
    private static function GetInstance(){
        if(is_null(Stores::$settings)){
            Stores::$settings = new Stores();
        }
        return Stores::$settings;
    }
    public static function LoadItems(){
        $settings = Stores::GetInstance();
        return $settings->LoadAll();
    }
    public static function LoadItemId($id){
        $settings = Stores::GetInstance();
        return $settings->LoadById($id);
    }
    public static function SaveItem($data){
        $recipes = Stores::GetInstance();
        if(isset($data['id']) && !is_null($recipes->LoadById($data['id']))){
            return $recipes->Save($data,['id'=>$data['id']]);
        }
        return $recipes->Save($data);
    }


    public $table_name = "Stores";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"name",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Stores();
}
?>