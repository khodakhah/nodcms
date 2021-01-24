<div class="margin-top-40 margin-bottom-30">
    <div class="margin-bottom-30">
        <a href="<?php echo base_url($this->language['code']); ?>" title="<?php echo $this->settings['company']; ?>">
            <img src="<?php echo base_url($this->settings['logo']); ?>" alt="<?php echo $this->settings['company']; ?>" title="<?php echo $this->settings['company']; ?>">
        </a>
    </div>
    <div class="row justify-content-md-center no-gutters">
        <div class="col-md-3 bg-grey-steel padding-40 text-right">
            <i class="fas fa-exclamation-triangle fa-10x font-grey-mint"></i>
        </div>
        <div class="col-md-9 bg-grey-steel padding-40">
            <h1><?php echo $title; ?></h1>
            <h2><?php echo $heading; ?></h2>
            <p><?php echo $message; ?></p>
            <div class="margin-top-40">
                <a href="<?php echo base_url($this->language['code']); ?>" class="btn btn-lg dark btn-outline"><?php echo _l("Back to home", $this); ?></a>
                <?php if(isset($buttons) && count($buttons)!=0){ ?>
                    <?php foreach ($buttons as $item){ ?>
                        <a class="btn default" href="<?php echo $item['url']; ?>"><?php echo $item['label']; ?></a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="margin-top-30 font-grey-mint">
        <?php echo $this->render("copyright"); ?>
    </div>
</div>