(function ( $ ) {
    $.repeatFormInputs = function ($sample_element, $position_element) {
        if($sample_element.data('count')==undefined){
            $sample_element.data('count',1);
        }
        var inputs_index = parseInt($sample_element.data('count'));
        var $form_row = $sample_element.clone();
        $form_row.removeAttr('id');
        var removeButton = $('<button class="btn red btn-xs btn-circle btn-outline" type="button"><i class="fa fa-trash"></i></button>').click(function () {
            $form_row.remove();
        });
        $form_row.find('.tools-box').append(removeButton);
        $form_row.find('input').val('');
        $form_row.find('textarea').val('');
        $form_row.find('.total').text('');
        $form_row.setInputIndex('',inputs_index);
        $form_row.insertBefore($position_element);
        $form_row.calcItemPrices();
        inputs_index++;
        $sample_element.data('count',inputs_index);
    };

    $.fn.setInputIndex = function (old_index, new_index) {
        $(this).find("input,textarea,select").each(function () {
            var the_name = $(this).data('name').split('['+old_index+']').join('['+new_index+']'),
                filed_id = the_name.replace(/\[/g, '_1').replace(/\]/g, '1_');
            $(this).attr('name', the_name).attr('id', filed_id);
        });
        return $(this);
    };

    $.fn.calcItemPrices = function () {
        var $form_row = $(this);
        var $parent = $form_row.parents('table');
        var $total_label = $form_row.find('.total');
        var $price_input = $form_row.find('.price input');
        var $count_input = $form_row.find('.count input');
        var changeFunction = function () {
            var price = $price_input.val()!=''?$price_input.val():0;
            var count = $count_input.val()!=''?$count_input.val():0;
            $total_label.text(price*count);
            $parent.calcInvoicePrices();
        };
        $price_input.change(changeFunction);
        $count_input.change(changeFunction);
    };

    $.fn.calcInvoicePrices = function () {
        function calc(val,precent) {
            var num = parseFloat(parseFloat(val)*parseFloat(precent))/parseFloat(100);
            return num.toFixed(2);
        }
        var subtotal = 0.00;
        var $parent = $(this);
        $parent.find('td.total').each(function () {
            subtotal+=parseFloat($(this).text());
        });
        $parent.find('.subtotal').text(subtotal);
        var $discount = $parent.find('.discount_present input');
        var $vat = $parent.find('.vat_present input');
        var discount_val = $discount.val()!=''?parseInt($discount.val()):0;
        var vat_val = $vat.val()!=''?parseInt($vat.val()):0;
        var discount_label = discount_val!=0?calc(subtotal,discount_val):0;
        var vat_label = vat_val!=0?calc((subtotal-discount_label),vat_val):0;
        var end_total = parseFloat(parseFloat(subtotal)-parseFloat(discount_label)+parseFloat(vat_label));

        $parent.find('.subtotal').text(subtotal.toFixed(2));
        $parent.find('.discount').text('-'+discount_label);
        $parent.find('.vat').text('+'+vat_label);
        $parent.find('.end-total').text(end_total.toFixed(2));
    };

}( jQuery ));