<li class="nav-item" id="<?php echo $key; ?>">
    <a class="nav-link nav-toggle" href="<?php echo $uri === null ? "javascript:;" : $uri; ?>">
        <i class="<?php echo $icon; ?>"></i>
        <span class="title"><?php echo $title; ?></span>
        <?php if(!empty($subLink)){ ?>
            <span class="arrow"></span>
        <?php } ?>
    </a>
    <?php if(!empty($subLink)){ ?>
        <ul class="sub-menu">
            <?php echo $subLink; ?>
        </ul>
    <?php } ?>
</li>