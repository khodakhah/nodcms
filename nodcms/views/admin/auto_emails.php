<?php $this->load->addJsFile("assets/ckeditor/ckeditor"); ?>
<?php if(isset($data_list) && count($data_list)!=0){ ?>
    <div class="row">
        <div class="col-md-4">
            <ul class="nav nav-tabs tabs-left">
                <?php foreach($data_list as $key=>$item){ ?>
                    <li class="">
                        <a onclick="$('#<?php echo $key; ?>').loadIn('<?php echo $item['form_url']; ?>');" href="#<?php echo $key; ?>" class="" data-toggle="tab" aria-expanded="false">
                            <?php echo $item['label']; ?>
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
<?php } ?>
