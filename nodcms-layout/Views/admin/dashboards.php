<?php $this->addJsFile("assets/plugins/flot/jquery.flot.min"); ?>
<?php $this->addJsFile("assets/plugins/flot/jquery.flot.resize.min"); ?>
<?php $this->addJsFile("assets/plugins/flot/jquery.flot.categories.min"); ?>
<?php $this->addJsFile("assets/plugins/flot/jquery.flot.pie.min"); ?>
<?php $this->addJsFile("assets/nodcms/js/handel-charts"); ?>

<?php $this->addJsFile("assets/nodcms/js/ajaxlist"); ?>
<?php echo $this->render("general_links"); ?>
<?php if(isset($dashboards) && count($dashboards)!=0){ ?>
    <div id="dashboards">
        <?php foreach($dashboards as $key=>$item){ ?>
            <div class="card portlet-sortable" data-id="<?php echo $item['package_id']; ?>">
                <div class="card-header portlet-sortable-title d-flex justify-content-between">
                    <div class="btn">
                        <i class="fas fa-sort"></i> <?php echo $item['package_name']; ?>
                    </div>
                    <div>
                        <a class="btn btn-default package-toggle-active" data-url="<?php echo ADMIN_URL."packageToggleActive/".$item['package_id']; ?>" data-toggle="collapse" href="#portlet<?php echo $item['package_id']; ?>" role="button" aria-expanded="false" aria-controls="dashboards">
                            <i class="far <?php echo $item['active']==1?"fa-minus-square":"fa-plus-square"; ?>"></i>
                        </a>
                    </div>
                </div>
                <div id="portlet<?php echo $item['package_id']; ?>" class="card-body collapse <?php echo $item['active']==1?"show":""; ?>">
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
                        theElement.find('i.far').toggleClass('fa-minus-square fa-plus-square');
                        return false;
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    toastr.error('Send form with ajax failed!', translate('Error'));
                    theElement.find('i.far').toggleClass('fa-minus-square fa-plus-square');
                    return false;
                },
                beforeSend: function () {
                    theElement.find('i.far').toggleClass('fa-minus-square fa-plus-square');
                }
            });
        });
    });

</script>
