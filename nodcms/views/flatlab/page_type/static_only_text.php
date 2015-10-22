<div class="container">
    <div class="panel">
        <h1 class="panel-heading"><?=$data["title_caption"]?></h1>
        <div class="panel-body">
            <?php if(isset($data["body"]) && count($data["body"])!=0){ ?>
            <?php foreach($data["body"] as $item){ ?>
                <h2><?=$item["name"]?></h2>
                <div><?=$item["description"]?></div>
            <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>