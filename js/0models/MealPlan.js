/**
 * meal plan model
 */
class MealPlanData extends Collection {
    constructor(debug = false){
        super("meals","meal","/extensions/MealPlanner/api/meal/list/","/extensions/MealPlanner/api/meal/save","date","collection_",debug);
    }
    /**
     * 
     * @param {string} date 
     * @param {string} task 
     * @param {Function} callback 
     */
    completeTask(date,task,callback){
        var myData = {"date":date,"task":task};
        // do an ajax request... probably should be moved to the model?
        Model.push_requests_started++;
        $.ajax({  
            data: myData,
            type: 'GET',
            url: '/extensions/MealPlanner/api/tasks/',
            success: data=>{
                if(this.debug) console.log("MealPlanData::completeTask: push success",task,date,data);
                Model.server_errors--;
                Model.push_requests_completed++;
                if(Model.server_errors < 0) Model.server_errors = 0;
                if(callback) callback(data);
            },
            error: e=>{
                Model.push_requests_completed++;
                Model.server_errors++;
                if(this.debug) console.error("MealPlanData::completeTask: push error",e);
                if(callback) callback(e);
            },
            fail: res=>{
                Model.push_requests_completed++;
                Model.server_errors++;
                if(this.debug) console.error("MealPlanData::completeTask: push fail",res);
                if(callback) callback(res);
            }
        });
    }
}
