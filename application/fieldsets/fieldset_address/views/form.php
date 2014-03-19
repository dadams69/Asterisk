<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/address/" . get_if_set($form, "fieldset_address_id")); ?>">&times;</a>
        <h1>Address</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_address/form/" . get_if_set($form, "fieldset_address_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_address_id]" value="<?php echo get_if_set($form, "fieldset_address_id"); ?>" />
        <fieldset> 
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
            <?php
            form_field_input(array(
                "label" => "Address Line 1",
                "name" => "form[address_line_1]",
                "id" => "address_line_1",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "address_line_1"),
                "control_group_class" => get_if_set($validation, "fields>address_line_1>message>type"),
                "help_inline" => get_if_set($validation, "fields>address_line_1>message>text")
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Address Line 2",
                "name" => "form[address_line_2]",
                "id" => "address_line_2",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "address_line_2"),
                "control_group_class" => get_if_set($validation, "fields>address_line_2>message>type"),
                "help_inline" => get_if_set($validation, "fields>address_line_2>message>text")
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "City",
                "name" => "form[city]",
                "id" => "city",
                "type" => "text",
                "input_class" => "input-large",
                "value" => get_if_set($form, "city"),
                "control_group_class" => get_if_set($validation, "fields>city>message>type"),
                "help_inline" => get_if_set($validation, "fields>city>message>text")
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "State",
                "name" => "form[state]",
                "id" => "state",
                "type" => "text",
                "input_class" => "input-large",
                "value" => get_if_set($form, "state"),
                "control_group_class" => get_if_set($validation, "fields>state>message>type"),
                "help_inline" => get_if_set($validation, "fields>state>message>text")
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Postal Code",
                "name" => "form[postal_code]",
                "id" => "postal_code",
                "type" => "text",
                "input_class" => "input-medium",
                "value" => get_if_set($form, "postal_code"),
                "control_group_class" => get_if_set($validation, "fields>postal_code>message>type"),
                "help_inline" => get_if_set($validation, "fields>postal_code>message>text")
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Country",
                "name" => "form[country]",
                "id" => "country",
                "type" => "text",
                "input_class" => "input-large",
                "value" => get_if_set($form, "country"),
                "control_group_class" => get_if_set($validation, "fields>country>message>type"),
                "help_inline" => get_if_set($validation, "fields>country>message>text")
            ));
            ?>
            <div class="control-group">
                <label class="control-label" for="inputWarning">Point on Map</label>
                <div class="controls">
                    <div>
                        <span>
                            <a href="#" id="link_map" class="btn btn-primary">Look up point</a>
                        </span>            
                        <span>
                            <a href="#" id="destroy_map" class="btn btn-warning">Clear point</a>
                        </span>            
                    </div>
                    <div class="gmap_canvas" style="margin-top:20px;height: 400px; position: relative; border: 1px solid #ccc; background-color: #eeeeee;overflow: hidden;" id="address_<?php echo get_if_set($form, "fieldset_address_id"); ?>_map_canvas">

                    </div>
                </div>
            </div>
            
            
            <?php
            form_field_input(array(
                "label" => "Latitude",
                "name" => "form[latitude]",
                "id" => "latitude",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "latitude"),
                "control_group_class" => get_if_set($validation, "fields>latitude>message>type"),
                "help_inline" => get_if_set($validation, "fields>latitude>message>text"),
                "extra_attr" => " readonly=readonly "
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Longitude",
                "name" => "form[longitude]",
                "id" => "longitude",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "longitude"),
                "control_group_class" => get_if_set($validation, "fields>longitude>message>type"),
                "help_inline" => get_if_set($validation, "fields>longitude>message>text"),
                "extra_attr" => " readonly=readonly "
            ));
            ?> 
        </fieldset>
        <fieldset>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?php echo site_url("/fieldset_address/form/" . get_if_set($form, "fieldset_address_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script src="http://maps.google.com/maps/api/js?sensor=true&callback=init_google_map"></script>
<script>
    function init_google_map(){ 
        head.js(
            {jquery_ui_map: "/application/fieldsets/fieldset_points_on_map/public/jquery.ui.map.full.min.js"}
        );
        head.ready("redactor", function() {
           set_map_functionality();
        }); 
    }
    function set_map_and_location(latitude, longitude,zoom,load_marker,description){                
            
        var GMStartLocation = new google.maps.LatLng(latitude, longitude);
        $("#address_<?php echo get_if_set($form, "fieldset_address_id"); ?>_map_canvas").gmap();
        $("#address_<?php echo get_if_set($form, "fieldset_address_id"); ?>_map_canvas").gmap('clear','markers');
        if(load_marker == true){
            $("#address_<?php echo get_if_set($form, "fieldset_address_id"); ?>_map_canvas").gmap('addMarker',{'position':GMStartLocation,'draggable': true,'bounds':false })
            .dragend( function(event) {
                $("input#latitude").val(event.latLng.Ya);
                $("input#longitude").val(event.latLng.Za);                        
                        
            })
            .click(function(){
                return; //$("#address_<?php echo get_if_set($form, "fieldset_address_id"); ?>_map_canvas").gmap('openInfoWindow',{'content':description},this);
            });
        }
        $("#address_<?php echo get_if_set($form, "fieldset_address_id"); ?>_map_canvas").gmap('option','center',GMStartLocation);
        $("#address_<?php echo get_if_set($form, "fieldset_address_id"); ?>_map_canvas").gmap('option','zoom',zoom);
        $("#address_<?php echo get_if_set($form, "fieldset_address_id"); ?>_map_canvas").gmap('refresh');
        $("#destroy_map").click(function(event){
            $("#address_<?php echo get_if_set($form, "fieldset_address_id"); ?>_map_canvas").gmap('destroy').html("");
            $("input#latitude").val("");
            $("input#longitude").val("");                                    
            event.preventDefault();
        })
    }
        
    function set_map_functionality(){
        <?php if (get_if_set($form, "latitude") != "" && get_if_set($form, "longitude") != "") { ?>
            set_map_and_location("<?php echo get_if_set($form, "latitude"); ?>","<?php echo get_if_set($form, "longitude") ?>",16,true, "<?php echo get_if_set($form, "name") ?>");
        <?php } ?>
         
        $("#link_map").click(function(event){
        var address = "";
        address = $("input#address_line_1").val().replace(' ','+') + ",+";
        address = address + $("input#city").val().replace(' ','+') + ",+";
        address = address + $("input#state").val().replace(' ','+') + "+";
        address = address + $("input#postal_code").val().replace(' ','+') + ",+";
        address = address + $("input#country").val().replace(' ','+');
        address = encodeURIComponent(address);

        $.post("http://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=true",function(data){
            if(data.status=="OK"){
                set_map_and_location(data.results[0].geometry.location.lat,data.results[0].geometry.location.lng,16,true, data.results[0].formatted_address);
                $("input#latitude").val(data.results[0].geometry.location.lat);
                $("input#longitude").val(data.results[0].geometry.location.lng);
            }else{
                $("input#latitude").val("");
                $("input#longitude").val("");
            }        
        },"json");

        event.preventDefault();
    });
    } 
    
    
    
        
    
</script>
