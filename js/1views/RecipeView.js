class MealRecipeView extends View {
    constructor(debug = true){
        if(debug) console.log("RecipeView::Constructor");
        super(null,new Template("meal_select","/extensions/MealPlanner/templates/widgets/meal.html"),null,60000,debug);
    }
    build(){

    }
    display(){

    }
    refresh(){
        this.display();
    }
}