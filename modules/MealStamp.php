<?php
class MealStamp {
    public static function Today(){
        $today = SyncMealPlanners::Today();
        $today['recipe'] = MealStamp::Recipe($today['recipe_id']); //Recipes::LoadRecipeId($today['recipe_id']);
        $today['side'] = MealStamp::Side($today['side_id']); //Sides::LoadSideId($today['side_id']);
        return $today;
    }
    public static function Tomorrow($days){
        $tomorrow = SyncMealPlanners::Tomorrow($days);
        if(isset($tomorrow['recipe_id'])) $tomorrow['recipe'] = MealStamp::Recipe($tomorrow['recipe_id']); //Recipes::LoadRecipeId($tomorrow['recipe_id']);
        if(isset($tomorrow['side_id'])) $tomorrow['side'] = MealStamp::Side($tomorrow['side_id']); //Sides::LoadSideId($tomorrow['side_id']);
        return $tomorrow;
    }
    public static function Recipe($recipe_id){
        $recipe = Recipes::LoadRecipeId($recipe_id);
        $recipe['ingredients'] = MealRecipeIngredient::LoadRecipeIngredients($recipe_id);
        return $recipe;
    }
    public static function Side($side_id){
        $side = Sides::LoadSideId($side_id);
        $side['ingredients'] = MealSideIngredient::LoadRecipeIngredients($side_id);
        return $side;
    }
}
?>