<?php if(isset($data_list) && count($data_list)!=0){ ?>
    <div class="padding-top-40 padding-bottom-40">
        <div class="container">
            <h2 class="text-center margin-bottom-20"><?php echo $title; ?></h2>
            <div class="row">
                <?php foreach($data_list as $i=>$item){ ?>
                    <div class="col-md-4">
                        <div class="card <?php echo $item['table_highlight']==1?"border-1 border-blue":""; ?>">
                            <div class="card-body text-center">
                                <h4><?php echo $item['title']; ?></h4>
                                <div class="font-weight-bold font-theme margin-bottom-20">
                                    <?php echo \Config\Services::currency()->format($item['table_price']); ?>
                                </div>
                                <?php if(isset($item['records']) && count($item['records'])!=0){ ?>
                                    <?php foreach($item['records'] as $j=>$record){ ?>
                                        <div class="<?php echo $j>0?"border-top-1":""; ?> padding-top-10 padding-bottom-10">
                                            <?php echo $record['label']; ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <?php if($item['table_url']!=""){ ?>
                                    <div class="margin-top-20">
                                        <a class="btn default" href="<?php echo $item['table_url']; ?>">
                                            <?php echo $item['btn_label']; ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php if(($i+1)%3 == 0){ ?>
                        <div class="w-100 margin-top-20"></div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>