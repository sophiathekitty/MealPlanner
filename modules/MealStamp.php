<?php
/**
 * combines everything together for a meal (recipe and side and when to do stuff)
 */
class MealStamp {
    /**
     * makes meal stamp for today
     * @return array the meal stamp
     */
    public static function Today(){
        $today = SyncMealPlanners::Today();
        if(isset($today['recipe_id'])) $today['recipe'] = MealStamp::Recipe($today['recipe_id']); //Recipes::LoadRecipeId($today['recipe_id']);
        if(isset($today['recipe']))$today['chef'] = Chef::LoadUserId($today['recipe']['user_id']);
        if(isset($today['side_id'])) $today['side'] = MealStamp::Side($today['side_id']); //Sides::LoadSideId($today['side_id']);
        // calculate when to start cooking and prepping and thawing....
        $dinner_hour = Settings::LoadSettingsVar("dinner_hour",18);
        $today['dinner_at'] = date("Y-m-d $dinner_hour:00:00",time());
        $today['cook_at'] = date("Y-m-d H:i:s",strtotime($today['dinner_at']) - MinutesToSeconds($today['recipe']['cook_time']));
        $today['prep_at'] = date("Y-m-d H:i:s",strtotime($today['cook_at']) - MinutesToSeconds($today['recipe']['prep_time']));
        $today['thaw_at'] = date("Y-m-d H:i:s",strtotime($today['prep_at']) - HoursToSeconds($today['recipe']['thaw_time']));
        return $today;
    }
    /**
     * makes meal stamp for a future day
     * @param int $days how many days into the future
     * @return array the meal stamp
     */
    public static function Tomorrow($days){
        $tomorrow = SyncMealPlanners::Tomorrow($days);
        if(isset($tomorrow['recipe_id'])) $tomorrow['recipe'] = MealStamp::Recipe($tomorrow['recipe_id']); //Recipes::LoadRecipeId($tomorrow['recipe_id']);
        if(isset($tomorrow['recipe'])) $tomorrow['chef'] = Chef::LoadUserId($tomorrow['recipe']['user_id']);
        if(isset($tomorrow['side_id'])) $tomorrow['side'] = MealStamp::Side($tomorrow['side_id']); //Sides::LoadSideId($tomorrow['side_id']);
        // calculate when to start cooking and prepping and thawing....
        $dinner_hour = Settings::LoadSettingsVar("dinner_hour",8);
        $tomorrow['dinner_at'] = date("Y-m-d $dinner_hour:00:00",strtotime($tomorrow['date']));
        $tomorrow['cook_at'] = date("Y-m-d H:i:s",strtotime($tomorrow['dinner_at']) - MinutesToSeconds($tomorrow['recipe']['cook_time']));
        $tomorrow['prep_at'] = date("Y-m-d H:i:s",strtotime($tomorrow['cook_at']) - MinutesToSeconds($tomorrow['recipe']['prep_time']));
        $tomorrow['thaw_at'] = date("Y-m-d H:i:s",strtotime($tomorrow['prep_at']) - HoursToSeconds($tomorrow['recipe']['thaw_time']));
        return $tomorrow;
    }
    public static function Stamp($meal){
        if(isset($meal['recipe_id'])) $meal['recipe'] = MealStamp::Recipe($meal['recipe_id']); //Recipes::LoadRecipeId($meal['recipe_id']);
        if(isset($meal['recipe'])) $meal['chef'] = Chef::LoadUserId($meal['recipe']['user_id']);
        if(isset($meal['side_id'])) $meal['side'] = MealStamp::Side($meal['side_id']); //Sides::LoadSideId($meal['side_id']);
        // calculate when to start cooking and prepping and thawing....
        $dinner_hour = Settings::LoadSettingsVar("dinner_hour",8);
        $meal['dinner_at'] = date("Y-m-d $dinner_hour:00:00",strtotime($meal['date']));
        $meal['cook_at'] = date("Y-m-d H:i:s",strtotime($meal['dinner_at']) - MinutesToSeconds($meal['recipe']['cook_time']));
        $meal['prep_at'] = date("Y-m-d H:i:s",strtotime($meal['cook_at']) - MinutesToSeconds($meal['recipe']['prep_time']));
        $meal['thaw_at'] = date("Y-m-d H:i:s",strtotime($meal['prep_at']) - HoursToSeconds($meal['recipe']['thaw_time']));
        return $meal;
    }
    /**
     * loads a recipe and adds ingredients list
     * @param int $recipe_id the id of the recipe
     * @return array the recipe with ingredients
     */
    public static function Recipe($recipe_id){
        $recipe = Recipes::LoadRecipeId($recipe_id);
        $recipe['ingredients'] = MealRecipeIngredient::LoadRecipeIngredients($recipe_id);
        return $recipe;
    }
    /**
     * loads a side and adds ingredients list
     * @param int $side_id the id of the side
     * @return array the side with ingredients
     */
    public static function Side($side_id){
        $side = Sides::LoadSideId($side_id);
        $side['ingredients'] = MealSideIngredient::LoadSideIngredients($side_id);
        return $side;
    }
}
?>