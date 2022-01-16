<?php
/**
 * defines an ingredient used in recipes
 */
class MealIngredient extends clsModel {
    private static $ingredients = null;
    /**
     * @return MealIngredient|clsModel
     */
    private static function GetInstance(){
        if(is_null(MealIngredient::$ingredients)){
            MealIngredient::$ingredients = new MealIngredient();
        }
        return MealIngredient::$ingredients;
    }
    /**
     * load all ingredients
     * @return array list of ingredients
     */
    public static function LoadItems(){
        $ingredients = MealIngredient::GetInstance();
        return $ingredients->LoadAll();
    }
    /**
     * load ingredient by id
     * @param int $id the ingredient id
     * @return array|null ingredient data array or null if none
     */
    public static function LoadItemId($id){
        $ingredients = MealIngredient::GetInstance();
        return $ingredients->LoadById($id);
    }
    /**
     * save ingredient
     * @param array $data the data array
     * @return array save report
     */
    public static function SaveItem($data){
        $ingredients = MealIngredient::GetInstance();
        $data = $ingredients->CleanData($data);
        if(isset($data['id']) && !is_null($ingredients->LoadById($data['id']))){
            return $ingredients->Save($data,['id'=>$data['id']]);
        }
        return $ingredients->Save($data);
    }


    public $table_name = "MealIngredient";
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
            'Field'=>"type",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"units",
            'Type'=>"varchar(50)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"stock",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new MealIngredient();
}
?>