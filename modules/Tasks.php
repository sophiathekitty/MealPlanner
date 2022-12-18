<?php
/**
 * handles meal task stuff
 */
class MealTasks {
    /**
     * find any upcoming tasks (thaw,prep,cook)
     * @return array an array of upcoming tasks
     */
    public static function UpcomingTasks(){
        $meals = [];
        $meals[0] = MealStamp::Today();
        for($i = 1; $i < 4; $i++){
            $meals[$i] = MealStamp::Tomorrow($i);
        }
        $tasks = [];
        foreach($meals as $meal){
            if(is_null($meal['thawed'])){
                // has thawed task
                if(strtotime($meal['thaw_at']) < time()){
                    $task = ['task'=>'thaw'];
                    if(isset($meal['chef'])){
                        $task['assigned_to'] = $meal['chef']['user_id'];
                    } else $task['assigned_to'] = 0;
                    $task['name'] = "Thaw\nDinner";// ".$meal['meal'];
                    $task['due'] = $meal['thaw_at'];
                    $task['app'] = "MealPlanner";
                    //$task['meal'] = $meal;
                    $task['room_id'] = Settings::LoadSettingsVar("room_id",0);
                    $task['guid'] = md5($task['due'].$task['name']);
                    $tasks[] = $task;
                }
                //continue;
            } else {
                $task = ['task'=>'thaw'];
                if(isset($meal['chef'])){
                    $task['assigned_to'] = $meal['chef']['user_id'];
                    $task['completed_by'] = $meal['chef']['user_id'];
                } else $task['assigned_to'] = 0;
                $task['name'] = "Thaw\nDinner";// ".$meal['meal'];
                $task['due'] = $meal['thaw_at'];
                $task['completed'] = $meal['thawed'];
                $task['app'] = "MealPlanner";
                //$task['meal'] = $meal;
                $task['room_id'] = Settings::LoadSettingsVar("room_id",0);
                $task['guid'] = md5($task['due'].$task['name']);
                $tasks[] = $task;

            }
            if(is_null($meal['prepped'])){
                // has thawed task
                if(strtotime($meal['prep_at']) < time()){
                    $task = ['task'=>'prep'];
                    if(isset($meal['chef'])){
                        $task['assigned_to'] = $meal['chef']['user_id'];
                    } else $task['assigned_to'] = 0;
                    $task['name'] = "Prep\nDinner";// ".$meal['meal'];
                    $task['due'] = $meal['prep_at'];
                    //$task['meal'] = $meal;
                    $task['room_id'] = 0;
                    $task['app'] = "MealPlanner";
                    $task['guid'] = md5($task['due'].$task['name']);
                    $tasks[] = $task;
                }
                //continue;
            } else {
                $task = ['task'=>'prep'];
                if(isset($meal['chef'])){
                    $task['completed_by'] = $meal['chef']['user_id'];
                    $task['assigned_to'] = $meal['chef']['user_id'];
                } else $task['assigned_to'] = 0;
                $task['name'] = "Prep\nDinner";// ".$meal['meal'];
                $task['due'] = $meal['prep_at'];
                $task['completed'] = $meal['prepped'];
                //$task['meal'] = $meal;
                $task['app'] = "MealPlanner";
                $task['room_id'] = 0;
                $task['guid'] = md5($task['due'].$task['name']);
                $tasks[] = $task;
            }
            if(is_null($meal['cooked'])){
                // has thawed task
                if(strtotime($meal['cook_at']) < time()){
                    $task = ['task'=>'cook'];
                    if(isset($meal['chef'])){
                        $task['assigned_to'] = $meal['chef']['user_id'];
                    } else $task['assigned_to'] = 0;
                    $task['name'] = "Cook\nDinner";// ".$meal['meal'];
                    $task['due'] = $meal['cook_at'];
                    $task['app'] = "MealPlanner";
                    ////$task['meal'] = $meal;
                    $task['room_id'] = Settings::LoadSettingsVar("room_id",0);
                    $task['guid'] = md5($task['due'].$task['name']);
                    $tasks[] = $task;
                }
                //continue;
            } else {
                $task = ['task'=>'cook'];
                if(isset($meal['chef'])){
                    $task['completed_by'] = $meal['chef']['user_id'];
                    $task['assigned_to'] = $meal['chef']['user_id'];
                } else $task['assigned_to'] = 0;
                $task['name'] = "Cook\nDinner";// ".$meal['meal'];
                $task['due'] = $meal['cook_at'];
                $task['completed'] = $meal['cooked'];
                $task['app'] = "MealPlanner";
                ////$task['meal'] = $meal;
                $task['room_id'] = Settings::LoadSettingsVar("room_id",0);
                $task['guid'] = md5($task['due'].$task['name']);
                $tasks[] = $task;
            }
            // sides
            if(is_null($meal['side_prepped'])){
                // has thawed task
                if(strtotime($meal['side_prep_at']) < time()){
                    $task = ['task'=>'side_prep'];
                    if(isset($meal['chef'])){
                        $task['assigned_to'] = $meal['chef']['user_id'];
                    } else $task['assigned_to'] = 0;
                    $task['name'] = "Prep\nSide";// ".$meal['meal'];
                    $task['due'] = $meal['side_prep_at'];
                    ////$task['meal'] = $meal;
                    $task['room_id'] = 0;
                    $task['app'] = "MealPlanner";
                    $task['guid'] = md5($task['due'].$task['name']);
                    $tasks[] = $task;
                }
                //continue;
            } else {
                $task = ['task'=>'side_prep'];
                if(isset($meal['chef'])){
                    $task['completed_by'] = $meal['chef']['user_id'];
                    $task['assigned_to'] = $meal['chef']['user_id'];
                } else $task['assigned_to'] = 0;
                $task['name'] = "Prep\nSide";// ".$meal['meal'];
                $task['due'] = $meal['side_prep_at'];
                $task['completed'] = $meal['side_prepped'];
                ////$task['meal'] = $meal;
                $task['app'] = "MealPlanner";
                $task['room_id'] = 0;
                $task['guid'] = md5($task['due'].$task['name']);
                $tasks[] = $task;
            }
            if(is_null($meal['side_cooked'])){
                // has thawed task
                if(strtotime($meal['side_cook_at']) < time()){
                    $task = ['task'=>'side_cook'];
                    if(isset($meal['chef'])){
                        $task['assigned_to'] = $meal['chef']['user_id'];
                    } else $task['assigned_to'] = 0;
                    $task['name'] = "Cook\nSide";// ".$meal['meal'];
                    $task['due'] = $meal['side_cook_at'];
                    $task['app'] = "MealPlanner";
                    ////$task['meal'] = $meal;
                    $task['room_id'] = Settings::LoadSettingsVar("room_id",0);
                    $task['guid'] = md5($task['due'].$task['name']);
                    $tasks[] = $task;
                }
                //continue;
            } else {
                $task = ['task'=>'side_cook'];
                if(isset($meal['chef'])){
                    $task['completed_by'] = $meal['chef']['user_id'];
                    $task['assigned_to'] = $meal['chef']['user_id'];
                } else $task['assigned_to'] = 0;
                $task['name'] = "Cook\nSide";// ".$meal['meal'];
                $task['due'] = $meal['side_cook_at'];
                $task['completed'] = $meal['side_cooked'];
                $task['app'] = "MealPlanner";
                ////$task['meal'] = $meal;
                $task['room_id'] = Settings::LoadSettingsVar("room_id",0);
                $task['guid'] = md5($task['due'].$task['name']);
                $tasks[] = $task;
            }
        }        
        return $tasks;
    }
    /**
     * completes a meal recipe task. if this isn't the hub it will complete it on hub as well
     * @param string $data YYYY-MM-DD
     * @param string $task thawed|prepped|cooked
     * @return array save report
     */
    public static function CompleteTask($date,$task){
        $data = [];
        $meal = MealPlan::GetMeal($date);
        $meal[$task] = date("Y-m-d H:i:s");
        $data['save'] = MealPlan::SaveMeal($meal);
        $data['meal'] = $meal;
        $data['meal_reload'] = MealPlan::GetMeal($date);
        // if not hub report back to hub
        if(!IsHub()){
            if(HubType() == "old_hub"){
                if($task == "thawed") $task = "thaw";
                if($task == "prepped") $task = "prep";
                if($task == "cooked") $task = "cook";
                if($task == "side_prepped") $task = "side_prep";
                if($task == "side_cooked") $task = "side_cook";
                $api = "/api/meal/?meal_date=$date&task=$task";
                //$url = "http://".GetHubUrl()."/api/meal/?meal_date=$date&task=$task";
            } else {
                $api = "/extensions/MealPlanner/api/tasks?date=$date&task=$task";
                //$url = "http://".GetHubUrl()."/extensions/MealPlanner/api/tasks?date=$date&task=$task";
            }
            //$info = file_get_contents($url);
            //$data['remote'] = json_decode($info,true);    
            $data['remote'] = ServerRequests::LoadHubJSON($api);    
        }
        return $data;
    }
}
?>