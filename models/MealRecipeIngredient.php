<?php
class MealRecipeIngredient extends clsModel {
    private static $settings = null;
    private static function GetInstance(){
        if(is_null(MealRecipeIngredient::$settings)){
            MealRecipeIngredient::$settings = new MealRecipeIngredient();
        }
        return MealRecipeIngredient::$settings;
    }
    public static function LoadItems(){
        $settings = MealRecipeIngredient::GetInstance();
        return $settings->LoadAll();
    }
    public static function LoadItemId($id){
        $settings = MealRecipeIngredient::GetInstance();
        return $settings->LoadById($id);
    }
    public static function SaveItem($data){
        $recipes = MealRecipeIngredient::GetInstance();
        $data = $recipes->CleanData($data);
        if(isset($data['id']) && !is_null($recipes->LoadById($data['id']))){
            return $recipes->Save($data,['id'=>$data['id']]);
        }
        return $recipes->Save($data);
    }
    public static function LoadRecipeIngredients($recipe_id){
        $me = MealRecipeIngredient::GetInstance();
        return $me->JoinFieldsWhere(new MealIngredient(),["id","unit","amount","ingredient_id","ingredient_type"],["name","type"],"ingredient_id","id",null,['recipe_id'=>$recipe_id]);
        //return $me->JoinWhere(new MealIngredient(),"ingredient_id","id",null,['recipe_id'=>$recipe_id]);
    }


    public $table_name = "MealRecipeIngredient";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"ingredient_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"recipe_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"ingredient_type",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"amount",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"unit",
            'Type'=>"varchar(10)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new MealRecipeIngredient();
}
?>