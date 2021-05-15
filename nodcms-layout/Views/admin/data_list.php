<?php if(isset($tabs) && count($tabs)!=0){ ?>
    <div class="text-right margin-bottom-20">
        <div class="btn-group">
            <?php foreach($tabs as $item){ ?>
                <?php if(!isset($item['sub_links'])){ ?>
                    <a href="<?php echo $item['url']; ?>" class="btn btn-default <?php echo (isset($item['active']) && $item['active']==1)?"active":""; ?>" <?php echo isset($item['target'])?'target="'.$item['target'].'"':''; ?>>
                        <?php if(isset($item['icon'])){ ?>
                            <i class="<?php echo $item['icon']; ?>"></i>
                        <?php } ?>
                        <?php echo $item['title']; ?>
                        <?php if(isset($item['badge']) && $item['badge']!=0 && $item['badge']!=''){ ?>
                            <span class="badge badge-xs bg-red bold"><?php echo $item['badge']; ?></span>
                        <?php } ?>
                    </a>
                <?php }else{ ?>
                    <div class="btn-group">
                        <button class="btn btn-default dropdown-toggle <?php echo (isset($item['active']) && $item['active']==1)?"active":""; ?>" type="button" data-toggle="dropdown" data-close-others="true" data-hover="dropdown" data-delay="1000">
                            <?php if(isset($item['icon'])){ ?>
                                <i class="<?php echo $item['icon']; ?>"></i>
                            <?php } ?>
                            <?php echo $item['title']; ?> <i class="fa fa-angle-down"></i>
                            <?php if(isset($item['badge']) && $item['badge']!=0 && $item['badge']!=''){ ?>
                                <span class="badge badge-xs bg-red bold"><?php echo $item['badge']; ?></span>
                            <?php } ?>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <?php foreach($item['sub_links'] as $sub_item){ ?>
                                <li>
                                    <a href="<?php echo $sub_item['url']; ?>">
                                        <?php if(isset($sub_item['icon'])){ ?>
                                            <i class="<?php echo $sub_item['icon']; ?>"></i>
                                        <?php } ?>
                                        <?php echo $sub_item['title']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<?php if(isset($search_form) && $search_form!=null && $search_form!=''){ ?>
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption"><i class="fas fa-search"></i> <?php echo _l("Search", $this); ?></div>
        </div>
        <div class="portlet-body">
            <?php echo $search_form ?>
        </div>
    </div>
<?php } ?>

<div class="portlet">
    <?php if(isset($actions_buttons) && count($actions_buttons)!=0){ ?>
        <div class="margin-bottom-20 bg-grey-steel">
            <div class="text-right">
                <?php foreach($actions_buttons as $key=>$item){ ?>
                    <?php if($key == 'add'){ ?>
                        <a class="btn blue" href="<?php echo $item; ?>"><i class="fas fa-plus"></i> <?php echo _l("Add", $this); ?></a>
                    <?php }elseif($key == 'ajax_add'){ ?>
                        <button type="button" class="btn blue" onclick="$.loadInModal('<?php echo $item; ?>', 'modal-lg');"><i class="fas fa-plus"></i> <?php echo _l("Add", $this); ?></button>
                    <?php }elseif($key == 'all-uninstall'){ ?>
                        <button type="button" class="btn red" onclick="$.loadConfirmModal('<?php echo $item; ?>');"><?php echo _l("Uninstall all", $this); ?></button>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <div class="portlet-body">
        <?php echo isset($the_list)?$the_list:""; ?>
    </div>
</div>
