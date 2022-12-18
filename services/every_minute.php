<?php
require_once("../includes/main.php");
Services::Start("EveryMinute");
Services::Log("EveryMinute","SyncMealPlanners::Sync()");
SyncMealPlanners::Sync();
Services::Complete("EveryMinute");
OutputJson([]);
?>