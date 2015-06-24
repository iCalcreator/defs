<?php
/*********************************************************************************/
/**
 *
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
 */
/*********************************************************************************/
/**
 * defsLoader
 *
 * load defs classes
 *
 * @param     string $class
 * @staticvar string $defsLib
 */
function defsLoader( $class ) {
  static $defsLib = null;
  if( empty( $defsLib ))
    $defsLib      = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
  $file           = $defsLib . $class . '.php';
  if( file_exists( $file ))
    include $file;
}
spl_autoload_register( 'defsLoader' );
