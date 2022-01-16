<?php
/**
 * linking table between recipe and meal ingredients
 */
class MealRecipeIngredient extends clsModel {
    private static $settings = null;
    /**
     * @return MealRecipeIngredient|clsModel
     */
    private static function GetInstance(){
        if(is_null(MealRecipeIngredient::$settings)){
            MealRecipeIngredient::$settings = new MealRecipeIngredient();
        }
        return MealRecipeIngredient::$settings;
    }
    /**
     * load all the meal recipe items for some reason?
     * it will just be the linking table not the actual ingredients from the ingredients table
     * @return array an array of linking table data
     */
    public static function LoadItems(){
        $settings = MealRecipeIngredient::GetInstance();
        return $settings->LoadAll();
    }
    /**
     * load recipe ingredient by id
     * it will just be the linking table not the actual ingredients from the ingredients table
     * @return array a data array for the recipe ingredient link (includes reference to recipe and ingredient)
     */
    public static function LoadItemId($id){
        $settings = MealRecipeIngredient::GetInstance();
        return $settings->LoadById($id);
    }
    /**
     * save a recipe ingredient link
     * @param array $data the recipe ingredient link data (what recipe, what ingredient, how much)
     * @return array returns save report
     */
    public static function SaveItem($data){
        $recipes = MealRecipeIngredient::GetInstance();
        $data = $recipes->CleanData($data);
        if(isset($data['id']) && !is_null($recipes->LoadById($data['id']))){
            return $recipes->Save($data,['id'=>$data['id']]);
        }
        return $recipes->Save($data);
    }
    /**
     * does a join with meal ingredient to load the ingredients for a recipe
     * @param int $recipe_id the id of the recipe
     * @return array an array of ingredients for the recipe
     */
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