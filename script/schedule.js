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
    $.getJSON(url, {command: 'get_all'}, function (response) {
        for (var i in response)
            $('#weekday-{0}'.format(response[i].day)).append(get_scene_html(response[i], false));
    });

    $(window).scroll(function (event) {
        // what the y position of the scroll is
        var new_margin = $(this).scrollTop();
        if(new_margin <= 550)
            $('#sidebar').animate({marginTop: (((new_margin - new_margin % 120) / 120) * 120) + 'px'}, "fast");
    });


    $('#search-pain .search-button').click(function () {
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
            for (var i in response) {
                $('#result-list').append(get_list_item(response[i]));
            }
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

function add_scene_attend(id) {
    var url = $('#search_form').attr('action');
    var data = {
        command: 'add',
        id: id
    };
    $.getJSON(url, data);
}

function remove_scene_attend(id) {
    var url = $('#search_form').attr('action');
    var data = {
        command: 'remove',
        id: id
    };
    $.getJSON(url, data);
}

function time_to_int(inp) {
    return (inp - inp % 100) + Math.ceil((inp % 100) / 60 * 100);
}

function get_list_item(info) {
    var li_element = $(('<li><a>{2} ,{1} ,{0}</a></li>').format(info.film_name + '', info.cinema_name + '', info.time_fr + ''));
    li_element.hover(function () {
        console.log(info.id);
        console.log(info);
        var div_element = get_scene_html(info, true);
        div_element.css('background', '-webkit-linear-gradient(top, rgb(174, 243, 207) 0%,rgb(118, 189, 236) 100%)');
        $('#weekday-{0}'.format(info.day)).append(div_element);
    }, function () {
        $('#tp-scene-{0}'.format(info.id + '')).remove();
    })
    li_element.click(function () {
        add_scene_attend(info.id);
        var id_before = 'tp-scene-{0}'.format(info.id + '');
        var id_after = 'scene-{0}'.format(info.id + '');
        if ($('#' + id_after).length > 0)
            return;
        $('#' + id_before).css('background', '-webkit-linear-gradient(top, #f3b3e3 0%, #ec95c7 100%)');
        $('#' + id_before).attr('id', id_after);
    })
    return li_element;
}

function get_scene_html(info, tempo) {
    var base = 62;
    var time_to = time_to_int(info['time_to']);
    var time_fr = time_to_int(info['time_fr']);
    if (time_to < time_fr)
        time_to = time_to + 2400;
    var top = (time_fr - 1100) / 100 * base;
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
        $(this).parent().css('background', '-webkit-linear-gradient(top, #f36d6a 0%, #ec534e 100%)');
        $(this).parent().css('background', '-moz-linear-gradient(center top, #f36d6a 0%, #ec534e 100%) repeat scroll 0% 0% transparent');
    }, function () {
        $(this).parent().css('background', '-webkit-linear-gradient(top, #f3b3e3 0%, #ec95c7 100%)');
        $(this).parent().css('background', '-moz-linear-gradient(center top, #f3b3e3 0%, #ec95c7 100%) repeat scroll 0% 0% transparent');
    });
    element.find('a').click(function () {
        $(this).parent().hide('slow', function () {
            remove_scene_attend(info.id);
            $(this).remove();
        });
    })
    return element;
}

if (!String.prototype.format) {
    String.prototype.format = function () {
        var args = arguments;
        return this.replace(/{(\d+)}/g, function (match, number) {
            return typeof args[number] != 'undefined'
                ? args[number]
                : match
                ;
        });
    };
}