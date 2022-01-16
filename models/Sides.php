<?php
class Sides extends clsModel {
    private static $sides = null;
    /**
     * @return Sides|clsModel
     */
    private static function GetInstance(){
        if(is_null(Sides::$sides)){
            Sides::$sides = new Sides();
        }
        return Sides::$sides;
    }
    /**
     * load all sides
     * @return array array of all sides
     */
    public static function LoadSides(){
        $sides = Sides::GetInstance();
        return $sides->LoadAll();
    }
    /**
     * loads side by id
     * @param int $id side_id
     * @return array|null the data array for the side or null if it doesn't exist
     */
    public static function LoadSideId($id){
        $sides = Sides::GetInstance();
        return $sides->LoadById($id);
    }
    /**
     * saves a side
     * @param array $data the data array
     * @return array save report
     * @todo double check the weird extra saving?
     */
    public static function SaveSide($data){
        $sides = Sides::GetInstance();
        $side = $sides->LoadById($data['id']);
        //print_r($data);
        //print_r($side);
        if(isset($data['id']) && !is_null($side)){
            return $sides->Save($data,['id'=>$data['id']]);
        }
        $report = [];
        if(isset($data['id']) && (int)$data['id'] == 0){
            $report['save1'] = $sides->Save($data);
            $report['save2'] = $sides->Save($data,['name'=>$data['name']]);
        }
        $report['save3'] =  $sides->Save($data);
        return $report;
    }

    public $table_name = "Sides";
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
            'Field'=>"instructions",
            'Type'=>"text",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"user_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"schedule",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"photo",
            'Type'=>"varchar(60)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"none.png",
            'Extra'=>""
        ],[
            'Field'=>"cook_level",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"cook_unit",
            'Type'=>"varchar(10)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"thaw_time",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"8",
            'Extra'=>""
        ],[
            'Field'=>"prep_time",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"10",
            'Extra'=>""
        ],[
            'Field'=>"cook_time",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"25",
            'Extra'=>""
        ]
    ];
}

if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Sides();
}
?>