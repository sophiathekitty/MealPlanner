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
    }
    ready(){
        if(this.debug) console.log("MealPlanController::Ready");
        this.view.build();
        this.popup.build();
        //this.interval = setTimeout(this.refresh.bind(this),this.view.refresh_rate*View.refresh_ratio);
        //this.addButtonEvents();
        this.refresh();
    }
    addButtonEvents(){
        if(this.debug) console.log("MealPlanController::AddButtonEvents");
        this.clickTask();
        this.recipeChanged();
        //this.clickPopupButtons();
    }
    clickTask(){
        if(this.debug) console.log("MealPlanController::ClickTask");
        this.click("[collection=meal_plan]","[model=meal_plan] a",e=>{
            // click filter options
            e.preventDefault();
            if(this.debug) console.log("MealPlanController::ClickTask::Clicked",$(e.currentTarget).attr("task"));
            //$("section#weather").attr("show",$(e.currentTarget).attr("filter"));
            var task = $(e.currentTarget).attr("task");
            var date = $(e.currentTarget).attr("date");
            if(task == "change"){
                // show the meal select popup
                this.view.model.getItem(date,json=>{
                    if(this.debug) console.log("MealPlanController::ClickTask::Clicked--change--",json);
                    this.popup.display(json);
                });
            } else {
                // submit task complete
                if(task == "thaw") task = "thawed";
                if(task == "prep") task = "prepped";
                if(task == "cook") task = "cooked";
                var myData = {"date":date,"task":task};
                // do an ajax request
                Model.push_requests_started++;
                $.ajax({  
                    data: myData,
                    type: 'GET',
                    url: '/extensions/MealPlanner/api/tasks/',
                    success: data=>{
                        if(this.debug) console.log("MealPlanController::ClickTask::Clicked: push success",task,date,data);
                        var now = this.view.dateToHourMin(new Date());
                        if(task == "thawed"){
                            $("[model=meal_plan][date="+date+"]").attr("step","prep");
                            $("[model=meal_plan][date="+date+"] [var=thawed]").html(now);
                        } 
                        if(task == "prepped"){
                            $("[model=meal_plan][date="+date+"]").attr("step","cook");
                            $("[model=meal_plan][date="+date+"] [var=prepped]").html(now);
                        } 
                        if(task == "cooked"){
                            $("[model=meal_plan][date="+date+"]").attr("step","cooking");
                            $("[model=meal_plan][date="+date+"] [var=cooked]").html(now);
                        } 
                        Model.server_errors--;
                        Model.push_requests_completed++;
                        if(Model.server_errors < 0) Model.server_errors = 0;
                    },
                    error: e=>{
                        Model.push_requests_completed++;
                        Model.server_errors++;
                        if(this.debug) console.error(e);    
                    },
                    fail: res=>{
                        Model.push_requests_completed++;
                        Model.server_errors++;
                        if(this.debug) console.error(res);
                    }
                });    
            }
        });
    }
    recipeChanged(){
        if(this.debug) console.log("MealPlanController::recipeChanged");
        this.change(".popups","#meal_select [var=recipe_id]",e=>{
            if(this.debug) console.log("MealPlanController::recipeChanged::Changed",$(e.currentTarget).val(),$("#meal_select [var=recipe_id] [value="+$(e.currentTarget).val()+"]").attr("side"));
            $("#meal_select [var=side_id]").val($("#meal_select [var=recipe_id] [value="+$(e.currentTarget).val()+"]").attr("side"));
        });
    }
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
    refresh(){
        clearTimeout(this.interval);
        this.view.display();
        this.interval = setTimeout(this.refresh.bind(this),this.view.refresh_rate*View.refresh_ratio);
        View.refresh_ratio += 0.01;
    }
}