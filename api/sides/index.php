<?php
require_once("../../includes/main.php");
$data = [];
$data['sides'] = Sides::LoadSides();

OutputJson($data);
?>