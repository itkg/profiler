(function ( $ ) {

    $.fn.loadCollector = function(content_id) {

        this.click(function() {
            collector = $(this).attr('rel');
            $(this).parent().addClass('selected');
            $(this).parent().parent().children().removeClass('selected');
            $.ajax({
               'url': '/_/profiler/'+collector,
                dataType: 'html',
                success : function(data){
                    $(content_id).html(data);
                }
            });
        });
        return this;
    };

    $.fn.clearCollector = function(content_id) {

        this.click(function() {
            collector = $(this).attr('rel');
            $.ajax({
                'url': '/_/profiler/'+collector+'?action=clear',
                dataType: 'html',
                success : function(data){
                    $(content_id).html(data);
                }
            });
        });
        return this;
    };


}( jQuery ));