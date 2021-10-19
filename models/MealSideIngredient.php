<?php
class MealSideIngredient extends clsModel {
    private static $settings = null;
    private static function GetInstance(){
        if(is_null(MealSideIngredient::$settings)){
            MealSideIngredient::$settings = new MealSideIngredient();
        }
        return MealSideIngredient::$settings;
    }
    public static function LoadItems(){
        $settings = MealSideIngredient::GetInstance();
        return $settings->LoadAll();
    }
    public static function LoadItemId($id){
        $settings = MealSideIngredient::GetInstance();
        return $settings->LoadById($id);
    }
    public static function SaveItem($data){
        $recipes = MealSideIngredient::GetInstance();
        $data = $recipes->CleanData($data);
        if(isset($data['id']) && !is_null($recipes->LoadById($data['id']))){
            return $recipes->Save($data,['id'=>$data['id']]);
        }
        return $recipes->Save($data);
    }
    public static function LoadRecipeIngredients($side_id){
        $me = MealSideIngredient::GetInstance();
        return $me->JoinFieldsWhere(new MealIngredient(),["id","unit","amount","ingredient_id","ingredient_type"],["name","type"],"ingredient_id","id",null,['side_id'=>$side_id]);
        //return $me->JoinWhere(new MealIngredient(),"ingredient_id","id",null,['recipe_id'=>$recipe_id]);
    }


    public $table_name = "MealSideIngredient";
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
            'Field'=>"side_id",
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
    clsModel::$models[] = new MealSideIngredient();
}
?>