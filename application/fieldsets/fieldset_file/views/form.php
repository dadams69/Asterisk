<section class="file_fieldset_section">
     <div class="page-header">
         <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/file/". get_if_set($form, "fieldset_file_id")); ?>">&times;</a>
        <h1>Files</h1>
    </div>
    <?php 
        $container_id = "file_manager_".$form["fieldset_file_id"]; 
    ?>
    <div id="<?php echo $container_id; ?>">
        <div id="fileupload" class="fileupload-container">                                
            <div class="progress progress-striped active fileupload-progressbar">
                <div class="bar"></div>
            </div>
            <table class="files table">
                <thead>
                    <tr>
                        <th style="width: 90px;">Preview</th>
                        <th style="width: 100%">Title</th>
                        <th style="width: 100px;">Size</th>
                        <th style="width: 100px">Actions</th>
                    </tr>
                </thead>
                <?php 
                if(count($form["files"])>0){
                    foreach($form["files"] as $file_data):             
                        ?>
                <tr id="row_<?php echo $file_data["fieldset_file_data_id"]; ?>">
                    <td>
                        <a href="<?php echo base_url("public/files/".$file_data["fieldset_file_data_id"]."/".$file_data["filename"].".".$file_data["extension"]); ?>" class="thumbnail_image_link" target="_blank">
                            <?php 
                                if($file_data["extension"] == "jpg" || $file_data["extension"] == "jpeg" || $file_data["extension"] == "png" || $file_data["extension"] == "gif" || $file_data["extension"] == "bmp"){ 
                                    $is_image = true;
                                }else{
                                    $is_image = false;
                                }
                                if($is_image){
                            ?>
                                <img class="file_thumbnail" src="<?php echo base_url("public/files/".$file_data["fieldset_file_data_id"]."/".$file_data["filename"].".".$file_data["extension"]); ?>" >                        
                            <?php }else{ ?>
                                <div class="extension"><?php echo $file_data["extension"]; ?></div>
                            <?php } ?>
                        </a>
                    </td>    
                    <td class="name">
                        <a href="<?php echo base_url("public/files/".$file_data["fieldset_file_data_id"]."/".$file_data["filename"].".".$file_data["extension"]); ?>" target="_blank"><?php echo $file_data["title"]; ?></a><span><?php $size_key_name = $this->model->get_size_key_name($form["fieldset_file_id"],$file_data["image_size_key"]); echo ($size_key_name != "")? " (".$size_key_name.")": ""; ?></span>
                    </td>
                    <td class="size"><?php echo $this->model->get_file_size_string($file_data["size"]); ?></td>
                    <td>
                        <div class="btn-group">
                                <button class="btn dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                <ul class="dropdown-menu">                                        
                                    <li><a class="copy_file" href="#" fieldset_file_data_id="<?php echo $file_data["fieldset_file_data_id"]; ?>">Copy</a></li>                        
                                    <?php if($is_image){ ?><li><a class="resize_image" href="#" fieldset_file_data_id="<?php echo $file_data["fieldset_file_data_id"]; ?>" image_name="<?php echo $file_data["filename"]; ?>" image_width="<?php echo $file_data["image_width"]; ?>" image_height="<?php echo $file_data["image_height"]; ?>" image_source="<?php echo base_url("public/files/".$file_data["fieldset_file_data_id"]."/".$file_data["filename"].".".$file_data["extension"]); ?>">Resize</a></li> <?php } ?>
                                    <li><a class="edit_file" href="#" fieldset_file_data_id="<?php echo $file_data["fieldset_file_data_id"]; ?>">Edit</a></li>
                                    <li><a class="delete_file" href="#" fieldset_file_data_id="<?php echo $file_data["fieldset_file_data_id"]; ?>">Delete</a></li>
                                </ul>
                        </div>                        
                    </td>                    
                </tr>
                <?php   
                    endforeach; 
                }else{
                ?>
                <tr class="no_results_row">
                    <td colspan="4">No Results</td>
                </tr>                
                <?php }?>                    

            </table>
            <div class="well">
                <span class="fileinput-button">
                    <input id="input_fileupload" type="file" name="file_<?php echo $form["fieldset_file_id"]; ?>" multiple style="display: none">
                    <a class="btn btn-primary" href="#" id="btn_fileupload" style="width: 200px">Upload File</a>

                </span>
                <span style="width:75px;text-align:center;display:inline-block;">or</span>
                <select class="file_searcher" source="<?php echo site_url("/fieldset_file/get_files/".$form["fieldset_file_id"]); ?>" style="margin-bottom: 0px;">
                    <option value="">Attach an existing file...</option>
                </select> 
            </div>
        </div>          
        
        <script class="template-upload" type="text/x-jquery-tmpl">
            <tr class="template-upload{{if error}} template-error{{/if}}">
                {{if error}}
                    <td class="error" colspan="3">
                        ${name}-${sizef}
                        Error:
                        {{if error === 'maxFileSize'}}File is too big, max size 16 MB
                        {{else error === 'minFileSize'}}File is too small
                        {{else error === 'acceptFileTypes'}}Filetype not allowed, must be JPEG/GIF/PNG
                        {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                        {{else}}${error}
                        {{/if}}                        
                    </td>
                    <td class="error">                
                        <a class="cancel btn">Ok</a>
                    </td>
                {{else}}
                    <td class="preview"></td>
                    <td class="name">
                        ${name}
                        <div class="progress progress-success progress-striped active row-progressbar" >
                            <div class="bar" ></div>
                        </div>
                    </td>
                    <td class="size">${sizef}</td>
                    <td><a class="cancel btn btn-warning">Cancel</a></td>
                {{/if}}

            </tr>
        </script>
        <script class="template-download" type="text/x-jquery-tmpl">
            <tr class="template-download{{if error}} template-error{{/if}}" id="row_${fieldset_file_data_id}">

                {{if error}}
                    <td class="error" colspan="3">Error:
                        {{if error === 1}}File exceeds upload_max_filesize (php.ini directive)
                        {{else error === 2}}File exceeds MAX_FILE_SIZE (HTML form directive)
                        {{else error === 3}}File was only partially uploaded
                        {{else error === 4}}No File was uploaded
                        {{else error === 5}}Missing a temporary folder
                        {{else error === 6}}Failed to write file to disk
                        {{else error === 7}}File upload stopped by extension
                        {{else error === 'maxFileSize'}}File is too big
                        {{else error === 'minFileSize'}}File is too small
                        {{else error === 'acceptFileTypes'}}Filetype not allowed
                        {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                        {{else error === 'uploadedBytes'}}Uploaded bytes exceed file size
                        {{else error === 'emptyResult'}}Empty file upload result
                        {{else}}${error}
                        {{/if}}
                    </td>
                    <td><a class="delete btn" data-type="${delete_type}" data-url="${delete_url}">Ok</a></td>
                {{else}}   
                    <td class="preview">
                        <a href="${url}" target="_blank">
                        {{if extension == "jpeg" || extension == "jpg" || extension == "gif" || extension == "png" || extension == "bmp"}}
                            <img src="${url}" class="file_thumbnail">
                        {{else}} 
                            <div class="extension">${extension}</div>
                        {{/if}}
                        </a>
                    </td>
                    <td class="name">
                        <a href="${url}" target="_blank">${name}</a>
                        {{if size_key_name != ""}}
                        <span>(${size_key_name})</span>
                        {{/if}}
                    </td>
                    <td class="size">${size}</td>
                    <td>
                        <div class="btn-group">
                                <button class="btn dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                <ul class="dropdown-menu">                                                                            
                                    <li><a class="copy_file" href="#" fieldset_file_data_id="${fieldset_file_data_id}">Copy</a></li>
                                    {{if extension == "jpeg" || extension == "jpg" || extension == "gif" || extension == "png" || extension == "bmp"}}<li><a class="resize_image" href="#" fieldset_file_data_id="${fieldset_file_data_id}" image_name="${name}" image_width="${image_width}" image_height="${image_height}" image_source="${url}">Resize</a></li>{{/if}}
                                    <li><a class="edit_file" href="#" fieldset_file_data_id="${fieldset_file_data_id}">Edit</a></li>
                                    <li><a class="delete_file" href="#" fieldset_file_data_id="${fieldset_file_data_id}">Delete</a></li>
                                </ul>
                        </div>                                                
                    </td>                    
                {{/if}}
            </tr>
        </script>        

        
        <div id="file_information_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="header_label" aria-hidden="true">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="header_label">Edit Image Information</h3>
          </div>
          <div class="modal-body">
              
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary" id="modal_save">Save changes</button>
          </div>
        </div>        
        
        <div class="image_edit_modal modal hide fade" tabindex="-1" role="dialog" aria-labelledby="header_label" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="header_label">Resize Image Information</h3>
            </div>
            <div class="modal-body" >
                    <div class="image_edit_container">
                    </div>  

                    <div class="coords_container">
                        <form>
                        <div>
                            <label for="size_key">Select a Size:</label>
                            <select name="image_edit_form[size_key]" id="size_key" class="input-large">
                                <?php foreach($this->model->get_size_key_array($form["fieldset_file_id"]) as $size_key_name=>$sizes): ?>
                                <option ratio_width="<?php echo get_if_set($sizes,'ratio_width',"");?>" ratio_height="<?php echo get_if_set($sizes,'ratio_height',"");?>" width_min="<?php echo get_if_set($sizes,'min_width');?>" width_max="<?php echo get_if_set($sizes,'max_width');?>" height_min="<?php echo get_if_set($sizes,'min_height');?>" height_max="<?php echo get_if_set($sizes,'max_height');?>" value="<?php echo $size_key_name;?>"><?php echo get_if_set($sizes,'name',"")." [w: ".((get_if_set($sizes,'min_width')== get_if_set($sizes,'max_width'))?get_if_set($sizes,'min_width'):get_if_set($sizes,'min_width')." to ".get_if_set($sizes,'max_width'))."px h: ".((get_if_set($sizes,'min_height')==get_if_set($sizes,'max_height'))?get_if_set($sizes,'min_height'):get_if_set($sizes,'min_height')." to ".get_if_set($sizes,'max_height'))."px".((get_if_set($sizes,'ratio_width',"")!="" && get_if_set($sizes,'ratio_height',"")!="")?' Ratio: '.get_if_set($sizes,'ratio_width').":".get_if_set($sizes,'ratio_height'):'')."]";?></option>
                                <?php endforeach; ?>
                            </select>
                            <input id="fieldset_file_data_id" name="image_edit_form[fieldset_file_data_id]" type="hidden" value=""/>
                            <input id="fieldset_file_id" name="image_edit_form[fieldset_file_id]" type="hidden" value="<?php echo $form["fieldset_file_id"]; ?>"/>
                        </div>
                        
                        <div>
                            <label>Crop X/Y:</label>
                            <input type="text" value="" name="image_edit_form[crop_x]" id="crop_x" class="input-small"/>
                            <input type="text" value="" name="image_edit_form[crop_y]" id="crop_y" class="input-small"/>
                        </div>
                        <div>
                            <label>Crop Width/Height:</label>
                            <input type="text" value="" name="image_edit_form[crop_width]" id="crop_width" class="input-small"/>
                            <input type="text" value="" name="image_edit_form[crop_height]" id="crop_height" class="input-small"/>
                        </div>
                        <div>
                            <label>Image Width/Height:</label>
                            <input type="text" value="" name="image_edit_form[image_width]" id="image_width" class="input-small"/>
                            <input type="text" value="" name="image_edit_form[image_height]" id="image_height" class="input-small"/>
                        </div>            
                        </form>
                    </div>           
                    <div style="clear: both;"></div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-primary" id="modal_save">Save changes</button>
            </div>
        </div> 
        
    </div>   
</section>                    
<script>
    jQuery(function(){
        
        loadCSS("/application/fieldsets/fieldset_file/public/css/file.css");
        loadCSS("/application/fieldsets/fieldset_file/public/css/jrac.css");
        head.js(
            {fileupload:"/application/fieldsets/fieldset_file/public/js/fileupload.js"},
            {fileupload_ui:"/application/fieldsets/fieldset_file/public/js/fileupload-ui.js"},
            {iframe_transport:"/application/fieldsets/fieldset_file/public/js/iframe-transport.js"},
            {jquery_tmpl:"/application/asterisk/asterisk_common/public/js/jquery.tmpl.js"},
            {jquery_jrac:"/application/fieldsets/fieldset_file/public/js/jquery.jrac.js"}
        );
        head.ready(function() {
            fileupload_process();
        });    
        
        function fileupload_process(){
            var edit_image_submit = function(save_button){
                if(!save_button.hasClass('disabled')){
                    var form_params = $("#<?php echo $container_id; ?> .image_edit_modal form").serialize();                    
                    $.post(
                        "<?php echo site_url('/fieldset_file/resize_image')?>",
                        form_params,
                        function(data){
                            if(data.result=="true"){
                                var $updated_row = $("script.template-download").tmpl(data.file);
                                $("#<?php echo $container_id; ?> tr#row_"+data.file.fieldset_file_data_id).replaceWith($updated_row);         
                                var time = new Date().getTime();
                                $("#<?php echo $container_id; ?> tr#row_"+data.file.fieldset_file_data_id + " img").attr('src', $("#<?php echo $container_id; ?> tr#row_"+data.file.fieldset_file_data_id + " img").attr("src") + '?' + time);                                
                                $("#<?php echo $container_id; ?> .image_edit_modal").modal("hide");
                                $("#message-holder").html('<div class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                            }else if(data.result=="false"){
                                $("#message-holder").html('<div class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                            }
                        }
                    ,'json');
                }
            };                
            
            function open_edit_image(anchor){
                $("#<?php echo $container_id; ?> .image_edit_modal").off("click","#modal_save");
                $("#<?php echo $container_id; ?> .image_edit_modal").on("click","#modal_save",function(event){edit_image_submit($(this));event.preventDefault();});

                $("#<?php echo $container_id; ?> .image_edit_modal #fieldset_file_data_id").val(anchor.attr("fieldset_file_data_id"));

                $("#<?php echo $container_id; ?> .image_edit_modal .image_edit_container").html("");
                $("#<?php echo $container_id; ?> .image_edit_modal .image_edit_container").html('<img id="image_box" style="max-width: none" src=""></img>');

                $("#<?php echo $container_id; ?> .image_edit_modal .image_edit_container #image_box").attr("src",anchor.attr("image_source"));
                $("#<?php echo $container_id; ?> .image_edit_modal .image_edit_container #image_box").attr("width",anchor.attr("image_width"));
                $("#<?php echo $container_id; ?> .image_edit_modal .image_edit_container #image_box").attr("height",anchor.attr("image_height"));

                $("#<?php echo $container_id; ?> .image_edit_modal .image_edit_container #image_box ").jrac(jrac_config(anchor)) 
                    .unbind('viewport_events').bind('viewport_events', function(event, $viewport) {
                        var inputs = $(this).parent().parent().parent().parent().find('.coords_container input');
                        
                        var drag_selection = $(this).parent().find(".jrac_crop");

                        if($viewport.observator.crop_consistent()){
                            $("#<?php echo $container_id; ?> .image_edit_modal #modal_save").removeClass('disabled');
                            drag_selection.css('border','1px solid #A8F8C9');
                            inputs.css('background-color','#A8F8C9');
                        }else{
                            $("#<?php echo $container_id; ?> .image_edit_modal #modal_save").addClass('disabled');
                            drag_selection.css('border','1px solid salmon');
                            inputs.css('background-color','salmon');                                
                        }

                    });
                    
                $("#<?php echo $container_id; ?> .image_edit_modal").modal();
                
            }            


            var jrac_config = function(data){ 

                                return {
                                    'viewport_resize': false,
                                    'crop_x': 0,
                                    'crop_y': 0,
                                    'crop_width': 100,
                                    'crop_height': 100,
                                    'zoom_min': 50,
                                    'zoom_max': data.attr("image_width"),
                                    'viewport_onload': function() {
                                      var $viewport = this;

                                      //Set Initial sizes
                                      set_select_sizes($viewport,$viewport.$container.parent('.image_edit_container').parent().find("#size_key").find("option:selected"));

                                      var inputs = $viewport.$container.parent('.image_edit_container').parent().find('.coords_container input:text');
                                      var events = ['crop_x','crop_y','crop_width','crop_height','image_width','image_height'];
                                      for (var i = 0; i < events.length; i++) {
                                        var event_name = events[i];
                                        // Register an event with an element.
                                        $viewport.observator.register(event_name, inputs.eq(i));
                                        // Attach a handler to that event for the element.
                                        inputs.eq(i).bind(event_name, function(event, $viewport, value) {
                                          $(this).attr("last_value",parseInt(value));
                                          $(this).val(parseInt(value));
                                        })
                                        // Attach a handler for the built-in jQuery change event, handler
                                        // which read user input and apply it to relevent viewport object.
                                        .change(event_name, function(event) {
                                          var event_name = event.data;
                                          if($viewport.observator.check_before_set_property(event_name,$(this).val())){
                                            $viewport.observator.set_property(event_name,$(this).val());    
                                          }else{
                                            $(this).val($(this).attr("last_value"));   
                                          }
                                        })
                                      }

                                      //This manage the action on size select change
                                      var $size_key_select = $viewport.$container.parent('.image_edit_container').parent().find("#size_key");
                                      $size_key_select.unbind("change");
                                      $size_key_select.change(function() {
                                          var selected_option = $(this).find("option:selected");
                                          set_select_sizes($viewport,selected_option);
                                      })
                                    }
                                };
                              };

            function set_select_sizes($viewport,selected_option){
              $viewport.observator.set_sizes(
              selected_option.attr("ratio_width"),
              selected_option.attr("ratio_height"),
              selected_option.attr("width_min"),
              selected_option.attr("width_max"),
              selected_option.attr("height_min"),
              selected_option.attr("height_max"));                
            }
            
            $("#<?php echo $container_id; ?>").on("click",".resize_image",function(event){
                open_edit_image($(this));
                event.preventDefault();
            });
            /**************************************************/



            $("#<?php echo $container_id; ?>").on("click",".copy_file",function(event){
                var fieldset_file_data_id = $(this).attr("fieldset_file_data_id");
                var btn = $(this);
                $(this).dialog_confirmation({on_confirm: function(){ 
                    var tr = btn.parents("tr");
                    $.post(
                        "<?php echo site_url('/fieldset_file/copy_file/'.$form["fieldset_file_id"])?>/" + fieldset_file_data_id, 
                        function(data){ 
                            if(data.result == "true"){
                                $("script.template-download").tmpl(data.file).appendTo("#<?php echo $container_id; ?> table.files");         
                                $("#message-holder").html('<div class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                            }else{
                                $("#message-holder").html('<div class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                            }
                        }
                    ,'json');                                    
                }});
                event.preventDefault();         
            });

            $("#<?php echo $container_id; ?>").on("click",".delete_file",function(event){
                var fieldset_file_data_id = $(this).attr("fieldset_file_data_id");
                var btn = $(this);
                $(this).dialog_confirmation({on_confirm: function(){ 
                    var tr = btn.parents("tr");
                    $.post(
                        "<?php echo site_url('/fieldset_file/delete_file/'.$form["fieldset_file_id"])?>/" + fieldset_file_data_id, 
                        function(data){ 
                            if(data.result == "true"){
                                tr.fadeOut(500,function(){
                                    $(this).remove();
                                    if($("table.files").find("tr").length < 2){$("table.files").append("<tr class='no_results_row'><td colspan='4'>No Results</td></tr>");}
                                });
                                $("#message-holder").html('<div class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                            }else{
                                $("#message-holder").html('<div class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                            }
                        }
                    ,'json');
                }});
                event.preventDefault();
            });
            
            $("#<?php echo $container_id; ?>").on("click",".edit_file",function(event){
                var fieldset_file_data_id = $(this).attr("fieldset_file_data_id");
                $.post("<?php echo site_url("/fieldset_file/form_file_data/".$form["fieldset_file_id"]); ?>/" + fieldset_file_data_id,function(data){
                    $('#<?php echo $container_id; ?> #file_information_modal .modal-body').html(data.modal_body);
                    $('#<?php echo $container_id; ?> #file_information_modal').modal();                    
                },"json");
                event.preventDefault();
            });
            
            $("#<?php echo $container_id; ?> #file_information_modal").on("submit","#form_file_data",function(event){
                var url = $(this).attr("action");
                $.post(url, $(this).serialize(),function(data){
                    if(data.result == "false"){
                        $('#<?php echo $container_id; ?> #file_information_modal .modal-body').html(data.modal_body);
                    }else if(data.result == "true"){
                        $("#message-holder").html('<div class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                        $('#<?php echo $container_id; ?> #file_information_modal').modal("hide");
                        var $updated_row = $("script.template-download").tmpl(data.file);
                        $("#<?php echo $container_id; ?> tr#row_"+data.file.fieldset_file_data_id).replaceWith($updated_row);                                 
                    }
                },"json");                
                event.preventDefault();
                return false;
            });
            
            
            $("#<?php echo $container_id; ?> #file_information_modal").on("click","#modal_save",function(event){
                $("#<?php echo $container_id; ?> #file_information_modal #form_file_data").submit();
                event.preventDefault();
            })


            $('#<?php echo $container_id ?>').fileupload({
                url: '<?php echo site_url('/fieldset_file/upload_file/'.$form["fieldset_file_id"]); ?>',
                type: 'POST',
                dateType: 'json',
                maxFileSize: 16000000,
                sequentialUploads: true,
                autoUpload: true,
                dropZone: $("#<?php echo $container_id; ?> #fileupload"),
                acceptFileTypes: /^/,
                uploadTemplate: $("#<?php echo $container_id ?> script.template-upload"),
                downloadTemplate: $("#<?php echo $container_id ?> script.template-download"),
                singleFileUploads: true,
                afterDone: function(){                    
                    $("#message-holder").html('<div class="alert alert-notice"><a class="close" data-dismiss="alert" href="#">×</a>File(s) uploaded</div>').fadeIn();                
                },
                onAdd: function() {
                    $("table.files").find("tr.no_results_row").slideUp("fast",function(){$(this).remove()});
                }                
            });
        }
                         
        $("#btn_fileupload").click(function(event){
            $("#input_fileupload").click();
            event.preventDefault();
        });            
        
        (function( $ ){
          $.fn.getFiles = function(options) {
              this.each(function() {
                $(this).focus(function() {
                    var $input_select = $(this);
                    if($input_select.attr("source")){
                        $input_select.html("");
                        $input_select.append("<option value=''>Loading...</option>");            
                        $.ajax(
                            { url : $input_select.attr("source"),
                              type: "POST",
                              success : function(data) {                
                                $input_select.html("");
                                $input_select.append("<option value=''>Attach an existing file...</option>");
                                $.each(data, function(key,file) {
                                    $input_select.append("<option class='file_option' value='" + file.fieldset_file_data_id + "' >" + file.label + "</option>");
                                });        
                              },
                              async : false,
                              dataType : "JSON"
                            }
                        );  

                    }
                });
              });
          };
        })( jQuery );

        $("select.file_searcher").getFiles();
        
        $("select.file_searcher").change(function(){
                if($(this).val()!=""){
                    var $file_data_id = $(this).val();
                    $.post(
                        "<?php echo site_url('/fieldset_file/add_file/'.$form["fieldset_file_id"])?>/"+$file_data_id, 
                        function(data){ 
                            if(data.result == "true"){
                                $("table.files").find("tr.no_results_row").slideUp("fast",function(){$(this).remove()});
                                $("#message-holder").html('<div class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                                $("script.template-download").tmpl(data.file).appendTo("#<?php echo $container_id; ?> table.files");         
                                $(".file_searcher").val("");
                            }else{
                                $("#message-holder").html('<div class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                            }
                        }
                    ,'json'); 
                }
        });
    });
</script>