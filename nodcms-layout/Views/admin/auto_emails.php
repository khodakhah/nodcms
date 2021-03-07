<?php $this->addJsFile("assets/plugins/ckeditor/ckeditor"); ?>
<?php if(isset($data_list) && count($data_list)!=0){ ?>
    <div class="row">
        <div class="col-md-4">
            <ul class="nav nav-pills nav-stacked">
                <?php foreach($data_list as $key=>$item){ ?>
                    <li role="presentation" class="d-block w-100 padding-bottom-10">
                        <a onclick="$('#<?php echo $key; ?>').click(function() { $(this).addClass('active'); }).loadIn('<?php echo $item['form_url']; ?>');" href="#<?php echo $key; ?>" class="btn default btn-block text-left d-flex justify-content-between" data-toggle="tab" aria-expanded="false">
                            <span><?php echo $item['label']; ?></span>
                            <?php if(isset($item['form_badge'])){ ?>
                                <span class="badge badge-danger"><?php echo $item['form_badge']; ?></span>
                            <?php } ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="col-md-8">
            <div class="tab-content">
                <?php foreach($data_list as $key=>$item){ ?>
                    <div id="<?php echo $key; ?>" class="tab-pane">
                        <i class="fa fa-spinner fa-pulse"></i>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
