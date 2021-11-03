<li id="<?php echo $element_id; ?>" class="dd-item dd3-item <?php echo isset($class)?$class:""; ?>" data-id="<?php echo $id; ?>" data-public="<?php echo $visibility; ?>">
    <div class="dd-handle dd3-handle"> </div>
    <div class="dd3-content">
        <?php echo $title; ?>
        <?php if(isset($remove_url) && $remove_url!=""){ ?>
            <button type="button" class="btn btn-xs btn-link font-red" data-target="#<?php echo $element_id; ?>" onclick="$(this).removeAnItemFromList('<?php echo $remove_url; ?>');">
                <i class="fas fa-trash"></i> <?=_l('Delete',$this)?></button>
        <?php } ?>
        <?php if(isset($edit_url) && $edit_url!=""){ ?>
            <a class="btn btn-xs btn-link font-blue" href="<?php echo $edit_url; ?>">
                <i class="fas fa-edit"></i> <?php echo _l('Edit',$this); ?></a>
        <?php } ?>
        <?php if(isset($visibility_url) && $visibility_url!=""){ ?>
            <button type="button" data-href="<?php echo $visibility_url; ?>" data-id="<?php echo $id; ?>" class="btn btn-xs btn-link font-grey-gallery pull-right visibility" title="<?=_l('Visibility',$this)?>">
                <?php if($visibility==1){ ?>
                    <i class="far fa-eye"></i>
                <?php }else{ ?>
                    <i class="far fa-eye-slash"></i>
                <?php } ?>
            </button>
        <?php } ?>
        <?php if(isset($btn_urls) && count($btn_urls)!=0){ ?>
            <?php foreach($btn_urls as $item){ ?>
                <a href="<?php echo $item['url']; ?>" class="btn btn-link" <?php echo isset($item['target'])?"target=\"{$item['target']}\"":""; ?>>
                    <?php echo $item['label']; ?>
                </a>
            <?php } ?>
        <?php } ?>
    </div>
    <?php if(isset($sub_items) && $sub_items!=""){ ?>
        <ol class="dd-list">
            <?php echo $sub_items; ?>
        </ol>
    <?php } ?>
</li>