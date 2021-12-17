<?php
require_once("../includes/main.php");
$data = [];
$data['MealPlanner'] = LocalAPIs();
OutputJson($data);
?>