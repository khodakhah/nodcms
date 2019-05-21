<?php $this->load->addJsFile("assets/plugins/flot/jquery.flot.min"); ?>
<?php $this->load->addJsFile("assets/plugins/flot/jquery.flot.resize.min"); ?>
<?php $this->load->addJsFile("assets/plugins/flot/jquery.flot.categories.min"); ?>
<?php $this->load->addJsFile("assets/plugins/flot/jquery.flot.pie.min"); ?>
<?php $this->load->addJsFile("assets/nodcms/js/handel-charts"); ?>

<?php $this->load->addJsFile("assets/nodcms/js/ajaxlist"); ?>
<?php $this->load->view($this->mainTemplate."/general_links"); ?>
<?php if(isset($dashboards) && count($dashboards)!=0){ ?>
    <div id="dashboards">
        <?php foreach($dashboards as $key=>$item){ ?>
            <div class="card portlet-sortable" data-id="<?php echo $item['package_id']; ?>">
                <div class="card-header portlet-sortable-title">
                    <div class="caption"><i class="fas fa-sort"></i> <?php echo $item['package_name']; ?></div>
                    <div class="tools">
                        <a href="javascript;" class="package-toggle-active <?php echo $item['active']==1?"collapse":"expand"; ?>" data-original-title="" title="" data-url="<?php echo ADMIN_URL."packageToggleActive/".$item['package_id']; ?>"> </a>
                    </div>
                </div>
                <div class="card-body <?php echo $item['active']==1?"":"portlet-collapsed"; ?>">
                    <?php echo $item['content']; ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<script>
    $(function () {
        $("#dashboards").sortable({
            items: ".portlet-sortable",
            opacity: 0.8,
            handle : '.portlet-sortable-title',
            coneHelperSize: true,
            placeholder: 'portlet-sortable-placeholder',
            tolerance: "pointer",
            forcePlaceholderSize: !0,
            helper: "clone",
            cancel: ".portlet-sortable-empty, .portlet-fullscreen", // cancel dragging if portlet is in fullscreen mode
            revert: true,
            update: function(event, ui ) {
                var newSort = {};
                $("#dashboards").find('.portlet-sortable').each(function () {
                    newSort[$(this).index()] = $(this).data('id');
                });
                $.ajax({
                    url:'<?php ADMIN_URL; ?>',
                    dataType: 'json',
                    type: 'post',
                    data: {'data':newSort},
                    success: function (data) {
                        if(data.status=="error"){
                            toastr.error(data.error, translate('Error'));
                            return false;
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                        toastr.error('Send form with ajax failed!', translate('Error'));
                        return false;
                    }
                });
            }
        });
        $('.package-toggle-active').click(function () {
            var theElement = $(this);
            $.ajax({
                url: theElement.data('url'),
                dataType: 'json',
                success: function (data) {
                    if(data.status=="error"){
                        toastr.error(data.error, translate('Error'));
                        return false;
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    toastr.error('Send form with ajax failed!', translate('Error'));
                    return false;
                }
            });
        });
    });

</script>
