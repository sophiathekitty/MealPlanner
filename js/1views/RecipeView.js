class MealRecipeView extends View {
    constructor(debug = false){
        if(debug) console.log("MealRecipeView::Constructor");
        super(
            new MealPlanData(),
            new Template("meal_recipe","/extensions/MealPlanner/templates/widgets/meal.html"),
            new Template("recipe_ingredient","/extensions/MealPlanner/templates/items/ingredient.html"),
            60000,
            debug);
    }
    build(){
        if(this.template){
            this.template.getData(html=>{
                $(html).appendTo("main");
                if(this.model){
                    this.model.getData(json=>{
                        this.index = 0;
                        this.meal = json.meals[this.index];
                        this.display();
                        this.controller.clickRecipeTask();
                    });
                }
            });
        }
    }
    displaySelectedMeal(meal,index){
        if(this.debug) console.log("MealRecipeView::Display",meal,index);
        this.meal = meal;
        this.index = index;
        this.display();
    }
    display(){
        if(this.meal && this.item_template){
            this.item_template.getData(html=>{
                if(this.debug) console.log("MealRecipeView::Display",this.meal);
                this.displayRecipe(html,this.meal.recipe,"recipe");
                this.displayRecipe(html,this.meal.side,"side");
                /*
                $("[var=recipe] [var=recipe_name]").html(this.meal.recipe.name);
                $("[var=recipe] [var=instructions]").html(this.meal.recipe.instructions);
                if(this.debug) console.log("RecipeView::Display",this.meal.recipe.ingredients);
                $("[var=recipe] [collection=ingredients]").html("");
                this.meal.recipe.ingredients.forEach((ingredient,index)=>{
                    $(html).appendTo("[var=recipe] [collection=ingredients]").attr("index",index);
                    $("[var=recipe] [collection=ingredients] [index="+index+"] [var=ingredient_name]").html(ingredient.name);
                    $("[var=recipe] [collection=ingredients] [index="+index+"] [var=quantity]").html(ingredient.amount);
                    $("[var=recipe] [collection=ingredients] [index="+index+"] [var=quantity]").attr("unit",ingredient.unit);
                });
                $("[var=side] [var=side_name]").html(this.meal.side.name);
                $("[var=side] [var=instructions]").html(this.meal.side.instructions);
                $("[var=side] [collection=ingredients]").html("");
                this.meal.side.ingredients.forEach((ingredient,index)=>{
                    $(html).appendTo("[var=side] [collection=ingredients]").attr("index",index);
                    $("[var=side] [collection=ingredients] [index="+index+"] [var=ingredient_name]").html(ingredient.name);
                    $("[var=side] [collection=ingredients] [index="+index+"] [var=quantity]").html(ingredient.amount);
                    $("[var=side] [collection=ingredients] [index="+index+"] [var=quantity]").attr("unit",ingredient.unit);
                });
                */
                $("section.meal_details").attr("day",this.meal.day);
                $("section.meal_details").attr("date",this.meal.date);

                $("section.meal_details [task=thaw]").attr("date",this.meal.date);
                $("section.meal_details [task=prep]").attr("date",this.meal.date);
                $("section.meal_details [task=cook]").attr("date",this.meal.date);
                
                $("section.meal_details [task=change]").attr("date",this.meal.date);
                /*
                $("section.meal_details [var=recipe] [var=thaw_time]").html(this.meal.recipe.thaw_time);
                $("section.meal_details [var=recipe] [var=prep_time]").html(this.meal.recipe.prep_time);
                $("section.meal_details [var=recipe] [var=cook_time]").html(this.meal.recipe.cook_time);

                $("section.meal_details [var=recipe] [var=thaw_time]").attr("val",this.meal.recipe.thaw_time);
                $("section.meal_details [var=recipe] [var=prep_time]").attr("val",this.meal.recipe.prep_time);
                $("section.meal_details [var=recipe] [var=cook_time]").attr("val",this.meal.recipe.cook_time);
                */
                // no times
                if(this.meal.recipe.thaw_time == 0 && this.meal.recipe.prep_time == 0 && this.meal.recipe.cook_time == 0){
                    $("section.meal_details").attr("step","none");
                } else {
                    if(this.meal.thawed == null){
                        $("section.meal_details [var=recipe]").attr("step","thaw");
                    } else if(this.meal.prepped == null){
                        $("section.meal_details [var=recipe]").attr("step","prep");
                    } else if(this.meal.cooked == null){
                        $("section.meal_details [var=recipe]").attr("step","cook");
                    } else if(this.meal.dinner_done == null){
                        $("section.meal_details [var=recipe]").attr("step","cooking");
                    }    
                }
                
                if(this.meal.thawed){
                    $("section.meal_details [var=recipe] [var=thawed]").html(this.dateStringToHourMin(this.meal.thawed));
                    $("section.meal_details [var=recipe] [var=thawed]").attr("val",this.meal.thawed);
                    $("section.meal_details [var=recipe] [var=thawed]").attr("unit",this.dateStringToAM(this.meal.thawed));
                }
                if(this.meal.prepped){
                    $("section.meal_details [var=recipe] [var=prepped]").html(this.dateStringToHourMin(this.meal.prepped));
                    $("section.meal_details [var=recipe] [var=prepped]").attr("val",this.meal.prepped);
                    $("section.meal_details [var=recipe] [var=prepped]").attr("unit",this.dateStringToAM(this.meal.prepped));
                }
                if(this.meal.cooked){
                    this.date = new Date(this.meal.cooked);
                    this.cook_time = Number(this.meal.recipe.cook_time);
                    $("section.meal_details [var=recipe] [var=cooked]").html(this.dateStringToHourMin(this.meal.cooked));
                    $("section.meal_details [var=recipe] [var=cooked]").attr("val",this.meal.cooked);
                    $("section.meal_details [var=recipe] [var=cooked]").attr("unit",this.dateStringToAM(this.meal.cooked));
                }
                /*
                $("section.meal_details [var=recipe] [var=cook_temp]").html(this.meal.recipe.cook_level);
                $("section.meal_details [var=recipe] [var=cook_temp]").attr("val",this.meal.recipe.cook_level);
                $("section.meal_details [var=recipe] [var=cook_temp]").attr("unit",this.meal.recipe.cook_unit);


                $("section.meal_details [var=side] [var=thaw_time]").html(this.meal.side.thaw_time);
                $("section.meal_details [var=side] [var=prep_time]").html(this.meal.side.prep_time);
                $("section.meal_details [var=side] [var=cook_time]").html(this.meal.side.cook_time);

                $("section.meal_details [var=side] [var=thaw_time]").attr("val",this.meal.side.thaw_time);
                $("section.meal_details [var=side] [var=prep_time]").attr("val",this.meal.side.prep_time);
                $("section.meal_details [var=side] [var=cook_time]").attr("val",this.meal.side.cook_time);
                */
                // no times
                if(this.meal.side.thaw_time == 0 && this.meal.side.prep_time == 0 && this.meal.side.cook_time == 0){
                    $("section.meal_details").attr("step","none");
                } else {
                    if(this.meal.side_prepped == null){
                        $("section.meal_details [var=side]").attr("step","prep");
                    } else if(this.meal.side_cooked == null){
                        $("section.meal_details [var=side]").attr("step","cook");
                    } else if(this.meal.dinner_done == null){
                        $("section.meal_details [var=side]").attr("step","cooking");
                    }    
                }
                
                if(this.meal.side_prepped){
                    $("section.meal_details [var=side] [var=prepped]").html(this.dateStringToHourMin(this.meal.side_prepped));
                    $("section.meal_details [var=side] [var=prepped]").attr("val",this.meal.side_prepped);
                    $("section.meal_details [var=side] [var=prepped]").attr("unit",this.dateStringToAM(this.meal.side_prepped));
                }
                if(this.meal.side_cooked){
                    this.date = new Date(this.meal.side_cooked);
                    this.cook_time = Number(this.meal.side.cook_time);
                    $("section.meal_details [var=side] [var=cooked]").html(this.dateStringToHourMin(this.meal.side_cooked));
                    $("section.meal_details [var=side] [var=cooked]").attr("val",this.meal.side_cooked);
                    $("section.meal_details [var=side] [var=cooked]").attr("unit",this.dateStringToAM(this.meal.side_cooked));
                }
                $("section.meal_details [var=side] [var=cook_temp]").html(this.meal.side.cook_level);
                $("section.meal_details [var=side] [var=cook_temp]").attr("val",this.meal.side.cook_level);
                $("section.meal_details [var=side] [var=cook_temp]").attr("unit",this.meal.side.cook_unit);


                if(this.debug) console.log("MealRecipeView:Display--chef:",this.meal.chef);
                if(this.meal.chef){
                    $("section.meal_details [var=chef_name]").html(this.meal.chef.name);
                    $("section.meal_details [var=chef_name]").attr("val",this.meal.chef.name);
                    $("section.meal_details [var=chef_name]").attr("user_id",this.meal.chef.user_id);
                    $("section.meal_details [var=chef_name]").attr("hat",this.meal.chef.hat);
                    $("section.meal_details [var=chef_name]").attr("face",this.meal.chef.face);
                } else {
                    $("section.meal_details [var=chef_name]").attr("user_id",0);
                }

            });
        }
    }
    /**
     * display a recipe or side for the meal
     * @param {string} html the item template html
     * @param {json} recipe the json object
     * @param {string} type recipe or side
     */
    displayRecipe(html,recipe,type = "recipe"){
        if(this.debug) console.log("RecipeView::DisplayRecipe",recipe);
        if(type == "recipe" && recipe.recipe) recipe = recipe.recipe;
        if(type == "side" && recipe.side) recipe = recipe.side;
        $("[var="+type+"] [var="+type+"_name]").html(recipe.name);
        $("[var="+type+"] [var=instructions]").html(recipe.instructions);
        $("[var="+type+"] [collection=ingredients]").html("");
        if(recipe.ingredients){
            recipe.ingredients.forEach((ingredient,index)=>{
                $(html).appendTo("[var="+type+"] [collection=ingredients]").attr("index",index);
                $("[var="+type+"] [collection=ingredients] [index="+index+"] [var=ingredient_name]").html(ingredient.name);
                $("[var="+type+"] [collection=ingredients] [index="+index+"] [var=quantity]").html(ingredient.amount);
                $("[var="+type+"] [collection=ingredients] [index="+index+"] [var=quantity]").attr("unit",ingredient.unit);
            });    
        } else {
            if(this.debug) console.error("RecipeView::DisplayRecipe-missing ingredients array?",type,recipe.ingredients,recipe);
        }
        $("section.meal_details [var="+type+"] [var=thaw_time]").html(recipe.thaw_time);
        $("section.meal_details [var="+type+"] [var=prep_time]").html(recipe.prep_time);
        $("section.meal_details [var="+type+"] [var=cook_time]").html(recipe.cook_time);

        $("section.meal_details [var="+type+"] [var=thaw_time]").attr("val",recipe.thaw_time);
        $("section.meal_details [var="+type+"] [var=prep_time]").attr("val",recipe.prep_time);
        $("section.meal_details [var="+type+"] [var=cook_time]").attr("val",recipe.cook_time);
        
        $("section.meal_details [var="+type+"] [var=cook_temp]").html(recipe.cook_level);
        $("section.meal_details [var="+type+"] [var=cook_temp]").attr("val",recipe.cook_level);
        $("section.meal_details [var="+type+"] [var=cook_temp]").attr("unit",recipe.cook_unit);
    }
    refresh(){
        if(this.model){
            this.model.getData(json=>{
                this.meal = json.meals[this.index];
                this.display();
            });
        }
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