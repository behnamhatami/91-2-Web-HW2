/**
 * Created with JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/22/13
 * Time: 3:24 PM
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function () {
    end_sync();
    var url = "ajax.php";
    var id = $('.content').attr('rid');
    $.getJSON(url, {command: 'get_cinemas_that_show_film', id: id}, function (response) {
        (create_cinemas_that_show_film(response)).insertAfter($('.content'));
    })
    $('#sync').click(function () {
        start_sync();
        $.getJSON(url, {command: 'update_film_scenes', id: id}, function (response) {
            replace_new_table(create_cinemas_that_show_film(response));
            end_sync();
        });
    });
});


function replace_new_table(table) {
    $('.table-container').slideUp('slow', function () {
        $('.table-container').remove();
        table.hide();
        table.insertAfter($('.content'));
        table.slideDown('slow');
    });
}

function create_scene(scene) {
    var html_code =
        '<div class="scenebox">\
            <div class="start">\
                {0}\
            </div>\
            <div class="finish">\
                {1}\
            </div>\
        </div>'.format(int_to_time(scene.time_fr) + '', int_to_time(scene.time_to) + '');
    return $(html_code);
}

function create_scenes_in_special_day(day, scenes) {
    var html_code =
        '<div class="daybox">\
            <div class="datebox">\
                <div style="margin: 10px 3px 10px 3px; display: block;">\
                    {0}:\
                </div>\
            </div>\
            <div class="clear" style="clear: both;"></div>\
         </div>'.format(day + '');

    var element = $(html_code);
    for (var i in scenes)
        create_scene(scenes[i]).insertBefore(element.find('.clear'));
    return element;
}

function create_cinema_scenes(cinema) {
    var html_code =
        '<div class="togglebox" style="display: none;">\
            <div class="scene">\
            </div>\
        </div>';
    var element = $(html_code);
    for (var day in cinema)
        element.find('.scene').append(create_scenes_in_special_day(day, cinema[day]));
    return element;
}


function create_cinema(cinema) {
    var html_code =
        '<tr>\
            <td>\
                <div class="toggletop">\
                    <div class="cinema_header">\
                        <i class="icon-chevron-left"></i>\
                        <i class="icon-chevron-down" style="display: none"></i>\
                        <strong>\
                            {0}\
                        </strong>\
                        <span style="font-size:8pt">\
                        {1}، تلفن: {2}\
                        </span>\
                    </div>\
                </div>\
            </tr>\
        </td>'.format(cinema.name + '', cinema.address + '', cinema.phone + '');

    var element = $(html_code);
    element.find('.toggletop').parent().append(create_cinema_scenes(cinema.instance));

    element.click(function () {
        $(this).find('.toggletop').next().slideToggle(function () {
            $(this).parent().find('.cinema_header i').toggle();
        });
    });
    return element;
}

function create_cinemas_that_show_film(info) {
    var html_code =
        '<div class="table-container">\
            <table>\
                <tbody>\
                </tbody>\
            </table>\
        </div>';

    var element = $(html_code);
    for (var i in info)
        element.find('tbody').append(create_cinema(info[i]));
    return element;
}