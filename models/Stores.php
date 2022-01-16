<?php
/**
 * stores store names...
 */
class Stores extends clsModel {
    private static $store = null;
    /**
     * @return Stores|clsModel
     */
    private static function GetInstance(){
        if(is_null(Stores::$store)){
            Stores::$store = new Stores();
        }
        return Stores::$store;
    }
    /**
     * load all stores
     * @return array an array of stores
     */
    public static function LoadItems(){
        $store = Stores::GetInstance();
        return $store->LoadAll();
    }
    /**
     * load store by id
     * @param int $id the store id
     * @return array|null the store data array or null if store doesn't exist
     */
    public static function LoadItemId($id){
        $store = Stores::GetInstance();
        return $store->LoadById($id);
    }
    /**
     * save a store
     * @param array $data store data
     * @return array save report
     */
    public static function SaveItem($data){
        $store = Stores::GetInstance();
        if(isset($data['id']) && !is_null($store->LoadById($data['id']))){
            return $store->Save($data,['id'=>$data['id']]);
        }
        return $store->Save($data);
    }


    public $table_name = "Stores";
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
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Stores();
}
?>