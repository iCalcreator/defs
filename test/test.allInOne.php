<?php
/**
 * defs, a PHP key-value definition handle class, managing keyed data
 *
 * @package   defs
 * @copyright Copyright (c) 2015 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @author    Kjell-Inge Gustafsson <ical@kigkonsult.se>
 * @link      http://kigkonsult.se/defs/index.php
 * @license   non-commercial use: Creative Commons
 *            Attribution-NonCommercial-NoDerivatives 4.0 International License
 *            (http://creativecommons.org/licenses/by-nc-nd/4.0/)
 *            commercial use :defs1license
 * @version   1.0
 *
 * test.allInOne.php
 *
 */
$allInOne = TRUE;
include 'defs.test.inc.php';
include 'test.simple.php';
include 'test.array.db.php';
include 'test.array.file.php';
include 'test.file.db.php';
include 'test.file.file.php';
include 'test.file.larger.db.php';
include 'test.multi.file.db.php';
endPage();
