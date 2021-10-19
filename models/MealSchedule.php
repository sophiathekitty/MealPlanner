<?php
class MealSchedule extends clsModel {
    private static $settings = null;
    private static function GetInstance(){
        if(is_null(MealSchedule::$settings)){
            MealSchedule::$settings = new MealSchedule();
        }
        return MealSchedule::$settings;
    }
    public static function LoadRecipes(){
        $settings = MealSchedule::GetInstance();
        return $settings->LoadAll();
    }
    public static function LoadRecipeId($id){
        $settings = MealSchedule::GetInstance();
        return $settings->LoadById($id);
    }
    public static function SaveDay($data){
        $recipes = MealSchedule::GetInstance();
        if(isset($data['id'])) $data['recipe_id'] = $data['id'];
        $data = $recipes->CleanData($data);
        if(isset($data['day_of_week']) && !is_null($recipes->LoadWhere(["day_of_week"=>$data['day_of_week']]))){
            return $recipes->Save($data,['day_of_week'=>$data['day_of_week']]);
        }
        return $recipes->Save($data);
    }


    public $table_name = "WeeklySchedule";
    public $fields = [
        [
            'Field'=>"day_of_week",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"recipe_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"user_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new MealSchedule();
}
?>