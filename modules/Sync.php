<?php
/**
 * sync meal planner data from the hub
 */
class SyncMealPlanners {
    private static $inst = null;
    /**
     * @return SyncMealPlanners
     */
    private static function GetInstance(){
        if(is_null(SyncMealPlanners::$inst)) SyncMealPlanners::$inst = new SyncMealPlanners();
        return SyncMealPlanners::$inst;
    }
    /**
     * sync from the hub
     */
    public static function Sync(){
        Debug::Trace("SyncMealPlanner::Sync");
        $inst = SyncMealPlanners::GetInstance();
        $inst->PullIngredientsFromHub();
        $inst->PullRecipesFromHub();
        $inst->PullSidesFromHub();
        $inst->PullMealPlanFromHub();
    }
    /**
     * sync today's meal from the hub if it doesn't exist locally
     * @return array today's meal
     */
    public static function Today(){
        Debug::Trace("SyncMealPlanner::Today");
        $inst = SyncMealPlanners::GetInstance();
        $meal = MealPlan::GetTodaysMeal();
        if(!is_null($meal)) return $meal;
        SyncMealPlanners::Sync();
        return MealPlan::GetTodaysMeal();
    }
    /**
     * sync upcoming meal from hub if it doesn't exist locally
     * @param int $days how many days into the future (tomorrow = 1)
     * @return array tomorrow's meal
     */
    public static function Tomorrow($days = 1){
        Debug::Trace("SyncMealPlanner::Tomorrow");
        $inst = SyncMealPlanners::GetInstance();
        $meal = MealPlan::GetTomorrowsMeal($days);
        if(!is_null($meal)) return $meal;
        SyncMealPlanners::Sync();
        return MealPlan::GetTomorrowsMeal($days);
    }

