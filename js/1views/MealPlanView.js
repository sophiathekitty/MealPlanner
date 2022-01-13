/**
 * view for displaying the forecast
 */
class MealPlanView extends View {
    constructor(debug = false){
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
    cookTimer(){
        if(this.cook_time > 0){
            var now = new Date();
            var dif = now.getTime() - this.date.getTime();
            var time_left = (this.cook_time*1000) - dif;
            if(time_left < 0) time_left = 0;
            var time_txt = this.MillisecondsToTime(time_left);
            if(time_txt == "0:00"){
                time_txt = "Ready";
            }
            $("[model=meal_plan] [var=timer]").html(time_txt);
        }
    }
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
    build(){
        if(this.debug) console.log("MealPlanView::Build");
        if(this.template){
            this.template.getData(html=>{
                $(html).appendTo("main");
                $("<a href=\"/extensions/MealPlanner/\">meals</a>").appendTo("nav.extensions");
                $("main").addClass("MealPlanner");
                this.display();
                this.controller.addButtonEvents();
            });
        }
        if(this.timerInterval) clearInterval(this.timerInterval);
        this.timerInterval = setInterval(this.cookTimer.bind(this),1000);

    }
    display(){
        if(this.debug) console.log("MealPlanView::Display");
        if(this.model){
            if(this.debug) console.log("MealPlanView::Display-has model");
            this.model.getData(json=>{
                if(this.debug) console.log("MealPlanView::Display-json",json);
                if(this.item_template){
                    this.item_template.getData(html=>{
                        if(this.debug) console.log("MealPlanView::Display-item_template",html);
                        $("[collection=meal_plan]").html("");
                        json.meals.forEach((item,index)=>{
                            if(this.debug) console.log("MealPlanView::Display",index,item,item.recipe.name);
                            $(html).appendTo("[collection=meal_plan]").attr("index",index);
                            if(this.debug) console.log($("[collection=meal_plan] [index="+index+"]"),item.recipe.name);
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
                            if(this.debug) console.log("MealPlanView::Display--chef:",item.chef);
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
     * 
     * @param {string} date_string 
     * @returns 
     */
    dateStringToHourMin(date_string){
        var date = new Date(date_string);
        return this.dateToHourMin(date);
    }
    /**
     * 
     * @param {Date} date 
     * @returns 
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
        return h+":"+m;
    }
    dateStringToAM(date_string){
        var date = new Date(date_string);
        var h = date.getHours();
        var am = "am";
        if(h >= 12) am = "pm";
        return am;
    }
}
