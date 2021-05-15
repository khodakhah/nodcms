<div class="row">
    <?php if(isset($menu_types) && count($menu_types)!=0){ ?>
        <?php foreach($menu_types as $menu_type){ ?>
            <div class="<?php echo count($menu_types) > 1 ? "col-md-6" : "col-md-12" ?>">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4 bold"><h4><?php echo $menu_type['title']; ?></h4></div>
                            <div class="col-md text-right">
                                <button type="button" data-key="<?php echo $menu_type['key']; ?>" id="save-sort-<?php echo $menu_type['key']; ?>" class="save-sort btn btn-success hidden"><?php echo _l("Save sort", $this); ?></button>
                                <button data-href="<?php echo ADMIN_URL; ?>menuForm/0/<?php echo $menu_type['key']; ?>" class="btn blue load-form" type="button">
                                    <i class="fa fa-plus"></i> <?php echo _l("Add New",$this); ?>
                                </button>
                                <button type="button" onclick="$('#data_list_<?php echo $menu_type['key']; ?>').nestable('collapseAll');" class="btn default" title="<?php echo _l("Collapse All", $this); ?>"><i class="far fa-minus-square"></i></button>
                                <button type="button" onclick="$('#data_list_<?php echo $menu_type['key']; ?>').nestable('expandAll');" class="btn default" title="<?php echo _l("Expand All", $this); ?>"><i class="far fa-plus-square"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if(isset($menu_type['description']) && $menu_type['description']!=''){ ?>
                            <div class="note">
                                <?php echo $menu_type['description']; ?>
                            </div>
                        <?php } ?>
                        <div class="data_list dd" id="data_list_<?php echo $menu_type['key']; ?>" data-output="#output_<?php echo $menu_type['key']; ?>" data-savebtn="#save-sort-<?php echo $menu_type['key']; ?>" data-key="<?php echo $menu_type['key']; ?>">
                            <?php if(isset($menu_type['data_list']) && count($menu_type['data_list'])!=0){ ?>
                                <ol class="dd-list">
                                    <?php $i=0; foreach($menu_type['data_list'] as $item){ $i++; ?>
                                        <li class="dd-item dd3-item" data-id="<?php echo $item["menu_id"]; ?>" data-public="<?php echo $item["public"]; ?>" data-parent="<?php echo $item["sub_menu"]; ?>">
                                            <div class="dd-handle dd3-handle"> </div>
                                            <div class="dd3-content">
                                                <?php echo $item["menu_name"]; ?>
                                                <a href="javascript: $(this).removeItem('<?php echo ADMIN_URL; ?>menuDelete/<?php echo $item["menu_id"]?>', <?=$item["menu_id"]?>);" class="btn btn-xs btn-link font-red pull-right btn-ask" title="<?=_l('Delete',$this)?>"><i class="fas fa-trash"></i></a>
                                                <button type="button" data-href="<?php echo ADMIN_URL; ?>menuForm/<?php echo $item["menu_id"].'/'.$menu_type['key']; ?>" class="btn btn-xs btn-link font-blue pull-right load-form" title="<?=_l('Edit',$this)?>"><i title="<?=_l('Edit',$this)?>" class="fas fa-edit"></i></button>
                                                <button type="button" data-href="<?php echo ADMIN_URL; ?>menuVisibility/<?php echo $item["menu_id"]?>" data-id="<?=$item["menu_id"]?>" class="btn btn-xs btn-link font-grey-gallery pull-right visibility" title="<?=_l('Visibility',$this)?>"><i class="fa <?php echo (isset($item['public'])&&$item['public']==1)?"fa-eye":"fa-eye-slash"; ?>"></i></button>
                                            </div>
                                            <?php if(isset($item['sub_menu_data']) && count($item['sub_menu_data'])!=0){ ?>
                                                <ol class="dd-list">
                                                    <?php $i=0; foreach($item['sub_menu_data'] as $item2){ $i++; ?>
                                                        <li class="dd-item dd3-item" data-id="<?php echo $item2["menu_id"]; ?>" data-public="<?php echo $item2["public"]; ?>" data-parent="<?php echo $item2["sub_menu"]; ?>">
                                                            <div class="dd-handle dd3-handle"> </div>
                                                            <div class="dd3-content">
                                                                <?php echo $item2["menu_name"]; ?>
                                                                <a href="javascript: $(this).removeItem('<?php echo ADMIN_URL; ?>menuDelete/<?php echo $item2["menu_id"]; ?>', <?=$item2["menu_id"]?>);" class="btn btn-xs btn-link font-red pull-right btn-ask" title="<?=_l('Delete',$this)?>"><i title="<?=_l('Delete',$this)?>" class="fas fa-trash"></i></a>
                                                                <button type="button" data-href="<?php echo ADMIN_URL; ?>menuForm/<?php echo $item2["menu_id"].'/'.$menu_type['key']; ?>" class="btn btn-xs btn-link font-blue pull-right load-form" title="<?=_l('Edit',$this)?>"><i class="fas fa-edit"></i></button>
                                                                <button type="button" data-href="<?php echo ADMIN_URL; ?>menuVisibility/<?php echo $item2["menu_id"]; ?>" data-id="<?=$item2["menu_id"]?>" class="btn btn-xs btn-link font-grey-gallery pull-right visibility" title="<?=_l('Visibility',$this)?>"><i class="fa fa-eye"></i></button>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                </ol>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ol>
                            <?php }else{ ?>
                                <div class="dd-empty"></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <textarea data-key="<?php echo $menu_type['key']; ?>" id="output_<?php echo $menu_type['key']; ?>" class="form-control hidden"></textarea>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<?php $this->addCssFile("assets/plugins/jquery-nestable/jquery.nestable"); ?>
<?php $this->addJsFile("assets/plugins/jquery-nestable/jquery.nestable"); ?>
<script>
    $(function () {
        var formModal = $('#menu-form');
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target);
            var output = $(list.data('output'));
            var saveSortBtn = $(list.data('savebtn'));
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
                saveSortBtn.removeClass('hidden');
//                output.removeClass('hidden');
            } else {
                toastr.error('JSON browser support required for this action.', 'Error');
            }
        };

        $('.load-form').click(function () {
            var my_btn = $(this);
            $.loadInModal(my_btn.data('href'));
        });

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

        $('.save-sort').click(function() {
            var saveSortBtn = $(this);
            var output = $('#output_'+saveSortBtn.data('key'));
            $.ajax({
                url: '<?php echo ADMIN_URL; ?>menuSort/'+saveSortBtn.data('key'),
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

        $('.data_list').nestable({
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
