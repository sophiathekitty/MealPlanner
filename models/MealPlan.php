<?php
class MealPlan extends clsModel {
    private static $settings = null;
    private static function GetInstance(){
        if(is_null(MealPlan::$settings)){
            MealPlan::$settings = new MealPlan();
        }
        return MealPlan::$settings;
    }
    public static function GetTodaysMeal(){
        $meals = MealPlan::GetInstance();
        return $meals->LoadWhere(['date'=>date("Y-m-d")]);
    }
    public static function GetTomorrowsMeal(){
        $meals = MealPlan::GetInstance();
        return $meals->LoadWhere(['date'=>date("Y-m-d",time()+DaysToSeconds(1))]);
    }
    public $table_name = "MealPlan";
    public $fields = [
        [
            'Field'=>"date",
            'Type'=>"date",
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
            'Field'=>"side_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"meal",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"thawed",
            'Type'=>"datetime",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"prepped",
            'Type'=>"datetime",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"cooked",
            'Type'=>"datetime",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"side_prepped",
            'Type'=>"datetime",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"side_cooked",
            'Type'=>"datetime",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"dinner_done",
            'Type'=>"datetime",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"leftovers_gone",
            'Type'=>"datetime",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new MealPlan();
}
?>