<?php 

$hour_options = array();
$hour_options["0"] = "12";
$hour_options["1"] = "1";
$hour_options["2"] = "2";
$hour_options["3"] = "3";
$hour_options["4"] = "4";
$hour_options["5"] = "5";
$hour_options["6"] = "6";
$hour_options["7"] = "7";
$hour_options["8"] = "8";
$hour_options["9"] = "9";
$hour_options["10"] = "10";
$hour_options["11"] = "11";

$am_pm_options = array();
$am_pm_options["0"] = "AM";
$am_pm_options["12"] = "PM";


$minute_options = array();
for ($i = 0; $i < 60; $i = $i + 5) 
{
    $minute_options[$i] = str_pad($i, 2,"0");
}

$duration_options = array();
$duration_options[0] = ":00";
$duration_options[5] = ":05";
$duration_options[10] = ":10";
$duration_options[15] = ":15";
$duration_options[20] = ":20";
$duration_options[30] = ":30";
$duration_options[45] = ":45";
$duration_options[60] = "1:00";
$duration_options[75] = "1:15";
$duration_options[90] = "1:30";
$duration_options[105] = "1:45";
$duration_options[120] = "2:00";
$duration_options[135] = "2:15";
$duration_options[150] = "2:30";
$duration_options[180] = "3:00";
$duration_options[240] = "4:00";
$duration_options[300] = "5:00";
$duration_options[360] = "6:00";
$duration_options[420] = "7:00";
$duration_options[480] = "8:00" ;
$duration_options[720] = "12:00";
$duration_options[1440] = "All Day";

$day_options = array();
$day_options["*"] = "Every Day";
for ($i = 1; $i < 32; $i++) {
    $day_options[$i] = $i;
}
$day_options["EVERY"] = "Every ...";
$day_options["#1"] = "First ...";
$day_options["#2"] = "Second ...";
$day_options["#3"] = "Third ...";
$day_options["#4"] = "Fourth ...";
$day_options["#5"] = "Fifth ...";
$day_options["L"] = "Last ...";
//$day_options[""] = "Every Weekday ...";

$day_secondary_options = array();
$day_secondary_options["SUN"] = "Sunday";
$day_secondary_options["MON"] = "Monday";
$day_secondary_options["TUE"] = "Tuesday";
$day_secondary_options["WED"] = "Wednesday";
$day_secondary_options["THU"] = "Thursday";
$day_secondary_options["FRI"] = "Friday";
$day_secondary_options["SAT"] = "Saturday";

$year_options = array();
$year_options["*"] = "Every Year";
$year_options[2013] = "2013";
$year_options[2014] = "2014";
$year_options[2015] = "2015";
$year_options[2016] = "2016";
$year_options[2017] = "2017";
$year_options[2018] = "2018";
$year_options[2019] = "2019";
$year_options[2020] = "2020";
$year_options[""] = "Previous Years...";
$year_options[2012] = "2012";
$year_options[2011] = "2011";
$year_options[2010] = "2010";
$year_options[2009] = "2009";

$month_options = array();
$month_options["*"] = "Every Month";
$month_options[1] = "January";
$month_options[2] = "February";
$month_options[3] = "March";
$month_options[4] = "April";
$month_options[5] = "May";
$month_options[6] = "June";
$month_options[7] = "July";
$month_options[8] = "August";
$month_options[9] = "September";
$month_options[10] = "October";
$month_options[11] = "November";
$month_options[12] = "December";
?>


