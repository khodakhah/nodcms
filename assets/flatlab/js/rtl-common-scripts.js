/*---LEFT BAR ACCORDION----*/
$(function() {

    $('.fa-bars').click(function () {
        if ($('#sidebar > ul').is(":visible") === true) {
            $('#main-content').css({
                'margin-right': '0px'
            });
            $('#sidebar').css({
                'margin-right': '-210px'
            });
            $('#sidebar > ul').hide();
            $("#container").addClass("sidebar-closed");
        } else {
            $('#main-content').css({
                'margin-right': '210px'
            });
            $('#sidebar > ul').show();
            $('#sidebar').css({
                'margin-right': '0'
            });
            $("#container").removeClass("sidebar-closed");
        }
    });


}();