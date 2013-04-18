/**
 * Created with JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/10/13
 * Time: 3:51 PM
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function () {
    $('#header .icon-film').css("color", "#004d75");
    $('.ui-draggable').draggable();
    $('.ui-dialog-titlebar-close').click(function () {
        $('.ui-draggable').hide();
        $('#modal iframe').remove();
    });
    $('.film-wrapper').click(function () {
        if ($('#modal iframe').length > 0)
            return;
        var id = $(this).attr('rid');
        var name = $(this).attr('name');
        $('#modal').append('<iframe src="./filmdetail.php?id=' + id + '"></iframe>');
        $('.ui-dialog .ui-dialog-titlebar .ui-dialog-title').text(name);
        $('.ui-draggable').show();
    });
});