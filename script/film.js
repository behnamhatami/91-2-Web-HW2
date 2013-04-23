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
    $(document).keydown(function (event) {
        if (event.which == 27) {
            $('.ui-dialog-titlebar-close').click();
        }
    });
    $('.ui-dialog-titlebar-close').click(function () {
        $('.overlay').hide();
        $('#modal iframe').remove();
    });
    var url = "ajax.php";
    $.getJSON(url, {'command': 'get_all_films'}, function (response) {
        add_all_filmbox(response);
    });
    $('#sync').click(function () {
        if (!sync_started()) {
            start_sync();
            $.getJSON(url, {'command': 'update_live_films'}, function (response) {
                add_all_filmbox(response);
                setTimeout(function () {
                    end_sync();
                }, 1000);
            });
        }
    });
});


function truncate_string(str, limit) {
    if (str.length < limit)
        return str;
    else return str.substr(0, limit - 4) + ' ...';
}

function add_all_filmbox(response) {
    for (var i in response) {
        var element = create_filmbox(response[i]);
        $('.film-tube').append(element);
        element.show();
    }
}

function create_filmbox(info) {
    var html_code =
        '<div rid="{0}" name="{1}" class="film-wrapper">\
            <h2 class="film-title">\
            {2}\
            </h2>\
            <div class="film-info">\
                <img class="film-poster" src="{3}">\
                <div class="film-director">\
                    {4}\
                </div>\
            </div>\
        </div>'.format(info.id + '', info.name + '', truncate_string(info.name, 22) + '', info.poster + '', info.directors);
    var element = $(html_code);
    element.hide();
    element.click(function () {
        if ($('#modal iframe').length > 0)
            return;
        var id = $(this).attr('rid');
        var name = $(this).attr('name');
        $('#modal').append('<iframe src="./filmdetail.php?id=' + id + '"></iframe>');
        $('.ui-dialog .ui-dialog-titlebar .ui-dialog-title').text(name);
        $('.overlay').show();
    });
    return element;
}