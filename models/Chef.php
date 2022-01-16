<?php
class Chef extends clsModel {
    private static $chef = null;
    /**
     * @return Chef|clsModel
     */
    private static function GetInstance(){
        if(is_null(Chef::$chef)){
            Chef::$chef = new Chef();
        }
        return Chef::$chef;
    }
    /**
     * loads all chefs
     * @return array list of chefs
     */
    public static function LoadItems(){
        $chef = Chef::GetInstance();
        return $chef->LoadAll();
    }
    /**
     * load chef by id
     * @param int $id chef id
     * @return array|null chef data array or null if none
     */
    public static function LoadItemId($id){
        $chef = Chef::GetInstance();
        return $chef->LoadById($id);
    }
    /**
     * load chef by user_id
     * @param int $user_id chef id
     * @return array|null chef data array or null if none
     */
    public static function LoadUserId($user_id){
        $chef = Chef::GetInstance();
        return $chef->LoadWhere(["user_id"=>$user_id]);
    }
    /**
     * save chef
     * @param array $data chef data array
     * @return array save report
     */
    public static function SaveItem($data){
        $chef = Chef::GetInstance();
        $data = $chef->CleanData($data);
        if(isset($data['id']) && !is_null($chef->LoadById($data['id']))){
            return $chef->Save($data,['id'=>$data['id']]);
        }
        return $chef->Save($data);
    }


    public $table_name = "Chef";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"user_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"name",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"face",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"hat",
            'Type'=>"varchar(50)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Chef();
}
?>