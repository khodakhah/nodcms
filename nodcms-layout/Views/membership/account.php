<?php $this->load->addCssFile("assets/dropzone/dropzone"); ?>
<?php $this->load->addCssFile("assets/dropzone/basic"); ?>
<?php $this->load->addJsFile("assets/dropzone/dropzone"); ?>
<?php $this->load->addJsFile("assets/nodcms/form-handler/files"); ?>
<div class="card margin-top-10">
    <div class="card-body">
        <?php if(isset($tabs) && count($tabs)!=0){ ?>
            <ul class="nav nav-pills">
                <?php foreach($tabs as $key=>$item){ ?>
                <li class="nav-item <?php echo $item["active"]; ?>">
                    <a class="nav-link <?php echo $item["active"]; ?>" href="#account-tab<?php echo $key; ?>" data-role="auto-load-tab" data-url="<?php echo $item['url'];?>" data-toggle="tab" aria-expanded="true"><?php echo $item['label']; ?></a>
                </li>
                <?php } ?>
            </ul>
            <div class="margin-top-40">
                <div class="tab-content">
                    <?php foreach($tabs as $key=>$item){ ?>
                        <div class="tab-pane <?php echo $item["active"]; ?>" id="account-tab<?php echo $key; ?>">
                            <i class="fas fa-spinner fa-pulse fa-1x"></i> <span class="font-lg"><?php echo _l("Loading", $this); ?></span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
