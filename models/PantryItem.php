<?php
class PantryItem extends clsModel {
    private static $settings = null;
    private static function GetInstance(){
        if(is_null(PantryItem::$settings)){
            PantryItem::$settings = new PantryItem();
        }
        return PantryItem::$settings;
    }
    public static function LoadItems(){
        $settings = PantryItem::GetInstance();
        return $settings->LoadAll();
    }
    public static function LoadItemId($id){
        $settings = PantryItem::GetInstance();
        return $settings->LoadById($id);
    }
    public static function SaveItem($data){
        $recipes = PantryItem::GetInstance();
        if(isset($data['id']) && !is_null($recipes->LoadById($data['id']))){
            return $recipes->Save($data,['id'=>$data['id']]);
        }
        return $recipes->Save($data);
    }


    public $table_name = "PantryItem";
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
        ],[
            'Field'=>"store_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"ingredient_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"size",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new PantryItem();
}
?>