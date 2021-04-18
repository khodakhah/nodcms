<div class="portlet">
    <div class="portlet-title">
        <div class="actions">
            <button type="button" id="save-sort" class="btn green hidden"><?php echo _l("Save new sorts", $this); ?></button>
            <button type="button" onclick="$('#data_list').nestable('collapseAll');" class="btn default" title="<?php echo _l("Collapse All", $this); ?>"><i class="fa fa-compress"></i></button>
            <button type="button" onclick="$('#data_list').nestable('expandAll');" class="btn default" title="<?php echo _l("Expand All", $this); ?>"><i class="fa fa-expand"></i></button>
        </div>
    </div>
    <div class="portlet-body">
        <?php if(isset($data_list) && count($data_list)!=0){ ?>
            <div class="dd" id="data_list">
                <ol class="dd-list">
                    <?php $i=0; foreach($data_list as $item){ $i++; ?>
                        <li class="dd-item dd3-item" data-id="<?php echo $item["package_id"]; ?>" data-active="<?php echo $item["active"]; ?>">
                            <div class="dd-handle dd3-handle"> </div>
                            <div class="dd3-content">
                                <?php echo $item["package_name"]; ?>
                                <button type="button" data-url="<?php echo ADMIN_URL."settingsHomepageToggleActive/$item[package_id]"; ?>" data-id="<?=$item["package_id"]?>" class="btn btn-xs btn-link font-grey-gallery pull-right visibility" title="<?=_l('Visibility',$this)?>"><i class="fa <?php echo (isset($item['active'])&&$item['active']==1)?"fa-eye":"fa-eye-slash"; ?>"></i></button>
                            </div>
                        </li>
                    <?php } ?>
                </ol>
            </div>
        <?php }else{ ?>
            <div class="note note-info"><i class="fa fa-exclamation"></i> <?php echo _l("Empty",$this); ?></div>
        <?php } ?>
    </div>
</div>
<textarea id="output" class="form-control hidden"></textarea>
<?php $this->render("homepage_sort_includes"); ?>
<script>
    $(function () {
        var output = $('#output');
        var saveSortBtn = $('#save-sort');
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target);
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
                saveSortBtn.removeClass('hidden');
//                output.removeClass('hidden');
            } else {
                toastr.error('JSON browser support required for this action.', 'Error');
            }
        };

        saveSortBtn.click(function() {
            $.ajax({
                url: '<?php echo ADMIN_URL; ?>settingsHomepageSort',
                data: {'data':output.val()},
                method:'post',
                dataType: 'json',
                beforeSend: function () {
                    saveSortBtn.addClass('disabled').prepend($("<i class='fa fa-spinner fa-pulse fa-fw'></i>"));
                },
                complete:function () {
                    saveSortBtn.removeClass('disabled').find('i').remove();
                },
                success: function (resullt) {
                    if(resullt.status=='error') {
                        toastr.error(resullt.error, '<?php echo _l("Error", $this)?>');
                    }else if(resullt.status=='success'){
                        output.addClass('hidden').val('');
                        saveSortBtn.addClass('hidden');
                        toastr.success(resullt.msg, '<?php echo _l("Success", $this)?>');
                    }
                },
                error:function (xhr, status, error) {
                    $.showInModal(translate('Error')+': '+translate('Ajax failed!'), '<div class="alert alert-danger">' +
                        '<h4>'+translate('Error')+'</h4>' +
                        error +
                        '</div>' +
                        '<h4>'+translate('Result')+'</h4>' +
                        xhr.responseText);
                }
            });
        });

        $('#data_list').nestable({
            maxDepth: 1
        }).on('change', updateOutput);

        $('.visibility').click(function () {
            var TheElement = $(this);
            var toggleIcon = function (result) {
                TheElement.find('i').toggleClass('fa-eye fa-eye-slash');
            };
            TheElement.ajaxActionButton(TheElement.data('url'),toggleIcon);
        });
    });
</script>
