<div class="container">
    <?php if($data["image"]!=""){ $image = getimagesize(FCPATH.$data["image"]); ?>
        <?php if($image[1] <= ($image[0]/2)){ ?>
            <div class="card">
                <img src="<?php echo base_url().$data["image"]; ?>" alt="image-<?php echo $data["name"]; ?>" class="card-image-right">
            </div>
            <h1 class="margin-top-40"><?php echo $title; ?></h1>
        <?php }else{ ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <img src="<?php echo base_url().$data["image"]; ?>" alt="image-<?php echo $data["name"]; ?>" class="card-image-top">
                        <div class="card-body">
                            <?php echo $data["content"]; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <h1 class="margin-top-40"><?php echo $title; ?></h1>
                    <div class="font-grey-mint"><?php echo $data["description"]; ?></div>
                </div>
            </div>
        <?php } ?>
    <?php }else{ ?>
        <h1 class="margin-top-40"><?php echo $title; ?></h1>
        <?php if($data["description"]!=""){ ?>
            <div class="font-grey-mint margin-bottom-40"><?php echo $data["description"]; ?></div>
        <?php } ?>
        <div><?php echo $data["content"]; ?></div>
    <?php } ?>
</div>
<div class="margin-top-10 margin-bottom-10">
    <div class="container">
        <div class="row">
            <div class="col">
                <?php if(isset($prev_article)){ ?>
                    <a href="<?php echo $prev_article['article_url']; ?>"><?php echo $prev_article['link_title']; ?></a>
                <?php } ?>
            </div>
            <div class="col">
                <?php if(isset($next_article)){ ?>
                    <a href="<?php echo $next_article['article_url']; ?>"><?php echo $next_article['link_title']; ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="bg-grey-steel padding-top-20 padding-bottom-20">
    <div class="container">
        <p class="font-grey-mint"><?php echo _l("If you didn't find what you looking for, you may take a look at the below links.", $this); ?></p>
        <div class="row">
            <?php if(isset($relevant_articles) && count($relevant_articles)!=0){ ?>
                <div class="col-md">
                    <h3 class="font-grey-mint"><?php echo _l("Relevant Articles", $this); ?></h3>
                    <ul class="list-unstyled">
                        <?php foreach($relevant_articles as $article){ ?>
                            <li>
                                <a href="<?php echo $article['article_url']; ?>" title="<?php echo $article['name']; ?>"><?php echo $article['name']; ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php if(isset($other_articles) && count($other_articles)!=0){ ?>
                <div class="col-md">
                    <h3 class="font-grey-mint"><?php echo _l("Other Articles", $this); ?></h3>
                    <ul class="list-unstyled">
                        <?php foreach($other_articles as $article){ ?>
                            <li>
                                <a href="<?php echo $article['article_url']; ?>" title="<?php echo $article['name']; ?>"><?php echo $article['name']; ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</div>