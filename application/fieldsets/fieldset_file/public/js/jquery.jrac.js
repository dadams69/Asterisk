/* jQuery Resize And Crop (jrac)
 * Version 1.0-beta1 - 25 jun 2011
 * A jQuery 1.4+ and jQueryUi 1.8+ plugin under GNU GENERAL PUBLIC LICENSE version 2 lisense.
 * Copyright (c) 2011 Cedric Gampert - cgampert@gmail.com
 */

(function( $ ){

  $.fn.jrac = function(options) {

    // Default settings
    var settings = {
      'crop_width': 200,
      'crop_height': 100,
      // The two following properties define the crop position (relative to the
      // image).
      'crop_x': 0,
      'crop_y': 0,
      'crop_resize': true,
      'crop_aspect_ratio': false,
      'crop_aspect_width_min': 10,
      'crop_aspect_width_max': 300,
      'crop_aspect_height_min': 10,
      'crop_aspect_height_max': 300,
      'image_width': null,
      'image_height': null,
      'zoom_min': 100,
      'zoom_max': 3000,
      'viewport_resize': true,
      // The two following properties allow to position the content (negative 
      // value allowed). It can be use to focus the viewport on the cropped 
      // part of the image. 
      'viewport_content_left': 0,
      'viewport_content_top': 0,
      // Submit here a callback function (context is the viewport).
      'viewport_onload': null
    };

    // Apply the resize and crop tools to each images
    return this.each(function() {

      if (!$(this).is('img')) {
        return;
      }

      // Read options
      if ( options ) {
        $.extend( settings, options );
      }

      // Prepare image
      var $image = $(this).hide().css('position','absolute').wrap('<div class="jrac_container"><div class="jrac_viewport"></div></div>');
      
      $image.width($image.attr("width"));
      $image.height($image.attr("height"));
      // Get viewport and container references
      var $viewport = $image.parent();
      var $container = $viewport.parent();

      // Add a waiting on load input image
      var $loading = $('<div class="jrac_loading" />');
      $viewport.append($loading);

      // Wait on image load to build the next processes
      $('<img>').attr('src', $image.attr('src'))
      .load(function(){

        // Add some custom properties to $image
        $.extend($image, {
          scale_proportion_locked: true,
          originalWidth: $image.width(),
          originalHeight: $image.height(),
          originalRatio: $image.width()/$image.height(),
          real_width: $image.width(),
          real_height: $image.height(),
          scale_bar: 1
        });

        // Set given optional image size
        //$image.width(settings.image_width);
        //$image.height(settings.image_height);

        
        // Set the viewport content position for the image
        $image.css({'left': settings.viewport_content_left, 'top': settings.viewport_content_top});

        // Create the zoom widget which permit to resize the image
        var $zoom_widget = $('<div class="slider"><span>Resize Image</span><div class="ui-slider-handle"></div></div>')
        .width($viewport.width())
        .slider({
          value: $image.width(),
          min: settings.zoom_min,
          max: settings.zoom_max,
          start: function(event, ui) {
            $.extend($zoom_widget,{
              on_start_width_value: $image.originalWidth,
              on_start_height_value: $image.originalHeight
            })
          },
          slide: function(event, ui) {
            var height = Math.round($zoom_widget.on_start_height_value * ui.value / $zoom_widget.on_start_width_value);
            var width = ui.value;
            
            $image.real_height = height;
            $image.real_width = width;
            
            $viewport.observator.notify('image_height', height);
            $viewport.observator.notify('image_width', width);

            height = height / $image.scale_bar;
            width = width / $image.scale_bar;
            
            $image.height(height);
            $image.width(width);

          }
        });
        
        $container.append($zoom_widget);
        
        // Create the scale widget which permit to resize the image
        var $scale_widget = $('<div class="slider"><span>Zoom: 1:1</span><div class="ui-slider-handle"></div></div>')
        .width($viewport.width())
        .slider({
          value: $image.scale_bar,
          min: 1,
          max: 6,
          step: 0.1,
          slide: function(event, ui) {
            $image.scale_bar = ui.value;
            $scale_widget.find("span").html("Zoom: 1:" + ui.value);
            $viewport.observator.scale_all();
          }
        });
        
        $container.append($scale_widget);        

        // Make the viewport resizeable
        if (settings.viewport_resize) {
          $viewport.resizable({
            resize: function(event, ui) {
              $zoom_widget.width(ui.size.width);
            }
          });
        }

        // Enable the image draggable interaction
        /*$image.draggable({
          drag: function(event, ui) {
            if (ui.position.left != ui.originalPosition.left) {
              $viewport.observator.notify('crop_x', $viewport.observator.crop_position_x());
            }
            if (ui.position.top != ui.originalPosition.top) {
              $viewport.observator.notify('crop_y', $viewport.observator.crop_position_y());
            }
          }
        });*/
    
        var crop_drag = function(event, ui){
            
              $crop.attr("crop_x",ui.position.left * $image.scale_bar);  
              $viewport.observator.notify('crop_x', ui.position.left  * $image.scale_bar);

              $crop.attr("crop_y",ui.position.top * $image.scale_bar);  
              $viewport.observator.notify('crop_y', ui.position.top * $image.scale_bar);
              
        }
        
        var crop_resize = function(event, ui){
                $crop.attr("crop_width",ui.size.width * $image.scale_bar);
                $viewport.observator.notify('crop_width', ui.size.width * $image.scale_bar);
 
                $crop.attr("crop_height",ui.size.height * $image.scale_bar);  
                $viewport.observator.notify('crop_height', ui.size.height * $image.scale_bar);
        }

        // Build the crop element
        var $crop = $('<div crop_width="" crop_height="" crop_x="" crop_y="" class="jrac_crop"><div class="jrac_crop_drag_handler"></div></div>')
        .css({
          'width': settings.crop_width,
          'height': settings.crop_height,
          'left':settings.crop_x+settings.viewport_content_left,
          'top':settings.crop_y+settings.viewport_content_top
        }).attr("crop_width",settings.crop_width)
        .attr("crop_height",settings.crop_height)
        .attr("crop_x",settings.crop_x+settings.viewport_content_left)
        .attr("crop_y",settings.crop_y+settings.viewport_content_top)
        .draggable({
          containment: $viewport,
          handle: 'div.jrac_crop_drag_handler',
          drag: crop_drag
        });
        if (settings.crop_resize) {
          $crop.resizable({
            containment: $viewport,
            aspectRatio: settings.crop_aspect_ratio,
            minWidth: settings.crop_aspect_width_min/$image.scale_bar,
            maxWidth: settings.crop_aspect_width_max/$image.scale_bar,
            minHeight: settings.crop_aspect_height_min/$image.scale_bar,
            maxHeight: settings.crop_aspect_height_max/$image.scale_bar,
            resize: crop_resize
          })
        }
        $viewport.append($crop);

        // Extend viewport witch usefull objects as it will be exposed to user
        // functions interface
        $.extend($viewport, {
          $container: $container,
          $image: $image,
          $crop: $crop,
          // Let me introduce the following Terminator's friend which handle the
          // creation of the viewport events.
          observator: {
            items: {},
            // Register an event with a given element
            register: function(event_name, element, onevent_callback) {
              if (event_name && element) {
                this.items[event_name] = {
                  element: element,
                  callback: onevent_callback
                };
              }
            },
            // Unregister an event
            unregister: function(event_name) {
              delete this.items[event_name];
            },
            // Trigger an event and optionally supply a value
            notify: function(event_name, value) {
              //value = value * $image.scale_bar;
              if (this.items[event_name]) {
                var element = this.items[event_name].element;
                var onevent_callback = this.items[event_name].callback;
                element.trigger(event_name,[$viewport, value]);
                if ($.isFunction(onevent_callback)) {
                  onevent_callback.call($viewport, event_name, element, value);
                }
              }
              $image.trigger('viewport_events',[$viewport]);
            }
            ,
            notify_all: function() {
              this.notify('crop_x', $crop.attr("crop_x")/* + $image.position().left*/);
              this.notify('crop_y', $crop.attr("crop_y")/* + $image.position().top*/);
              this.notify('crop_width', $crop.attr("crop_width"));
              this.notify('crop_height', $crop.attr("crop_height"));
              this.notify('image_width', $image.real_width);
              this.notify('image_height', $image.real_height);
            },
            scale_all: function(){
                
                $image.width(Math.round($image.real_width / $image.scale_bar));
                $image.height(Math.round($image.real_height / $image.scale_bar));
                
                $crop.width(Math.round($crop.attr("crop_width")/$image.scale_bar));
                $crop.height(Math.round ($crop.attr("crop_height")/$image.scale_bar));
                
                $crop.css('left',Math.round(($crop.attr("crop_x") / $image.scale_bar))/* + $image.position().left*/);
                $crop.css('top',Math.round(($crop.attr("crop_y") / $image.scale_bar))/* + $image.position().top*/);
                
                
                if(!$crop.resizable("option", "disabled")){
                $crop.resizable('destroy');
                $crop.resizable({
                    containment: $viewport,
                    aspectRatio: settings.crop_aspect_ratio,
                    minWidth: settings.crop_aspect_width_min / $image.scale_bar,
                    maxWidth: settings.crop_aspect_width_max / $image.scale_bar,
                    minHeight: settings.crop_aspect_height_min / $image.scale_bar,
                    maxHeight: settings.crop_aspect_height_max / $image.scale_bar,
                    resize: crop_resize
                });                
                }
                $viewport.observator.notify_all();

            },
            // Return crop x position relative to $image
            crop_position_x: function() {
              return $crop.position().left;// - $image.position().left;
            },
            // Return crop y position relative to $image
            crop_position_y: function() {
              return $crop.position().top;// - $image.position().top;
            },
            // Does the crop is completely inside the image?
            crop_consistent: function() {
              return parseInt($crop.attr("crop_x"))/* + $image.position().left*/>=0 
                  && parseInt($crop.attr("crop_y"))/* + $image.position().top*/>=0
              && parseInt($crop.attr("crop_x"))/* + $image.position().left*/ + parseInt($crop.attr("crop_width"))<=$image.real_width
              && parseInt($crop.attr("crop_y"))/* + $image.position().top*/ + parseInt($crop.attr("crop_height"))<=$image.real_height;                
            },
            // Set a property (which his name is one of the event) with a given
            // value then notify this operation
            set_property: function(that, value) {

              if (isNaN(value)) {
                return;
              }
              var real_value = parseInt(value)
              var scaled_value = real_value / $image.scale_bar;              
              switch (that) {
                case 'crop_x':
                  $crop.attr("crop_x",real_value);    
                  $crop.css('left',(scaled_value)/*+ $image.position().left*/);
                  break;
                case 'crop_y':
                  $crop.attr("crop_y",real_value);    
                  $crop.css('top',(scaled_value)/*+ $image.position().top*/);
                  break;
                case 'crop_width':
                  $crop.attr("crop_width",real_value);  
                  $crop.width(scaled_value);
                  break;
                case 'crop_height':
                  $crop.attr("crop_height",real_value);  
                  $crop.height(scaled_value);
                  break;
                case 'image_width':
                  if ($image.scale_proportion_locked) {
                    var real_image_height = Math.round(real_value/$image.originalRatio) ;
                    var scaled_image_height = Math.round(real_value/$image.originalRatio/$image.scale_bar) ;
                    $image.height(scaled_image_height);
                    $image.real_height = real_image_height;
                    
                    this.notify('image_height', real_image_height);
                  }
                  $image.width(scaled_value);
                  $image.real_width = real_value;
                  $zoom_widget.slider('value', real_value);
                  break;
                case 'image_height':
                  if ($image.scale_proportion_locked) {  
                    var real_image_width = Math.round(real_value*$image.originalRatio);
                    var scaled_image_width = Math.round(real_value*$image.originalRatio/$image.scale_bar);
                    $image.width(scaled_image_width);
                    $image.real_width = real_image_width;
                    
                    this.notify('image_width', real_image_width);
                    $zoom_widget.slider('value', real_image_width);
                  }
                  $image.height(scaled_value);
                  $image.real_height = real_value;
                  break;
              }
              this.notify(that, real_value);
            },
            check_before_set_property: function(that, value){
              if (isNaN(value)) {
                return false;
              }else{
                  

                  switch (that) {
                    case 'crop_width':
                        if(value >= parseInt(settings.crop_aspect_width_min) && value <= parseInt(settings.crop_aspect_width_max)){
                            if(settings.crop_aspect_ratio){
                                $viewport.observator.set_property('crop_height',Math.round(value/settings.crop_aspect_ratio))
                                return true;
                            }else
                                return true;
                        }else
                            return false;

                        break;
                    case 'crop_height':
                        if(value >= parseInt(settings.crop_aspect_height_min) && value <= parseInt(settings.crop_aspect_height_max))
                            if(settings.crop_aspect_ratio){
                                $viewport.observator.set_property('crop_width',Math.round(value*settings.crop_aspect_ratio))
                                return true;
                            }else
                                return true;
                        else
                            return false;
                        break;
                    case 'crop_x':
                        if(value>=0)
                            return true;
                        else
                            return false;
                        break;
                    case 'crop_y':
                        if(value>=0)
                            return true;
                        else
                            return false;
                        break;                        
                    case 'image_width':
                        if(value >= 1 && value <= parseInt($image.originalWidth))
                            return true;
                        else
                            return false;
                        break;
                    case 'image_height':
                        if(value >= 1 && value <= parseInt($image.originalHeight))
                            return true;
                        else
                            return false;
                        break;
                    default:
                        return true;  
                        break;
                  }
                  
              }
            },
            set_sizes: function(ratio_width, ratio_height,width_min,width_max,height_min,height_max){
                if (settings.crop_resize) {

                  if(ratio_width == "" || ratio_height == "" || isNaN(ratio_width) || isNaN(ratio_height))
                      settings.crop_aspect_ratio = false;
                  else
                      settings.crop_aspect_ratio = parseFloat(ratio_width/ratio_height);

                  settings.crop_aspect_width_min = parseInt(width_min);
                  settings.crop_aspect_width_max = parseInt(width_max);
                  settings.crop_aspect_height_min = parseInt(height_min);
                  settings.crop_aspect_height_max = parseInt(height_max);
                  
                  $crop.attr("crop_width",settings.crop_aspect_width_min);  
                  $crop.width(settings.crop_aspect_width_min/ $image.scale_bar);
                  $crop.attr("crop_height",settings.crop_aspect_height_min);  
                  $crop.height(settings.crop_aspect_height_min/ $image.scale_bar);     
                  $viewport.observator.notify_all();
                  if(settings.crop_aspect_width_min == settings.crop_aspect_width_max &&
                    settings.crop_aspect_height_min == settings.crop_aspect_height_max)    
                      $crop.resizable("option", "disabled", true);   
                  else{
                    
                    $crop.resizable("option", "disabled", false);   
                    $crop.resizable('destroy');
                    $crop.resizable({
                        containment: $viewport,
                        aspectRatio: settings.crop_aspect_ratio,
                        minWidth: settings.crop_aspect_width_min / $image.scale_bar,
                        maxWidth: settings.crop_aspect_width_max / $image.scale_bar,
                        minHeight: settings.crop_aspect_height_min / $image.scale_bar,
                        maxHeight: settings.crop_aspect_height_max / $image.scale_bar,
                        resize: crop_resize
                    });
                    
                  }
                }
            }
          }
        });        

        // Hide the loading notice
        $loading.hide();

        // Finally display the image
        $image.show();
        
        // Trigger the viewport_onload callback
        if ($.isFunction(settings.viewport_onload)) {
          settings.viewport_onload.call($viewport);
          $viewport.observator.notify_all();
        }
      });
    });
  };
})( jQuery );
