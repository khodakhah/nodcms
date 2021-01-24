<?php $this->addJsFile("assets/metronic/global/plugins/jquery-ui/jquery-ui.min"); ?>
<div class="portlet">
    <div class="portlet-body">
        <button class="btn default add-to-sortable-<?php echo $field_id; ?>" type="button"><i class="fa fa-plus font-green-jungle"></i> <?php echo _l("Add item", $this); ?></button>
        <textarea class="donotreset hidden" id="sortable-default-<?php echo $field_id; ?>"><?php echo ($default); ?></textarea>
        <textarea class="donotreset hidden" id="sortable-items-<?php echo $field_id; ?>"><?php echo ($items); ?></textarea>
    </div>
</div>
<div id="sortable-<?php echo $field_id; ?>" class="bg-grey-cararra" style="padding:40px 10px 10px 10px;"></div>
<script>
    $(function () {
        $("#sortable-<?php echo $field_id; ?>").nodcmsFormSortable({
            sample: function(form_content) {
                return '<div class="portlet field-item portlet-sortable light bordered">' +
                    '<div class="portlet-title">' +
                    '<div class="actions">' +
                    '<a class="btn btn-icon-only btn-circle btn-default" href="javascript:;" onclick="$(this).parents(\'.portlet-sortable\').remove();">' +
                    '<i class="icon-trash"></i></a>' +
                    '</div>' +
                    '</div>' +
                    '<div class="portlet-body">' +
                    form_content +
                    '</div>' +
                    '</div>';
            },
            add_button: $(".add-to-sortable-<?php echo $field_id; ?>"),
            fields_input: $("#sortable-items-<?php echo $field_id; ?>"),
            default_input: $("#sortable-default-<?php echo $field_id; ?>"),
            sortable_options: {
                items: ".portlet",
                opacity: 0.8,
                handle : '.portlet-title',
                coneHelperSize: true,
                placeholder: 'portlet-sortable-placeholder',
                tolerance: "pointer",
                forcePlaceholderSize: !0,
                helper: "clone",
                cancel: ".portlet-sortable-empty, .portlet-fullscreen", // cancel dragging if portlet is in fullscreen mode
                revert: true,
            }
        });
    });

</script>