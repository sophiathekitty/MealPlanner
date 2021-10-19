<?php
require_once("../../includes/main.php");
$data = [];
$data['today'] = MealStamp::Today();
$data['tomorrow'] = MealStamp::Tomorrow(1);
$data['tomorrow2'] = MealStamp::Tomorrow(2);
$data['tomorrow3'] = MealStamp::Tomorrow(3);
OutputJson($data);
?>