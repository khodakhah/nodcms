<div class="card">
    <div class="card-header">
        <div class="text-right">
            <button type="button" id="save-sort" class="btn btn-success hidden"><i class="far fa-save"></i> <?php echo _l("Save new sorts", $this); ?></button>
            <a href="<?php echo ABOUT_ADMIN_URL; ?>profileForm" class="btn blue load-form">
                <i class="fa fa-plus"></i> <?php echo _l("Add",$this); ?>
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if(isset($data_list) && count($data_list)!=0){ ?>
            <div class="dd" id="data_list">
                <ol class="dd-list">
                    <?php $i=0; foreach($data_list as $item){ $i++; ?>
                        <li class="dd-item dd3-item" data-id="<?php echo $item["profile_id"]; ?>" data-public="<?php echo $item["public"]; ?>">
                            <div class="dd-handle dd3-handle"> </div>
                            <div class="dd3-content">
                                <?php echo $item["profile_name"]; ?>
                                <a href="javascript: $(this).removeItem('<?php echo ABOUT_ADMIN_URL; ?>profileRemove/<?php echo $item["profile_id"]; ?>', <?=$item["profile_id"]?>);" class="btn btn-xs btn-link font-red pull-right"><i class="fa fa-trash-o"></i> <?=_l('Delete',$this)?></a>
                                <a href="<?php echo ABOUT_ADMIN_URL; ?>profileForm/<?=$item["profile_id"]?>" class="btn btn-xs btn-link font-blue pull-right load-form"><i class="fa fa-pencil"></i> <?=_l('Edit',$this)?></a>
                                <button type="button" data-href="<?php echo ABOUT_ADMIN_URL; ?>profileVisibility/<?=$item["profile_id"]?>" data-id="<?=$item["profile_id"]?>" class="btn btn-xs btn-link font-grey-gallery pull-right visibility" title="<?=_l('Visibility',$this)?>"><i class="fa <?php echo (isset($item['public'])&&$item['public']==1)?"fa-eye":"fa-eye-slash"; ?>"></i></button>
                                <a target="_blank" href="<?php echo base_url("{$this->language['code']}/about-{$item["profile_uri"]}"); ?>" class="btn btn-xs btn-link font-grey-gallery pull-right" title="<?=_l('Display',$this)?>"><i class="fa fa-link"></i></a>
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

<?php $this->addCssFile("assets/plugins/jquery-nestable/jquery.nestable"); ?>
<?php $this->addJsFile("assets/plugins/jquery-nestable/jquery.nestable"); ?>
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
            $.loadConfirmModal(url, function (result, mymodel) {
                $('.dd-item[data-id="'+id+'"]').hide(500, function () {
                    $(this).remove();
                });
                mymodel.modal('hide');
            });
        };

        saveSortBtn.click(function() {
            $.ajax({
                url: '<?php echo ABOUT_ADMIN_URL; ?>profileSort',
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
            maxDepth: 1
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
