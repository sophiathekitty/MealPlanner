<?php
/**
 * makes meal schedule stamp
 */
class MealScheduleStamp {
    /**
     * makes a meal schedule with recipe stamps
     * @return array meal schedule
     */
    public static function Schedule(){
        $schedule = MealSchedule::LoadRecipes();
        for($i = 0; $i < count($schedule); $i++){
            $schedule[$i]['recipe'] = MealStamp::Recipe($schedule[$i]['recipe_id']);
//            $schedule[$i]['name'] = $schedule[$i]['recipe']['name'];
            if($schedule[$i]['recipe']['side_id']){
                $schedule[$i]['side'] = MealStamp::Side($schedule[$i]['recipe']['side_id']);
//                $schedule[$i]['name'] = $schedule[$i]['recipe']['name']." with ".$schedule[$i]['side']['name'];
            }
            $schedule[$i]['user'] = Chef::LoadUserId($schedule[$i]['recipe']['user_id']);
        }
        return $schedule;
    }
}
?>