<div class="container">
    <section class="panel">
        <header class="panel-heading">
            <?=_l("Search result",$this)?>
        </header>
        <div class="panel-body">
            <?php if(isset($data) && count($data)!=0){ ?>
                <?php foreach($data as $item){ ?>
                <div class="classic-search">
                    <h4><a href="<?=base_url().$lang?>/extension/<?=$item["extension_id"]?>?filter=<?=str_replace(" ","_",$search_word)?>"><?=str_replace($text_search,$text_replace,$item["name"])?></a></h4>
                    <a href="<?=base_url().$lang?>/extension/<?=$item["extension_id"]?>"><?=base_url().$lang?>/extension/<?=$item["extension_id"]?></a>
                    <p><?=str_replace($text_search,$text_replace,substr_string($item['description'],0,30))?></p>
                </div>
                <?php } ?>
            <?php } ?>
            <div id="ajax_load"></div>
        </div>
        <div class="text-center"><img src="<?=base_url()?>/upload_file/loading.gif" id="loading" style="display: none;" alt="<?=_l("loading...",$this)?>" title="<?=_l("loading...",$this)?>"></div>
    </section>
</div>
<script>
    $(function(){
        var displayAll = 0;
        var lastofset = 0;
        if(displayAll==0){
            $(window).scroll(function(){
                if ($(document).height() <= $(window).scrollTop() + $(window).height() && displayAll==0) {
                    $("#loading").show();
                    lastofset+=20;
                    $.ajax({
                        url: "<?=base_url().$lang?>/search?filter=<?=$search_word?>&offset=" + lastofset + "&ajax"
                    }).done(function(data) {
                                if(data!=""){
                                    $("#ajax_load").before(data);
                                }else{
                                    displayAll = 1;
                                }
                                $("#loading").hide();
                            });
                }
            });
        }
    });
</script>