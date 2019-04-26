(function ( $ ) {
    $.fn.requestStatusChange = function(url)
    {
        var $this = $(this);
        var current_content = $this.html();
        $.ajax({
            url: url,
            dataType: "json",
            beforeSend:function(){
                $this.html('<i class="fa fa-spinner fa-pulse"></i>');
            },
            success:function (result) {
                if(result.status == "success") {
                    if(typeof result.data=='undefined'){
                        toastr.error("Ajax undefined result.", translate('Error'));
                        $this.html(current_content);
                    }else{
                        $this.html(result.data);
                    }
                }
                else{
                    toastr.error(result.error, translate('Error'));
                    $this.html(current_content);
                }
            },
            error: function (xhr, status, error) {
                // var err = eval("(" + xhr.responseText + ")");
                // toastr.error(result.error, translate('Error'));
                // console.log(xhr.responseText);
                $this.html('<div class="note note-danger"><h4 class="title">Error</h4><p>Ajax failed: '+error+'!</p><div>'+xhr.responseText+'</div></div>');
            },
            complete: function () {
            }
        });
    };

    $.fn.changeRequestInvoice = function (url) {
        var the_element = $(this);
        $.ajax({
            url:url,
            dataType:"json",
            beforeSend: function () {
                the_element.addClass("disabled");
                the_element.append($('<i class="fa fa-spinner fa-pulse"></i>'));
            },
            complete: function () {
                the_element.removeClass("disabled");
                the_element.find('i.fa-spinner.fa-pulse').remove();
            },
            success: function (result) {
                if(result.status == "success"){
                    toastr.success(result.msg, translate('Success'));
                    $(the_element.data('reload')).loadIn(the_element.data('url'));
                }else{
                    toastr.error(result.error, 'Error')
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
                toastr.error(translate('Send form with ajax failed!'), translate('Error'));
            },
        });
    }
}( jQuery ));
$(function () {
    $('#status-box').loadIn($('#status-box').attr('data-status-box-load'));
});