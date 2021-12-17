/**
 * the controller that should handle the buttons for the weather section
 * it's also handling refreshing weather views controlled by this controller
 * - WeatherSection
 * - WeatherView
 * - WeatherSettings
 * - DaytimeInfoView
 */
class MealPlanController extends Controller {
    constructor(){
        console.log("MealPlanController::Constructor");
        super(new MealPlanView());
    }
    ready(){
        console.log("MealPlanController::Ready");
        this.view.build();
        //this.interval = setTimeout(this.refresh.bind(this),this.view.refresh_rate*View.refresh_ratio);
        this.addButtonEvents();
        this.refresh();
    }
    addButtonEvents(){
        console.log("MealPlanController::AddButtonEvents");
        this.click("[collection=meal_plan]","[model=meal_plan] a",e=>{
            // click filter options
            e.preventDefault();
            console.log("MealPlanController::ButtonClicked",$(e.currentTarget).attr("task"));
            //$("section#weather").attr("show",$(e.currentTarget).attr("filter"));
            var task = $(e.currentTarget).attr("task");
            var date = $(e.currentTarget).attr("date");
            if(task == "thaw") task = "thawed";
            if(task == "prep") task = "prepped";
            if(task == "cook") task = "cooked";
            var myData = {"date":date,"task":task};

            Model.push_requests_started++;
            $.ajax({  
                data: myData,
                type: 'GET',
                url: '/extensions/MealPlanner/api/tasks/',
                success: data=>{
                    console.log("MealPlanController::Tasks: push success",task,date,data);
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
                    console.log(e);    
                },
                fail: res=>{
                    Model.push_requests_completed++;
                    Model.server_errors++;
                    console.log(res);
                }
            });
        });
    }

    refresh(){
        clearTimeout(this.interval);
        this.view.display();
        this.interval = setTimeout(this.refresh.bind(this),this.view.refresh_rate*View.refresh_ratio);
        View.refresh_ratio += 0.01;
    }
}