<?php header("Content-Type: application/rss+xml; charset=UTF-8"); ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <atom:link href="<?=base_url("rss.xml"); ?>" rel="self" type="application/rss+xml" />
        <title><?=isset($settings["options"]["company"])?$settings["options"]["company"]:$settings["company"]?><?php echo isset($title)?" | ".$title:""; ?></title>
        <link><?=base_url($lang) ?>/</link>
        <description><?php if(isset($description)) echo substr_string(strip_tags($description),0,50); ?></description>
        <language><?=$lang?></language>
        <copyright>Copyright (C) <?=date("Y",time())?> <?=isset($settings["options"]["company"])?$settings["options"]["company"]:$settings["company"]?></copyright>
        <?php foreach ($extensions as $extension) { ?>
        <item>
            <title><?=$extension["name"]?></title>
            <link><?=base_url($lang."/extension/".$extension["extension_id"])?></link>
            <description><?=substr_string(str_replace(array("&nbsp;","&hellip;","&zwnj;","\n")," ",strip_tags($extension["description"])),0,50)?></description>
            <pubDate><?php echo (isset($extension["extensions.updated_date"]) && $extension["updated_date"]!=0) ? date("D, d M Y H:i:s O",$extension["updated_date"]) : date("D, d M Y H:i:s O",$extension["created_date"]); ?></pubDate>
            <guid><?=base_url("extension/".$extension["extension_id"])?></guid>
        </item>
        <?php } ?>
    </channel>
</rss>
