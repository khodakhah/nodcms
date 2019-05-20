<?php $this->load->addJsFile("assets/nodcms/js/ajaxlist"); ?>
<?php if(isset($dashboards) && count($dashboards)!=0){ ?>
    <?php foreach($dashboards as $key=>$item){ ?>
        <div id="dashboard-box-<?php echo $key; ?>" data-url="<?php echo $item?>" data-role="auto-load">
            <i class="fa fa-spinner fa-pulse"></i>
        </div>
    <?php } ?>
<?php } ?>