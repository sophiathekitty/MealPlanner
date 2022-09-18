<?php
require_once("../includes/main.php");
SyncMealPlanners::Sync();
OutputJson([]);
?>