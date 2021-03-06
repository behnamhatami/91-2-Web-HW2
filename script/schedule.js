/**
 * Created with JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/17/13
 * Time: 8:55 AM
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function () {
    $('#header .icon-calendar').css("color", "#004d75");

    var url = $('#search_form').attr('action');
    show_loading();
    $.getJSON(url, {command: 'get_all_scene_attend'}, function (response) {
            for (var i in response)
                $('#weekday-{0}'.format(response[i].day)).append(get_scene_html(response[i], false));
            hide_loading();
        }
    );

    $(window).scroll(function (event) {
        var top = $(this).scrollTop();
        if (top <= 135) {
            $('#sidebar').css('position', 'relative');
            $('#sidebar').css('margin-top', '4px');
            $('#sidebar').css('width', '24.1%');
            $('#sidebar').css('margin-right', '0');
            return;
        } else {
            $('#sidebar').css('position', 'fixed');
            $('#sidebar').css('margin-top', '-135px');
            $('#sidebar').css('width', '22.5%');
            $('#sidebar').css('margin-right', '69.3%');
        }
    });


    $('#search-pain .search-button').click(function () {
        show_loading();
        var data = {
            command: 'search',
            film_name: $('#id_film_name').val(),
            cinema_name: $('#id_cinema_name').val(),
            from_time: $('#id_from_time').val(),
            time_to: $('#id_time_to').val(),
            from_date: $('#id_from_date').val(),
            date_to: $('#id_date_to').val(),
            city_name: $('#id_city_name').val()
        };
        var url = $('#search_form').attr('action');
        $.getJSON(url, data, function (response) {
            $('#result-list li').remove();
            for (var i in response)
                $('#result-list').append(get_list_item(response[i]));
            hide_loading();
        });
        $('#search-pain').slideToggle('slow', function () {
            $('#result-pain').slideToggle('slow');
        });
    });
    $('#result-pain .search-again').click(function () {
        $('#result-pain').slideToggle('slow', function () {
            $('#search-pain').slideToggle('slow');
        });
    });
});

function change_color_scene(element, first, last, border_color) {
    element.css('background', '-webkit-linear-gradient(top, {0} 0%, {1} 100%)'.format(first + '', last + ''));
    element.css('background', '-moz-linear-gradient(center top, {0} 0%, {1} 100%) repeat scroll 0% 0% transparent'.format(first + '', last + ''));
    element.css('border', '1px solid {0}'.format(border_color + ''));

}

function show_loading() {
    $('#loading').slideToggle();
}

function hide_loading() {
    setTimeout(function () {
            $('#loading').slideToggle();
        }, 1000
    );
}

function add_scene_attend(id) {
    show_loading();
    var url = $('#search_form').attr('action');
    var data = {
        command: 'add',
        id: id
    };
    $.getJSON(url, data, function (response) {
        hide_loading();
    });
}

function remove_scene_attend(id) {
    show_loading();
    var url = $('#search_form').attr('action');
    var data = {
        command: 'remove',
        id: id
    };
    $.getJSON(url, data, function (response) {
        hide_loading();
    });
}

function time_to_int(inp) {
    return (inp - inp % 100) + Math.ceil((inp % 100) / 60 * 100);
}

function int_to_day(inp) {
    var days = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
    return days[inp];
}

function get_list_item(info) {
    var html_code =
        '<li>\
            <div class="tooltip">\
                <h4>{0}</h4>\
                <br>\
                <label>کارگردان: </label>\
                    {4}\
                <br>\
                <label>بازیگران: </label>\
                    {5}\
                <br>\
                <label>تهیه‌کنندگان: </label>\
                    {6}\
                <br>\
            </div>\
            <a>\
                <span>\
                    {2}\
                </span>\
                <span style="width: 55px">\
                    {3}\
                </span>\
                &nbsp &nbsp{1}، {0}\
            </a>\
         </li>'.format(info.film_name + '', info.cinema_name + '', int_to_time(info.time_fr) + ' ',
                int_to_day(info.day), info.directors, info.actors, info.producers);
    var li_element = $(html_code);
    li_element.hover(function () {
        console.log(info.id);
        var div_element = get_scene_html(info, true);
        change_color_scene(div_element, '#6cb8d5', '#94C3D5', '#246B86');
        $('#weekday-{0}'.format(info.day)).append(div_element);
        $(this).find('.tooltip').show();
    }, function () {
        $('#tp-scene-{0}'.format(info.id + '')).remove();
        $(this).find('.tooltip').hide();
    })
    li_element.click(function () {
        add_scene_attend(info.id);
        var id_before = 'tp-scene-{0}'.format(info.id + '');
        var id_after = 'scene-{0}'.format(info.id + '');
        if ($('#' + id_after).length > 0)
            return;
        change_color_scene($('#' + id_before), '#EAC467', '#f0dba4', '#D68A36');
        $('#' + id_before).attr('id', id_after);
    })
    return li_element;
}

function get_scene_html(info, tempo) {
    var base = 62;
    var time_to = time_to_int(info['time_to']);
    var time_fr = time_to_int(info['time_fr']);
    if (time_fr == 0)
        time_fr = 2400;
    if (time_to < time_fr)
        time_to = time_to + 2400;
    var top = (time_fr - 800) / 100 * base;
    var height = (time_to - time_fr) / 100 * base;
    var id = "scene-{0}".format(info.id);
    if (tempo)
        id = "tp-scene-{0}".format(info.id);


    var element = ('<div id="{0}" class="event event-final" style="top:{1}px; height:{2}px;">' +
        '<a style="display: none;" class="del-button"></a>' +
        '<p class="film-name">{3}</p>' +
        '<p class="cinema-name">{4}</p>' +
        '<p class="film-director">{5}</p>' +
        '</div>').format(id + '', top + '', height + '', info.film_name, info.cinema_name, info.directors);
    element = $(element);
    element.hover(function () {
        $(this).find('a').css('display', 'block');
    }, function () {
        $(this).find('a').css('display', 'none');
    });
    element.find('a').hover(function () {
        change_color_scene($(this).parent(), '#f08A75', '#f0b2a4', '#A03018');
    }, function () {
        change_color_scene($(this).parent(), '#EAC467', '#f0dba4', '#D68A36');
    });
    element.find('a').click(function () {
        $(this).parent().hide('slow', function () {
            remove_scene_attend(info.id);
            $(this).remove();
        });
    })
    return element;
}