<?php
class MealIngredient extends clsModel {
    private static $settings = null;
    private static function GetInstance(){
        if(is_null(MealIngredient::$settings)){
            MealIngredient::$settings = new MealIngredient();
        }
        return MealIngredient::$settings;
    }
    public static function LoadItems(){
        $settings = MealIngredient::GetInstance();
        return $settings->LoadAll();
    }
    public static function LoadItemId($id){
        $settings = MealIngredient::GetInstance();
        return $settings->LoadById($id);
    }
    public static function SaveItem($data){
        $recipes = MealIngredient::GetInstance();
        $data = $recipes->CleanData($data);
        if(isset($data['id']) && !is_null($recipes->LoadById($data['id']))){
            return $recipes->Save($data,['id'=>$data['id']]);
        }
        return $recipes->Save($data);
    }


    public $table_name = "MealIngredient";
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
            'Field'=>"type",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"units",
            'Type'=>"varchar(50)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"stock",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new MealIngredient();
}
?>