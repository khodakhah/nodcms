SET FOREIGN_KEY_CHECKS=0;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping structure for table nodcms_demo.comments
DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(50) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `content` text,
  `created_date` int(11) unsigned DEFAULT NULL,
  `extension_id` int(11) unsigned DEFAULT NULL,
  `status` int(1) unsigned NOT NULL,
  `sub_id` int(11) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table nodcms_demo.extensions
DROP TABLE IF EXISTS `extensions`;
CREATE TABLE IF NOT EXISTS `extensions` (
  `extension_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `extension_icon` varchar(255) DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `description` text,
  `full_description` text,
  `data_type` varchar(255) DEFAULT NULL,
  `relation_id` int(10) unsigned DEFAULT NULL,
  `language_id` int(10) unsigned DEFAULT NULL,
  `created_date` int(10) unsigned DEFAULT NULL,
  `updated_date` int(10) unsigned NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  `public` int(1) unsigned DEFAULT NULL,
  `like` int(10) unsigned DEFAULT NULL,
  `dislike` int(10) unsigned DEFAULT NULL,
  `star_rate_sum` int(10) unsigned DEFAULT NULL,
  `star_rate_count` int(10) unsigned DEFAULT NULL,
  `count_view` int(10) unsigned DEFAULT NULL,
  `count_comment` int(10) unsigned NOT NULL DEFAULT '0',
  `extension_order` int(10) unsigned NOT NULL DEFAULT '0',
  `extension_more` text NOT NULL,
  PRIMARY KEY (`extension_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `extensions` (`extension_id`, `user_id`, `category_id`, `name`, `image`, `extension_icon`, `tag`, `description`, `full_description`, `data_type`, `relation_id`, `language_id`, `created_date`, `updated_date`, `status`, `public`, `like`, `dislike`, `star_rate_sum`, `star_rate_count`, `count_view`, `count_comment`, `extension_order`, `extension_more`) VALUES
(1, 1, 0, 'پشتبیانی از تمام زبان ها', 'upload_file/images20/0ce83a1d4aaebad05ae132336227cd9e.png', NULL, NULL, '<p>پشتیبانی از تمام زبانهای راست چین و چپ چین در مدیریت و قست کاربران</p>\r\n', NULL, 'page', 1, 2, 1407668181, 1407862941, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(2, 1, 0, 'استفاده آسان برای کاربران', 'upload_file/images20/d957d2eec45d829edbca76baedc6209e.png', NULL, NULL, '<p>طراحی این سیستم به صورتی است که به راحتی برای کاربران مبتدی و حرفه ای قابل استفاده است</p>\r\n', NULL, 'page', 1, 2, 1407668249, 1407862925, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 2, ''),
(3, 1, 0, 'ساده برای برنامه نویسان', 'upload_file/images20/6319fb8a942a57c184e11172dd66e7a0.png', NULL, NULL, '<p>بیس ساده و کار آمد این سیستم شرایطی را برای تمامی برنامه نویسان حتی مبتیدان پیش می آورد که به راحتی بتوانند آن را ویرایش وتغییر دهند</p>\r\n', NULL, 'page', 1, 2, 1407668278, 1407862902, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 3, ''),
(4, 1, 0, 'All Language Support', 'upload_file/images20/0ce83a1d4aaebad05ae132336227cd9e.png', NULL, NULL, '<p>This CMS supports all RTL &amp; LTR languages.</p>\n', NULL, 'page', 1, 1, 1407668449, 1410425967, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(5, 1, 0, 'Very Simple & Easy To Use', 'upload_file/images20/d957d2eec45d829edbca76baedc6209e.png', NULL, NULL, '<p>A very simple admin panel, suitable for professionals and even beginners!</p>\n', NULL, 'page', 1, 1, 1407668840, 1410425946, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 2, ''),
(6, 1, 0, 'Great For Developers', 'upload_file/images20/6319fb8a942a57c184e11172dd66e7a0.png', NULL, NULL, '<p>Very powerful yet simple core, which can be easily modified if needed</p>\n', NULL, 'page', 1, 1, 1407669258, 1410425974, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 3, ''),
(7, 1, 0, 'Information', NULL, 'fa-info-circle', NULL, '<p>We have provided a very powerful yet simple multilingual CMS based on CodeIgniter. You can launch any website using this CMS.</p>\n', NULL, 'page', 2, 1, 1407671647, 1410425112, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 1, ''),
(8, 1, 0, 'Download for FREE!', NULL, 'fa-legal', NULL, '<p>This CMS is totally free and everyone can have it!</p>\n', NULL, 'page', 2, 1, 1407673813, 1410425081, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 2, ''),
(9, 1, 0, 'Our emails', NULL, 'fa-envelope-o', NULL, '<p>You can also contact us using this email address: Khodakhah.mojtaba@yahoo.com</p>\n', NULL, 'page', 2, 1, 1407673988, 1410425052, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 3, ''),
(10, 1, 0, 'Hello World', 'upload_file/images20/3ec9fbb924448fabe2c81289e3369161.jpg', NULL, NULL, '<p>This is a demo version of the NodCMS. This is a free CMS based on the famous CodeIgniter framework which you can download for free and enjoy using it. All the features you see in this demo, can be created, edited and removed using the back-end panel.<br />\nThis free CMS also supports RTL languages and you can actually launch your website in any desired language!<br />\nNodCMS is the best choice for both beginners and professionals!</p>\n', NULL, 'page', 3, 1, 1407686265, 1410425427, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(11, 1, 0, 'Hello World 2', 'upload_file/images20/347f306a9f945a8b2b9c8cc8e65a0387.jpg', NULL, NULL, '<p>This is a demo version of the NodCMS. This is a free CMS based on the famous CodeIgniter framework which you can download for free and enjoy using it. All the features you see in this demo, can be created, edited and removed using the back-end panel.<br />\nThis free CMS also supports RTL languages and you can actually launch your website in any desired language!<br />\nNodCMS is the best choice for both beginners and professionals!</p>\n', NULL, 'page', 3, 1, 1407686336, 1410425415, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(12, 1, 0, 'Hello World 3', 'upload_file/images20/4805c870664fd0e78dc4eec8c088652a.jpg', NULL, NULL, '<p>This is a demo version of the NodCMS. This is a free CMS based on the famous CodeIgniter framework which you can download for free and enjoy using it. All the features you see in this demo, can be created, edited and removed using the back-end panel.<br />\nThis free CMS also supports RTL languages and you can actually launch your website in any desired language!<br />\nNodCMS is the best choice for both beginners and professionals!</p>\n', NULL, 'page', 3, 1, 1407686370, 1410425404, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(13, 1, 0, 'سلام دنیا', 'upload_file/images20/3ec9fbb924448fabe2c81289e3369161.jpg', NULL, NULL, '<p dir="rtl">سیستم مدیریت محتوای چند زبانه نوشته شده با استفاده از کد ایگنایتر (Codeigniter) در حال حاظر به صورت رایگان در اختیار همگان قرار دارد. کاربران اینترنت می توانند به راحتی این سیستم را دانلود کرده و بر روی سرور خود نصب کنند. پشتیبانی این سیستم نیز به صورت رایگان انجام می شود و همواره این سیستم در حال برورسانی است.</p>\r\n\r\n<p dir="rtl">کاربران نیز می توانند در صورت بروز احتمالی خطا آن را به ما گذارش دهند تا در اسرع وقت آن مشکل حل شود.</p>\r\n\r\n<p dir="rtl">از قابلیت های مهم این سیستم می توان تمرکز خاص آن را به چندزبانه بودن اشاره کرد، همچنین مدیریت بسیار ساده آن از ویژگی هایی است که مدیران سایت ها را به خود جذب می کند.</p>\r\n\r\n<p dir="rtl">بیس ساده این سیستم باعث می شود تا نگهداری و افزودن قسمت های دیگر به این سیستم کاری بسیار ساده باشد که حتی برنامه نویسان مبتدی هم قادر به انجام آن هستند. تمامی توضیحات و فایل های آموزشی را می توانید در سایت بیابیند.</p>\r\n', NULL, 'page', 3, 2, 1407862288, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(14, 1, 0, 'سلام دنیا 2', 'upload_file/images20/4805c870664fd0e78dc4eec8c088652a.jpg', NULL, NULL, '<p dir="rtl">سیستم مدیریت محتوای چند زبانه نوشته شده با استفاده از کد ایگنایتر (Codeigniter) در حال حاظر به صورت رایگان در اختیار همگان قرار دارد. کاربران اینترنت می توانند به راحتی این سیستم را دانلود کرده و بر روی سرور خود نصب کنند. پشتیبانی این سیستم نیز به صورت رایگان انجام می شود و همواره این سیستم در حال برورسانی است.</p>\r\n\r\n<p dir="rtl">کاربران نیز می توانند در صورت بروز احتمالی خطا آن را به ما گذارش دهند تا در اسرع وقت آن مشکل حل شود.</p>\r\n\r\n<p dir="rtl">از قابلیت های مهم این سیستم می توان تمرکز خاص آن را به چندزبانه بودن اشاره کرد، همچنین مدیریت بسیار ساده آن از ویژگی هایی است که مدیران سایت ها را به خود جذب می کند.</p>\r\n\r\n<p dir="rtl">بیس ساده این سیستم باعث می شود تا نگهداری و افزودن قسمت های دیگر به این سیستم کاری بسیار ساده باشد که حتی برنامه نویسان مبتدی هم قادر به انجام آن هستند. تمامی توضیحات و فایل های آموزشی را می توانید در سایت بیابیند.</p>\r\n', NULL, 'page', 3, 2, 1407862316, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(15, 1, 0, 'سلام دنیا 3', 'upload_file/images20/347f306a9f945a8b2b9c8cc8e65a0387.jpg', NULL, NULL, '<p dir="rtl">سیستم مدیریت محتوای چند زبانه نوشته شده با استفاده از کد ایگنایتر (Codeigniter) در حال حاظر به صورت رایگان در اختیار همگان قرار دارد. کاربران اینترنت می توانند به راحتی این سیستم را دانلود کرده و بر روی سرور خود نصب کنند. پشتیبانی این سیستم نیز به صورت رایگان انجام می شود و همواره این سیستم در حال برورسانی است.</p>\r\n\r\n<p dir="rtl">کاربران نیز می توانند در صورت بروز احتمالی خطا آن را به ما گذارش دهند تا در اسرع وقت آن مشکل حل شود.</p>\r\n\r\n<p dir="rtl">از قابلیت های مهم این سیستم می توان تمرکز خاص آن را به چندزبانه بودن اشاره کرد، همچنین مدیریت بسیار ساده آن از ویژگی هایی است که مدیران سایت ها را به خود جذب می کند.</p>\r\n\r\n<p dir="rtl">بیس ساده این سیستم باعث می شود تا نگهداری و افزودن قسمت های دیگر به این سیستم کاری بسیار ساده باشد که حتی برنامه نویسان مبتدی هم قادر به انجام آن هستند. تمامی توضیحات و فایل های آموزشی را می توانید در سایت بیابیند.</p>\r\n', NULL, 'page', 3, 2, 1407862353, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(16, 1, 0, 'سلام دنیا 4', 'upload_file/images20/a034a35f7fd2881a2978e48f43f7a8ac.jpg', NULL, NULL, '<p dir="rtl">سیستم مدیریت محتوای چند زبانه نوشته شده با استفاده از کد ایگنایتر (Codeigniter) در حال حاظر به صورت رایگان در اختیار همگان قرار دارد. کاربران اینترنت می توانند به راحتی این سیستم را دانلود کرده و بر روی سرور خود نصب کنند. پشتیبانی این سیستم نیز به صورت رایگان انجام می شود و همواره این سیستم در حال برورسانی است.</p>\r\n\r\n<p dir="rtl">کاربران نیز می توانند در صورت بروز احتمالی خطا آن را به ما گذارش دهند تا در اسرع وقت آن مشکل حل شود.</p>\r\n\r\n<p dir="rtl">از قابلیت های مهم این سیستم می توان تمرکز خاص آن را به چندزبانه بودن اشاره کرد، همچنین مدیریت بسیار ساده آن از ویژگی هایی است که مدیران سایت ها را به خود جذب می کند.</p>\r\n\r\n<p dir="rtl">بیس ساده این سیستم باعث می شود تا نگهداری و افزودن قسمت های دیگر به این سیستم کاری بسیار ساده باشد که حتی برنامه نویسان مبتدی هم قادر به انجام آن هستند. تمامی توضیحات و فایل های آموزشی را می توانید در سایت بیابیند.</p>\r\n', NULL, 'page', 3, 2, 1407862387, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(17, 1, 0, 'سلام دنیا 5', 'upload_file/images20/fb88b34b99583e5bfa3b2603a782d5a2.jpg', NULL, NULL, '<p dir="rtl">سیستم مدیریت محتوای چند زبانه نوشته شده با استفاده از کد ایگنایتر (Codeigniter) در حال حاظر به صورت رایگان در اختیار همگان قرار دارد. کاربران اینترنت می توانند به راحتی این سیستم را دانلود کرده و بر روی سرور خود نصب کنند. پشتیبانی این سیستم نیز به صورت رایگان انجام می شود و همواره این سیستم در حال برورسانی است.</p>\r\n\r\n<p dir="rtl">کاربران نیز می توانند در صورت بروز احتمالی خطا آن را به ما گذارش دهند تا در اسرع وقت آن مشکل حل شود.</p>\r\n\r\n<p dir="rtl">از قابلیت های مهم این سیستم می توان تمرکز خاص آن را به چندزبانه بودن اشاره کرد، همچنین مدیریت بسیار ساده آن از ویژگی هایی است که مدیران سایت ها را به خود جذب می کند.</p>\r\n\r\n<p dir="rtl">بیس ساده این سیستم باعث می شود تا نگهداری و افزودن قسمت های دیگر به این سیستم کاری بسیار ساده باشد که حتی برنامه نویسان مبتدی هم قادر به انجام آن هستند. تمامی توضیحات و فایل های آموزشی را می توانید در سایت بیابیند.</p>\r\n', NULL, 'page', 3, 2, 1407862400, 1407862431, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(18, 1, 0, 'Hello World 4', 'upload_file/images20/a034a35f7fd2881a2978e48f43f7a8ac.jpg', NULL, NULL, '<p>This is a demo version of the NodCMS. This is a free CMS based on the famous CodeIgniter framework which you can download for free and enjoy using it. All the features you see in this demo, can be created, edited and removed using the back-end panel.<br />\nThis free CMS also supports RTL languages and you can actually launch your website in any desired language!<br />\nNodCMS is the best choice for both beginners and professionals!</p>\n', NULL, 'page', 3, 1, 1407862476, 1410425396, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(19, 1, 0, 'Hello World 5', 'upload_file/images20/fb88b34b99583e5bfa3b2603a782d5a2.jpg', NULL, NULL, '<p>This is a demo version of the NodCMS. This is a free CMS based on the famous CodeIgniter framework which you can download for free and enjoy using it. All the features you see in this demo, can be created, edited and removed using the back-end panel.<br />\nThis free CMS also supports RTL languages and you can actually launch your website in any desired language!<br />\nNodCMS is the best choice for both beginners and professionals!</p>\n', NULL, 'page', 3, 1, 1407862512, 1410425385, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(20, 1, 0, 'اطلاعات', NULL, 'fa-info-circle', NULL, '<p dir="rtl">ما یک سیستم مدیریت محتوای چند زبانه که با استفاده از Codeigniter تولید کرده و به طور رایگان آن برای استفاده گذاشته و از آن پشتیبانی می کنیم. تمامی کاربران اینترنت از همه کشورها می توانند این سیستم را دانلود کرده و به صورت رایگان از آن استفاده کنند.<br />\r\n&nbsp;</p>\r\n', NULL, 'page', 2, 2, 1407863433, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(21, 1, 0, 'BASIC', NULL, NULL, NULL, NULL, NULL, 'page', 5, 1, 1408007763, 1410425572, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nprice: 0\nspecial: 0\nrow1: Multi-lingual System\nrow2: Powerful SEO\nrow3: Blog\nrow4: Photo Gallery\nbutton_link:\nbutton_name: Order Now\n'),
(22, 1, 0, 'Standard', NULL, NULL, NULL, NULL, NULL, 'page', 5, 1, 1408007892, 1410425540, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nprice: 49\nspecial: 0\nrow1: Multi-lingual System\nrow2: Powerful SEO\nrow3: ''Blog & Photo Gallery''\nrow4: Freely Change Themes\nbutton_link:\nbutton_name: Order Now\n'),
(23, 1, 0, 'Professional', NULL, NULL, NULL, NULL, NULL, 'page', 5, 1, 1408007999, 1410425514, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nprice: 99\nspecial: 1\nrow1: Multi-lingual System\nrow2: Full System\nrow3: Freely Change Themes\nrow4: Customizable\nbutton_link:\nbutton_name: Order Now\n'),
(24, 1, 0, 'حرفه ای', NULL, NULL, NULL, NULL, NULL, 'page', 5, 2, 1408401073, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nprice: 99\nrow1: سیستم چند زبانه\nrow2: سیستم کامل\nrow3: تعویض قالب رایگان\nrow4: شخصی سازی\nbutton_link:\nbutton_name: سفارش\n'),
(25, 1, 0, 'استاندارد', NULL, NULL, NULL, NULL, NULL, 'page', 5, 2, 1408401183, 1408401280, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nprice: 49\nspecial: 1\nrow1: سیستم چند زبانه\nrow2: SEO قدرتمند\nrow3: بلاگ و آلبوم تصاویر\nrow4: تعویض رایگان قالب\nbutton_link:\nbutton_name: سفارش\n'),
(26, 1, 0, 'ساده', NULL, NULL, NULL, NULL, NULL, 'page', 5, 2, 1408401259, 1408401307, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nprice: 0\nrow1: سیستم چند زبانه\nrow2: SEO قدرتمند\nrow3: بلاگ\nrow4: آلبوم تصاویر\nbutton_link:\nbutton_name: سفارش\n'),
(27, 1, 0, 'Informationen', NULL, 'fa-info-circle', NULL, '<p>Wir sind unterst&uuml;tzt eine kostenlose multi&szlig;language CodeIgniter CMS. Das ist sehr einfach und kraftvoll. Sie k&ouml;nnen es f&uuml;r Ihre internationalen Unternehmen, Informations pers&ouml;nliche Website oder mehrsprachige Zeitung zu verwenden.</p>\n', NULL, 'page', 2, 3, 1408648195, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 1, ''),
(28, 1, 0, ' جسیکا آلبا', 'upload_file/images20/fcc4435cf12591ff6530efe4fe62efc8.jpg', NULL, NULL, NULL, NULL, 'page', 6, 2, 1408787787, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 1, '---\njob: بازیگر\n'),
(29, 1, 0, 'تام کروز', 'upload_file/images20/7295604bb0454470e707632b41d69724.jpg', NULL, NULL, NULL, NULL, 'page', 6, 2, 1408787833, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 2, '---\njob: بازیگر\n'),
(30, 1, 0, 'کارلی شن', 'upload_file/images20/05dc424f24399027548e5c48a0850c93.jpg', NULL, NULL, NULL, NULL, 'page', 6, 2, 1408787868, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 3, '---\njob: بازیگر\n'),
(31, 1, 0, 'Charlie Sheen', 'upload_file/images20/05dc424f24399027548e5c48a0850c93.jpg', NULL, NULL, NULL, NULL, 'page', 6, 1, 1408787921, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 3, '---\njob: َActor\n'),
(32, 1, 0, 'Tom Cruise', 'upload_file/images20/7295604bb0454470e707632b41d69724.jpg', NULL, NULL, NULL, NULL, 'page', 6, 1, 1408787956, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 2, '---\njob: َActor\n'),
(33, 1, 0, 'Jessica Alba', 'upload_file/images20/fcc4435cf12591ff6530efe4fe62efc8.jpg', NULL, NULL, NULL, NULL, 'page', 6, 1, 1408788007, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 1, '---\njob: َActor\n'),
(34, 1, 0, 'Jessica Alba', 'upload_file/images20/fcc4435cf12591ff6530efe4fe62efc8.jpg', NULL, NULL, NULL, NULL, 'page', 6, 3, 1408788041, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 1, '---\njob: Schauspieler\n'),
(35, 1, 0, 'Tom Cruise', 'upload_file/images20/7295604bb0454470e707632b41d69724.jpg', NULL, NULL, NULL, NULL, 'page', 6, 3, 1408788065, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 2, '---\njob: Schauspieler\n'),
(36, 1, 0, 'Charlie Sheen', 'upload_file/images20/05dc424f24399027548e5c48a0850c93.jpg', NULL, NULL, NULL, NULL, 'page', 6, 3, 1408788102, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 3, '---\njob: Schauspieler\n'),
(37, 1, 0, 'Portfolio 1', 'upload_file/images20/104b902bca496da551264ec2babc140e.jpg', NULL, NULL, '<p>You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.</p>\n\n<p>You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.</p>\n\n<p>You can write proper descriptions here to show with your portfolio.</p>\n', NULL, 'page', 7, 1, 1408792747, 1410425765, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n'),
(38, 1, 0, 'Portfolio 2', 'upload_file/images20/ab680dd3a472bfb39490713612429c26.jpg', NULL, NULL, '<p>You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.</p>\n\n<p>You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.</p>\n\n<p>You can write proper descriptions here to show with your portfolio.</p>\n', NULL, 'page', 7, 1, 1408793545, 1410425736, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n'),
(39, 1, 0, 'Portfolio 3', 'upload_file/images20/4ca2b1c818b4e10f3753d5b92ce9afc6.png', NULL, NULL, '<p>You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.</p>\n\n<p>You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.</p>\n\n<p>You can write proper descriptions here to show with your portfolio.</p>\n', NULL, 'page', 7, 1, 1408797401, 1410425775, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n'),
(40, 1, 0, 'Gut für Entwickler', 'upload_file/images20/6319fb8a942a57c184e11172dd66e7a0.png', NULL, NULL, '<p>Sehr einfach und einfache Basis, das ist nicht notwendig, verwenden und &auml;ndern sehr professionelles Aussehen.</p>\n', NULL, 'page', 1, 3, 1409048834, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 3, ''),
(41, 1, 0, 'Sehr einfache Bedienung', 'upload_file/images20/d957d2eec45d829edbca76baedc6209e.png', NULL, NULL, '<p>Einfache Administrator Seite Rost f&uuml;r Anf&auml;nger und Profis.</p>\n', NULL, 'page', 1, 3, 1409048952, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 2, ''),
(42, 1, 0, 'Alle Sprachunterstützung', 'upload_file/images20/6319fb8a942a57c184e11172dd66e7a0.png', NULL, NULL, '<p>Das CMS unterst&uuml;tzt alle RTL &amp; LTR Sprachen.</p>\n', NULL, 'page', 1, 3, 1409049079, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 1, ''),
(43, 1, 0, 'Hallo Welt', 'upload_file/images20/a034a35f7fd2881a2978e48f43f7a8ac.jpg', NULL, NULL, '<p>Dies ist eine Demo von &quot;NodCMS&quot;. Das ist kostenlos und Codeigniter CMS k&ouml;nnen Sie kostenlos herunterladen simplity und verwenden. Alle Funktionen, die Sie hier sehen k&ouml;nnen, genau besteht in diesem CMS.&nbsp;</p>\n\n<p>&quot;NodCMS&quot; ist ein echtes Multi Sprache CMS und unterst&uuml;tzt alle Sprachen. Einige Sprache sind von links nach rechts, aber das CMS unterst&uuml;tzen.&nbsp;</p>\n\n<p>In &quot;NodCMS&quot; Verabreichung ist eine mehrsprachige auch. Sie k&ouml;nnen Ihre Landessprache auf unserer Website w&auml;hlen oder Ihr Liguster Sprache.&nbsp;</p>\n\n<p>F&uuml;r Anf&auml;nger und professionelle Entwickler, &quot;NodCMS&quot; ist die beste Wahl, denn das ist sehr einfach und kraftvoll.</p>\n', NULL, 'page', 3, 3, 1409052393, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(44, 1, 0, 'Hallo Welt 2', 'upload_file/images20/347f306a9f945a8b2b9c8cc8e65a0387.jpg', NULL, NULL, '<p>Dies ist eine Demo von &quot;NodCMS&quot;. Das ist kostenlos und Codeigniter CMS k&ouml;nnen Sie kostenlos herunterladen simplity und verwenden. Alle Funktionen, die Sie hier sehen k&ouml;nnen, genau besteht in diesem CMS.&nbsp;</p>\n\n<p>&quot;NodCMS&quot; ist ein echtes Multi Sprache CMS und unterst&uuml;tzt alle Sprachen. Einige Sprache sind von links nach rechts, aber das CMS unterst&uuml;tzen.&nbsp;</p>\n\n<p>In &quot;NodCMS&quot; Verabreichung ist eine mehrsprachige auch. Sie k&ouml;nnen Ihre Landessprache auf unserer Website w&auml;hlen oder Ihr Liguster Sprache.&nbsp;</p>\n\n<p>F&uuml;r Anf&auml;nger und professionelle Entwickler, &quot;NodCMS&quot; ist die beste Wahl, denn das ist sehr einfach und kraftvoll.</p>\n', NULL, 'page', 3, 3, 1409052452, 1409052478, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(45, 1, 0, 'Hallo Welt 3', 'upload_file/images20/4805c870664fd0e78dc4eec8c088652a.jpg', NULL, NULL, '<p>Dies ist eine Demo von &quot;NodCMS&quot;. Das ist kostenlos und Codeigniter CMS k&ouml;nnen Sie kostenlos herunterladen simplity und verwenden. Alle Funktionen, die Sie hier sehen k&ouml;nnen, genau besteht in diesem CMS.&nbsp;</p>\n\n<p>&quot;NodCMS&quot; ist ein echtes Multi Sprache CMS und unterst&uuml;tzt alle Sprachen. Einige Sprache sind von links nach rechts, aber das CMS unterst&uuml;tzen.&nbsp;</p>\n\n<p>In &quot;NodCMS&quot; Verabreichung ist eine mehrsprachige auch. Sie k&ouml;nnen Ihre Landessprache auf unserer Website w&auml;hlen oder Ihr Liguster Sprache.&nbsp;</p>\n\n<p>F&uuml;r Anf&auml;nger und professionelle Entwickler, &quot;NodCMS&quot; ist die beste Wahl, denn das ist sehr einfach und kraftvoll.</p>\n', NULL, 'page', 3, 3, 1409052562, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(46, 1, 0, 'Hallo Welt 4', 'upload_file/images20/fb88b34b99583e5bfa3b2603a782d5a2.jpg', NULL, NULL, '<p>Dies ist eine Demo von &quot;NodCMS&quot;. Das ist kostenlos und Codeigniter CMS k&ouml;nnen Sie kostenlos herunterladen simplity und verwenden. Alle Funktionen, die Sie hier sehen k&ouml;nnen, genau besteht in diesem CMS.&nbsp;</p>\n\n<p>&quot;NodCMS&quot; ist ein echtes Multi Sprache CMS und unterst&uuml;tzt alle Sprachen. Einige Sprache sind von links nach rechts, aber das CMS unterst&uuml;tzen.&nbsp;</p>\n\n<p>In &quot;NodCMS&quot; Verabreichung ist eine mehrsprachige auch. Sie k&ouml;nnen Ihre Landessprache auf unserer Website w&auml;hlen oder Ihr Liguster Sprache.&nbsp;</p>\n\n<p>F&uuml;r Anf&auml;nger und professionelle Entwickler, &quot;NodCMS&quot; ist die beste Wahl, denn das ist sehr einfach und kraftvoll.</p>\n', NULL, 'page', 3, 3, 1409052606, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, ''),
(47, 1, 0, 'Profi', NULL, NULL, NULL, NULL, NULL, 'page', 5, 3, 1409052849, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nprice: 99\nrow1: Zeile 1 Beschreibung\nrow2: Zeile 2 Beschreibung\nrow3: Zeile 3 Beschreibung\nrow4: Zeile 4 Beschreibung\nbutton_link:\nbutton_name: bestellen\n'),
(48, 1, 0, 'Standard', NULL, NULL, NULL, NULL, NULL, 'page', 5, 3, 1409052921, 1410425614, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nprice: 49\nspecial: 1\nrow1: Zeile 1 Beschreibung\nrow2: Zeile 2 Beschreibung\nrow3: Zeile 3 Beschreibung\nrow4: Zeile 4 Beschreibung\nbutton_link:\nbutton_name: bestellen\n'),
(49, 1, 0, 'elementar', NULL, NULL, NULL, NULL, NULL, 'page', 5, 3, 1409053030, 1410425587, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nprice: 0\nspecial: 0\nrow1: Zeile 1 Beschreibung\nrow2: Zeile 2 Beschreibung\nrow3: Zeile 3 Beschreibung\nrow4: Zeile 4 Beschreibung\nbutton_link:\nbutton_name: bestellen\n'),
(50, 1, 0, 'Portfolio 1', 'upload_file/images20/ab680dd3a472bfb39490713612429c26.jpg', NULL, NULL, '<p>Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.</p>\n\n<p>Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;</p>\n\n<p>Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.</p>\n', NULL, 'page', 7, 3, 1409053343, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n'),
(51, 1, 0, 'Portfolio 2', 'upload_file/images20/7d598d44b338118030f44317691ee2ae.jpg', NULL, NULL, '<p>Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.</p>\n\n<p>Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;</p>\n\n<p>Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.</p>\n', NULL, 'page', 7, 3, 1409053399, 1410425719, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n'),
(52, 1, 0, 'Portfolio 3', 'upload_file/images20/4ca2b1c818b4e10f3753d5b92ce9afc6.png', NULL, NULL, '<p>Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.</p>\n\n<p>Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;</p>\n\n<p>Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.&nbsp;Sie k&ouml;nnen Ihre Portfolio Beschreibung hier zu schreiben, um Ihre Website-Besucher zu zeigen.</p>\n', NULL, 'page', 7, 3, 1409053443, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n'),
(53, 1, 0, 'نمونه کار 1', 'upload_file/images20/ab680dd3a472bfb39490713612429c26.jpg', NULL, NULL, '<p>در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.</p>\n\n<p>در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.</p>\n\n<p>در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.</p>\n', NULL, 'page', 7, 2, 1409055080, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n'),
(54, 1, 0, 'نمونه کار 2', 'upload_file/images20/104b902bca496da551264ec2babc140e.jpg', NULL, NULL, '<p>در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;</p>\n\n<p>در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند. ر&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.</p>\n\n<p>در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;</p>\n', NULL, 'page', 7, 2, 1409055143, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n'),
(55, 1, 0, 'نمونه کار 3', 'upload_file/images20/4ca2b1c818b4e10f3753d5b92ce9afc6.png', NULL, NULL, '<p>در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;</p>\n\n<p>در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند. ر&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.</p>\n\n<p>در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;در اینجا می توانید توضیحات در باره نمون کار خود بنویسید تا بازدید کنندگان سایت شما اطلاعات جامعی از کار شما داشته باشند.&nbsp;</p>\n', NULL, 'page', 7, 2, 1409055186, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n'),
(56, 1, 0, 'Portfolio 4', 'upload_file/images20/c4ea4e6b789d0a16ec540b8ce0ef46b1.jpg', NULL, NULL, '<p>You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.</p>\n\n<p>You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.</p>\n\n<p>You can write proper descriptions here to show with your portfolio.</p>\n', NULL, 'page', 7, 1, 1410121526, 1410425703, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n'),
(57, 1, 0, 'Portfolio 5', 'upload_file/images20/7ef2b11a3698ba1365446da55df5f1b4.jpg', NULL, NULL, '<p>You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.</p>\n\n<p>You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.&nbsp;You can write proper descriptions here to show with your portfolio.</p>\n\n<p>You can write proper descriptions here to show with your portfolio.</p>\n', NULL, 'page', 7, 1, 1410121667, 1410425693, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, '---\nlink:\n');



-- Dumping structure for table nodcms_demo.gallery
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE IF NOT EXISTS `gallery` (
  `gallery_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_name` varchar(255) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `relation_id` int(10) unsigned DEFAULT NULL,
  `data_type` varchar(255) DEFAULT NULL,
  `created_date` int(10) unsigned DEFAULT NULL,
  `gallery_order` int(10) unsigned DEFAULT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `gallery` (`gallery_id`, `gallery_name`, `user_id`, `relation_id`, `data_type`, `created_date`, `gallery_order`, `status`) VALUES
(1, 'Frontend', 1, 4, 'page', 1407689200, 1, 1),
(2, 'Backend', 1, 4, 'page', 1407689444, 2, 1);


-- Dumping structure for table nodcms_demo.gallery_image
-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.24 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table nodcms_github3.gallery_image
DROP TABLE IF EXISTS `gallery_image`;
CREATE TABLE IF NOT EXISTS `gallery_image` (
  `image_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `relation_id` int(11) unsigned NOT NULL,
  `data_type` varchar(200) NOT NULL,
  `gallery_id` int(10) unsigned NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `size` float unsigned NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `count_view` int(10) unsigned NOT NULL,
  `created_date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- Dumping data for table nodcms_github3.gallery_image: 23 rows
DELETE FROM `gallery_image`;
INSERT INTO `gallery_image` (`image_id`, `relation_id`, `data_type`, `gallery_id`, `image`, `name`, `size`, `width`, `height`, `count_view`, `created_date`) VALUES
  (28, 4, 'page', 1, 'upload_file/images/a3a3780f54ff8399cdccdad2e6b129dd.png', 'a3a3780f54ff8399cdccdad2e6b129dd.png', 165.1, 1280, 600, 0, 1450196006),
  (19, 4, 'page', 2, 'upload_file/images/2557ae0612e7dc43d51ee4ace5bcd3d4.png', '2557ae0612e7dc43d51ee4ace5bcd3d4.png', 36.11, 1280, 600, 0, 1450195949),
  (18, 4, 'page', 2, 'upload_file/images/fd18c833995aab5e09c38076636f4985.png', 'fd18c833995aab5e09c38076636f4985.png', 44.39, 1280, 600, 0, 1450195949),
  (26, 4, 'page', 1, 'upload_file/images/4821b41c8c23d3b53b8d1ccd7ff05bed.png', '4821b41c8c23d3b53b8d1ccd7ff05bed.png', 435.32, 1280, 600, 0, 1450196005),
  (27, 4, 'page', 1, 'upload_file/images/680d59f3e79533f528f18a8d3d5cc302.png', '680d59f3e79533f528f18a8d3d5cc302.png', 521.49, 1280, 600, 0, 1450196005),
  (17, 4, 'page', 2, 'upload_file/images/6bfd32e40090a2674376c1afcfbf8920.png', '6bfd32e40090a2674376c1afcfbf8920.png', 40.16, 1280, 600, 0, 1450195886),
  (20, 4, 'page', 2, 'upload_file/images/2a1ffa5c250747d7ac2ad5d5e73e8d74.png', '2a1ffa5c250747d7ac2ad5d5e73e8d74.png', 48.81, 1280, 600, 0, 1450195950),
  (21, 4, 'page', 2, 'upload_file/images/009a144230984137d0b0e53fc97ffbda.png', '009a144230984137d0b0e53fc97ffbda.png', 557.48, 1280, 600, 0, 1450195950),
  (22, 4, 'page', 2, 'upload_file/images/9371802989e822d208d5093e6b422399.png', '9371802989e822d208d5093e6b422399.png', 30.81, 1280, 600, 0, 1450195950),
  (23, 4, 'page', 2, 'upload_file/images/575bcbeb8c92c3ad17c6ec66c6ef340e.png', '575bcbeb8c92c3ad17c6ec66c6ef340e.png', 34.19, 1280, 600, 0, 1450195951),
  (24, 4, 'page', 2, 'upload_file/images/980f76eefdeb6102a77774949ef8e193.png', '980f76eefdeb6102a77774949ef8e193.png', 47.65, 1280, 600, 0, 1450195951),
  (25, 4, 'page', 2, 'upload_file/images/6f3afd777f962a2c9e7da8df2e056c15.png', '6f3afd777f962a2c9e7da8df2e056c15.png', 47.65, 1280, 600, 0, 1450195951),
  (29, 4, 'page', 1, 'upload_file/images/a5793564df223877a706e04ce576b7d2.png', 'a5793564df223877a706e04ce576b7d2.png', 28.56, 1280, 600, 0, 1450196006),
  (30, 4, 'page', 1, 'upload_file/images/5df46b1cfc5c7b99670c05213bfb2c64.png', '5df46b1cfc5c7b99670c05213bfb2c64.png', 365.44, 1280, 600, 0, 1450196007),
  (31, 4, 'page', 1, 'upload_file/images/440db2974ded20b41abb3ca7a873c473.png', '440db2974ded20b41abb3ca7a873c473.png', 917.52, 1280, 600, 0, 1450196007),
  (32, 4, 'page', 1, 'upload_file/images/e18fe6fdefa22e01c1bfdf6ae39abe14.png', 'e18fe6fdefa22e01c1bfdf6ae39abe14.png', 316.03, 1280, 600, 0, 1450196007),
  (33, 4, 'page', 1, 'upload_file/images/4ca0e1c5e102e4192ff1d4dbe7d4b155.png', '4ca0e1c5e102e4192ff1d4dbe7d4b155.png', 43.27, 1280, 600, 0, 1450196008),
  (34, 4, 'page', 1, 'upload_file/images/3df72773fd0b5bd5b61e343e8d3d6e2c.png', '3df72773fd0b5bd5b61e343e8d3d6e2c.png', 25.04, 1280, 600, 0, 1450196008),
  (35, 4, 'page', 1, 'upload_file/images/5b511c48ffbc08ca4f594e191bcac4b6.png', '5b511c48ffbc08ca4f594e191bcac4b6.png', 223.75, 1280, 600, 0, 1450196008),
  (36, 4, 'page', 1, 'upload_file/images/994075994e0effa69444ff342618bcf9.png', '994075994e0effa69444ff342618bcf9.png', 24.56, 1280, 600, 0, 1450196008),
  (37, 4, 'page', 1, 'upload_file/images/e532df6e5a38e0d6bfa368dd187c7a73.png', 'e532df6e5a38e0d6bfa368dd187c7a73.png', 441.04, 1280, 600, 0, 1450196008),
  (38, 4, 'page', 1, 'upload_file/images/285d685fb97554ddc9ba81af0c903ec5.png', '285d685fb97554ddc9ba81af0c903ec5.png', 438.73, 1280, 600, 0, 1450196009),
  (39, 4, 'page', 2, 'upload_file/images/99367080652536fa9757c731dfc4f4fe.png', '99367080652536fa9757c731dfc4f4fe.png', 10.44, 1280, 600, 0, 1450196023);

-- Dumping structure for table nodcms_demo.groups
DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(50) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `groups` (`group_id`, `groupname`) VALUES
(1, 'Admin'),
(2, 'Editor'),
(3, 'Member');


-- Dumping structure for table nodcms_demo.images
DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `image_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `width` int(11) unsigned DEFAULT NULL,
  `height` int(11) unsigned DEFAULT NULL,
  `size` int(11) unsigned DEFAULT NULL,
  `root` varchar(255) DEFAULT NULL,
  `folder` varchar(255) DEFAULT NULL,
  `created_date` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `images` (`image_id`, `name`, `image`, `width`, `height`, `size`, `root`, `folder`, `created_date`, `user_id`) VALUES
(1, '3ec9fbb924448fabe2c81289e3369161.jpg', 'upload_file/images20/3ec9fbb924448fabe2c81289e3369161.jpg', 1600, 1200, 205, 'upload_file/images20/', 'images20/', 1407838268, 1),
(2, 'fb88b34b99583e5bfa3b2603a782d5a2.jpg', 'upload_file/images20/fb88b34b99583e5bfa3b2603a782d5a2.jpg', 1920, 1080, 244, 'upload_file/images20/', 'images20/', 1407838287, 1),
(3, '4805c870664fd0e78dc4eec8c088652a.jpg', 'upload_file/images20/4805c870664fd0e78dc4eec8c088652a.jpg', 284, 177, 5, 'upload_file/images20/', 'images20/', 1407838310, 1),
(4, '347f306a9f945a8b2b9c8cc8e65a0387.jpg', 'upload_file/images20/347f306a9f945a8b2b9c8cc8e65a0387.jpg', 640, 427, 74, 'upload_file/images20/', 'images20/', 1407838327, 1),
(5, '1074fa7d9af521f81914ed5a43b7eb63.jpg', 'upload_file/images20/1074fa7d9af521f81914ed5a43b7eb63.jpg', 593, 370, 44, 'upload_file/images20/', 'images20/', 1407841676, 1),
(8, 'a034a35f7fd2881a2978e48f43f7a8ac.jpg', 'upload_file/images20/a034a35f7fd2881a2978e48f43f7a8ac.jpg', 1600, 1200, 101, 'upload_file/images20/', 'images20/', 1407841690, 1),
(10, '6319fb8a942a57c184e11172dd66e7a0.png', 'upload_file/images20/6319fb8a942a57c184e11172dd66e7a0.png', 128, 128, 4, 'upload_file/images20/', 'images20/', 1407862895, 1),
(11, 'd957d2eec45d829edbca76baedc6209e.png', 'upload_file/images20/d957d2eec45d829edbca76baedc6209e.png', 128, 128, 4, 'upload_file/images20/', 'images20/', 1407862921, 1),
(12, '0ce83a1d4aaebad05ae132336227cd9e.png', 'upload_file/images20/0ce83a1d4aaebad05ae132336227cd9e.png', 128, 128, 2, 'upload_file/images20/', 'images20/', 1407862937, 1),
(13, 'ac766864fe71eb91573945f4e663529e.png', 'upload_file/images20/ac766864fe71eb91573945f4e663529e.png', 240, 200, 36, 'upload_file/images20/', 'images20/', 1407863215, 1),
(24, 'windows_live_language_setting6.png', 'upload_file/logo/windows_live_language_setting6.png', 128, 128, 8, 'upload_file/logo/', 'logo/', 1408550301, 1),
(25, 'windows_live_language_setting7.png', 'upload_file/logo/windows_live_language_setting7.png', 128, 128, 8, 'upload_file/logo/', 'logo/', 1408550323, 1),
(26, '94a8816758fe10e815c339b6751fddbe.jpg', 'upload_file/images20/94a8816758fe10e815c339b6751fddbe.jpg', 593, 370, 44, 'upload_file/images20/', 'images20/', 1408567262, 1),
(27, 'fcc4435cf12591ff6530efe4fe62efc8.jpg', 'upload_file/images20/fcc4435cf12591ff6530efe4fe62efc8.jpg', 2048, 2048, 276, 'upload_file/images20/', 'images20/', 1408787759, 1),
(28, '7295604bb0454470e707632b41d69724.jpg', 'upload_file/images20/7295604bb0454470e707632b41d69724.jpg', 570, 770, 110, 'upload_file/images20/', 'images20/', 1408787821, 1),
(29, '05dc424f24399027548e5c48a0850c93.jpg', 'upload_file/images20/05dc424f24399027548e5c48a0850c93.jpg', 1920, 1080, 179, 'upload_file/images20/', 'images20/', 1408787855, 1),
(30, '0072c697d622bdf06b26aa3d37a55e90.png', 'upload_file/images20/0072c697d622bdf06b26aa3d37a55e90.png', 640, 480, 119, 'upload_file/images20/', 'images20/', 1408792732, 1),
(31, '44ab8ef63b3082a8705af64a16e10c7b.jpg', 'upload_file/images20/44ab8ef63b3082a8705af64a16e10c7b.jpg', 800, 600, 75, 'upload_file/images20/', 'images20/', 1408793508, 1),
(36, '104b902bca496da551264ec2babc140e.jpg', 'upload_file/images20/104b902bca496da551264ec2babc140e.jpg', 2560, 1600, 1287, 'upload_file/images20/', 'images20/', 1408989148, 1),
(37, 'ab680dd3a472bfb39490713612429c26.jpg', 'upload_file/images20/ab680dd3a472bfb39490713612429c26.jpg', 1920, 1080, 456, 'upload_file/images20/', 'images20/', 1408989246, 1),
(38, '7d598d44b338118030f44317691ee2ae.jpg', 'upload_file/images20/7d598d44b338118030f44317691ee2ae.jpg', 1280, 800, 158, 'upload_file/images20/', 'images20/', 1408989299, 1),
(39, '4ca2b1c818b4e10f3753d5b92ce9afc6.png', 'upload_file/images20/4ca2b1c818b4e10f3753d5b92ce9afc6.png', 1024, 576, 941, 'upload_file/images20/', 'images20/', 1408989501, 1),
(40, 'c4ea4e6b789d0a16ec540b8ce0ef46b1.jpg', 'upload_file/images20/c4ea4e6b789d0a16ec540b8ce0ef46b1.jpg', 1920, 1080, 286, 'upload_file/images20/', 'images20/', 1410121460, 1),
(41, '7ef2b11a3698ba1365446da55df5f1b4.jpg', 'upload_file/images20/7ef2b11a3698ba1365446da55df5f1b4.jpg', 500, 312, 50, 'upload_file/images20/', 'images20/', 1410121657, 1);


-- Dumping structure for table nodcms_demo.languages
DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `public` int(1) unsigned DEFAULT NULL,
  `rtl` int(1) unsigned DEFAULT '0',
  `sort_order` int(11) DEFAULT NULL,
  `created_date` int(11) DEFAULT NULL,
  `default` int(11) DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `languages` (`language_id`, `language_name`, `code`, `public`, `rtl`, `sort_order`, `created_date`, `default`, `image`) VALUES
(1, 'english', 'en', 1, 0, 1, 1369730191, 1, 'upload_file/lang/united_states_flag.png'),
(2, 'farsi', 'fa', 1, 1, 2, 1381317624, 0, 'upload_file/lang/iran_flag.png'),
(3, 'deutsch', 'de', 1, 0, 3, 1403201887, 0, 'upload_file/lang/germany_flag.png');


-- Dumping structure for table nodcms_demo.menu
DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_icon` varchar(255) DEFAULT NULL,
  `menu_link` varchar(255) DEFAULT NULL,
  `sub_menu` int(10) unsigned DEFAULT '0',
  `page_id` int(10) unsigned DEFAULT NULL,
  `created_date` int(10) unsigned DEFAULT NULL,
  `menu_order` int(10) unsigned DEFAULT NULL,
  `public` int(1) unsigned DEFAULT NULL,
  `menu_url` varbinary(255) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `menu` (`menu_id`, `menu_name`, `menu_icon`, `menu_link`, `sub_menu`, `page_id`, `created_date`, `menu_order`, `public`, `menu_url`) VALUES
(1, 'About Us', '', NULL, 0, 2, 1407879004, 10, 1, '');


-- Dumping structure for table nodcms_demo.page
DROP TABLE IF EXISTS `page`;
CREATE TABLE IF NOT EXISTS `page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_type` int(10) unsigned NOT NULL DEFAULT '1',
  `page_dynamic` int(1) unsigned NOT NULL DEFAULT '0',
  `page_name` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `country_id` int(11) unsigned NOT NULL,
  `created_date` int(11) unsigned NOT NULL,
  `public` int(1) unsigned NOT NULL,
  `preview` int(1) unsigned NOT NULL,
  `default` int(1) unsigned NOT NULL,
  `page_order` int(11) unsigned DEFAULT NULL,
  `page_more` text,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `page` (`page_id`, `page_type`, `page_dynamic`, `page_name`, `avatar`, `country_id`, `created_date`, `public`, `preview`, `default`, `page_order`, `page_more`) VALUES
(1, 202, 0, 'NodCMS – A Free CMS powered by CodeIgniter', '', 0, 1407662888, 1, 1, 0, 2, ''),
(2, 204, 0, 'About us', 'upload_file/images20/1074fa7d9af521f81914ed5a43b7eb63.jpg', 0, 1407670498, 1, 1, 0, 0, ''),
(3, 101, 1, 'Blog', '', 0, 1407679386, 1, 1, 0, 3, ''),
(4, 301, 0, 'Multi-Lang screenshots', '', 0, 1407689043, 1, 1, 0, 4, ''),
(5, 401, 0, 'Pricing', '', 0, 1407966926, 1, 1, 0, 5, ''),
(6, 205, 0, 'Our Group', NULL, 0, 1408787535, 1, 1, 0, 7, ''),
(7, 206, 1, 'Portfolio', NULL, 0, 1408792518, 1, 1, 0, 8, '');


-- Dumping structure for table nodcms_demo.setting
DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `fav_icon` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `zip_code` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `description` text,
  `smtp_host` varchar(255) NOT NULL,
  `smtp_port` int(11) NOT NULL,
  `smtp_username` varchar(255) NOT NULL,
  `smtp_password` varchar(255) NOT NULL,
  `default_image` varchar(255) DEFAULT NULL,
  `fb_api` varchar(255) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `setting` (`id`, `email`, `company`, `logo`, `fav_icon`, `address`, `location`, `zip_code`, `country_id`, `language_id`, `phone`, `description`, `smtp_host`, `smtp_port`, `smtp_username`, `smtp_password`, `default_image`, `fb_api`, `site_name`) VALUES
(1, 'info@nodcms.com', 'nod-CMS', 'upload_file/logo/windows_live_language_setting6.png', 'upload_file/logo/windows_live_language_setting6.png', 'Wien, 1150 Sechshause strasse', '+48.137769, +16.276226', 1230, 1, 1, '068860114434', '', '', 0, '', '', 'assets/frontend/img/noimage.jpg', '486992334724444', NULL);



-- Dumping structure for table nodcms_demo.setting_options_per_lang
DROP TABLE IF EXISTS `setting_options_per_lang`;
CREATE TABLE IF NOT EXISTS `setting_options_per_lang` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `site_title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `site_description` text,
  `site_keyword` text,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `setting_options_per_lang` (`option_id`, `language_id`, `site_title`, `company`, `site_description`, `site_keyword`) VALUES
(1, 1, 'Free download multi language CMS - nodCMS', 'nod-CMS', 'Free download multi language CMS - nodCMS', 'Free download multi language CMS - nodCMS'),
(2, 2, 'دانلود رایگان سی ام اس چند زبانه نود سی ام اس', 'نود سی ام اس', 'دانلود رایگان سی ام اس چند زبانه نود سی ام اس', 'دانلود رایگان سی ام اس چند زبانه نود سی ام اس'),
(3, 3, 'Frei download mehrsprachige CMS - nodCMS', 'nod-CMS', 'Frei download mehrsprachige CMS - nodCMS', 'Frei download mehrsprachige CMS - nodCMS');



-- Dumping structure for table nodcms_demo.titles
DROP TABLE IF EXISTS `titles`;
CREATE TABLE IF NOT EXISTS `titles` (
  `title_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_caption` varchar(255) DEFAULT NULL,
  `relation_id` int(10) unsigned DEFAULT NULL,
  `data_type` varchar(255) DEFAULT NULL,
  `language_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`title_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `titles` (`title_id`, `title_caption`, `relation_id`, `data_type`, `language_id`) VALUES
(16, 'Blog', 3, 'page', 1),
(17, 'بلاگ', 3, 'page', 2),
(18, 'Blog', 3, 'page', 3),
(19, 'Multi-Lang screenshots', 4, 'page', 1),
(20, 'عکس از مولتی-لنگ', 4, 'page', 2),
(21, 'Multi-Lang screenshots', 4, 'page', 3),
(22, 'Frontend', 1, 'gallery', 1),
(23, 'قسمت کاربران', 1, 'gallery', 2),
(24, 'Frontend', 1, 'gallery', 3),
(25, 'Administration', 2, 'gallery', 1),
(26, 'مدیریت', 2, 'gallery', 2),
(27, 'Verwaltung', 2, 'gallery', 3),
(28, 'About us', 2, 'page', 1),
(29, 'درباره ما', 2, 'page', 2),
(30, 'Über uns', 2, 'page', 3),
(34, 'About us', 1, 'menu', 1),
(35, 'درباره ما', 1, 'menu', 2),
(36, 'Über uns', 1, 'menu', 3),
(40, 'Pricing Table', 5, 'page', 1),
(41, 'جدول قیمت', 5, 'page', 2),
(42, 'Kosten Tabelle', 5, 'page', 3),
(46, 'Our Group', 6, 'page', 1),
(47, 'گروه ما', 6, 'page', 2),
(48, 'Uns', 6, 'page', 3),
(49, 'Portfolio', 7, 'page', 1),
(50, 'نمونه کار', 7, 'page', 2),
(51, 'Portfolio', 7, 'page', 3),
(52, 'NodCMS – A Free CMS powered by CodeIgniter', 1, 'page', 1),
(53, 'سی ام اس (سیستم مدیریت محتوا) چند زبانه رایگان', 1, 'page', 2),
(54, 'Codeigniter multi-languge free CMS', 1, 'page', 3);


-- Dumping structure for table nodcms_demo.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `group_id` tinyint(3) DEFAULT NULL,
  `created_date` int(11) unsigned NOT NULL,
  `reset_pass_exp` int(11) unsigned NOT NULL,
  `status` int(1) unsigned NOT NULL,
  `active_register` int(1) unsigned NOT NULL,
  `active` int(1) unsigned NOT NULL,
  `active_code` varchar(255) NOT NULL,
  `email_hash` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `user_agent` text,
  `keep_me_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`user_id`, `username`, `password`, `fullname`, `firstname`, `lastname`, `email`, `group_id`, `created_date`, `reset_pass_exp`, `status`, `active_register`, `active`, `active_code`, `email_hash`, `avatar`, `user_agent`, `keep_me_time`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'Administrator', NULL, NULL, '', 1, 1, 0, 1, 1, 1, '', '', '', NULL, 0);


-- Dumping structure for table nodcms_demo.visitors
DROP TABLE IF EXISTS `visitors`;
CREATE TABLE IF NOT EXISTS `visitors` (
  `visit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `created_date` int(10) unsigned DEFAULT NULL,
  `updated_date` int(10) unsigned DEFAULT NULL,
  `count_view` int(10) unsigned DEFAULT NULL,
  `user_agent` text,
  `user_ip` varchar(50) DEFAULT NULL,
  `language_id` int(10) unsigned DEFAULT NULL,
  `language_code` varchar(2) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `request_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`visit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping structure for table nodcms_demo.statistic
CREATE TABLE IF NOT EXISTS `statistic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_date` int(11) unsigned DEFAULT NULL,
  `statistic_date` int(11) unsigned DEFAULT NULL,
  `visitors` int(11) unsigned DEFAULT NULL,
  `visits` int(11) unsigned DEFAULT NULL,
  `popular_url` varchar(255) DEFAULT NULL,
  `popular_url_count` int(11) unsigned DEFAULT NULL,
  `popular_lang` int(10) unsigned DEFAULT NULL,
  `popular_lang_count` int(11) unsigned DEFAULT NULL,
  `popular_lang_percent` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
