/**
 * Created with JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/21/13
 * Time: 5:42 PM
 * To change this template use File | Settings | File Templates.
 */

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

function int_to_time(inp){
    return Math.floor(inp/100) + ':' + inp%100;
}


function sync_started(){
    return $('#sync i').attr('class') == 'icon-refresh icon-spin icon-3x';
}

function start_sync(){
    $('#sync i').attr('class', 'icon-refresh icon-spin icon-3x');
}

function end_sync(){
    $('#sync i').attr('class', 'icon-refresh icon-3x');
}