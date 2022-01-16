<?php
/**
 * linking table for side ingredients holds the amount value
 */
class MealSideIngredient extends clsModel {
    private static $settings = null;
    /**
     * @return MealSideIngredient|clsModel
     */
    private static function GetInstance(){
        if(is_null(MealSideIngredient::$settings)){
            MealSideIngredient::$settings = new MealSideIngredient();
        }
        return MealSideIngredient::$settings;
    }
    /**
     * loads all side ingredients links
     * @return array the link table dumped out for some reason?
     */
    public static function LoadItems(){
        $settings = MealSideIngredient::GetInstance();
        return $settings->LoadAll();
    }
    /**
     * loads a side ingredient link by it's id
     * @param int $id the id of the side ingredient link
     */
    public static function LoadItemId($id){
        $settings = MealSideIngredient::GetInstance();
        return $settings->LoadById($id);
    }
    /**
     * save a side ingredient link
     * @param array $data the data for the thing
     * @return array save report
     */
    public static function SaveItem($data){
        $recipes = MealSideIngredient::GetInstance();
        $data = $recipes->CleanData($data);
        if(isset($data['id']) && !is_null($recipes->LoadById($data['id']))){
            return $recipes->Save($data,['id'=>$data['id']]);
        }
        return $recipes->Save($data);
    }
    /**
     * fancy join load that loads the side ingredients with the ingredient details
     * @param int $side_id the side id
     * @return array list of ingredients for side
     */
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