<?php
require_once("../../includes/main.php");
$data = [];
if(isset($_GET['date'],$_GET['task'])){
    $data['task'] = MealTasks::CompleteTask($_GET['date'],$_GET['task']);
} else {
    $data['tasks'] = MealTasks::UpcomingTasks();
}
OutputJson($data);
?>