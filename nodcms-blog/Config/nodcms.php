<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-Jun-19
 * Time: 12:12 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$config['Blog_autoEmailMessages'] = array(
    'reply_blog_comment'=> array(
        'label'=>"Blog comment's reply notification",
        'keys'=>array(
            array('label'=>'Commenter Name','value'=>'[--$commenter_name-]'),
            array('label'=>'Commenter text','value'=>'[--$commenter_content--]'),
            array('label'=>'Replier Name','value'=>'[--$replier_name-]'),
            array('label'=>'Replier text','value'=>'[--$replier_content--]'),
            array('label'=>'Blog\'s post title','value'=>'[--$blog_post_title--]'),
            array('label'=>'Request reference','value'=>'[--$reference_url--]'),
        ),
    ),
);