<?php
require_once("../../../includes/main.php");
$data = [];
if(isset($_GET['date'])){
    //$data['get'] = $_GET;
    $data['save'] = MealPlan::SaveMeal($_GET);
    $data['meal_reloaded'] = MealStamp::Stamp(MealPlan::GetMeal($_GET['date']));
    if(!IsHub()){
        if(HubType() == "old_hub"){
            $date = $_GET['date'];
            $recipe_id = $_GET['recipe_id'];
            $side_id = $_GET['side_id'];
            $api = "/api/meal/?meal_date=$date&recipe_id=$recipe_id&side_id=$side_id";
            //$url = "http://".GetHubUrl()."/api/meal/?meal_date=$date&recipe_id=$recipe_id&side_id=$side_id";
        } else{
            $api = "/extensions/MealPlanner/api/meal/save";
            $first = true;
            foreach($_GET as $key => $value){
                if(!is_array($value)){
                    if($first){
                        $api .= "?";
                    } else {
                        $api .= "&";
                    }
                    $api .= $key."=".$value;
                    $first = false;
                }
            }
        }
        //$info = file_get_contents($url);
        //$data['remote'] = json_decode($info,true);    
        $data['remote'] = ServerRequests::LoadHubJSON($api);
    }
}
$data['meals'] = [];
$count = 4;
if($_GET['count']){
    $count = $_GET['count'];
    if($count > 14) $count = 14;
}
$data['meals'][0] = MealStamp::Today();
$data['meals'][0]['day'] = 'today';
for($i = 1; $i < $count; $i++){
    $data['meals'][$i] = MealStamp::Tomorrow($i);
    if($i == 1)  $data['meals'][$i]['day'] = 'tomorrow';
    else  $data['meals'][$i]['day'] = 'tomorrow'.$i;
}
OutputJson($data);
?>