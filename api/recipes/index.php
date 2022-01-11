<?php
require_once("../../includes/main.php");
$data = [];
$data['recipes'] = Recipes::LoadRecipes();

OutputJson($data);
?>