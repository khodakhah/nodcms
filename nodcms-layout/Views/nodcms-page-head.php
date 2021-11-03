<?php if($this->display_page_title){ ?>
    <div class="page-head short-height d-print-none">
        <?php if(isset($title_bg)){ ?>
            <img class="bg-image <?php echo (isset($title_bg_blur)&&$title_bg_blur==1)?"bg-blur":""; ?>" src="<?php echo $title_bg; ?>">
        <?php } ?>
        <div class="text-center">
            <?php if(isset($title_logo)){ ?>
                <div>
                    <img class="image-logo" src="<?php echo $title_logo; ?>">
                </div>
                <h1 class="margin-bottom-20 font-theme"><?php echo $title; ?></h1>
            <?php }else{ ?>
                <h1 class="margin-bottom-20"><?php echo $title; ?></h1>
            <?php } ?>
            <?php if(isset($sub_title)){ ?>
                <div class="font-lg margin-bottom-20 font-white"><?php echo $sub_title; ?></div>
            <?php } ?>
        </div>
        <?php if(isset($breadcrumb) && count($breadcrumb)!=0){ ?>
            <nav aria-label="breadcrumb" class="text-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <i class="icon-home"></i>
                        <a  href="<?php echo base_url($this->language['code']); ?>"><?php echo _l('Home', $this); ?></a>
                    </li>
                    <?php foreach($breadcrumb as $item){ ?>
                        <li class="breadcrumb-item">
                            <?php if(isset($item['url'])){ ?>
                                <a href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a>
                            <?php }else{ ?>
                                <?php echo $item['title']; ?>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ol>
                <?php if(isset($breadcrumb_options) && count($breadcrumb_options)!=0){ ?>
                    <div class="page-toolbar">
                        <div class="btn-group pull-right">
                            <?php foreach($breadcrumb_options as $item){ ?>
                                <?php if(!isset($item['sub_links'])){ ?>
                                    <a href="<?php echo $item['url']; ?>" class="btn btn-fit-height grey-salt <?php echo (isset($item['active']) && $item['active']==1)?"active":""; ?>" <?php echo isset($item['target'])?'target="'.$item['target'].'"':''; ?>>
                                        <?php if(isset($item['icon'])){ ?>
                                            <i class="<?php echo $item['icon']; ?>"></i>
                                        <?php } ?>
                                        <?php echo $item['title']; ?>
                                    </a>
                                <?php }else{ ?>
                                    <div class="btn-group">
                                        <button class="btn btn-fit-height grey-salt dropdown-toggle <?php echo (isset($item['active']) && $item['active']==1)?"active":""; ?>" type="button" data-toggle="dropdown" data-close-others="true" data-hover="dropdown" data-delay="1000">
                                            <?php if(isset($item['icon'])){ ?>
                                                <i class="<?php echo $item['icon']; ?>"></i>
                                            <?php } ?>
                                            <?php echo $item['title']; ?> <i class="fa fa-angle-down"></i>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <?php foreach($item['sub_links'] as $sub_item){ ?>
                                                <li>
                                                    <a href="<?php echo $sub_item['url']; ?>">
                                                        <?php if(isset($sub_item['icon'])){ ?>
                                                            <i class="<?php echo $sub_item['icon']; ?>"></i>
                                                        <?php } ?>
                                                        <?php echo $sub_item['title']; ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </nav>
        <?php } ?>
        <?php if(isset($page_header)) echo $page_header; ?>
    </div>
<?php } ?>