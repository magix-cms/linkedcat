var linkedcat = (function ($, undefined) {
    'use strict';
    return {
        run: function(controller){
            $( ".ui-sortable" ).sortable({
                items: "> tr",
                cursor: "move",
                axis: "y",
                update: function(){
                    var serial = $( ".ui-sortable" ).sortable('serialize');
                    $.jmRequest({
                        handler: "ajax",
                        url: controller+'&action=order',
                        method: 'POST',
                        data : serial,
                        success:function(e){
                            $.jmRequest.initbox(e,{
                                    display: false
                                }
                            );
                        }
                    });
                }
            });
            $( ".ui-sortable" ).disableSelection();
        }
    };
})(jQuery);