<div class="row">
    <aside class="profile-nav col-md-3">
        <section class="panel">
            <div class="user-heading round">
                <a href="#">
                    <?php if($banners["avatar"]=="NULL" || $banners["avatar"]=="" ){?>
                    <img src="<?php echo base_url(); ?>upload_file/avatars/avatar-00.png" alt="<?=_l('Account',$this);?>" />
                    <?php }else{ ?>
                    <img src="<?php echo base_url().$banners["avatar"]; ?>"  alt="<?=_l('Account',$this);?>" />
                    <?php } ?>
                </a>
            </div>
            <ul class="nav nav-pills nav-stacked">
                <li><a class="select" href="<?php echo base_url(); ?>profile">  <i class="fa fa-user"></i> <?=_l('Account',$this);?></a></li>
                <li><a href="<?php echo base_url(); ?>profile-detail"> <i class="fa fa-pencil-square-o"></i> <?=_l('Edit Details',$this);?></a></li>
                <li><a href="<?php echo base_url(); ?>profile-password"> <i class="fa fa-lock"></i> <?=_l('Chang Password',$this);?> </a></li>
                <?php if(isset($_SESSION["user"]["user_type"]) && $_SESSION["user"]["user_type"]==1){ ?>
                <li><a href="<?php echo base_url(); ?>profile-extension"> <i class="fa fa-code"></i> <?=_l('Manage Extensions',$this);?> </a></li>
                <li class="active"><a href="<?php echo base_url(); ?>profile/sale"> <i class="fa fa-money"></i> <?=_l('Your Sales',$this);?></a></li>
                <?php }else{ ?>
                <li><a href="<?php echo base_url(); ?>profile/request"> <i class="fa fa-money"></i> <?=_l('Your Request',$this);?></a></li>
                <?php } ?>
            </ul>
        </section>
    </aside>
    <div class="profile-nav col-md-9">
        <section class="panel">
            <header class="panel-heading"><?=_l("Your Sales",$this)?></header>
            <div class="panel-body">
                <?php $unReception=0; if(isset($sale_data) && $sale_data !=null) {?>
                <div class="adv-table">
                <table  class="display table table-bordered table-striped" style="margin-top: 20px;" id="example">
                    <thead>
                        <tr>
                            <th><?=_l('Transaction ID',$this);?></th>
                            <th><?=_l('Buyer',$this);?></th>
                            <th><?=_l('Extension',$this);?></th>
                            <th><?=_l('Amount',$this);?></th>
                            <th><?=_l('Commission Percent',$this);?></th>
                            <th><?=_l('Your Commission',$this);?></th>
                            <th style="width: 120px;"><?=_l('Settlements',$this);?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach($sale_data as $sale) { ?>
                        <?php if($sale['reception']!=1 && $sale["request"]!=1) { $unReception++; } ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $sale["trans_id"]; ?></td>
                            <td><?php echo $sale['username'];?></td>
                            <td><?php Echo $sale['extension_name'];?></td>
                            <td><?php echo $this->currency->format($sale['amount']);?></td>
                            <td><?=100-$sale['commission']?>%</td>
                            <td><?php Echo $sale['user_commission'];?></td>
                            <td style="text-align: center;"><div class="fa <?php echo $sale['reception']!=1 ? "fa-times red" : "fa-check green" ?> fa-lg"></div></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?=_l('Transaction ID',$this);?></th>
                        <th><?=_l('Buyer',$this);?></th>
                        <th><?=_l('Extension',$this);?></th>
                        <th><?=_l('Amount',$this);?></th>
                        <th><?=_l('Commission Percent',$this);?></th>
                        <th><?=_l('Your Commission',$this);?></th>
                        <th style="width: 70px;"><?=_l('Settlements',$this);?></th>
                    </tr>
                    </tfoot>
                </table>
                </div>
                <?php }else{ ?>
                <div class="alert alert-warning"><i class="fa fa-exclamation-circle fa-2x"></i><p><?=_l("You don't have eny sell",$this)?></p></div>
                <?php } ?>
            </div>
        </section>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php if($unReception != 0) {?>
                    <div id="request_form" style="margin: 10px 0;" class="text-center">
                        <button type="button" class="btn btn-success btn-lg" onclick="send_request()"><?=_l("Get your money",$this)?> (<?=_l("Fees are paid per",$this)?>)</button>
                    </div>
                <?php }else{ ?>
                    <div class="alert alert-success"><?=_l("There are no items to request",$this)?></div>
                <?php }?>
                <?php if(isset($sale_data) && $sale_data !=null) {?>
                    <p>
                        <?=_l("Sum amount",$this)?>: <strong><?php echo $this->currency->format($sumamount); ?></strong> |
                        <?=_l("Your commission",$this)?>: <strong><?php echo $this->currency->format($your_commission); ?></strong>
                    </p>
                    <p>
                        <?=_l("Sum reception amount",$this)?>: <strong><?php echo $this->currency->format($sumreception); ?></strong> |
                        <?=_l("Your reception commission",$this)?>: <strong><?php echo $this->currency->format($reception_commission); ?></strong>
                    </p>
                    <p>
                        <?=_l("Sum unreception amount",$this)?>: <strong><?php echo $this->currency->format($sumunreception); ?></strong> |
                        <?=_l("Your unreception commission",$this)?>: <strong><?php echo $this->currency->format($unreception_commission); ?></strong>
                    </p>
                    <div class="alert alert-warning"><i class="fa fa-info-circle fa-2x"></i> <?=_l("Your commission is 75% and only 25% of each sale is for site's share.",$this)?></div>
                <?php }else{ ?>
                    <div class="alert alert-warning"><i class="fa fa-exclamation-circle fa-2x"></i><p><?=_l("You don't have eny sell",$this)?></p></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!--<link href="--><?//=base_url()?><!--assets/flatlab/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />-->
<link rel="stylesheet" href="<?=base_url()?>assets/flatlab/assets/data-tables/DT_bootstrap.css" />
<link rel="stylesheet" href="<?=base_url()?>assets/flatlab/assets/data-tables/DT_bootstrap-rtl.css" />

<script type="text/javascript" language="javascript" src="<?=base_url()?>assets/flatlab/assets/advanced-datatable/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/flatlab/assets/data-tables/DT_bootstrap.js"></script>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#example').dataTable( {
            "aaSorting": [[ 4, "desc" ]],
            "oLanguage": {
                "sSearch": "<?=_l("Search",$this)?>:",
                "oPaginate":{
                    "sNext": "<?=_l("Next",$this)?>",
                    "sPrevious": "<?=_l("Previous",$this)?>"
                },
                "sZeroRecords": "<?=_l("No matching records found",$this)?>",
                "sLengthMenu": "<?=_l("_MENU_ Records per page",$this)?>"
            }
        } );
    } );
    <?php if($unReception != 0) {?>
    function send_request(){
        $.ajax({
            url:"<?=base_url()?>ajax_reception_request",
            success: function(dataout){
                if(dataout=="success"){
                    $("#request_form").html('<div class="alert alert-success"><?=_l("Your request send successful",$this)?></div>');
                }else{
                    $("#request_form").append('<div class="alert alert-danger"><?=_l("System error, try again",$this)?>' + dataout.msg + '</div>');
                }
            }
        })
    }
    <?php } ?>
</script>