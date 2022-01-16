<?php
/**
 * the weekly meal schedule
 */
class MealSchedule extends clsModel {
    private static $settings = null;
    /**
     * @return MealSchedule|clsModel
     */
    private static function GetInstance(){
        if(is_null(MealSchedule::$settings)){
            MealSchedule::$settings = new MealSchedule();
        }
        return MealSchedule::$settings;
    }
    /**
     * load all recipes
     * @return array list of recipes
     */
    public static function LoadRecipes(){
        $settings = MealSchedule::GetInstance();
        return $settings->LoadAll();
    }
    /**
     * load recipe by id
     * @param int $id the recipe id
     * @return array recipe data array
     */
    public static function LoadRecipeId($id){
        $settings = MealSchedule::GetInstance();
        return $settings->LoadById($id);
    }
    /**
     * save a recipe
     * @param array $data the recipe data array
     * @return array save report
     */
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
        ],[
            'Field'=>"mandatory",
            'Type'=>"tinyint(1)",
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