<?php
class MealTasks {
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
    public static function CompleteTask($date,$task){
        $data = [];
        $meal = MealPlan::GetMeal($date);
        $meal[$task] = date("Y-m-d H:i:s");
        $data['save'] = MealPlan::SaveMeal($meal);
        $data['meal'] = $meal;
        // if not hub report back to hub
        if(!IsHub()){
            if(HubType() == "old_hub"){
                if($task == "thawed") $task = "thaw";
                if($task == "prepped") $task = "prep";
                if($task == "cooked") $task = "cook";
                $url = "http://".GetHubUrl()."/api/meal/?meal_date=$date&task=$task";
            } else{
                $url = "http://".GetHubUrl()."/extensions/MealPlanner/api/tasks?date=$date&task=$task";
            }
            $info = file_get_contents($url);
            $data['remote'] = json_decode($info,true);    
        }
        return $data;
    }
}
?>