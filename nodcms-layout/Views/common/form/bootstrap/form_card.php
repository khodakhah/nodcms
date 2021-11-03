<div class="card">
    <?php if((isset($tabs) && count($tabs)!=0)||(isset($title) && $title!="")){ ?>
        <div class="card-header">
            <?php if(isset($title) && $title!=""){ ?>
                <div class="card-title"><?php echo $title; ?></div>
            <?php } ?>
            <ul class="nav nav-tabs card-header-tabs">
                <?php foreach($tabs as $item){ ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($item['active']) && $item['active']==1)?'active':""; ?>" href="<?php echo isset($item['url'])?$item['url']:'#'; ?>">
                            <?php if(isset($item['icon'])){ ?>
                                <i class="<?php echo $item['icon']; ?>"></i>
                            <?php } ?>
                            <?php echo $item['title']; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
    <div class="card-body">
        <div class="card-text">
            <?php echo $notes; ?>
        </div>
        <form data-submit="<?php echo $form_type; ?>" id="<?php echo $form_id; ?>" action="<?php echo $action; ?>" method="<?php echo $method; ?>" class="<?php echo $class; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'" '; } ?>>
            <?php echo $this->render($form_content_theme); ?>
        </form>
    </div>
</div>