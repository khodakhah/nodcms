<div class="container mt-4">
    <div class="row">
        <?php if($this->settings['google_map_url']!=""){ ?>
            <div class="col-md">
                <iframe src="<?php echo $this->settings['google_map_url']; ?>" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
        <?php } ?>
        <div class="col-md">
            <h2 class="font-grey-mint"><?php echo _l("Contact info", $this); ?></h2>
            <?php if($this->settings['address']!=""){ ?>
                <p><i class="fa fa-map-marker font-grey-mint"></i> <?php echo $this->settings['address']; ?></p>
            <?php } ?>
            <?php if($this->settings['phone']!=""){ ?>
                <p><i class="fa fa-phone font-grey-mint"></i> <?php echo $this->settings['phone']; ?></p>
            <?php } ?>
            <?php if($this->settings['fax']!=""){ ?>
                <p><i class="fa fa-fax font-grey-mint"></i> <?php echo $this->settings['fax']; ?></p>
            <?php } ?>
            <?php if($this->settings['email']!=""){ ?>
                <p><i class="fa fa-envelope font-grey-mint"></i> <?php echo $this->settings['email']; ?></p>
            <?php } ?>
        </div>
        <?php if(isset($contact_form)){ ?>
            <div class="col-md">
                <h2 class="font-grey-mint"><?php echo _l("Contact form", $this); ?></h2>
                <?php echo $contact_form; ?>
            </div>
        <?php } ?>
    </div>
</div>
