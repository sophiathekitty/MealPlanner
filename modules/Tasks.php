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
                    $task['name'] = "Thaw ".$meal['meal'];
                    $task['due'] = $meal['thaw_at'];
                    $task['meal'] = $meal;
                    $task['guid'] = md5($task['due'].$task['name']);
                    $tasks[] = $task;
                }
                continue;
            }
            if(is_null($meal['prepped'])){
                // has thawed task
                if(strtotime($meal['prep_at']) < time()){
                    $task = ['task'=>'prep'];
                    $task['name'] = "Prep ".$meal['meal'];
                    $task['due'] = $meal['prep_at'];
                    $task['meal'] = $meal;
                    $task['guid'] = md5($task['due'].$task['name']);
                    $tasks[] = $task;
                }
                continue;
            }
            if(is_null($meal['cooked'])){
                // has thawed task
                if(strtotime($meal['cook_at']) < time()){
                    $task = ['task'=>'cook'];
                    $task['name'] = "Cook ".$meal['meal'];
                    $task['due'] = $meal['cook_at'];
                    $task['meal'] = $meal;
                    $task['guid'] = md5($task['due'].$task['name']);
                    $tasks[] = $task;
                }
                continue;
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
                $url = "http://".GetHubUrl()."/api/meal/?meal_date=$date&task=$task";
            } else {
                $url = "http://".GetHubUrl()."/extensions/MealPlanner/api/tasks?date=$date&task=$task";
            }
            $info = file_get_contents($url);
            $data['remote'] = json_decode($info,true);    
        }
        return $data;
    }
}
?>