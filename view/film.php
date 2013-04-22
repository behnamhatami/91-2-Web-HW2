<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/17/13
 * Time: 10:10 AM
 * To change this template use File | Settings | File Templates.
 */

function view_film($film)
{
    ?>
    <div rid="<?php echo $film['id'] ?>" name="<?php echo $film['name'] ?>" class="film-wrapper">
        <h2 class="film-title">
            <?php
            echo truncate_string($film['name'], 22);
            ?>
        </h2>

        <div class="film-info">
            <img class="film-poster"
                 src="<?php echo $film['poster'] ?>">

            <div class="film-director">
                <?php echo $film['directors']?>
            </div>
        </div>
    </div>
<?php
}

?>

<?php
function view_all_films()
{
    ?>
    <div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable"
         style="display: none; z-index: 1002; outline: 0; position: absolute; height: auto; width: 1163px; top: 46px; left: 46px; margin: 0; line-height: 1.25em;">
        <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
            <span class="ui-dialog-title">[name|="value"]</span>

            <div class="ui-dialog-titlebar-close ui-corner-all" style="cursor: pointer;">
                <span class="ui-icon ui-icon-closethick">close</span>
            </div>
        </div>
        <div id="modal" class="ui-dialog-content ui-widget-content"
             style="padding: 5px 0 0; width: auto; min-height: 0; height: 539.4px;" scrolltop="0" scrollleft="0">
        </div>
    </div>
    <div class="content-body big-content-body">
        <div class="film-tube">
<!--                        --><?php
            //                        foreach (get_all_films() as $film) {
            //                            view_film($film);
            //                        }
            //                        ?>
        </div>
        <br style="clear: both"/>

        <div id="sync" class="btn btn-block" style="margin: auto; width: 215px">
            <i class="icon-3x icon-refresh"></i>
        </div>
        <br style="clear: both"/>
    </div>
<?php
}

?>

<?php
function get_scene_html($info)
{
    $base = 62;
//    $info = array('id' => 1, 'film_name' => 'حوض نقاشی', 'cinema_name' => 'بهنام', 'director' => 'بهنام حاتمی',
//        'time_fr' => 1200, 'time_to' => 1345);
    $top = (time_to_int($info['time_fr']) - 1100) / 100 * $base;
    $height = time_to_int($info['time_to']) - time_to_int($info['time_fr']);
    $height = ($height / 100) * $base;

    ?>
    <div class="event scene-<?php echo $info['id'] ?> event-final"
         style="top: <?php echo $top ?>px; height: <?php echo $height ?>px; width: 95%; z-index: 0;">
        <a style="" class="del-button"></a>

        <p class="film-name"><?php echo $info['film_name']?></p>

        <p class="cinema-name"><?php echo $info['cinema_name']?></p>

        <p class="film-director"><?php echo $info['director']?></p>
    </div>
<?php
}
?>
