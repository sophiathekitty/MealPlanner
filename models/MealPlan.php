<?php
/**
 * keeps track of what's for dinner
 */
class MealPlan extends clsModel {
    private static $settings = null;
    /**
     * @return MealPlan|clsModel
     */
    private static function GetInstance(){
        if(is_null(MealPlan::$settings)){
            MealPlan::$settings = new MealPlan();
        }
        return MealPlan::$settings;
    }
    /**
     * get a meal by date
     * @param string $date the date the meal is happening
     * @return array|null the meal data array or null if no meal on date
     */
    public static function GetMeal($date){
        $meals = MealPlan::GetInstance();
        return $meals->LoadWhere(['date'=>$date]);
    }
    /**
     * gets today's meal
     * @return array|null the meal data array or null if no meal
     */
    public static function GetTodaysMeal(){
        $meals = MealPlan::GetInstance();
        return $meals->LoadWhere(['date'=>date("Y-m-d")]);
    }
    /**
     * gets upcoming meal
     * @param int $day how many days into future? (1 = tomorrow)
     * @return array|null the meal data array or null if no meal
     */
    public static function GetTomorrowsMeal($days = 1){
        $meals = MealPlan::GetInstance();
        return $meals->LoadWhere(['date'=>date("Y-m-d",time()+DaysToSeconds($days))]);
    }
    /**
     * get left overs
     * @param int $days how many days back to include
     * @return array list of meals with leftovers
     */
    public static function GetLeftovers($days){
        //$meals = MealPlan::GetInstance();
        $date = date("Y-m-d",time()-DaysToSeconds($days));
        return clsDB::$db_g->select("SELECT * FROM `MealPlan` WHERE `date` > '$date' AND `cooked` IS NOT NULL AND `leftovers_gone` IS NULL;");
        //return $meals->LoadWhere(['date'=>date("Y-m-d")]);
    }
    /**
     * save a meal
     * @param array $meal the meal data array
     * @return array save report
     */
    public static function SaveMeal($meal){
        $meals = MealPlan::GetInstance();
        if(is_null($meals->LoadWhere(['date'=>$meal['date']]))){
            return $meals->Save($meal);
        }
        return $meals->Save($meal,['date'=>$meal['date']]);
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