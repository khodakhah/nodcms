<section class="card">
    <div class="card-header">
        <div class="text-right">
            <button type="button" data-url="<?php echo $save_sort_url; ?>" id="save-sort-<?php echo $page; ?>" class="save-sort btn btn-success hidden"><i class="fas fa-save"></i> <?php echo _l("Save new sort", $this); ?></button>
            <a href="<?php echo $add_url; ?>" class="btn btn-primary"><i class="fas fa-plus"></i> <?php echo _l("Add",$this); ?></a>
        </div>
    </div>
    <div class="card-body">
        <div class="nodcms-sortable-list dd" id="data_list_<?php echo $page; ?>" data-output="#output_<?php echo $page; ?>" data-savebtn="#save-sort-<?php echo $page; ?>" data-key="<?php echo $page; ?>">
            <?php if(isset($data_list) && count($data_list)!=0){ ?>
                <ol class="dd-list">
                    <?php $i=0; foreach($data_list as $item){ $i++; ?>
                        <li id="<?php echo $page."-item-".$item[$field_key]; ?>" class="dd-item dd3-item" data-id="<?php echo $item[$field_key]; ?>" data-public="<?php echo $item[$field_public]; ?>">
                            <div class="dd-handle dd3-handle"> </div>
                            <div class="dd3-content">
                                <?php echo $item[$field_title]; ?>
                                <button type="button" class="btn btn-xs btn-link font-red" data-target="#<?php echo $page."-item-".$item[$field_key]; ?>" onclick="$(this).removeAnItemFromList('<?php echo $delete_url."/".$item[$field_key]; ?>');">
                                    <i class="fas fa-trash"></i> <?=_l('Delete',$this)?></button>
                                <a class="btn btn-xs btn-link font-blue" href="<?php echo $edit_url."/".$item[$field_key]; ?>">
                                    <i class="fas fa-edit"></i> <?=_l('Edit',$this)?></a>
                            </div>
                        </li>
                    <?php } ?>
                </ol>
                <textarea id="output_<?php echo $page; ?>" class="sort-text-output hidden"></textarea>
            <?php }else{ ?>
                <div class="dd-empty"></div>
            <?php } ?>
        </div>
    </div>
</section>

<?php $this->load->addCssFile("assets/plugins/jquery-nestable/jquery.nestable"); ?>
<?php $this->load->addJsFile("assets/plugins/jquery-nestable/jquery.nestable.min", "assets/plugins/jquery-nestable/jquery.nestable-rtl.min"); ?>
<?php $this->load->addJsFile("assets/nodcms/js/netstable.min"); ?>