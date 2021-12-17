<?php
require_once("../../../includes/main.php");
$data = [];
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