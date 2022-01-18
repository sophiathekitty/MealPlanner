<?php
/**
 * linking table for side ingredients holds the amount value
 */
class MealSideIngredient extends clsModel {
    private static $me = null;
    /**
     * @return MealSideIngredient|clsModel
     */
    private static function GetInstance(){
        if(is_null(MealSideIngredient::$me)){
            MealSideIngredient::$me = new MealSideIngredient();
        }
        return MealSideIngredient::$me;
    }
    /**
     * loads all side ingredients links
     * @return array the link table dumped out for some reason?
     */
    public static function LoadItems(){
        $me = MealSideIngredient::GetInstance();
        return $me->LoadAll();
    }
    /**
     * loads a side ingredient link by it's id
     * @param int $id the id of the side ingredient link
     */
    public static function LoadItemId($id){
        $me = MealSideIngredient::GetInstance();
        return $me->LoadById($id);
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
    public static function LoadSideIngredients($side_id){
        $me = MealSideIngredient::GetInstance();
        //echo $me->table_name."\n";
        return $me->JoinFieldsWhere(
            new MealIngredient(), // @param clsModel $model — instance of the model to be joined with this one
            ["id","unit","amount","ingredient_id","ingredient_type"], // @param array $fields list of fields to select from the model running this function
            ["name","type"], // @param array $model_fields list of fields to select from the model passed to this function
            "ingredient_id", // @param string $on field to join the tables on my_table.$on = model_table.$model_on
            "id", // @param string $model_on field to join the tables on my_table.$on = model_table.$model_on
            null, // @param array $model_where where array for fields in model being passed to this function
            ['side_id'=>$side_id] // @param array $where where array for fields in the model the function is being called on
        );
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