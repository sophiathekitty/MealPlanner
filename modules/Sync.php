<?php
class SyncMealPlanners {
    private static $inst = null;
    private static function GetInstance(){
        if(is_null(SyncMealPlanners::$inst)) SyncMealPlanners::$inst = new SyncMealPlanners();
        return SyncMealPlanners::$inst;
    }
    public static function Sync(){
        $inst = SyncMealPlanners::GetInstance();
        $inst->PullIngredientsFromHub();
        $inst->PullRecipesFromHub();
        $inst->PullSidesFromHub();
        $inst->PullMealPlanFromHub();
    }
    public static function Today(){
        $inst = SyncMealPlanners::GetInstance();
        $meal = MealPlan::GetTodaysMeal();
        if(!is_null($meal)) return $meal;
        SyncMealPlanners::Sync();
        return MealPlan::GetTodaysMeal();
    }
    public static function Tomorrow($days = 1){
        $inst = SyncMealPlanners::GetInstance();
        $meal = MealPlan::GetTomorrowsMeal($days);
        if(!is_null($meal)) return $meal;
        SyncMealPlanners::Sync();
        return MealPlan::GetTomorrowsMeal($days);
    }

    private $hub = null;
    private function GetHubUrl(){
        if(is_null($this->hub)) $this->hub = GetHubUrl();
        return "http://".$this->hub."/api/meal/";
    }
    public function PullRecipesFromHub(){
        $url = $this->GetHubUrl()."recipe?verbose=1";
        //echo "$url\n";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        //print_r($data);
        foreach($data['recipes'] as $recipe){
            Recipes::SaveRecipe($recipe);
            echo clsDB::$db_g->get_err();
            foreach($recipe['ingredients'] as $ingredient){
                $ingredient['recipe_id'] = $recipe['id'];
                MealRecipeIngredient::SaveItem($ingredient);
                echo clsDB::$db_g->get_err();
            }
        }
    }
    public function PullSidesFromHub(){
        $url = $this->GetHubUrl()."recipe?sides=true&verbose=1";
        //echo "$url\n";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        //print_r($data);
        foreach($data['sides'] as $side){
            Sides::SaveSide($side);
            echo clsDB::$db_g->get_err();
            foreach($side['ingredients'] as $ingredient){
                $ingredient['recipe_id'] = $side['id'];
                MealSideIngredient::SaveItem($ingredient);
                echo clsDB::$db_g->get_err();
            }
        }
    }
    public function PullIngredientsFromHub(){
        $url = $this->GetHubUrl()."recipe/ingredients";
        //echo "$url\n";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        //print_r($data);
        foreach($data['ingredients'] as $ingredients){
            if(isset($ingredients['name'])){
                //print_r($ingredients);
                MealIngredient::SaveItem($ingredients);
                echo clsDB::$db_g->get_err();    
            } else {
                foreach($ingredients as $ingredient){
                    print_r($ingredient);
                    MealIngredient::SaveItem($ingredient);
                    echo clsDB::$db_g->get_err();    
                }    
            }
        }
    }

    public function PullMealPlanFromHub(){
        $url = $this->GetHubUrl();
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        //print_r($data['meal_plan']['today']);
        $today = $this->CleanMealPlan($data['meal_plan']['today']);
        $tomorrow = $this->CleanMealPlan($data['meal_plan']['tomorrow']);
        $tomorrow2 = $this->CleanMealPlan($data['meal_plan']['tomorrow2']);
        $tomorrow3 = $this->CleanMealPlan($data['meal_plan']['tomorrow3']);
        MealPlan::SaveMeal($today);
        MealPlan::SaveMeal($tomorrow);
        MealPlan::SaveMeal($tomorrow2);
        MealPlan::SaveMeal($tomorrow3);
        echo clsDB::$db_g->get_err()."\n";
        foreach($data['meal_plan']['schedule'] as $day){
            MealSchedule::SaveDay($day);
        }
    }
    private function CleanMealPlan($meal){
        $m = [
            "recipe_id" => $meal['id'],
            "side_id" => $meal['side_id'],
            "meal" => $meal['name'],
            "thawed" => $meal['thawed'],
            "prepped" => $meal['prepped'],
            "cooked" => $meal['cooked'],
            "side_prepped" => $meal['side_prepped'],
            "side_cooked" => $meal['side_cooked'],
            "date" => $meal['date']
        ];
        return $m;
    }
}
?>