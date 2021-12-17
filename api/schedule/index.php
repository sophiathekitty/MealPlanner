<?php
require_once("../../includes/main.php");
$data = [];
$data['schedule'] = MealScheduleStamp::Schedule();
OutputJson($data);
?>