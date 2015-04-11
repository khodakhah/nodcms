/**
 * Created with JetBrains PhpStorm.
 * User: MKH
 * Date: 2/15/14
 * Time: 12:39 PM
 * To change this template use File | Settings | File Templates.
 */

    // hidden sidebar in first load
$('#main-content').css({
    'margin-left': '0px'
});
$('#sidebar').css({
    'margin-left': '-210px'
});
$('#sidebar > ul').hide();
$("#container").addClass("sidebar-closed");
// ------------------------------------------------