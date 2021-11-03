<h2 class="page-title"><?php echo $title; ?></h2>
<div class="m-grid m-grid-responsive-sm">
    <div class="m-grid-row">
        <div class="m-grid-col m-grid-col-md-2 m-grid-flex bg-dark">
            <div class="portlet light bg-dark font-white">
                <div class="portlet-title">
                    <div class="caption font-white"><?php echo _l("Contact info", $this); ?></div>
                </div>
                <div class="portlet-body font-white">
                    <?php if($this->settings['address']!=""){ ?>
                        <p><i class="fa fa-map-marker font-grey-cararra"></i> <?php echo $this->settings['address']; ?></p>
                    <?php } ?>
                    <?php if($this->settings['phone']!=""){ ?>
                        <p><i class="fa fa-phone font-grey-cararra"></i> <?php echo $this->settings['phone']; ?></p>
                    <?php } ?>
                    <?php if($this->settings['fax']!=""){ ?>
                        <p><i class="fa fa-fax font-grey-cararra"></i> <?php echo $this->settings['fax']; ?></p>
                    <?php } ?>
                    <?php if($this->settings['email']!=""){ ?>
                        <p><i class="fa fa-envelope font-grey-cararra"></i> <?php echo $this->settings['email']; ?></p>
                    <?php } ?>
                    <a class="btn white btn-outline" href="<?php echo base_url($this->language['code']."/contact"); ?>"><?php echo _l("Send Message", $this); ?></a>
                </div>
            </div>
        </div>
        <div class="m-grid-col m-grid-col-middle m-grid-col-center m-grid-flex bg-grey">
            <?php if($this->settings['google_map_url']!=""){ ?>
                <iframe src="<?php echo $this->settings['google_map_url']; ?>" class="google-map-home" allowfullscreen></iframe>
            <?php } ?>
        </div>
    </div>
</div>