<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/calendar/". get_if_set($form, "fieldset_calendar_id")); ?>">&times;</a>
        <h1>Calendar</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_calendar/form/" . get_if_set($form, "fieldset_calendar_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_calendar_id]" value="<?php echo get_if_set($form, "fieldset_calendar_id"); ?>" />
            <fieldset id="calendar_list">
                <ul class="repeatable-element element_container">
                    <!-- Data from Template -->
                </ul>

                <div class="repeatable-element-actions">
                    <a href="" class="btn element_add">Add Calendar Item</a>
                </div>

                <script type="text/x-jquery-tmpl" class="element_template">
                    <li>
                        <input type="hidden" name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][fieldset_calendar_data_id]" value="${fieldset_calendar_data_id}" />
                        <a class="close element_delete">&times;</a>
                        <div class="control-group ">
                            <label class="control-label" for="year">Date</label>
                            <div class="controls" style="display: inline; margin-left: 20px;">
                                <select name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][year]" id="year"  class="input-medium">
                                    <?php foreach($year_options as $k=>$v): ?>
                                        <option value="<?php echo $k ?>" {{if year=='<?php echo $k ?>' }}selected="selected"{{/if}} ><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                            
                            <div class="controls" style="display: inline; margin-left: 0px;">
                                <select name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][month]" id="month"  class="input-medium">
                                    <?php foreach($month_options as $k=>$v): ?>
                                        <option value="<?php echo $k ?>" {{if month=='<?php echo $k ?>' }}selected="selected"{{/if}} ><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                            
                            <div class="controls" style="display: inline; margin-left: 0px;">
                                <select name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][day]" id="day"  class="input-medium day_select">
                                    <?php foreach($day_options as $k=>$v): ?>
                                        <option value="<?php echo $k ?>" {{if day=='<?php echo $k ?>' }}selected="selected"{{/if}} ><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                            
                            <div class="controls" style="display: inline; margin-left: 0px;">
                                <select name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][day_secondary]" id="day_secondary"  class="input-medium day_secondary_select">
                                    <?php foreach($day_secondary_options as $k=>$v): ?>
                                        <option value="<?php echo $k ?>" {{if day_secondary=='<?php echo $k ?>' }}selected="selected"{{/if}} ><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                                    
                        </div>                        
                        <div class="control-group {{if validation.date}}${validation.date.type}{{/if}}">
                            <label class="control-label">Recurring</label>
                            <div class="controls input-append datepicker_container date" data-date="${start_date}" data-date-format="dd/mm/yyyy" style="margin-left: 20px;">
                                <input name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][start_date]" type="text" value="${start_date}" class="input-small" style="width: 96px;" placeholder="MM/DD/YYYYY hh:mm:ss"/>
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                            <span>to
                            </span>
                            <div class="controls input-append datepicker_container date" data-date="${end_date}" data-date-format="dd/mm/yyyy" style="margin-left: 0px;">
                                <input name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][end_date]" type="text" value="${end_date}" class="input-small" style="width: 96px;" placeholder="MM/DD/YYYYY hh:mm:ss"/>
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                            {{if validation.date}}
                                <span class="help-inline">${validation.date.message}</span>
                            {{/if}}
                        </div>                                                                                       
                        <div class="control-group">
                            <label class="control-label" for="year">At</label>
                            <div class="controls" style="display: inline; margin-left: 20px;">
                                <select name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][hour]" id="hour"  class="input-small">
                                    <?php foreach($hour_options as $k=>$v): ?>
                                        <option value="<?php echo $k ?>" {{if hour=='<?php echo $k ?>' }}selected="selected"{{/if}} ><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                                                        
                            <div class="controls" style="display: inline; margin-left: 0px;">
                                <select name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][minute]" id="minute"  class="input-small">
                                    <?php foreach($minute_options as $k=>$v): ?>
                                        <option value="<?php echo $k ?>" {{if minute=='<?php echo $k ?>' }}selected="selected"{{/if}} ><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                            
                            <div class="controls" style="display: inline; margin-left: 0px;">
                                <select name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][am_pm]" id="am_pm"  class="input-small">
                                    <?php foreach($am_pm_options as $k=>$v): ?>
                                        <option value="<?php echo $k ?>" {{if am_pm=='<?php echo $k ?>' }}selected="selected"{{/if}} ><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                                                                                            
                            <span>&nbsp;For</span>
                            <div class="controls" style="display: inline; margin-left: 0px;">
                                <select name="form[fieldset_calendar_data][${fieldset_calendar_data_id}][duration]" id="duration"  class="input-small">
                                    <?php foreach($duration_options as $k=>$v): ?>
                                        <option value="<?php echo $k ?>" {{if duration=='<?php echo $k ?>' }}selected="selected"{{/if}} ><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                            
   
                        </div>
                    </li>
                </script>
            </fieldset>                                 
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_calendar/form/".get_if_set($form, "fieldset_calendar_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
jQuery(function(){    
    function set_calendar_templates(){

        function set_day_secondary_select($day_select){
            if($.isNumeric($day_select.val()) || $day_select.val() == "*"){
                $day_select.parents("li").find(".day_secondary_select").hide();
            }else{
                $day_select.parents("li").find(".day_secondary_select").show();
            }
        }
    
        var $fieldset = $("#calendar_list");
        var $template = $fieldset.find(".element_template");
        var $container = $fieldset.find(".element_container");
        var next_element_id = 0;
        var element_list = <?php echo get_json_element_list($form, $validation, "fieldset_calendar_data") ?>;

        //element_add implementation
        $fieldset.find(".element_add").unbind("click");
        $fieldset.on("click", ".element_add", function(){
            $template.tmpl({
                fieldset_calendar_data_id: "new_"+next_element_id,    
                start_date: "",
                end_date: "",
                year: "",
                month: "",
                day: "",
                day_secondary: "",
                hour: "",
                minute: "",
                duration: "",
                am_pm: "",
                validation: {}
                //specify other default values
            }).appendTo($container);
            $('.datepicker_container').datepicker({format: 'mm/dd/yyyy'});
            $container.find('.day_select').each(function(){
            set_day_secondary_select($(this));
            $(this).change(function(){
                set_day_secondary_select($(this));
            })
        });
            next_element_id++;
            return false;
        });

        //element_delete implementation
        $container.find(".element_delete").unbind("click");
        $container.on("click", "a.element_delete", function(){
            $(this).parents("li").remove();
        });

        //start
        $template.tmpl(element_list).appendTo($container);          
        
        $('.datepicker_container').datepicker({format: 'mm/dd/yyyy'});
        $container.find('.day_select').each(function(){
            set_day_secondary_select($(this));
            $(this).change(function(){
                set_day_secondary_select($(this));
            });
        });
    }
    
    loadCSS("/application/fieldsets/fieldset_calendar/public/css/bootstrap-datepicker.css");

    head.js(
        {jquery_tmpl: "/application/asterisk/asterisk_common/public/js/jquery.tmpl.js"},
        {date_time_picker: "/application/fieldsets/fieldset_calendar/public/js/bootstrap-datepicker.js"}
    );
    head.ready(function() {
        set_calendar_templates()
    });

});
</script>