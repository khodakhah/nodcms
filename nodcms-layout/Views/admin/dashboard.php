<div id="dashboard-content"></div>
<script>
    $(function () {
        var load_pages = <?php echo $load_pages; ?>;
        $.each(load_pages,function (key,value) {
            var $div = $("<div><i class='fa fa-spinner fa-pals'></i> <?php echo _l("Loading...", $this)?></div>");
            $div.appendTo($('#dashboard-content'));
            $div.load(value);
        });
    });
</script>