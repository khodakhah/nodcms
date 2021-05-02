<?php if(isset($data_list) && count($data_list)!=0){ ?>
    <div class="bg-grey-steel padding-top-20 padding-bottom-20">
        <div class="container-fluid">

            <?php if(isset($categories)){ ?>
                <div class="text-center margin-bottom-20">
                    <h4><?php echo _l("Categories", $this)?></h4>
                    <ul class="nav nav-pills justify-content-center">
                        <?php foreach($categories as $item){ ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $item['selected']==1?"active":""; ?>" href="<?php echo $item['category_url']; ?>"><?php echo $item['title']?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

            <div class="row justify-content-md-center margin-bottom-20">
                <?php foreach($data_list as $i=>$item){ ?>
                    <?php if($i>0 && $i%4==0){ ?>
                        <div class="clearfix col-12 margin-top-10 margin-bottom-10"></div>
                    <?php } ?>
                    <div class="col-md-3">
                        <?php echo $this->setData(array('item'=>$item))->render("blog_item"); ?>
                    </div>
                <?php } ?>
            </div>
            <?php echo $pagination; ?>

        </div>
    </div>
<?php } ?>