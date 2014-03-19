(function( $ ){
  $.fn.getS3Files = function() {
      this.each(function() {
        var $input_select = $(this);
        if($input_select.attr("source")){
            var previous_value = $input_select.val();
            $input_select.html("");
            $input_select.append("<option value=''>Loading...</option>");            
            $.ajax(
                { url : $input_select.attr("source"),
                  type: "POST",
                  success : function(data) {                
                    $input_select.html("");
                    $input_select.append("<option value=''>No File</option>");
                    $.each(data, function(key, file) {
                        $input_select.append("<option value='" + file.filename + "' >" + file.filename + "</option>");
                    });        
                    if(previous_value){
                        $input_select.val(previous_value);
                    } 
                  },
                  async : true,
                  dataType : "JSON"
                }
            );  
        }
      });
  };
})( jQuery );