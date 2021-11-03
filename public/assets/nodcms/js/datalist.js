(function ( $ ) {
    $.fn.removeRow = function () {
        var the_element = $(this);
        $.ajax({
            url:the_element.data('url'),
            dataType:"json",
            method:"post",
            data:{'request':the_element.data('request'), 'id':the_element.data('id')},
            beforeSend: function () {
                the_element.addClass("disabled");
                the_element.find('i.fa-refresh').addClass("fa-pulse");
            },
            complete: function () {
                the_element.removeClass("disabled");
                the_element.find('i.fa-refresh').removeClass("fa-pulse");
            },
            success: function (result) {
                if(result.status == "success"){
                    var the_parent = the_element.parents('tr');
                    var main_parent = the_parent.parents('tbody');
                    the_parent.remove();
                    if(result.result != "undefined"){
                        main_parent.append(result.result);
                        main_parent.find('.btn-ask').each(function(){
                            $(this).makeConfirmationBtn();
                        });
                    }
                }else{
                    toastr.error(result.error, translate('Error'))
                }
            },
            error: function (xhr, status, error) {
                $.showInModal(translate('Error')+': '+translate('Ajax failed!'), '<div class="alert alert-danger">' +
                    '<h4>'+translate('Error')+'</h4>' +
                    error +
                    '</div>' +
                    '<h4>'+translate('Result')+'</h4>' +
                    xhr.responseText);
            }
        });
    };
}( jQuery ));