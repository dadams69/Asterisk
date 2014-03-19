jQuery(function(){
    
    $.fn.getStyleObject = function(){
        var dom = this.get(0);
        var style;
        var returns = {};
        if(window.getComputedStyle){
            var camelize = function(a,b){
                return b.toUpperCase();
            }
            style = window.getComputedStyle(dom, null);
            for(var i=0;i<style.length;i++){
                var prop = style[i];
                var camel = prop.replace(/\-([a-z])/, camelize);
                var val = style.getPropertyValue(prop);
                returns[camel] = val;
            }
            return returns;
        }
        if(dom.currentStyle){
            style = dom.currentStyle;
            for(var prop in style){
                returns[prop] = style[prop];
            }
            return returns;
        }
        return this.css();
    }

    $.fn.extend({
        author_search: function(options){

            $(this).each(function(){
                
                var $input_id = $(this);        
                var $input_autocomplete;
                var $div_container;
                var $span_tag;
                var $span_delete;
                var search_url = "/fieldset_author/find_author/";
                
                if(typeof $input_id.attr("placeholder") === "undefined")
                    $input_id.attr("placeholder",'');

                if(typeof $input_id.attr("additional_class") === "undefined")
                    $input_id.attr("additional_class",'');

                $div_container = $("<div class='autocomplete_container' style='display: inline;'></div>");
                $input_autocomplete = $('<input type="text" value="" placeholder="'+ $input_id.attr("placeholder") +'" class="' + $input_id.attr("additional_class") + ' autocomplete input-xlarge" />');
                $span_tag = $("<a class='author_text btn' ></a>");                    
                $span_delete = $("<a class='btn btn-warning' href='#' title='Remove' class='delete_link'>Remove</a>");

                var $div_span_container = $("<div></div>");

                $div_container.append($input_autocomplete);
                $div_span_container.append($span_tag);
                $div_span_container.append($span_delete);
                $div_container.append($div_span_container);
                
                if($input_id.val()!=""){
                    
                        $span_tag.html($("#"+$input_id.attr("ref_display")).val());
                        $span_tag.attr("href", "#");
                        if($input_id.parent().find(".autocomplete_container").size() > 0){
                            $input_id.parent().find(".autocomplete_container").remove();
                        }                                        
                        $input_autocomplete.css("display","none");
                        $div_span_container.css("display","inline-block")
                        $input_id.after($div_container);                        
                    
                }else{
                    if($input_id.parent().find(".autocomplete_container").size() > 0){
                        $input_id.parent().find(".autocomplete_container").remove();
                    }                                    
                    $input_autocomplete.css("display","inline-block");
                    $div_span_container.css("display","none");
                    $input_id.after($div_container);
                    $("#"+$input_id.attr("ref_display")).val("");
                }
                
                var styles = $input_autocomplete.getStyleObject();
                
                var container_styles = {};

                var input_styles = {};
                
                var span_tag_styles = {
                    'line-height' : styles['height'],
                    'height' : styles['height'],
                    'width' : (parseInt(styles['width'],10) - 60) + "px",
                    'font-size' : styles['fontSize'],
                    'border-radius': '4px 0px 0px 4px',
                    'font-size': styles['fontSize'],
                    //'margin': styles['marginTop'] + ' ' + styles['marginRight'] + ' ' + styles['marginBottom'] + ' ' + styles['marginLeft'],
                    'padding': styles['paddingTop'] + ' ' + styles['paddingRight'] + ' ' + styles['paddingBottom'] + ' ' + styles['paddingLeft'],
                    'text-align': 'left',
                    'vertical-align': 'middle',
                    'display': 'inline-block',
                    'overflow': 'hidden'
                }

                var span_delete_styles = {
                    'line-height' : styles['height'],
                    'height' : styles['height'],
                    'width' : "50px",
                    'font-size' : styles['fontSize'],
                    'border-radius': '0px 4px 4px 0px',
                    'font-size': styles['fontSize'],
                    //'margin': styles['marginTop'] + ' ' + styles['marginRight'] + ' ' + styles['marginBottom'] + ' ' + styles['marginLeft'],
                    'padding': styles['paddingTop'] + ' ' + styles['paddingRight'] + ' ' + styles['paddingBottom'] + ' ' + styles['paddingLeft'],
                    'text-align': 'center',
                    'vertical-align': 'middle',
                    'display': 'inline-block'
                }

                $div_container.css(container_styles);
                $input_autocomplete.css(input_styles);
                $span_tag.css(span_tag_styles);
                $span_delete.css(span_delete_styles);
		
                $input_autocomplete.autocomplete({
                    source: search_url,
                    minLength: 1,
                    select: function(event, ui) {
                        var selectedObj = ui.item;
                        $input_id.val(selectedObj.id).trigger('change');
                        $span_tag.html(selectedObj.display_name);
                        $span_tag.attr("href", "#");
                        $input_autocomplete.css("display","none");
                        $div_span_container.css("display","inline-block");                    
                        
                        $("#"+$input_id.attr("ref_display")).val(selectedObj.display_name)
                        $input_id.parents("div.item_list").find("input.author_display_name").val(selectedObj.display_name);
                        $input_id.parents("div.item_list").find("input.author_position").val(selectedObj.position);
                        //if(options.after_select !== undefined) options.after_select();
                        event.preventDefault();
                   }
                }).data("autocomplete")._renderItem = function (ul, item) {  
                    var $row = $("<li></li>")
                        .data("item.autocomplete", item)
                        .append(
                            "<a href='#'>" +
                                 item.label
                            + "</a>")
                        .appendTo(ul);
                        
                    return $row;
                };  
                
                $span_delete.click(function(){
                    $input_id.val("").trigger('change');
                    $input_autocomplete.val("");
                    $span_tag.attr("href","#");
                    $span_tag.html("");
                    
                    $input_autocomplete.css("display","inline-block");
                    $div_span_container.css("display","none");                        

                    $("#"+$input_id.attr("ref_display")).val("");
                    $input_id.parents("div.item_list").find("input.author_display_name").val("");
                    $input_id.parents("div.item_list").find("input.author_position").val("");
                    //if(options.after_change !== undefined) options.after_change();                    
                    return false;
                });
                
                $span_tag.click(function(event){
                    event.preventDefault();
                })

            });
        }
    });
});

