<div class="text-center margin-bottom-20">
    <a href="<?php echo BLOG_ADMIN_URL."postComments/$item[post_id]#comment$item[comment_id]"; ?>"><?php echo str_replace("{data}", "<strong>$post[post_name]</strong>", _l("Display all comments on {data}", $this)); ?></a>
</div>
<div class="clearfix">
    <div id="comment<?php echo $item['comment_id']; ?>" class="card w-75 margin-bottom-10 <?php echo $item['admin_side']==1?"float-right":"float-left";?>">
        <div class="card-body">
            <div class="clearfix">
                <div class="d-inline font-weight-bold"><?php echo $item['comment_name']; ?></div>
                <small class="float-right font-grey-mint margin-left-20"><?php echo my_int_fullDate($item['created_date']); ?></small>
                <small class="float-right font-grey-mint margin-left-20">
                    <img src="<?php echo base_url($item['language']['image']); ?>" style="height:18px;margin-top:-3px;">
                    <?php echo $item['language']['language_title']; ?>
                </small>
                <small class="float-right margin-left-20">
                    <a href="javascript:;" onclick="$.loadInModal('<?php echo $item['edit_url']; ?>');">
                        <i class="fas fa-edit"></i>
                        <?php echo _l("Edit", $this); ?>
                    </a>
                </small>
                <small class="float-right">
                    <a href="javascript:;" onclick="$.loadInModal('<?php echo $item['reply_url']; ?>');">
                        <i class="fas fa-reply"></i>
                        <?php echo _l("Reply", $this); ?>
                    </a>
                </small>
            </div>
            <div class="card-text">
                <?php echo $item['comment_content']; ?>
            </div>
            <?php if(isset($item['sub_items']) && count($item['sub_items'])!=0){ ?>
                <hr>
                <div class="clearfix padding-left-20 padding-right-20">
                    <?php foreach($item['sub_items'] as $sub_item){ ?>
                        <div id="comment<?php echo $sub_item['comment_id']; ?>" class="card bg-grey-steel w-75 margin-bottom-10 <?php echo $sub_item['admin_side']==1?"float-right":"float-left";?>">
                            <div class="card-body">
                                <div class="clearfix">
                                    <div class="d-inline font-weight-bold"><?php echo $sub_item['comment_name']; ?></div>
                                    <small class="float-right font-grey-mint margin-left-20"><?php echo my_int_fullDate($sub_item['created_date']); ?></small>
                                    <small class="float-right font-grey-mint margin-left-20">
                                        <img src="<?php echo base_url($item['language']['image']); ?>" style="height:18px;margin-top:-3px;">
                                        <?php echo $sub_item['language']['language_title']; ?></small>
                                    <small class="float-right">
                                        <a href="javascript:;" onclick="$.loadInModal('<?php echo $sub_item['edit_url']; ?>');">
                                            <i class="fas fa-edit"></i>
                                            <?php echo _l("Edit", $this); ?>
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
</div>
<script>
    $(function () {
        var hash = $(location).attr('hash');
        $(hash).addClass("border-warning");
    });
</script>