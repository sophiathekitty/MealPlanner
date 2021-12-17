<?php
require_once("../../includes/main.php");
$data = [];
$data['leftovers'] = MealPlan::GetLeftovers(7);
OutputJson($data);
?>