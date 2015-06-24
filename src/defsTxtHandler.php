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
 * defsTxtHandler class
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 1.0 - 2015-05-26
 */
class defsTxtHandler extends defsFileHandler implements defsSourceInterface {
/**
 * fetch values from txt file
 *
 * key/value rows delimited by '='
 * nodeid key value == nodeid (setup!) or in source['others']
 *
 * @param array   $source
 * @uses defsFileHandler::$logtxt
 * @uses defsFileHandler::setupMgnt()
 * @uses defsFileHandler::checkKey()
 * @uses defsFileHandler::fileClosure()
 * @throws file read exception
 * @static
 * @return array
 */
  public static function loadData( array $source ) {
    static $cC = array( '#', ';', '/' );  // comment chars
    static $eq = '=';                     // equal char
    $start     = microtime( TRUE );
    $output    = array();
    if( FALSE === ( $rows = file( $source['path'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES )))
      throw new Exception( sprintf( parent::$logtxt[0], $source['path'] ));
    foreach( $rows as $row ) {
      $row     = ltrim( $row );
      if( in_array( $row[0], $cC ) || ( FALSE === strpos( $row, $eq )))
        continue;
      list( $k, $v ) = explode( $eq, $row, 2 );
      $k       = trim( trim( $k ), '"' );
      $v       = trim( trim( $v ), '"' );
      $output[$k] = $v;
    }
    unset( $rows );
    if( isset( $source['setup'] ))
      return parent::setupMgnt( $output, $source['nodeid'] );
    $output    = parent::checkKey( $output, $source );
    return parent::fileClosure( $output, $source, $start );
  }
}
