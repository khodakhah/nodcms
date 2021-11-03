<section class="card">
    <div class="card-header">
        <div class="text-right">
            <button type="button" data-url="<?php echo $save_sort_url; ?>" id="save-sort-<?php echo $page; ?>" class="save-sort btn btn-success hidden"><i class="fas fa-save"></i> <?php echo _l("Save new sort", $this); ?></button>
            <?php if(isset($add_urls) && count($add_urls)!=0){ ?>
                <?php foreach($add_urls as $add_url){ ?>
                    <a href="<?php echo $add_url['url']; ?>" class="btn btn-primary"><i class="fas fa-plus"></i> <?php echo $add_url['label']; ?></a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <div class="card-body">
        <div class="nodcms-sortable-list dd" id="data_list_<?php echo $page; ?>" data-output="#output_<?php echo $page; ?>" data-visibility-btn=".visibility" data-save-btn="#save-sort-<?php echo $page; ?>" data-key="<?php echo $page; ?>" <?php echo isset($max_depth)?"data-max-depth=\"$max_depth\"":""; ?>>
            <?php if(isset($list_items) && $list_items!=""){ ?>
                <ol class="dd-list">
                    <?php echo $list_items; ?>
                </ol>
                <textarea id="output_<?php echo $page; ?>" class="sort-text-output hidden"></textarea>
            <?php }else{ ?>
                <div class="dd-empty"></div>
            <?php } ?>
        </div>
    </div>
</section>

<?php $this->addCssFile("assets/plugins/jquery-nestable/jquery.nestable"); ?>
<?php $this->addJsFile("assets/plugins/jquery-nestable/jquery.nestable.min", "assets/plugins/jquery-nestable/jquery.nestable-rtl.min"); ?>
<?php $this->addJsFile("assets/nodcms/js/netstable.min"); ?>