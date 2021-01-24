<?php if(isset($top_menu) && count($top_menu)!=0){ ?>
    <?php foreach($top_menu as $item){ ?>
        <?php if(isset($item['sub_menu']) && count($item['sub_menu'])!=0){ ?>
            <li class="classic-menu-dropdown">
                <a data-toggle="megamenu-dropdown" href="javascript:;" aria-expanded="false">
                    <?php echo $item['name']; ?>
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu pull-left">
                    <?php foreach($item['sub_menu'] as $sub_item){ ?>
                        <li><a href="<?php echo $sub_item["url"]; ?>" title="<?php echo $sub_item['name']; ?>">
                                <?php echo $sub_item['name']; ?></a></li>
                    <?php }?>
                </ul>
            </li>
        <?php }else{ ?>
            <li>
                <a href="<?php echo $item["url"]; ?>"><?php echo $item["name"]; ?></a>
            </li>
        <?php } ?>
    <?php } ?>
<?php } ?>

