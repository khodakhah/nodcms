<link href="<?=base_url()?>assets/flatlab/assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
<div class="bg-box-dark">
    <div class="container">
        <div class="headline">
            <h2><?=$page_data["title_caption"]?></h2>
        </div>
        <div id="menu-works" class="block-content works-wrapper">
            <div class="works block-padding fullwidth background-gray">
                <div class="works-filter row-color">
                    <a class="selected btn btn-primary btn-lg" href="#" data-filter="*"><span><?=_l("All",$this)?></span></a>
                    <?php if(isset($gallery) && count($gallery)!=0){ ?>
                        <?php foreach($gallery as $item){ ?>
                            <a href="#" class="btn btn-primary btn-lg" data-filter=".<?=$item['gallery_name']?>_<?=$item['gallery_id']?>"><?=$item['title_caption']?></a>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="row works-items fade-hover text-center">
                        <?php if(isset($gallery_image) && count($gallery_image)!=0){ ?>
                            <?php foreach($gallery_image as $item){ ?>
                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 work-item <?=$item['gallery_name']?>_<?=$item['gallery_id']?>">
                                    <a class="fancybox fade-hover-item" rel="group" href="<?=base_url().$item['image']?>" title="<?=$item['title_caption']?>">
                                        <img src="<?=base_url().image($item['image'],$settings['default_image'],300,300)?>" alt="<?=$item['title_caption']?>" title="<?=$item['title_caption']?>" style="width:100%;">
                                    </a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=base_url()?>assets/isotope/isotope.pkgd.min.js"></script>
<script src="<?=base_url()?>assets/flatlab/assets/fancybox/source/jquery.fancybox.js"></script>
<script>
    $(window).load(function() {
        'use strict';

        /******************************************************************************
         * ISOTOPE
         ******************************************************************************/
        var isotope_works = $('.works-items');
        isotope_works.isotope({});

        $('.works-filter a').click(function() {
            $(this).parent().find('a').removeClass('selected');
            $(this).addClass('selected');

            var selector = $(this).attr('data-filter');
            isotope_works.isotope({ filter: selector });
            return false;
        });
    });
    $(function(){
        //    fancybox
        jQuery(".fancybox").fancybox();
    });
</script>