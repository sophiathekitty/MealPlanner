/**
 * view for displaying the forecast
 */
class MealPlanView extends View {
    constructor(debug = true){
        if(debug) console.log("MealPlanView::Constructor");
        super(
            new MealPlanData(),
            new Template("meal_plan","/extensions/MealPlanner/templates/meal_plan.html"),
            new Template("meal_stamp","/extensions/MealPlanner/templates/widgets/meal_stamp.html"),
            60000,debug);
        this.pallet = ColorPallet.getPallet("calendar");
        this.days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
        this.date = new Date();
        this.cook_time = 0;
        
    }
    /**
     * handles the cooking time
     * @todo not working
     */
    cookTimer(){
        //if(this.debug) console.log("MealPlanView::CookTimer");
        this.DisplayClockTimer("today");
        this.DisplayClockTimerDish("today","recipe");
        this.DisplayClockTimerDish("today","side");
        this.DisplayClockTimer("tomorrow");
        this.DisplayClockTimerDish("tomorrow","recipe");
        this.DisplayClockTimerDish("tomorrow","side");
        this.DisplayClockTimer("tomorrow2");
        this.DisplayClockTimerDish("tomorrow2","recipe");
        this.DisplayClockTimerDish("tomorrow2","side");
        this.DisplayClockTimer("tomorrow3");
        this.DisplayClockTimerDish("tomorrow3","recipe");
        this.DisplayClockTimerDish("tomorrow3","side");
    }
    DisplayClockTimer(day){
        //if(this.debug) console.log("MealPlanView::DisplayCookTimer",day);
        var cook_timestamp = $("[day="+day+"] [var=cooked]").attr('val');
        var cook_time = Number($("[day="+day+"] [var=cook_time]").attr("val"));
        //if(this.debug) console.log("MealPlanView::DisplayCookTimer",day,{"cook_timestamp":cook_timestamp,"cook_time":cook_time});
        if(cook_timestamp != null && cook_timestamp != undefined && cook_timestamp != ""){
            var now_date = new Date();
            var date = new Date(cook_timestamp);
            var dif = now_date.getTime() - date.getTime();
            var time_left = (cook_time*60*1000) - dif;
            if(time_left < 0) time_left = 0;
            var time_txt = this.MillisecondsToTime(time_left);
            $("[day="+day+"]  [var=timer]").attr("val",time_txt);
            if(time_txt == "0:00"){
                time_txt = "Ready!";
            }
            $("[day="+day+"]  [var=timer]").html(time_txt);
            //if(this.debug) console.log("MealPlanView::DisplayCookTimer",{"cook_timestamp":cook_timestamp,"cook_time":cook_time,"now_date":now_date,"date":date,"dif":dif,"time_left":time_left,"time_txt":time_txt});
        }
    }
    DisplayClockTimerDish(day,dish){
        //if(this.debug) console.log("MealPlanView::DisplayCookTimerDish",day,dish);
        var cook_timestamp = $("[day="+day+"] [var="+dish+"] [var=cooked]").attr('val');
        var cook_time = Number($("[day="+day+"] [var="+dish+"] [var=cook_time]").attr("val"));
        //if(this.debug) console.log("MealPlanView::DisplayCookTimerDish",day,dish,{"cook_timestamp":cook_timestamp,"cook_time":cook_time});
        if(cook_timestamp != null && cook_timestamp != undefined && cook_timestamp != ""){
            var now_date = new Date();
            var date = new Date(cook_timestamp);
            var dif = now_date.getTime() - date.getTime();
            var time_left = (cook_time*60*1000) - dif;
            if(time_left < 0) time_left = 0;
            var time_txt = this.MillisecondsToTime(time_left);
            $("[day="+day+"] [var="+dish+"] [var=timer]").attr("val",time_txt);
            if(time_txt == "0:00"){
                time_txt = "Ready!";
            }
            $("[day="+day+"] [var="+dish+"] [var=timer]").html(time_txt);
            //if(this.debug) console.log("MealPlanView::DisplayCookTimerDish",{"cook_timestamp":cook_timestamp,"cook_time":cook_time,"now_date":now_date,"date":date,"dif":dif,"time_left":time_left,"time_txt":time_txt});
        }
    }
    /**
     * converts milliseconds to time string
     * @param {Number} mms milliseconds
     * @returns {string} time h:mm:ss or m:ss
     */
    MillisecondsToTime(mms){
        var seconds = Math.floor(mms / 1000);
        var min = 0;
        if(seconds >= 60){
            min = Math.floor(seconds/60);
            seconds -= (min*60);
        }
        var hour = 0;
        if(min >= 60){
            hour = Math.floor(min/60);
            min -= (hour*60);
        }
        if(min < 10 && hour > 0){
            min = "0"+min;
        }
        if(seconds < 10){
            seconds = "0"+seconds;
        }
        if(hour > 0)
            return hour+":"+min+":"+seconds;
        return min+":"+seconds;
        
    }
    /**
     * build the meal plan adds the main holder
     */
    build(){
        if(this.debug) console.log("MealPlanView::Build");
        if(this.template){
            this.template.getData(html=>{
                $(html).appendTo("main");
                $("<a href=\"#meal\" section=\"meal\">dinner</a>").appendTo("nav.sections");
                $("<a href=\"/extensions/MealPlanner/\" section=\"meal\">meals</a>").appendTo("nav.extensions");
                $("main").addClass("MealPlanner");
                this.display();
                this.controller.addButtonEvents();
            });
        }
        if(this.timerInterval) clearInterval(this.timerInterval);
        this.timerInterval = setInterval(this.cookTimer.bind(this),1000);
    }
    /**
     * display the meal plan data. rebuilding the list every time
     */
    display(){
        //if(this.debug) console.log("MealPlanView::Display");
        if(this.model){
            //if(this.debug) console.log("MealPlanView::Display-has model");
            this.model.getData(json=>{
                //if(this.debug) console.log("MealPlanView::Display-json",json);
                if(this.item_template){
                    this.item_template.getData(html=>{
                        //if(this.debug) console.log("MealPlanView::Display-item_template",html);
                        $("[collection=meal_plan]").html("");
                        json.meals.forEach((item,index)=>{
                            //if(this.debug) console.log("MealPlanView::Display",index,item,item.recipe.name);
                            $(html).appendTo("[collection=meal_plan]").attr("index",index);
                            //if(this.debug) console.log($("[collection=meal_plan] [index="+index+"]"),item.recipe.name);
                            $("[collection=meal_plan] [index="+index+"]").attr("day",item.day);
                            $("[collection=meal_plan] [index="+index+"]").attr("date",item.date);

                            $("[collection=meal_plan] [index="+index+"] [task=thaw]").attr("date",item.date);
                            $("[collection=meal_plan] [index="+index+"] [task=prep]").attr("date",item.date);
                            $("[collection=meal_plan] [index="+index+"] [task=cook]").attr("date",item.date);
                            
                            $("[collection=meal_plan] [index="+index+"] [task=change]").attr("date",item.date);
                            $("[collection=meal_plan] [index="+index+"] [var=recipe_name]").html(item.recipe.name);
                            $("[collection=meal_plan] [index="+index+"]").attr("side",item.side_id);
                            $("[collection=meal_plan] [index="+index+"] [var=side_name]").html(item.side.name);

                            $("[collection=meal_plan] [index="+index+"] [var=thaw_time]").html(item.recipe.thaw_time);
                            $("[collection=meal_plan] [index="+index+"] [var=prep_time]").html(item.recipe.prep_time);
                            $("[collection=meal_plan] [index="+index+"] [var=cook_time]").html(item.recipe.cook_time);

                            $("[collection=meal_plan] [index="+index+"] [var=thaw_time]").attr("val",item.recipe.thaw_time);
                            $("[collection=meal_plan] [index="+index+"] [var=prep_time]").attr("val",item.recipe.prep_time);
                            $("[collection=meal_plan] [index="+index+"] [var=cook_time]").attr("val",item.recipe.cook_time);
                            // no times
                            if(item.recipe.thaw_time == 0 && item.recipe.prep_time == 0 && item.recipe.cook_time == 0){
                                $("[collection=meal_plan] [index="+index+"]").attr("step","none");
                            } else {
                                if(item.thawed == null){
                                    $("[collection=meal_plan] [index="+index+"]").attr("step","thaw");
                                } else if(item.prepped == null){
                                    $("[collection=meal_plan] [index="+index+"]").attr("step","prep");
                                } else if(item.cooked == null){
                                    $("[collection=meal_plan] [index="+index+"]").attr("step","cook");
                                } else if(item.dinner_done == null){
                                    $("[collection=meal_plan] [index="+index+"]").attr("step","cooking");
                                }    
                            }
                            
                            if(item.thawed){
                                $("[collection=meal_plan] [index="+index+"] [var=thawed]").html(this.dateStringToHourMin(item.thawed));
                                $("[collection=meal_plan] [index="+index+"] [var=thawed]").attr("val",item.thawed);
                                $("[collection=meal_plan] [index="+index+"] [var=thawed]").attr("unit",this.dateStringToAM(item.thawed));
                            }
                            if(item.prepped){
                                $("[collection=meal_plan] [index="+index+"] [var=prepped]").html(this.dateStringToHourMin(item.prepped));
                                $("[collection=meal_plan] [index="+index+"] [var=prepped]").attr("val",item.prepped);
                                $("[collection=meal_plan] [index="+index+"] [var=prepped]").attr("unit",this.dateStringToAM(item.prepped));
                            }
                            if(item.cooked){
                                this.date = new Date(item.cooked);
                                this.cook_time = Number(item.recipe.cook_time);
                                $("[collection=meal_plan] [index="+index+"] [var=cooked]").html(this.dateStringToHourMin(item.cooked));
                                $("[collection=meal_plan] [index="+index+"] [var=cooked]").attr("val",item.cooked);
                                $("[collection=meal_plan] [index="+index+"] [var=cooked]").attr("unit",this.dateStringToAM(item.cooked));
                            }
                            $("[collection=meal_plan] [index="+index+"] [var=cook_temp]").html(item.recipe.cook_level);
                            $("[collection=meal_plan] [index="+index+"] [var=cook_temp]").attr("val",item.recipe.cook_level);
                            $("[collection=meal_plan] [index="+index+"] [var=cook_temp]").attr("unit",item.recipe.cook_unit);
                            //if(this.debug) console.log("MealPlanView::Display--chef:",item.chef);
                            if(item.chef){
                                $("[collection=meal_plan] [index="+index+"] [var=chef_name]").html(item.chef.name);
                                $("[collection=meal_plan] [index="+index+"] [var=chef_name]").attr("val",item.chef.name);
                                $("[collection=meal_plan] [index="+index+"] [var=chef_name]").attr("user_id",item.chef.user_id);
                                $("[collection=meal_plan] [index="+index+"] [var=chef_name]").attr("hat",item.chef.hat);
                                $("[collection=meal_plan] [index="+index+"] [var=chef_name]").attr("face",item.chef.face);
                            } else {
                                $("[collection=meal_plan] [index="+index+"] [var=chef_name]").attr("user_id",0);
                            }
                            this.cookTimer();
                        });
                    });
                }
            });
        }
    }
    refresh(){
        //this.display();
    }
    /**
     * converts a date string into the nice time display
     * @param {string} date_string YYYY-MM-DD HH:MM:SS
     * @returns {string} h:mm
     */
    dateStringToHourMin(date_string){
        var date = new Date(date_string);
        return this.dateToHourMin(date);
    }
    /**
     * date object to time string
     * @param {Date} date 
     * @returns {string} h:mm
     */
    dateToHourMin(date){
        var h = date.getHours();
        var m = date.getMinutes();
        var am = "am";
        if(h == 12) am = "pm";
        if(h > 12){
            am = "pm";
            h -= 12;
        }
        if(h == 0) h = 12;
        if(m < 10) m = "0"+m;
        return h+":"+m;
    }
    /**
     * figures out if the date string is am or pm
     * @param {string} date_string YYYY-MM-DD HH:MM:SS
     * @returns {string} am or pm
     */
    dateStringToAM(date_string){
        var date = new Date(date_string);
        var h = date.getHours();
        var am = "am";
        if(h >= 12) am = "pm";
        return am;
    }
}
