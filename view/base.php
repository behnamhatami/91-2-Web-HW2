<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/17/13
 * Time: 11:04 AM
 * To change this template use File | Settings | File Templates.
 */

function get_header_as_html()
{
    ?>
    <div id="header" class="icon-3x" style="display: block">
        <div style="width: 400px; height: auto; margin: auto">
            <a href="home.php" class="btn-large btn">
                <div class="icon-3x icon-home"></div>
            </a>
            <a href="film.php" class="btn-large btn">
                <div class="icon-film icon-3x"></div>
            </a>
            <a href="schedule.php" class="btn-large btn">
                <div class="icon-calendar icon-3x"></div>
            </a>
<!--            <a class="btn-large btn">-->
<!--                <div class="icon-envelope icon-3x"></div>-->
<!--            </a>-->
<!--            <a class="btn-large btn">-->
<!--                <div class="icon-cog icon-3x"></div>-->
<!--            </a>-->
            <a href="logout.php" class="btn-large btn">
                <div class="icon-off icon-3x"></div>
            </a>
        </div>
    </div>
<?php
}

?>