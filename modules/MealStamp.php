<?php
class MealStamp {
    public static function Today(){
        $today = SyncMealPlanners::Today();
        $today['recipe'] = Recipes::LoadRecipeId($today['recipe_id']);
        $today['side'] = Sides::LoadSideId($today['side_id']);
        return $today;
    }
}
?>