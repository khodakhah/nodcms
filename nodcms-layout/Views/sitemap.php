<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($extensions as $extension) { ?>
    <url>
        <loc><?php echo base_url($lang."/extension/".$extension["extension_id"]) ?></loc>
        <lastmod><?php echo (isset($extension["extensions.updated_date"]) && $extension["updated_date"]!=0) ? date("Y-m-d",$extension["updated_date"]) : date("Y-m-d",$extension["created_date"]); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.1</priority>
    </url>
    <?php } ?>
    <url>
        <loc><?php echo base_url($lang); ?>/</loc>
        <lastmod><?php echo date("Y-m-d",time()); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
</urlset>
