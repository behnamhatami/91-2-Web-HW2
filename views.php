<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/9/13
 * Time: 1:31 PM
 * To change this template use File | Settings | File Templates.
 */

function view_film($film)
{
    include_once('php/util.php');
    include_once('php/info_database.php');
    ?>
    <div rid="<?php echo $film['id'] ?>" name="<?php echo $film['name'] ?>" class="film-wrapper">
        <h2 class="film-title">
            <?php
            echo truncate_string($film['name'], 35);
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
         style="display: none; z-index: 1002; outline: 0px; position: absolute; height: auto; width: 1163px; top: 46px; left: 46px; margin: 0; line-height: 1.25em;">
        <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
            <span class="ui-dialog-title">[name|="value"]</span>

            <div class="ui-dialog-titlebar-close ui-corner-all" style="cursor: pointer;">
                <span class="ui-icon ui-icon-closethick">close</span>
            </div>
        </div>
        <div id="modal" class="ui-dialog-content ui-widget-content"
             style="padding: 5px 0 0; width: auto; min-height: 0px; height: 539.4px;" scrolltop="0" scrollleft="0">
        </div>
    </div>
    <div class="content-body big-content-body">
        <?php
        foreach (get_films() as $film) {
            view_film($film);
        }
        ?>
        <br style="clear: both"/>
    </div>
<?php
}

?>

<?php
function view_detailed_film($id)
{
    $cinemas = get_film_cinemas($id);
    $film = get_film_full_info($id);
    ?>
    <div class="content">
        <div class="imgside">
            <p>
                <img src="<?php echo $film['poster']?>" alt="پستر فیلم <?php echo $film['name']?>" class="image">
            </p>
        </div>
        <div class="film">
            <h1><?php echo $film['name']?></h1>
            <p>
                <label>کارگردان:</label>
                <span class="info"><?php echo $film['directors']?></span>
            </p>
            <p>
                <label>تهیه کننده:</label>
                <span class="info"><?php echo $film['producers']?></span>
            </p>
            <p>
                <label>بازیگران:</label>
                <span class="info"><?php echo $film['actors']?></span>
            </p>
        </div>
    </div>
    <table><tbody>
    <?php
    foreach ($cinemas as $cinema) {
        ?>
        <tr>
            <td>
                <div class="toggletop" onclick="$(this).next().slideToggle();">
                    <div class="cinema_header">
                        <strong>
                            <?php echo $cinema['name']?>
                        </strong>
                        <span style="font-size:8pt">
                            <?php echo $cinema['address'] . '، تلفن: ' . $cinema['phone']?>
                        </span>
                    </div>
                </div>
                <div class="togglebox" style="display: none;">
                    <div class="scene">
                        <table style="border-collapse: collapse;">
                            <tabody>
                                <?php
                                foreach ($cinema['instance'] as $day=>$day_cinema) {
                                    ?>
                                    <tr>
                                        <div class="daybox">
                                            <div class="datebox">
                                                <div style="margin: 10px 3px 10px 3px; display: block;">
                                                    <?php echo $day.':'?>
                                                </div>
                                            </div>
                                            <?php
                                            foreach ($day_cinema as $scene) {
                                            ?>
                                                <div class="scenebox">
                                                    <div class="start">
                                                        <?php echo intval($scene['time_fr']/100).':'.($scene['time_fr']%100) ?>
                                                    </div>
                                                    <div class="finish">
                                                        <?php echo intval($scene['time_to']/100).':'.($scene['time_to']%100) ?>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <div style="clear: both;"></div>
                                        </div>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody></table>
<?php
}
?>

<?php
function view_search_pain(){

}
?>