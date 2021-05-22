<?php
class SyncMealPlanners {
    private static $inst = null;
    private static function GetInstance(){
        if(is_null(SyncMealPlanners::$inst)) SyncMealPlanners::$inst = new SyncMealPlanners();
        return SyncMealPlanners::$inst;
    }
    public static function Sync(){
        $inst = SyncMealPlanners::GetInstance();
        $inst->PullRecipesFromHub();
        $inst->PullSidesFromHub();
        $inst->PullMealPlanFromHub();
    }

    private $hub = null;
    private function GetHubUrl(){
        if(is_null($this->hub)) $this->hub = GetHubUrl();
        return "http://".$this->hub."/api/meal/";
    }
    public function PullRecipesFromHub(){
        $url = $this->GetHubUrl()."recipe";
        echo "$url\n";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        print_r($data);
        foreach($data['recipes'] as $recipe){
            Recipes::SaveRecipe($recipe);
            echo clsDB::$db_g->get_err();
        }
    }
    public function PullSidesFromHub(){
        $url = $this->GetHubUrl()."recipe?sides=true";
        echo "$url\n";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        print_r($data);
        foreach($data['sides'] as $side){
            Sides::SaveSide($side);
            echo clsDB::$db_g->get_err();
        }
    }
    public function PullMealPlanFromHub(){
        $url = $this->GetHubUrl();

    }
}
?>