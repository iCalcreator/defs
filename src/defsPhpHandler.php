<?php
/*********************************************************************************/
/**
 *
 * defs, a PHP key-value definition handle class package, managing keyed data
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
 * defsPhpHandler class
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 1.0 - 2015-05-26
 */
class defsPhpHandler extends defsFileHandler implements defsSourceInterface {
/**
 * fetch values from php file using 'include'
 *
 * multi-dimension array, base key value == nodeid (setup!) or in source['others']
 *
 * @param array   $source
 * @uses defsFileHandler::setupMgnt()
 * @uses defsFileHandler::fileClosure()
 * @throws file read exception
 * @static
 * @return array
 */
  public static function loadData( array $source ) {
    $start  = microtime( TRUE );
    $output = (array) include $source['path'];
    if( isset( $source['setup'] ))
      return parent::setupMgnt( $output, $source['nodeid'] );
    return parent::fileClosure( $output, $source, $start );
  }
}
