(function ( $ ) {

    $.fn.loadCollector = function(content_id) {

        this.click(function() {
            collector = $(this).attr('rel');
            $(this).parent().parent().children().removeClass('selected');
            $(this).parent().addClass('selected');

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

    $.fn.clearCollector = function(content_id, archive_list) {


        this.click(function() {
            var current_archive = $(archive_list).children('option:selected').val();

            collector = $(this).attr('rel');
            $.ajax({
                'url': '/_/profiler/'+collector+'?action=clear&archive='+current_archive,
                dataType: 'html',
                success : function(data){
                    $(content_id).html(data);
                }
            });
        });
        return this;
    };

    $.fn.archiveCollector = function(content_id, archive_list) {

        this.click(function() {
            var current_archive = $(archive_list).children('option:selected').val();
            collector = $(this).attr('rel');
            $.ajax({
                'url': '/_/profiler/'+collector+'?action=archive&archive='+current_archive,
                dataType: 'html',
                success : function(data){
                    $(content_id).html(data);
                }
            });
        });
        return this;
    };

    $.fn.changeArchiveCollector  = function(content_id) {

        this.change(function() {
            var current_archive = this.options[this.selectedIndex].value;
            collector = $(this).attr('rel');
            $.ajax({
                'url': '/_/profiler/'+collector+'?action=change&archive='+current_archive,
                dataType: 'html',
                success : function(data){
                    $(content_id).html(data);
                }
            });
        });
        return this;
    };

}( jQuery ));