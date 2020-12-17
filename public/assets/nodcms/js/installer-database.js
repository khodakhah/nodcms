(function($){
    $.submitTableInstaller = function (forms, button) {
        var $this = forms.shift();
        $this.submit(function (e) {
            e.preventDefault();
            var inputs = {}, index = 0;
            $this.find('input[type="radio"]:checked,input[type="checkbox"]:checked,input[type="hidden"],input[type="text"],input[type="password"],input[type="email"],input[type="url"],input[type="number"],textarea,select').each(function () {
                if(typeof $(this).attr('name') !== typeof undefined){
                    inputs[$(this).attr('name')] = $(this).val();
                    index++;
                }
            });
            $.ajax({
                url: $this.attr('action'),
                data: inputs,
                type: $this.attr('method'),
                dataType: 'json',
                beforeSend: function () {
                    $this.find("i.fa").removeClass("fa-check fa-times fa-warning").addClass('fa-spinner fa-pulse');
                    $this.find('.form-error').remove();
                },
                complete: function () {
                },
                success: function(result) {
                    if(result.status == 'success') {
                        $this.find("i.fa").removeClass("fa-spinner fa-pulse font-grey-mint").addClass('fa-check text-success');
                    }
                    else{
                        if(typeof result.error === 'object') {
                            $.each(result.error, function (key, val) {
                                var error_message = '<div class="form-error font-red"><i class="fa fa-exclamation-circle"></i> ' + val + '</div>';
                                $(error_message).insertAfter($this.find('input[name="'+key+'"]'));
                            });
                        }
                        else{
                            $this.append($('<div class="form-error font-red">' + result.error + '</div>'));
                        }
                        $this.find("i.fa").removeClass("fa-spinner fa-pulse").addClass('fa-warning');
                    }

                    if(forms.length > 0) {
                        $.submitTableInstaller(forms, button);
                        return;
                    }
                    var $forwardButton = $('<a href="'+button.data('next')+'" class="btn btn-primary">Go forward <i class="fa fa-arrow-alt-circle-right"></i></a>');
                    button.removeAttr('disabled').removeClass('disabled').find('i.fa').remove();
                    button.after($forwardButton);
                    $.showInModal("Success", "The database has been completely built. Now you can go forward and complete the installation process.", $forwardButton.clone());
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    $this.find("i.fa").removeClass("fa-spinner fa-pulse").addClass('fa-warning');
                    $this.append($('<div class="form-error font-red">Send form with ajax failed!</div>'));
                }
            });
        });
        $this.submit();
    };
})(jQuery);

$(function () {
    $('button[data-role="submit-installer"]').click(function () {
        var $this = $(this);
        if($this.hasClass('disabled')) {
            return;
        }
        var forms = [];
        $('form[data-submit="ajax"]').each(function () {
            forms.push($(this));
        });
        if(forms.length > 0) {
            $.submitTableInstaller(forms, $this);
            $this.attr('disabled','disabled').addClass('disabled').append($('<i class="fa fa-spinner fa-pulse ml-2 d-inline-block"></i>'));
            return;
        }
        $.showInModal("Something Wrong", "There isn't any table to install!");
    });
});