<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/points_on_map/" . get_if_set($form, "fieldset_points_on_map_id")); ?>">&times;</a>
        <h1>Points On Map</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_points_on_map/form/" . get_if_set($form, "fieldset_points_on_map_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_summary_id]" value="<?php echo get_if_set($form, "fieldset_summary_id"); ?>" />

        <fieldset id="points_on_map">

            <?php
            form_field_input(array(
                "label" => "Name",
                "name" => "form[name]",
                "id" => "name",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "name"),
                "control_group_class" => get_if_set($validation, "fields>name>message>type"),
                "help_inline" => get_if_set($validation, "fields>name>message>text")
            ));
            ?>
            <?php
            form_field_textarea(array(
                "label" => "Description",
                "name" => "form[description]",
                "id" => "description",
                "input_class" => "input-xxlarge",
                "value" => get_if_set($form, "description"),
                "control_group_class" => get_if_set($validation, "fields>description>message>type"),
                "help_inline" => get_if_set($validation, "fields>description>message>text")
            ));
            ?>
            
            <div class="control-group">
                <label class="control-label" for="inputWarning">Points</label>
                <div class="controls">
                    <div class="input-append">
                        <input class="span6" id="search_address" type="text">
                        <button class="btn" id="search" type="button">Search</button>
                    </div>
                    <div id="gmap_canvas" style="clear: both;height:400px;margin-top:20px;width: 100%;border:1px solid #cccccc;"></div>
                </div>
            </div>
            <div id="point_on_map_markers" class="element_container">
                <?php
                if (isset($form["fieldset_points_on_map_data"])) {
                    foreach($form["fieldset_points_on_map_data"] as $p) {
                    ?>
                            <div id="p_<?php echo $p["fieldset_points_on_map_data_id"]?>" class="marker">
                                <input type="hidden" class="hidden_id" name="form[fieldset_points_on_map_data][<?php echo $p["fieldset_points_on_map_data_id"]?>][fieldset_points_on_map_data_id]" value="<?php echo $p["fieldset_points_on_map_data_id"]?>" />
                                <input type="hidden" class="hidden_name" name="form[fieldset_points_on_map_data][<?php echo $p["fieldset_points_on_map_data_id"]?>][name]" value="<?php echo $p["name"]?>" />
                                <input type="hidden" class="hidden_description" name="form[fieldset_points_on_map_data][<?php echo $p["fieldset_points_on_map_data_id"]?>][description]" value="<?php echo $p["description"]?>" />
                                <input type="hidden" class="hidden_longitude" name="form[fieldset_points_on_map_data][<?php echo $p["fieldset_points_on_map_data_id"]?>][longitude]" value="<?php echo $p["longitude"]?>" />
                                <input type="hidden" class="hidden_latitude" name="form[fieldset_points_on_map_data][<?php echo $p["fieldset_points_on_map_data_id"]?>][latitude]" value="<?php echo $p["latitude"]?>" />
                            </div>
                    <?php } ?>
                <?php } ?>
            </div>
            
            <script type="text/x-jquery-tmpl" class="element_template">
                <div id="p_${fieldset_points_on_map_data_id}" class="marker">
                     <input type="hidden" class="hidden_id" name="form[fieldset_points_on_map_data][${fieldset_points_on_map_data_id}][fieldset_points_on_map_data_id]" value="${fieldset_points_on_map_data_id}" />
                     <input type="hidden" class="hidden_name" name="form[fieldset_points_on_map_data][${fieldset_points_on_map_data_id}][name]" value="${name}" />
                     <input type="hidden" class="hidden_description" name="form[fieldset_points_on_map_data][${fieldset_points_on_map_data_id}][description]" value="${description}" />
                     <input type="hidden" class="hidden_longitude" name="form[fieldset_points_on_map_data][${fieldset_points_on_map_data_id}][longitude]" value="${longitude}" />
                     <input type="hidden" class="hidden_latitude" name="form[fieldset_points_on_map_data][${fieldset_points_on_map_data_id}][latitude]" value="${latitude}" />
                 </div>
             </script>
        </fieldset>
        <fieldset>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?php echo site_url("/fieldset_points_on_map/form/" . get_if_set($form, "fieldset_points_on_map_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>

<div id="point_edit_modal" class="modal hide fade" style="width:625px;margin-left:-240px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Edit point:</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
        <fieldset id="points_on_map">
            <input type="hidden" id="modal_id" value="" />
            <div class="control-group ">
                <label class="control-label" for="name">Name</label>
                <div class="controls">
                    <input type="text" class="input-large" id="modal_name" value="" placeholder="">
                </div>
            </div>
            <div class="control-group ">
                <label class="control-label" for="description">Description</label>
                <div class="controls">
                    <textarea class="input-xlarge" style="" rows="" id="modal_description" placeholder=""></textarea>
                </div>
            </div>
        </fieldset>
            </form>
    </div>
    <div class="modal-footer">
        <button class="btn" aria-hidden="true" id="modal_delete_point">Delete Point</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary" id="modal_save">Save changes</button>
    </div>
</div>



<script src="http://maps.google.com/maps/api/js?sensor=true&callback=init_google_map"></script>
<script>
    function init_google_map(){  
        head.js(
            {jquery_ui_map: "/application/fieldsets/fieldset_points_on_map/public/jquery.ui.map.full.min.js"},
            {jquery_tmpl: "/application/asterisk/asterisk_common/public/js/jquery.tmpl.js"}
        );
        head.ready(function() {
           initiate_fieldset();
        }); 
    }
    
    function initiate_fieldset(){
        
        initiate_map();
        
        $("#search").click(function(event){
            execute_search()
            event.preventDefault();
        });
        
        $('#search_address').on('keypress', function(e)
        {
            if(e.keyCode == 13)
            {
                execute_search();
                event.preventDefault();
            }
        });    
    } 
    
    function execute_search() {
        var address = "";
        address = encodeURIComponent($("input#search_address").val());

        $.post("http://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=true",function(data){
            if(data.status=="OK"){
                var GMStartLocation = new google.maps.LatLng(data.results[0].geometry.location.lat,data.results[0].geometry.location.lng);
                $("#gmap_canvas").gmap('option','center',GMStartLocation);
                $("#gmap_canvas").gmap('option','zoom',16);
                $("#gmap_canvas").gmap('refresh');
            }        
        },"json");
    }
    
    function open_modal(id) {
        $('#modal_id').val(id);
        $('#modal_name').val($("#" + id).find(".hidden_name").val());
        $('#modal_description').val($("#" + id).find(".hidden_description").val());
        $('#point_edit_modal').modal(); 
    }
    
    function delete_point(id) {
        $("#" + id).remove();
        $('#gmap_canvas').gmap('find', 'markers', { }, function(marker, found) {
		
                if (marker.id == id) {
                    marker.setMap(null);
                }
	});
        $('#point_edit_modal').modal('hide');
    }
    
    function save_point() {
        id = $('#modal_id').val()
        $("#" + id).find(".hidden_name").val($('#modal_name').val());
        $("#" + id).find(".hidden_description").val($('#modal_description').val());
        $('#point_edit_modal').modal('hide'); 
    }
    
    $("#modal_delete_point").click(function(e){
        delete_point($('#modal_id').val());
    });
    
    $("#modal_save").click(function(e){
        save_point();
    });
    
    function initiate_map(){                
        var $fieldset = $("#points_on_map");
        var $template = $fieldset.find(".element_template");
        var $container = $fieldset.find(".element_container");
        var next_element_id = 0;
        
        var GMStartLocation = new google.maps.LatLng(0, 0);
        $("#gmap_canvas").gmap({'center':GMStartLocation,'zoom':1}).bind('init', function(event, map) { 
            $(map).click( function(event) {
                var event = event;
                 update_timeout = setTimeout(function() {   
                    $template.tmpl({
                        fieldset_points_on_map_data_id: "new_"+next_element_id,    
                        latitude: event.latLng.Ya,
                        longitude : event.latLng.Za,
                        name: "",
                        description: "",
                        validation: {}
                        //specify other default values
                    }).appendTo($container);
                    
                    $('#gmap_canvas').gmap('addMarker', {
                            'position': event.latLng, 
                            'draggable': true, 
                            'bounds': false,
                            'id': 'p_' + "new_" + next_element_id
                    }, function(map, marker) {
                            //$('#dialog').append('<form id="dialog'+marker.__gm_id+'" method="get" action="/" style="display:none;"><p><label for="country">Country</label><input id="country'+marker.__gm_id+'" class="txt" name="country" value=""/></p><p><label for="state">State</label><input id="state'+marker.__gm_id+'" class="txt" name="state" value=""/></p><p><label for="address">Address</label><input id="address'+marker.__gm_id+'" class="txt" name="address" value=""/></p><p><label for="comment">Comment</label><textarea id="comment" class="txt" name="comment" cols="40" rows="5"></textarea></p></form>');
                            //findLocation(marker.getPosition(), marker);
                    }).dragend( function(event) {
                            $("#" + this.id).find(".hidden_longitude").val(event.latLng.Za);
                            $("#" + this.id).find(".hidden_latitude").val(event.latLng.Ya);
                    }).click( function() {
                            open_modal(this.id); 
                    })
                    
                    next_element_id++;
                }, 200);
            });
            $(map).dblclick( function(event) {
                   clearTimeout(update_timeout); 
            });
        });
        
        $("#gmap_canvas").gmap('clear','markers');
        
        $.each($("#point_on_map_markers .marker"), function(elem) {
            var markerLocation = new google.maps.LatLng($(this).find(".hidden_latitude").val(),$(this).find(".hidden_longitude").val());    
            $("#gmap_canvas").gmap('addMarker',{'position':markerLocation,'draggable': true,'bounds':true, id: 'p_' + $(this).find(".hidden_id").val() })
            .dragend( function(event) {
                $("#" + this.id).find(".hidden_longitude").val(event.latLng.Za);
                            $("#" + this.id).find(".hidden_latitude").val(event.latLng.Ya);                               
            })
            .click(function(){
                open_modal(this.id); 
            });

        });
        
        $("#gmap_canvas").gmap('refresh');
    }

    
</script>


