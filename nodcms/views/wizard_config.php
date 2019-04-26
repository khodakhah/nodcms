<?php if($this->userdata['group_id']==1 || $this->userdata['group_id']==100){ ?>
    <li class="dropdown dropdown-extended dropdown-dark" id="wizard_config">
        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
            <i class="icon-magic-wand"></i>
        </a>
        <ul class="dropdown-menu">
            <li class="external">
                <h3>NodAPS Configuration</h3>
                <a href="javascript:;" onclick="$('#wizard_config').removeClass('static');">
                    <i class="icon-close"></i>
                </a>
            </li>
            <li>
                <div class="portlet light bordered" style="margin-bottom: 0;">
                    <div class="portlet-title">
                        <div class="caption">
                            NodAPS Configuration
                        </div>
                    </div>
                    <div class="portlet-body"></div>
                </div>
            </li>
        </ul>
    </li>
    <?php $this->load->addJsFile("assets/nodaps/admin/wizard-config"); ?>
<?php } ?>