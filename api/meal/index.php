<?php
require_once("../../includes/main.php");
$data = [];
$data['today'] = MealStamp::Today();
OutputJson($data);
?>