<?php
class Recipes extends clsModel {
    private static $settings = null;
    private static function GetInstance(){
        if(is_null(Recipes::$settings)){
            Recipes::$settings = new Recipes();
        }
        return Recipes::$settings;
    }
    public static function LoadRecipes(){
        $settings = Recipes::GetInstance();
        return $settings->LoadAll();
    }
    public static function LoadRecipeId($id){
        $settings = Recipes::GetInstance();
        return $settings->LoadById($id);
    }
    public static function SaveRecipe($data){
        $recipes = Recipes::GetInstance();
        if(isset($data['id']) && !is_null($recipes->LoadById($data['id']))){
            return $recipes->Save($data,['id'=>$data['id']]);
        }
        return $recipes->Save($data);
    }


    public $table_name = "Recipes";
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
            'Field'=>"instructions",
            'Type'=>"text",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"user_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"schedule",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"photo",
            'Type'=>"varchar(60)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"none.png",
            'Extra'=>""
        ],[
            'Field'=>"cook_level",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"cook_unit",
            'Type'=>"varchar(10)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"side_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"thaw_time",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"8",
            'Extra'=>""
        ],[
            'Field'=>"prep_time",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"10",
            'Extra'=>""
        ],[
            'Field'=>"cook_time",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"25",
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Recipes();
}
?>