
              <!-- page start-->
              <div class="row">
				  <aside class="profile-nav col-lg-3">
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
							<li><a href="<?php echo base_url(); ?>profile">  <i class="fa fa-user"></i> <?=_l('Account',$this);?></a></li>
							<li><a href="<?php echo base_url(); ?>profile-detail"> <i class="fa fa-pencil-square-o"></i> <?=_l('Edit Details',$this);?></a></li>
							<li><a href="<?php echo base_url(); ?>profile-password"> <i class="fa fa-lock"></i> <?=_l('Chang Password',$this);?> </a></li>
                            <?php if(isset($_SESSION["user"]["user_type"]) && $_SESSION["user"]["user_type"]==1){ ?>
                            <li class="active"><a href="<?php echo base_url(); ?>profile-extension"> <i class="fa fa-code"></i> <?=_l('Manage Extensions',$this);?> </a></li>
                            <li><a href="<?php echo base_url(); ?>profile/sale"> <i class="fa fa-money"></i> <?=_l('Your Sales',$this);?></a></li>
                            <?php }else{ ?>
                            <li><a href="<?php echo base_url(); ?>profile/request"> <i class="fa fa-money"></i> <?=_l('Your Request',$this);?></a></li>
                            <?php } ?>
						</ul>
					</section>
				  </aside>
                  <div class="profile-info col-lg-9">
                      <section class="panel">
                          <header class="panel-heading height-head"><?=_l('Manage Extensions',$this);?></header>
                          <div class="panel-body">
                                  <div class="row"><div class="col-md-3">
                                      <a id="pulsate-regular" href="<?php echo base_url(); ?>profile-update-extension" class="btn btn-warning"><i class="fa fa-cloud-upload"></i><?=_l('Send Marketplace Item',$this);?></a>
                                  </div></div>
                              <div class="adv-table">
                                  <?php if(count($extension_data) > 0) {?>
                                  <table class="display table table-bordered table-striped" id="example">
                                      <thead>
                                      <tr>
                                          <th><?=_l('Extension Name',$this);?></th>
                                          <th><?=_l('Publish',$this);?></th>
                                          <th><?=_l('Downloads',$this);?></th>
                                          <th><?=_l('Date Added',$this);?></th>
                                          <th><?=_l('Action',$this);?></th>

                                      </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach($extension_data as $ex) {?>
                                          <tr class="odd gradeX">
                                              <td><?php echo $ex['name'];?></td>
                                              <td><?php echo $ex['public']==1?_l('Yes',$this):_l('No',$this);?></td>
                                              <td><?php echo $ex['download'];?></td>

                                              <td><?php echo my_int_date($ex['created_date']); ?></td>
                                              <td> <a  class="btn btn-success btn-xs" href="<?php echo base_url(); ?>profile-update-extension/<?php echo $ex['extension_id']?>"><i class="icon-pencil"></i> &nbsp;<?=_l('Edit',$this);?></a>
                                                  <?php if($ex['status']==1 && $ex['public']==1) {?> <a target="_blank" class="btn btn-primary btn-xs" href="<?php echo base_url(); ?>extension/<?php echo $ex['extension_id']?>/<?php echo url_title($ex['name']); ?>.html"><i class="icon-ok"></i> &nbsp;<?=_l('View',$this);?></a><?php }?></td>
                                          </tr>
                                              <?php }?>
                                      </tbody>
                                  </table>
                                  <?php } else { ?>
                                  <div class="alert alert-warning"><?=_l('No result',$this);?></div>
                                  <?php } ?>
                              </div>
                          </div>
                      </section>
                  </div>
              </div>
              <!-- page end-->
    
    <!--common script for all pages-->
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
	</script>



