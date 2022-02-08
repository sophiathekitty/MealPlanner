class MealSelectView extends View {
    constructor(debug = false){
        if(debug) console.log("MealSelectView::Constructor");
        super(new RecipesCollection(),new Template("meal_select","/extensions/MealPlanner/templates/widgets/meal_select.html"),null,60000,debug);
        this.sides = new SidesCollection();
    }
    /**
     * build the meal select popup
     */
    build(){
        if(this.debug) console.log("MealSelect::Build",$("#meal_select").length);
        if(this.template && this.model && this. sides && !$("#meal_select").length){
            this.template.getData(html=>{
                this.model.getData(recipes=>{
                    this.sides.getData(sides=>{
                        $(html).appendTo("#popup_holder");
                        if(this.debug) console.log("MealSelect::Build",$("#meal_select").length);
                        // populate selects
                        recipes.recipes.forEach(recipe=>{
                            $("<option value=\""+recipe.id+"\" side=\""+recipe.side_id+"\">"+recipe.name+"</option>").appendTo("[var=recipe_id]");
                        });
                        sides.sides.forEach(side=>{
                            $("<option value=\""+side.id+"\">"+side.name+"</option>").appendTo("[var=side_id]");
                        });
                        this.controller.clickPopupButtons();
                    });
                });
            });
        }
    }
    /**
     * do nothing
     */
    display(){
        if(this.debug) console.warn("MealSelectView::Display","No Meal To Display");
    }
    /**
     * display a meal 
     * @param {json} meal meal to display
     */
    display(meal){
        $("#meal_select [var=recipe_id]").val(meal.recipe_id);
        $("#meal_select [var=side_id]").val(meal.side_id);
        switch(meal.day){
            case "today":
                $("#meal_select h1").html("Today");
                break;
            case "tomorrow":
                $("#meal_select h1").html("Tomorrow");
                break;
            case "tomorrow2":
                $("#meal_select h1").html("Day After Tomorrow");
                break;
            case "tomorrow3":
                $("#meal_select h1").html("Three Days From Now");
                break;
            default:
                $("#meal_select h1").html(meal.date);
        }
        $("#meal_select [action=save]").attr("date",meal.date);
        $(".popups").show();
    }
}