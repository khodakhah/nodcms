<div class="card">
    <div class="card-header">
        <div class="text-right">
            <button type="button" id="save-sort" class="btn btn-success hidden"><i class="far fa-save"></i> <?php echo _l("Save new sorts", $this); ?></button>
            <a href="<?php echo ARTICLES_ADMIN_URL; ?>articleForm" class="btn blue load-form">
                <i class="fa fa-plus"></i> <?php echo _l("Add",$this); ?>
            </a>
            <button type="button" onclick="$('#data_list').nestable('collapseAll');" class="btn default" title="<?php echo _l("Collapse All", $this); ?>"><i class="fa fa-compress"></i></button>
            <button type="button" onclick="$('#data_list').nestable('expandAll');" class="btn default" title="<?php echo _l("Expand All", $this); ?>"><i class="fa fa-expand"></i></button>
        </div>
    </div>
    <div class="card-body">
        <?php if(isset($data_list) && count($data_list)!=0){ ?>
            <div class="dd" id="data_list">
                <ol class="dd-list">
                    <?php $i=0; foreach($data_list as $item){ $i++; ?>
                        <li class="dd-item dd3-item" data-id="<?php echo $item["article_id"]; ?>" data-public="<?php echo $item["public"]; ?>">
                            <div class="dd-handle dd3-handle"> </div>
                            <div class="dd3-content">
                                <?php echo $item["name"]; ?>
                                <a href="javascript: $(this).removeItem('<?php echo ARTICLES_ADMIN_URL; ?>articleRemove/<?=$item["article_id"]?>', <?=$item["article_id"]?>);" class="btn btn-xs btn-link font-red pull-right btn-ask"><i class="fa fa-trash-o"></i> <?=_l('Delete',$this)?></a>
                                <a href="<?php echo ARTICLES_ADMIN_URL; ?>articleForm/<?=$item["article_id"]?>" class="btn btn-xs btn-link font-blue pull-right load-form"><i class="fa fa-pencil"></i> <?=_l('Edit',$this)?></a>
                                <button type="button" data-href="<?php echo ARTICLES_ADMIN_URL; ?>articleVisibility/<?=$item["article_id"]?>" data-id="<?=$item["article_id"]?>" class="btn btn-xs btn-link font-grey-gallery pull-right visibility" title="<?=_l('Visibility',$this)?>"><i class="fa <?php echo (isset($item['public'])&&$item['public']==1)?"fa-eye":"fa-eye-slash"; ?>"></i></button>
                            </div>
                            <?php if(isset($item['sub_data']) && count($item['sub_data'])!=0){ ?>
                                <ol class="dd-list">
                                    <?php $i=0; foreach($item['sub_data'] as $item2){ $i++; ?>
                                        <li class="dd-item dd3-item" data-id="<?php echo $item2["article_id"]; ?>" data-public="<?php echo $item2["public"]; ?>">
                                            <div class="dd-handle dd3-handle"> </div>
                                            <div class="dd3-content">
                                                <?php echo $item2["name"]; ?>
                                                <a href="javascript: $(this).removeItem('<?php echo ARTICLES_ADMIN_URL; ?>articleRemove/<?=$item2["article_id"]?>', <?=$item2["article_id"]?>);" class="btn btn-xs btn-link font-red pull-right btn-ask"><i class="fa fa-trash-o"></i> <?=_l('Delete',$this)?></a>
                                                <a href="<?php echo ARTICLES_ADMIN_URL; ?>articleForm/<?=$item2["article_id"]?>" class="btn btn-xs btn-link font-blue pull-right load-form"><i class="fa fa-pencil"></i> <?=_l('Edit',$this)?></a>
                                                <button type="button" data-href="<?php echo ARTICLES_ADMIN_URL; ?>articleVisibility/<?=$item2["article_id"]?>" data-id="<?=$item2["article_id"]?>" class="btn btn-xs btn-link font-grey-gallery pull-right visibility" title="<?=_l('Visibility',$this)?>"><i class="fa fa-eye"></i></button>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ol>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ol>
            </div>
        <?php }else{ ?>
            <div class="alert alert-info"><i class="fa fa-exclamation"></i> <?php echo _l("Empty",$this); ?></div>
        <?php } ?>
    </div>
</div>
<textarea id="output" class="form-control hidden"></textarea>

<?php $this->load->addCssFile("assets/metronic/global/plugins/jquery-nestable/jquery.nestable"); ?>
<?php $this->load->addJsFile("assets/metronic/global/plugins/jquery-nestable/jquery.nestable"); ?>
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

        $.fn.removeItem = function (url, id) {
            var my_btn = $(this);
            if(my_btn.hasClass('btn-ask'))
                return;
            $.ajax({
                url: url,
                dataType: 'json',
                success: function (result) {
                    if(result.status=='error') {
                        toastr.error(resullt.error, '<?php echo _l("Error", $this)?>');
                    }
                },
                error:function () {
                    toastr.error('Ajax request fail!', 'Error');
                }
            });
            $('.dd-item[data-id="'+id+'"]').hide(500, function () {
                $(this).remove();
            });
        };

        saveSortBtn.click(function() {
            $.ajax({
                url: '<?php echo ARTICLES_ADMIN_URL; ?>articleSort',
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
                error:function () {
                    toastr.error('Ajax request fail!', 'Error');
                }
            });
        });

        $('#data_list').nestable({
            maxDepth: 2
        }).on('change', updateOutput);

        $('.visibility').click(function () {
            var visibility = $(this).parents('.dd-item').data('public');
            if(visibility == 1){
                $('.dd-item[data-id="'+$(this).data('id')+'"]').attr('data-public', 0).data('public', 0);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            }else if(visibility == 0){
                $('.dd-item[data-id="'+$(this).data('id')+'"]').attr('data-public', 1).data('public', 1);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            }else{
                toastr.error('<?php echo _l("Loaded data is not correct. Please reload the page to solve this problem.", $this)?>', '<?php echo _l("Error", $this)?>');
                return;
            }

            $.ajax({
                url: $(this).data('href'),
                data: {'data':visibility},
                method:'post',
                dataType: 'json',
                success: function (resullt) {
                    if(resullt.status=='error')
                        toastr.error(resullt.error, '<?php echo _l("Error", $this)?>');
                },
                error:function () {
                    toastr.error('Ajax request fail!', 'Error');
                }
            });
        });
    });
</script>
