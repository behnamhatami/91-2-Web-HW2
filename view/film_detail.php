<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/17/13
 * Time: 9:07 AM
 * To change this template use File | Settings | File Templates.
 */

function view_film_detail($id)
{
    $cinemas = get_film_cinemas($id);
    $film = get_film_full_info($id);
    ?>
    <div class="content">
        <div class="imgside">
            <p>
                <img src="<?php echo $film['poster'] ?>" class="image">
            </p>
        </div>
        <div class="film">
            <h1><?php echo $film['name']?></h1>

            <p>
                <label>کارگردان: </label>
                <span class="info"><?php echo $film['directors']?></span>
            </p>

            <p>
                <label>تهیه کننده: </label>
                <span class="info"><?php echo $film['producers']?></span>
            </p>

            <p>
                <label>بازیگران: </label>
                <span class="info"><?php echo $film['actors']?></span>
            </p>
        </div>
    </div>
    <table>
        <tbody>
        <?php
        foreach ($cinemas as $cinema) {
            ?>
            <tr>
                <td>
                    <div class="toggletop" onclick="$(this).next().slideToggle(function(){$(this).parent().find('.cinema_header i').toggle();});">
                        <div class="cinema_header">
                            <i class="icon-chevron-left"></i>
                            <i class="icon-chevron-down" style="display: none"></i>
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
                                <tbody>
                                <?php
                                foreach ($cinema['instance'] as $day => $day_cinema) {
                                    ?>
                                    <tr>
                                        <div class="daybox">
                                            <div class="datebox">
                                                <div style="margin: 10px 3px 10px 3px; display: block;">
                                                    <?php echo $day . ':'?>
                                                </div>
                                            </div>
                                            <?php
                                            foreach ($day_cinema as $scene) {
                                                ?>
                                                <div class="scenebox">
                                                    <div class="start">
                                                        <?php echo intval($scene['time_fr'] / 100) . ':' . ($scene['time_fr'] % 100) ?>
                                                    </div>
                                                    <div class="finish">
                                                        <?php echo intval($scene['time_to'] / 100) . ':' . ($scene['time_to'] % 100) ?>
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
        </tbody>
    </table>
<?php
}

?>
