<?php if(isset($data_list) && count($data_list)!=0){ ?>
    <div class="bg-grey-steel padding-top-20 padding-bottom-20">
        <div class="container">
            <h2 class="text-center margin-bottom-20"><?php echo $title; ?></h2>
            <div class="card-columns justify-content-md-center">
                <?php foreach($data_list as $item){ ?>
                    <?php echo $this->load->view($this->mainTemplate."/blog_item", array('item'=>$item), true); ?>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>