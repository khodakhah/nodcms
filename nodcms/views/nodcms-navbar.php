<?php if(isset($top_menu) && count($top_menu)!=0){ ?>
<nav class="navbar navbar-expand-lg d-print-none">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php foreach($top_menu as $item){ ?>
                <?php if(isset($item['sub_menu']) && count($item['sub_menu'])!=0){ ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="javascript:;" aria-expanded="false">
                            <?php echo $item['name']; ?>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-default">
                            <?php foreach($item['sub_menu'] as $sub_item){ ?>
                                <a class="dropdown-item" href="<?php echo $sub_item["url"]; ?>" title="<?php echo $sub_item['name']; ?>">
                                    <?php echo $sub_item['name']; ?></a>
                            <?php }?>
                        </div>
                    </li>
                <?php }else{ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $item["url"]; ?>"><?php echo $item["name"]; ?></a>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
</nav>
<?php } ?>
