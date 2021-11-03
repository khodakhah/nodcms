<div class="container">
    <div class="row no-gutters margin-top-20 margin-bottom-20">
        <div class="col-4 bg-grey-steel">
            <div class="margin-20 text-center">
                <?php if($this->settings['services_display_mode'] == "image"){ ?>
                    <img src="<?php echo base_url($data['service_image']); ?>" class="img-fluid rounded-circle">
                <?php }elseif($this->settings['services_display_mode'] == "icon"){ ?>
                    <i class="<?php echo $data['service_icon']; ?> fa-5x font-theme"></i>
                <?php } ?>
            </div>
        </div>
        <div class="col-8 bg-grey-cararra">
            <div class="padding-30">
                <h1><?php echo $data['title']; ?></h1>
                <div><?php echo $data['description']; ?></div>
                <?php if($services_has_price){ ?>
                    <div>
                        <?php echo _l("Price", $this); ?>:
                        <span class="<?php echo $data['service_price']!=0?"font-red":"font-green"; ?> font-weight-bold">
                        <?php echo $data['service_price']!=0?\Config\Services::currency()->format($data['service_price']):_l("Free", $this); ?>
                        </span>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div><?php echo $data['content']; ?></div>
</div>
<?php if(isset($data_list) && count($data_list)){ ?>
    <div class="padding-top-20 padding-bottom-20 bg-grey-steel">
        <div class="container">
            <h3><?php echo _l("Other services", $this); ?></h3>
            <div class="row justify-content-center">
                <?php foreach ($data_list as $item){ ?>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="margin-20 text-center">
                                    <?php if($this->settings['services_display_mode'] == "image"){ ?>
                                    <img src="<?php echo base_url($item['service_image']); ?>" class="img-fluid rounded-circle">
                                    <?php }elseif($this->settings['services_display_mode'] == "icon"){ ?>
                                        <i class="<?php echo $item['service_icon']; ?> fa-5x font-theme"></i>
                                    <?php } ?>
                                </div>
                                <h4 class="card-title text-center"><?php echo $item['title']; ?></h4>
                                <div class="card-text text-center"><?php echo $item['description']; ?></div>
                                <?php if($services_has_price){ ?>
                                    <div class="<?php echo $item['service_price']!=0?"font-red":"font-green"; ?> font-weight-bold text-center">
                                        <?php echo $item['service_price']!=0?\Config\Services::currency()->format($item['service_price']):_l("Free", $this); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if($services_has_content){ ?>
                                <div class="text-center">
                                    <a class="btn btn-block default" href="<?php echo $item['service_url']; ?>"><?php echo _l("Learn more", $this); ?></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>