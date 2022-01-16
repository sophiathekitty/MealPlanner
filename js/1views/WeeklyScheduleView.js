/**
 * view for displaying the forecast
 */
class WeeklyScheduleView extends View {
    constructor(debug = false){
        if(debug) console.log("WeeklyScheduleView::Constructor");
        super(new WeeklyScheduleData(),null,new Template("weekly_schedule","/extensions/MealPlanner/templates/items/weekly_day.html"),60000,debug);
        this.pallet = ColorPallet.getPallet("calendar");
        this.days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
    }
    /**
     * acting as an alias for display
     */
    build(){
        if(this.debug) console.log("WeeklyScheduleView::Build");
        this.display();
    }
    /**
     * rebuild schedule list and display info
     */
    display(){
        if(this.debug) console.log("WeeklyScheduleView::Display");
        if(this.model){
            if(this.debug) console.log("WeeklyScheduleView::Display-has model");
            this.model.getData(json=>{
                if(this.debug) console.log("WeeklyScheduleView::Display-json",json);
                if(this.item_template){
                    this.item_template.getData(html=>{
                        if(this.debug) console.log("WeeklyScheduleView::Display-template",html);
                        $("[collection=weekly_schedule]").html("");
                        json.schedule.forEach((item,index)=>{
                            if(this.debug) console.log("WeeklyScheduleView::Display",index,item,item.recipe.name);
                            $(html).appendTo("ul[collection=weekly_schedule]").attr("index",index);
                            if(this.debug) console.log($("ul[collection=weekly_schedule] [index="+index+"]"),item.recipe.name);
                            $("ul[collection=weekly_schedule] [index="+index+"] .key").html(this.days[index]);
                            $("ul[collection=weekly_schedule] [index="+index+"] [var=name]").html(item.recipe.name);
                            $("ul[collection=weekly_schedule] [index="+index+"] [var=name]").attr("val",item.recipe.name);
                            $("ul[collection=weekly_schedule] [index="+index+"] [var=mandatory]").attr("val",item.mandatory);
                        });
                    });
                }
            });
        }
    }
    /**
     * does nothing
     */
    refresh(){
        //this.display();
    }
}
