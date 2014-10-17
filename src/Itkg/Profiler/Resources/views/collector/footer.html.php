<script type="text/javascript">
    $('.tooltip').tooltip();
    $("table").tablesorter();
    $("#accordion").accordion({ collapsible: true, active: false, autoHeight: false, heightStyle: "content" });
    $('.collector-clear-link').clearCollector('#collector-content', '#archives');
    $('.collector-archive-link').archiveCollector('#collector-content', '#archives');
    $('#archives').changeArchiveCollector('#collector-content');
</script>
