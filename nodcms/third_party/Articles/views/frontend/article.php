<div class="container">
    <?php $this->load->view($this->mainTemplate."/".$content_type); ?>
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

<div class="container">
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
