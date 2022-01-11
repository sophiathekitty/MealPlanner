/**
 * meal plan model
 */
class MealPlanData extends Collection {
    constructor(debug = false){
        super("meals","meal","/extensions/MealPlanner/api/meal/list/","/extensions/MealPlanner/api/meal/save","date","collection_",debug);
    }
}