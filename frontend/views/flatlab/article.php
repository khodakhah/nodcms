<div class="container">
    <section class="panel">
        <h1 class="panel-heading"><?php echo _l($news['title_caption'],$this); ?></h1>
        <div class="panel-body">
            <?php echo isset($news['content_'.$_SESSION["lang"]]) ? $news['content_'.$_SESSION["lang"]] : $news['content'];  ?>
        </div>
    </section>
</div>

