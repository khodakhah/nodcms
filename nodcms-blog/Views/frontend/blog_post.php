<div class="bg-grey-steel padding-top-20 padding-bottom-20">
    <div class="container">
        <div class="row">
            <?php if($data['post_image']!=""){ ?>
                <div class="col-md">
                    <img alt="<?php echo $data['title']; ?>" title="<?php echo $data['title']; ?>" class="img-fluid" src="<?php echo base_url($data['post_image']); ?>">
                </div>
            <?php } ?>
            <div class="col-md order-md-1">
                <h1 class="margin-top-40"><?php echo $data['title']; ?></h1>
                <div class="font-lg font-grey-mint"><?php echo $data['description']?></div>
                <?php if(isset($categories) && is_array($categories) && count($categories)!=0){ ?>
                    <div>
                        <?php echo _l("Categories", $this); ?>
                        <ul class="list-inline">
                            <?php foreach($categories as $item){ ?>
                                <li>
                                    <a href="<?php echo $item['category_url']; ?>"><?php echo $item['title']; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
                <?php if(isset($post_keywords) && is_array($post_keywords) && count($post_keywords)!=0){ ?>
                    <div>
                        <?php echo _l("Keywords", $this); ?>
                        <ul class="list-inline">
                            <?php foreach($post_keywords as $item){ ?>
                                <li>
                                    <a href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="container margin-top-20 margin-bottom-20">
    <?php echo $data['content']; ?>
</div>
<?php if(isset($comment_form)){ ?>
    <div class="bg-grey-cararra padding-top-20 padding-bottom-20">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3 class="margin-bottom-40"><?php echo _l("Comments", $this); ?></h3>
                </div>
                <div class="col text-right">
                    <button class="btn btn-primary" onclick="$.loadInModal('<?php echo $comment_form; ?>');">
                        <?php echo _l("Send a comment", $this); ?>
                    </button>
                </div>
            </div>
            <?php if(isset($comments)){ ?>
                <div class="clearfix">
                    <?php foreach($comments as $item){ ?>
                        <div class="card margin-bottom-10 w-75 <?php echo isset($item['my_comment'])?"float-right":"float-left"; ?>" id="comment<?php echo $item['comment_id']; ?>">
                            <div class="card-body">
                                <div class="clearfix">
                                    <div class="d-inline font-weight-bold"><?php echo $item['comment_name']; ?></div>
                                    <small class="float-right font-grey-mint margin-left-20"><?php echo my_int_fullDate($item['created_date']); ?></small>
                                    <small class="float-right">
                                        <a href="javascript:;" onclick="$.loadInModal('<?php echo $item['reply_url']; ?>');">
                                            <i class="fas fa-reply"></i>
                                            <?php echo _l("Reply", $this); ?>
                                        </a>
                                    </small>
                                </div>
                                <div><?php echo $item['comment_content']; ?></div>
                                <?php if(isset($item['sub_items']) && count($item['sub_items'])!=0){ ?>
                                    <hr>
                                    <div class="clearfix padding-left-20 padding-right-20">
                                        <?php foreach($item['sub_items'] as $sub_item){ ?>
                                            <div id="comment<?php echo $sub_item['comment_id']; ?>" class="card bg-grey-steel w-75 margin-bottom-10 <?php echo isset($sub_item['my_comment'])?"float-right":"float-left"; ?>">
                                                <div class="card-body">
                                                    <div class="clearfix">
                                                        <div class="d-inline font-weight-bold"><?php echo $sub_item['comment_name']; ?></div>
                                                        <small class="float-right font-grey-mint margin-left-20"><?php echo my_int_fullDate($sub_item['created_date']); ?></small>
                                                        <small class="float-right">
                                                            <a href="javascript:;" onclick="$.loadInModal('<?php echo $sub_item['reply_url']; ?>');">
                                                                <i class="fas fa-reply"></i>
                                                                <?php echo _l("Reply", $this); ?>
                                                            </a>
                                                        </small>
                                                    </div>
                                                    <div class="card-text">
                                                        <?php echo $sub_item['comment_content']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<?php if(isset($related_posts) && count($related_posts)!=0){ ?>
    <div class="bg-grey-steel padding-top-20 padding-bottom-20">
        <div class="container">
            <h3 class="margin-bottom-20"><?php echo _l("Related posts", $this); ?></h3>
            <div class="card-columns justify-content-md-center">
                <?php foreach($related_posts as $item){ ?>
                    <?php echo $this->setData(array('item'=>$item))->render("blog_item"); ?>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>