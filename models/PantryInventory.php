<?php
/**
 * how much of a pantry item do we have and when did we know we had them.
 * this is for logging when we have stuff for calculating how much we actually have
 */
class PantryInventory extends clsModel {
    private static $pantry = null;
    /**
     * @return PantryInventory|clsModel
     */
    private static function GetInstance(){
        if(is_null(PantryInventory::$pantry)){
            PantryInventory::$pantry = new PantryInventory();
        }
        return PantryInventory::$pantry;
    }
    /**
     * loads all the pantry inventories
     * @todo this should probably actually do a join with pantry item
     * @return array array of pantry inventory logs
     */
    public static function LoadItems(){
        $pantry = PantryInventory::GetInstance();
        return $pantry->LoadAll();
    }
    /**
     * load pantry inventory by id
     * @param int $id pantry inventory log
     * @return array|null the data array for the pantry inventory or null if it doesn't exist
     */
    public static function LoadItemId($id){
        $pantry = PantryInventory::GetInstance();
        return $pantry->LoadById($id);
    }
    /**
     * save a pantry inventory
     * @param array $data the pantry inventory data array
     * @return array save report
     */
    public static function SaveItem($data){
        $pantry = PantryInventory::GetInstance();
        if(isset($data['id']) && !is_null($pantry->LoadById($data['id']))){
            return $pantry->Save($data,['id'=>$data['id']]);
        }
        return $pantry->Save($data);
    }


    public $table_name = "PantryInventory";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"item_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"count",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"date",
            'Type'=>"date",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new PantryInventory();
}
?>