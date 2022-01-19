/**
 * the controller that should handle the buttons for the weather section
 * it's also handling refreshing weather views controlled by this controller
 * - WeatherSection
 * - WeatherView
 * - WeatherSettings
 * - DaytimeInfoView
 */
class MealPlanController extends Controller {
    constructor(debug = true){
        if(debug) console.log("MealPlanController::Constructor");
        super(new MealPlanView(), debug);
        this.popup = new MealSelectView();
        this.popup.controller = this;
        this.details = new MealRecipeView();
        this.details.controller = this;
    }
    /**
     * build the views and start the refresh for them
     */
    ready(){
        if(this.debug) console.log("MealPlanController::Ready");
        this.view.build();
        this.popup.build();
        this.details.build();
        //this.interval = setTimeout(this.refresh.bind(this),this.view.refresh_rate*View.refresh_ratio);
        //this.addButtonEvents();
        this.refresh();
    }
    /**
     * add the task buttons and recipe change button
     */
    addButtonEvents(){
        if(this.debug) console.log("MealPlanController::AddButtonEvents");
        this.clickTask();
        this.recipeChanged();
        //this.clickPopupButtons();
    }
    /**
     * setup task click handler
     */
    clickTask(){
        if(this.debug) console.log("MealPlanController::ClickTask");
        this.click("[collection=meal_plan]","[model=meal_plan] a",e=>{
            // click filter options
            e.preventDefault();
            if(this.debug) console.log("MealPlanController::ClickTask::Clicked",$(e.currentTarget).attr("task"));
            //$("section#weather").attr("show",$(e.currentTarget).attr("filter"));
            var task = $(e.currentTarget).attr("task");
            var date = $(e.currentTarget).attr("date");
            this.completeRecipeTask(date,task);
        });
    }
    clickRecipeTask(){
        if(this.debug) console.log("MealPlanController::ClickTask");
        this.click(".meal_details [var=recipe] a",e=>{
            // click filter options
            e.preventDefault();
            if(this.debug) console.log("MealPlanController::ClickRecipeTask::RecipeClicked",$(e.currentTarget).attr("task"));
            //$("section#weather").attr("show",$(e.currentTarget).attr("filter"));
            var task = $(e.currentTarget).attr("task");
            var date = $(e.currentTarget).attr("date");
            this.completeRecipeTask(date,task);
        });
        this.click(".meal_details [var=side] a",e=>{
            // click filter options
            e.preventDefault();
            if(this.debug) console.log("MealPlanController::ClickRecipeTask::SideClicked",$(e.currentTarget).attr("task"));
            //$("section#weather").attr("show",$(e.currentTarget).attr("filter"));
            var task = $(e.currentTarget).attr("task");
            var date = $(e.currentTarget).attr("date");
            this.completeSideTask(date,task);
        });
    }
    /**
     * handle completing a recipe task
     * @param {string} date the date of the meal
     * @param {string} task the name of the task
     */
    completeRecipeTask(date,task){
        if(task == "change"){
            // show the meal select popup
            this.view.model.getItem(date,json=>{
                if(this.debug) console.log("MealPlanController::completeRecipeTask--change--",json);
                this.popup.display(json);
            });
        } else {
            // submit task complete
            if(task == "thaw") task = "thawed";
            if(task == "prep") task = "prepped";
            if(task == "cook") task = "cooked";
            this.view.model.completeTask(date,task,res=>{
                if(this.debug) console.log("MealPlanController::completeRecipeTask: save done",task,date,res);
                var now = this.view.dateToHourMin(new Date());
                if(task == "thawed"){
                    $("[model=meal_plan][date="+date+"]").attr("step","prep");
                    $("[model=meal_plan][date="+date+"] [var=thawed]").html(now);
                    $("[date="+date+"] [var=recipe]").attr("step","prep");
                    $("[date="+date+"] [var=recipe] [var=thawed]").html(now);
                } 
                if(task == "prepped"){
                    $("[model=meal_plan][date="+date+"]").attr("step","cook");
                    $("[model=meal_plan][date="+date+"] [var=prepped]").html(now);
                    $("[date="+date+"] [var=recipe]").attr("step","cook");
                    $("[date="+date+"] [var=recipe] [var=prepped]").html(now);
                } 
                if(task == "cooked"){
                    $("[model=meal_plan][date="+date+"]").attr("step","cooking");
                    $("[model=meal_plan][date="+date+"] [var=cooked]").html(now);
                    $("[date="+date+"] [var=recipe]").attr("step","cooking");
                    $("[date="+date+"] [var=recipe] [var=cooked]").html(now);
                } 
            });
        }

    }
    completeSideTask(date,task){
        // submit task complete
        if(task == "prep") task = "side_prepped";
        if(task == "cook") task = "side_cooked";
        this.view.model.completeTask(date,task,res=>{
            if(this.debug) console.log("MealPlanController::completeRecipeTask: save done",task,date,res);
            var now = this.view.dateToHourMin(new Date());
            if(task == "side_prepped"){
                $(".meal_details [var=side]").attr("step","cook");
                $(".meal_details [var=side] [var=prepped]").html(now);
            } 
            if(task == "side_cooked"){
                $(".meal_details [var=side]").attr("step","cooking");
                $(".meal_details [var=side] [var=cooked]").html(now);
            } 
        });
    }
    /**
     * setup change recipe click event
     */
    recipeChanged(){
        if(this.debug) console.log("MealPlanController::recipeChanged");
        this.change(".popups","#meal_select [var=recipe_id]",e=>{
            if(this.debug) console.log("MealPlanController::recipeChanged::Changed",$(e.currentTarget).val(),$("#meal_select [var=recipe_id] [value="+$(e.currentTarget).val()+"]").attr("side"));
            $("#meal_select [var=side_id]").val($("#meal_select [var=recipe_id] [value="+$(e.currentTarget).val()+"]").attr("side"));
        });
    }
    /**
     * setup popup button handlers
     */
    clickPopupButtons(){
        if(this.debug) console.log("MealPlanController::clickPopupButtons");
        this.click(".popups","#meal_select [action=save]",e=>{
            if(this.debug) console.log("MealPlanController::clickPopupButtons::Save--click",$(e.currentTarget).attr("date"));
            e.preventDefault();
            // apply changes
            this.view.model.getItem($(e.currentTarget).attr("date"),meal=>{
                if(this.debug) console.log("MealPlanController::clickPopupButtons::Save--meal",meal);
                this.popup.model.getItem($("#meal_select [var=recipe_id]").val(),recipe=>{
                    if(this.debug) console.log("MealPlanController::clickPopupButtons::Save--recipe",recipe);
                    this.popup.sides.getItem($("#meal_select [var=side_id]").val(),side=>{
                        if(this.debug) console.log("MealPlanController::clickPopupButtons::Save--side",side);
                        meal.recipe_id = $("#meal_select [var=recipe_id]").val();
                        meal.side_id = $("#meal_select [var=side_id]").val();
                        meal.recipe = recipe;
                        meal.meal = recipe.name;
                        meal.side = side;
                        if(this.debug) console.log("MealPlanController::clickPopupButtons::Save--update meal",meal);
                        this.view.model.setItem(meal);
                        this.view.model.pushData(json=>{
                            if(this.debug) console.log("MealPlanController::clickPopupButtons::Save--push complete",json);
                            //this.view.model.pullData(json=>{
                                this.view.display();
                            //});
                        },err=>{
                            if(this.debug) console.error("MealPlanController::clickPopupButtons::Save--push error",err);
                        },err=>{
                            if(this.debug) console.error("MealPlanController::clickPopupButtons::Save--push fail",err);
                        },json=>{
                            if(this.debug) console.log("MealPlanController::clickPopupButtons::Save--push done",json);
                        });
                    });
                });
            });
            $(".popups").hide();
        });
        this.click(".popups","#meal_select [action=cancel]",e=>{
            if(this.debug) console.log("MealPlanController::clickPopupButtons::Cancel--click");
            e.preventDefault();
            $(".popups").hide();
        });
    }
    /**
     * do refresh based on view refresh ratio
     */
    refresh(){
        clearTimeout(this.interval);
        this.view.display();
        this.details.refresh();
        this.interval = setTimeout(this.refresh.bind(this),this.view.refresh_rate*View.refresh_ratio);
        View.refresh_ratio += 0.01;
    }
}