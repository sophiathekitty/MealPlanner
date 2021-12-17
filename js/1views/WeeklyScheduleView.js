/**
 * view for displaying the forecast
 */
class WeeklyScheduleView extends View {
    constructor(){
        console.log("WeeklyScheduleView::Constructor");
        super(new WeeklyScheduleData(),null,new Template("weekly_schedule","/extensions/MealPlanner/templates/items/weekly_day.html"));
        this.pallet = ColorPallet.getPallet("calendar");
        this.days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
    }
    build(){
        console.log("WeeklyScheduleView::Build");
        this.display();
    }
    display(){
        console.log("WeeklyScheduleView::Display");
        if(this.model){
            console.log("WeeklyScheduleView::Display-has model");
            this.model.getData(json=>{
                console.log("WeeklyScheduleView::Display-json",json);
                if(this.item_template){
                    this.item_template.getData(html=>{
                        console.log("WeeklyScheduleView::Display-template",html);
                        $("[collection=weekly_schedule]").html("");
                        json.schedule.forEach((item,index)=>{
                            console.log("WeeklyScheduleView::Display",index,item,item.recipe.name);
                            $(html).appendTo("ul[collection=weekly_schedule]").attr("index",index);
                            console.log($("ul[collection=weekly_schedule] [index="+index+"]"),item.recipe.name);
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
    refresh(){
        //this.display();
    }
}
