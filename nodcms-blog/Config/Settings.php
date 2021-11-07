<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

namespace NodCMS\Blog\Config;


class Settings extends \Config\Settings
{
    /**
     * @inheritdoc
     * @var array
     */
    public $settings_default = [
        'blog_comments_private'=>0,
        'blog_private_preview'=>0,
        'blog_page_title'=>"Blog",
        'blog_page_description'=>"",
        'blog_page_keywords'=>""
    ];

    /**
     * @inheritdoc
     * @var array[]
     */
    public $autoEmailMessages = [
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
    ];
}
