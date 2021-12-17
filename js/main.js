var weekly_schedule = new WeeklyScheduleView();
var meal_plan = new MealPlanController();
/**
 * main meal injector
 */
$(document).ready(function(){
    weekly_schedule.build();
    meal_plan.ready();
    //setInterval(RefreshViews,120000)
});
function RefreshViews(){
    meal_plan.view.display();
}
