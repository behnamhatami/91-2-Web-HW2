<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/9/13
 * Time: 1:31 PM
 * To change this template use File | Settings | File Templates.
 */


function get_schedue_as_html()
{
    ?>
    <div id="main-content">
        <div id="sidebar">
            <div id="sidebar-header" style="font-family: 'B Titr Bold'; font-size: 17px;">
                جستجوی سانس
            </div>

            <div id="search-pain">
                <div class="container" style="width: 90%;">
                    <form id="search_form" action="ajax.php" class="form-horizontal" method="get" style="width: 100%;">
                        <table style="width: 100%;" class="table">
                            <tr>
                                <th><label for="id_film_name">نام فيلم:</label></th>
                                <td><input id="id_film_name" maxlength="64" name="film_name" type="text"/></td>
                            </tr>
                            <tr>
                                <th><label for="id_cinema_name">نام سينما:</label></th>
                                <td><input id="id_cinema_name" maxlength="64" name="cinema_name" type="text"/></td>
                            </tr>
                            <tr>
                                <th><label for="id_city_name">نام شهر:</label></th>
                                <td><input id="id_city_name" maxlength="64" name="city_name" type="text"/></td>
                            </tr>
                            <tr>
                                <th><label for="id_from_date">از تاريخ:</label></th>
                                <td><input id="id_from_date" name="from_date" type="date"/></td>
                            </tr>
                            <tr>
                                <th><label for="id_date_to">تا تاريخ:</label></th>
                                <td><input id="id_date_to" name="date_to" type="date"/></td>
                            </tr>
                            <tr>
                                <th><label for="id_from_time">زمان شروع:</label></th>
                                <td><input id="id_from_time" name="from_time" type="time"/></td>
                            </tr>
                            <tr>
                                <th><label for="id_time_to">زمان پايان:</label></th>
                                <td><input id="id_time_to" name="time_to" type="time"/></td>
                            </tr>
                        </table>
                        <div class="btn btn-primary search-button">
                            نمایش نتایج جستجو
                        </div>
                    </form>
                </div>
            </div>
            <div id="result-pain" style="display: none;">
                <ul id="result-list">
                </ul>
                <br>

                <div class="btn btn-primary search-again">
                    جستجوی دوباره
                </div>
                <br>
            </div>
            <div id="loading" style="display: none;">
                <i class="icon-spinner icon-spin icon-2x"></i>
            </div>
        </div>
        <div id="content-body">
            <div id="grid">
                <div id="hour-column">
                    <?php
                    for ($i = 8; $i <= 26; $i++) {
                        echo ($i % 24);
                        if ($i % 24 <= 12)
                            echo ' AM';
                        else echo ' PM';
                        echo '<br>';
                    }
                    ?>
                </div>
                <div id="week-columns">
                    <?php
                    for ($i = 0; $i < 7; $i++) {
                        ?>
                        <div class="column">
                            <div class="header">
                                <img src="images/weekdays/<?php echo $i; ?>.png">
                            </div>
                            <div id="weekday-<?php echo $i; ?>" class="events">
                                <?php
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <br style="clear: both">
                </div>
                <br style="clear: both;">
            </div>
        </div>
        <br style="clear:both;">
    </div>
<?php
}

?>
