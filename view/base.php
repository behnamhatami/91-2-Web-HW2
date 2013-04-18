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
        <div style="width: 500px; height: auto; margin: auto">
            <a href="index.php" class="btn-large btn">
                <li class="icon-3x icon-home"></li>
            </a>
            <a href="film.php" class="btn-large btn">
                <li class="icon-film icon-3x"></li>
            </a>
            <a href="schedule.php" class="btn-large btn">
                <li class="icon-calendar icon-3x"></li>
            </a>
            <a class="btn-large btn">
                <li class="icon-envelope icon-3x"></li>
            </a>
            <a class="btn-large btn">
                <li class="icon-cog icon-3x"></li>
            </a>
        </div>
    </div>
<?php
}

?>