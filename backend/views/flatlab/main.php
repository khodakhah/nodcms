<div class="row">
    <div class="col-lg-4">
        <!--user info table start-->
        <section class="panel">
            <div class="panel-body">
                <div class="task-progress">
                    <h1><?=_l("Statistics Content",$this)?></h1>
                    <p><?=_l("Just enabled contents",$this)?></p>
                </div>
            </div>
            <table class="table table-hover personal-task">
                <tbody>
                <tr>
                    <td>
                        <i class=" fa fa-file"></i>
                    </td>
                    <td><?=_l("Pages",$this)?></td>
                    <td><?=$page_count?></td>
                </tr>
                <tr>
                    <td>
                        <i class=" fa fa-file-text"></i>
                    </td>
                    <td><?=_l("Extensions",$this)?></td>
                    <td><?=$extension_count?></td>
                </tr>
                <tr>
                    <td>
                        <i class=" fa fa-photo"></i>
                    </td>
                    <td><?=_l("Galleries",$this)?></td>
                    <td><?=$gallery_count?></td>
                </tr>
                <tr>
                    <td>
                        <i class=" fa fa-file-image-o"></i>
                    </td>
                    <td><?=_l("Images",$this)?></td>
                    <td><?=$gallery_image_count?></td>
                </tr>
                <tr>
                    <td>
                        <i class=" fa fa-upload"></i>
                    </td>
                    <td><?=_l("Uploaded Images",$this)?></td>
                    <td><?=$image_count?></td>
                </tr>
                <tr>
                    <td>
                        <i class="fa fa-users"></i>
                    </td>
                    <td><?=_l("Members",$this)?></td>
                    <td><?=$users_count?></td>
                </tr>
                </tbody>
            </table>
        </section>
        <!--user info table end-->
    </div>
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-body">
                <div class="task-progress">
                    <h1><?=_l("Visit & Visitors Statistics",$this)?></h1>
                    <p>
                        <?php echo _l("Total Visits",$this)?>: <?php echo isset($statistic_total_visits)?$statistic_total_visits:0; ?> |
                        <?php echo _l("Total Visitors",$this)?>: <?php echo isset($statistic_total_visitors)?$statistic_total_visitors:0; ?>
                    </p>
                </div>
            </div>
            <div class="panel-body">
                <div class="custom-bar-chart">
                    <ul class="y-axis">
                        <?php if(isset($statistic_max_visitors) && $statistic_max_visitors!=0){ ?>
                            <?php if($statistic_max_visitors>=4){ ?>
                                <?php $counterPlus = round($statistic_max_visitors/4); ?>
                                <?php for($i=$statistic_max_visitors;$i>=0;$i-=$counterPlus){ ?>
                                    <li><span><?php echo $i; ?></span></li>
                                <?php } ?>
                            <?php }else{ ?>
                                <li><span><?php echo $statistic_max_visitors; ?></span></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php if(isset($statistic) && count($statistic)!=0){ ?>
                        <?php foreach($statistic as $item){ ?>
                            <div class="bar">
                                <div class="title">
                                    <?php echo date("D",$item["statistic_date"]); ?><br>
                                    <?php echo date("d.m",$item["statistic_date"]); ?><br>
                                    <?php echo date("Y",$item["statistic_date"]); ?>
                                </div>
                                <div class="value tooltips" data-original-title="<?php echo $item["visitors"]?>&nbsp;Visitors <?php echo $item["visits"]?>&nbsp;Visits" data-toggle="tooltip" data-placement="top"><?php echo round(($item["visitors"]*100)/$statistic_max_visitors); ?>%</div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=_l("Content",$this)?>
            </header>
            <div class="panel-body">
                <div id="graph3" class="chart" style="height: 300px;"></div>
            </div>
        </section>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=_l("Comments",$this)?>
            </header>
            <div class="panel-body">
                <div id="graph4" class="chart" style="height: 300px;"></div>
            </div>
        </section>
    </div>
</div>

<script src="<?=base_url()?>assets/flatlab/assets/flot/jquery.flot.js"></script>
<script src="<?=base_url()?>assets/flatlab/assets/flot/jquery.flot.resize.js"></script>
<script src="<?=base_url()?>assets/flatlab/assets/flot/jquery.flot.pie.js"></script>
<script src="<?=base_url()?>assets/flatlab/assets/flot/jquery.flot.stack.js"></script>
<script src="<?=base_url()?>assets/flatlab/assets/flot/jquery.flot.crosshair.js"></script>

<script src="<?=base_url()?>assets/flatlab/js/jquery.sparkline.js" type="text/javascript"></script>
<script>
    var Script = function () {
        $(function(){
            var data = [];
            var series = <?=count($languages)?>;
            <?php $i = 0; ?>
            <?php foreach($languages as $item){ ?>
                data[<?=$i?>] = { label: "<?=$item["language_name"]?>", data: <?=$item["content_percent"]?> }
            <?php $i++; } ?>
            $.plot($("#graph3"), data,
            {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 3/4,
                            formatter: function(label, series){
                                return '<div style="font-size:10pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
                            },
                            background: { opacity: 1 }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
            // Comments
            var data = [];
            <?php $i = 0; ?>
            <?php foreach($languages as $item){ ?>
                data[<?=$i?>] = { label: "<?=$item["language_name"]?>", data: <?=$item["comment_percent"]?> }
            <?php $i++; } ?>
            $.plot($("#graph4"), data,
            {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 3/4,
                            formatter: function(label, series){
                                return '<div style="font-size:10pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
                            },
                            background: { opacity: 1 }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        });
    }();
</script>