    private $hub = null;
    /**
     * because i can't use server request passthrough for things with params
     */
    private function GetHubUrl(){
        Debug::Trace("SyncMealPlanner::GetHubUrl");
        if(is_null($this->hub)) $this->hub = GetHubUrl();
        return "http://".$this->hub."/api/meal/";
    }
    /**
     * pull recipes from hub
     */
    public function PullRecipesFromHub(){
        Debug::Trace("SyncMealPlanner::PullRecipesFromHub");
        $api = "/extensions/MealPlanner/api/recipes";
        //$url = "http://localhost/api/requests/hub/?api=/extensions/MealPlanner/api/recipes";
        if(HubType() == "old_hub"){
            $api = "/api/meal/recipe?verbose=1";
            //$url = "http://localhost/api/requests/hub/?api=/api/meal/recipe?verbose=1";
            //$url = $this->GetHubUrl()."recipe?verbose=1";
        }
        //echo "$url\n";
        //$info = file_get_contents($url);
        //$data = json_decode($info,true);
        $data = ServerRequests::LoadHubJSON($api);
        //print_r($data);
        foreach($data['recipes'] as $recipe){
            Recipes::SaveRecipe($recipe);
            //echo clsDB::$db_g->get_err();
            foreach($recipe['ingredients'] as $ingredient){
                $ingredient['recipe_id'] = $recipe['id'];
                MealRecipeIngredient::SaveItem($ingredient);
                //echo clsDB::$db_g->get_err();
            }
        }
    }
    /**
     * pull sides from hub
     */
    public function PullSidesFromHub(){
        Debug::Trace("SyncMealPlanner::PullSidesFromHub");
        //echo "$url\n";
        $api = "/extensions/MealPlanner/api/sides";
        //$url = "http://localhost/api/requests/hub/?api=/extensions/MealPlanner/api/sides";
        if(HubType() == "old_hub"){
            $api = "/api/meal/recipe?sides=true&verbose=1";
            //$url = "http://localhost/api/requests/hub/?api=/api/meal/recipe?sides=true&verbose=1";
            //$url = $this->GetHubUrl()."recipe?sides=true&verbose=1";
        }
        //Debug::Log("Pull Sides From Hub: $url");
        //$info = file_get_contents($url);
        //$data = json_decode($info,true);
        $data = ServerRequests::LoadHubJSON($api);
        Debug::LogGroup("SyncMealPlanner::PullSidesFromHub",$api,$data);
        foreach($data['sides'] as $side){
            $save = Sides::SaveSide($side);
            Debug::Log("SyncMealPlanner::PullSidesFromHub",$save);
            foreach($side['ingredients'] as $ingredient){
                $ingredient['side_id'] = $side['id'];
                $save = MealSideIngredient::SaveItem($ingredient);
                Debug::LogGroup("SyncMealPlanner::PullSidesFromHub",$ingredient,$save);
            }
        }
    }
    /**
     * pull ingredients from hub
     */
    public function PullIngredientsFromHub(){
        Debug::Trace("SyncMealPlanner::PullIngredientsFromHub");
        //$url = $this->GetHubUrl()."recipe/ingredients";
        $api = "/extensions/MealPlanner/api/ingredients";
        //$url = "http://localhost/api/requests/hub/?api=/extensions/MealPlanner/api/ingredients";
        if(HubType() == "old_hub"){
            $api = "/api/meal/recipe/ingredients";
            //$url = "http://localhost/api/requests/hub/?api=/api/meal/recipe/ingredients";
        }
        //echo "$url\n";
        //$info = file_get_contents($url);
        //$data = json_decode($info,true);
        $data = ServerRequests::LoadHubJSON($api);
        //print_r($data);
        foreach($data['ingredients'] as $ingredients){
            if(isset($ingredients['name'])){
                //print_r($ingredients);
                $rep = MealIngredient::SaveItem($ingredients);
                Debug::LogGroup("SyncMealPlanner::PullIngredientsFromHub",$rep);
                //echo clsDB::$db_g->get_err();    
            } else {
                foreach($ingredients as $ingredient){
                    //print_r($ingredient);
                    $rep = MealIngredient::SaveItem($ingredient);
                    Debug::LogGroup("SyncMealPlanner::PullIngredientsFromHub",$ingredient,$rep);
                    //echo clsDB::$db_g->get_err();    
                }    
            }
        }
    }
    /**
     * pull 4 day meal plan (today, tomorrow, tomorrow2, tomorrow3)
     */
    public function PullMealPlanFromHub(){
        Debug::Trace("SyncMealPlanner::PullMealPlanFromHub");
        //$url = "http://localhost/api/requests/hub/?api=/extensions/MealPlanner/api/meal/";
        $api = "/extensions/MealPlanner/api/meal/";
        if(HubType() == "old_hub"){
            //$url = "http://localhost/api/requests/hub/?api=/api/meal/";
            $api = "/api/meal/";
        }
        //$info = file_get_contents($url);
        //$data = json_decode($info,true);
        $data = ServerRequests::LoadHubJSON($api);
        //print_r($data['meal_plan']['today']);
        $today = $this->CleanMealPlan($data['meal_plan']['today']);
        $tomorrow = $this->CleanMealPlan($data['meal_plan']['tomorrow']);
        $tomorrow2 = $this->CleanMealPlan($data['meal_plan']['tomorrow2']);
        $tomorrow3 = $this->CleanMealPlan($data['meal_plan']['tomorrow3']);
        $rep1 = MealPlan::SaveMeal($today,true);
        $rep2 = MealPlan::SaveMeal($tomorrow,true);
        $rep3 = MealPlan::SaveMeal($tomorrow2,true);
        $rep4 = MealPlan::SaveMeal($tomorrow3,true);
        Debug::LogGroup("SyncMealPlanner::PullMealPlanFromHub",$rep1,$rep2,$rep3,$rep4);
        //echo clsDB::$db_g->get_err()."\n";
        foreach($data['meal_plan']['schedule'] as $day){
            MealSchedule::SaveDay($day);
        }
    }
    /**
     * cleans the meal of extra fields
     * @param array $meal the raw meal array (probably a meal stamp)
     * @return array the clean meal array
     */
    private function CleanMealPlan($meal){
        Debug::Trace("SyncMealPlanner::CleanMealPlan");
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