/**
 * meal plan model
 */
class MealPlanData extends Collection {
    constructor(){
        super("meal_plan","meal_plan","/extensions/MealPlanner/api/meal/list/?count=3","/extensions/MealPlanner/api/meal/");
    }
}