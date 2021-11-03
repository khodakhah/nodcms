<section class="card">
    <div class="card-header">
        <div class="text-right">
            <button type="button" data-url="<?php echo ADMIN_URL."languageSortSubmit"; ?>" id="save-sort-language" class="save-sort btn green hidden"><?php echo _l("Save sort", $this); ?></button>
            <a href="<?php echo ADMIN_URL; ?>languageSubmit" class="btn btn-primary"><?=_l("Add New",$this)?> <i class="fa fa-plus"></i></a>
            <?php if(isset($key_changes["new"]) && count($key_changes["new"])!=0){ ?>
                <button type="button" data-toggle="modal" data-target="#updateTransaction" class="btn yellow-gold">
                    <i class="icon-plus"></i>
                    <?php echo str_replace("{data}", count($key_changes["new"])+count($key_changes["removed"]), _l("{data} translation changes", $this)); ?>
                </button>
            <?php } ?>
        </div>
    </div>
    <div class="card-body">
        <div class="data_list dd" id="data_list_language" data-output="#output_language" data-savebtn="#save-sort-language" data-key="language">
            <?php if(isset($data_list) && count($data_list)!=0){ ?>
                <ol class="dd-list">
                    <?php $i=0; foreach($data_list as $item){ $i++; ?>
                        <li id="item<?php echo $item['language_id']; ?>" class="dd-item dd3-item" data-id="<?php echo $item["language_id"]; ?>" data-public="<?php echo $item["public"]; ?>">
                            <div class="dd-handle dd3-handle"> </div>
                            <div class="dd3-content">
                                <?php if($item["image"]!=''){ ?>
                                    <img style="height: 18px;" src="<?php echo base_url($item["image"]); ?>">
                                <?php } ?>
                                <?=$item["language_title"]?> <?=$item["default"]==1?"("._l('Default',$this).")":""?>
                                <button type="button" class="btn btn-xs btn-link font-red" data-target="#item<?php echo $item['language_id']; ?>" onclick="$(this).removeAnItemFromList('<?php echo ADMIN_URL."languageDelete/$item[language_id]"; ?>');"><i class="fa fa-trash-o"></i> <?=_l('Delete',$this)?></button>
                                <a class="btn btn-xs btn-link font-blue" href="<?php ADMIN_URL; ?>languageSubmit/<?=$item["language_id"]?>"><i class="fa fa-pencil"></i> <?=_l('Edit',$this)?></a>
                                <a class="btn btn-xs btn-link font-blue-steel" href="<?php ADMIN_URL; ?>languageTranslation/<?=$item["language_id"]?>"><i class="fa fa-language"></i> <?php echo _l('Translation',$this); ?></a>
                            </div>
                        </li>
                    <?php } ?>
                </ol>
                <textarea id="output_language" class="form-control hidden"></textarea>
            <?php }else{ ?>
                <div class="dd-empty"></div>
            <?php } ?>
        </div>

    </div>
</section>

<?php if(isset($key_changes["new"]) && count($key_changes["new"])!=0){ ?>
    <div class="modal fade" id="updateTransaction" tabindex="-1" role="dialog" aria-labelledby="updateTransactionLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="updateTransactionLabel"><?php echo _l("Changes of translation keys", $this); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div style="max-height:500px;overflow-y: scroll;">
                        <h4 class="font-blue"><?php echo str_replace("{data}", count($key_changes["new"]), _l("{data} new translation keys.", $this)); ?></h4>
                        <ol>
                            <?php foreach($key_changes["new"] as $item){ ?>
                                <li class="font-blue"><?php echo $item; ?></li>
                            <?php } ?>
                        </ol>
                        <h4 class="font-red"><?php echo str_replace("{data}", count($key_changes["removed"]), _l("{data} removed translation keys.", $this)); ?></h4>
                        <ol>
                            <?php foreach($key_changes["removed"] as $item){ ?>
                                <li class="font-red"><?php echo $item; ?></li>
                            <?php } ?>
                        </ol>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l("Close", $this); ?></button>
                    <a href="<?php echo ADMIN_URL."languageUpdateTranslation"; ?>" class="btn btn-primary"><?php echo _l("Update", $this); ?></a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php $this->addCssFile("assets/plugins/jquery-nestable/jquery.nestable"); ?>
<?php $this->addJsFile("assets/plugins/jquery-nestable/jquery.nestable"); ?>
<script>
    $(function () {
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target);
            var output = $(list.data('output'));
            var saveSortBtn = $(list.data('savebtn'));
            // Update new sort in out-put textarea
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
                saveSortBtn.removeClass('hidden');
            } else {
                toastr.error('JSON browser support required for this action.', 'Error');
            }
        };

        $('.save-sort').click(function() {
            var saveSortBtn = $(this);
            var output = $('#output_language');
            $.ajax({
                url: saveSortBtn.data('url'),
                data: {'data':output.val()},
                type:'post',
                dataType: 'json',
                beforeSend: function () {
                    saveSortBtn.addClass('disabled').prepend($("<i class='fas fa-spinner fa-pulse fa-fw'></i>"));
                },
                complete:function () {
                    saveSortBtn.removeClass('disabled').find('i').remove();
                },
                success: function (resullt) {
                    if(resullt.status=='error') {
                        toastr.error(resullt.error, translate('Error'));
                    }else if(resullt.status=='success'){
                        output.addClass('hidden').val('');
                        saveSortBtn.addClass('hidden');
                        toastr.success(resullt.msg, translate('Success'));
                    }
                },
                error: function (xhr, status, error) {
                    $.showInModal(translate('Error')+': '+translate('Ajax failed!'), '<div class="alert alert-danger">' +
                        '<h4>'+translate('Error')+'</h4>' +
                        error +
                        '</div>' +
                        '<h4>'+translate('Result')+'</h4>' +
                        xhr.responseText);
                }
            });
        });

        $('.data_list').nestable({
            maxDepth: 1
        }).on('change', updateOutput);
    });
</script>