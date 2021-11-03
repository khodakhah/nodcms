<div class="portlet light">
    <?php if((isset($tabs) && count($tabs)!=0)||(isset($title) && $title!="")){ ?>
        <div class="portlet-title tabbable-line">
            <?php if(isset($title) && $title!=""){ ?>
                <div class="caption"><?php echo $title; ?></div>
            <?php } ?>
            <ul class="nav nav-tabs">
                <?php foreach($tabs as $item){ ?>
                    <li <?php echo (isset($item['active']) && $item['active']==1)?'class="active"':""; ?>>
                        <a href="<?php echo isset($item['url'])?$item['url']:'#'; ?>">
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
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-12">
                <?php echo $notes; ?>
                <form data-submit="<?php echo $form_type; ?>" id="<?php echo $form_id; ?>" action="<?php echo $action; ?>" method="<?php echo $method; ?>" class="<?php echo $class; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'" '; } ?>>
                    <?php echo $this->render($form_content_theme); ?>
                </form>
            </div>
        </div>
    </div>
</div>