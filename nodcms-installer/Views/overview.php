<p>Welcome to <strong><?php echo "{$this->product_name} version {$this->product_version}"; ?></strong> installer!
    <a href="https://nodcms.com" target="_blank">Learn more about NodCMS</a></p>
<p>Throughout this process, <strong><?php echo $this->product_name; ?></strong> will be installed automatically.
    You only need to follow wizard guides.</p>
<?php if(isset($requirements_error) && count($requirements_error) > 0) { ?>
    <?php foreach($requirements_error as $item) { ?>
    <div class="alert alert-danger d-flex">
        <div class="mr-3"><i class="fas fa-exclamation-circle"></i></div>
        <div>
            <?php echo $item; ?>
        </div>
    </div>
    <?php } ?>
<?php return; ?>
<?php } ?>
<div class="alert alert-success d-flex">
    <div class="mr-3"><i class="fas fa-check"></i></div>
    <div>
        <strong>Congratulations!</strong> Your hosting service meets all required services to run <?php echo "{$this->product_name} version {$this->product_version}"; ?>.
    </div>
</div>
<div class="">
    <a class="btn btn-success" href="<?php echo base_url("installer/license"); ?>">Start installer <i class="far fa-arrow-alt-circle-right"></i></a>
</div>