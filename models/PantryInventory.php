<?php
class PantryInventory extends clsModel {
    private static $settings = null;
    private static function GetInstance(){
        if(is_null(PantryInventory::$settings)){
            PantryInventory::$settings = new PantryInventory();
        }
        return PantryInventory::$settings;
    }
    public static function LoadItems(){
        $settings = PantryInventory::GetInstance();
        return $settings->LoadAll();
    }
    public static function LoadItemId($id){
        $settings = PantryInventory::GetInstance();
        return $settings->LoadById($id);
    }
    public static function SaveItem($data){
        $recipes = PantryInventory::GetInstance();
        if(isset($data['id']) && !is_null($recipes->LoadById($data['id']))){
            return $recipes->Save($data,['id'=>$data['id']]);
        }
        return $recipes->Save($data);
    }


    public $table_name = "PantryInventory";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"item_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"count",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"date",
            'Type'=>"date",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new PantryInventory();
}
?>