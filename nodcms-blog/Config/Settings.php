<?php
/**
 * NodCMS
 *
 *  Copyright (c) 2015-2021.
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
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