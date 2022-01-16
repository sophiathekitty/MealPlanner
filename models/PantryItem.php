<?php
/**
 * represents a physical item in the pantry that can be associated with an ingredient.
 */
class PantryItem extends clsModel {
    private static $pantry = null;
    /**
     * @return PantryItem|clsModel
     */
    private static function GetInstance(){
        if(is_null(PantryItem::$pantry)){
            PantryItem::$pantry = new PantryItem();
        }
        return PantryItem::$pantry;
    }
    /**
     * load all pantry items
     * @return array list of all pantry items
     */
    public static function LoadItems(){
        $pantry = PantryItem::GetInstance();
        return $pantry->LoadAll();
    }
    /**
     * load pantry item by id
     * @param int $id the pantry item id
     * @return array|null data array for pantry item or null if it doesn't exist
     */
    public static function LoadItemId($id){
        $pantry = PantryItem::GetInstance();
        return $pantry->LoadById($id);
    }
    /**
     * saves a pantry item
     * @param array $data the pantry item data
     * @return array save report
     */
    public static function SaveItem($data){
        $pantry = PantryItem::GetInstance();
        if(isset($data['id']) && !is_null($pantry->LoadById($data['id']))){
            return $pantry->Save($data,['id'=>$data['id']]);
        }
        return $pantry->Save($data);
    }


    public $table_name = "PantryItem";
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
            'Field'=>"store_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"ingredient_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"size",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new PantryItem();
}
?>