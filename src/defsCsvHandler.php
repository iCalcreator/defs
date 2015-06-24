<?php
/*********************************************************************************/
/**
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
 * defsCsvHandler class
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 1.0 - 2015-05-26
 */
class defsCsvHandler extends defsFileHandler implements defsSourceInterface {
/**
 * fetch values from csv file
 *
 * column one == nodeid (setup!) or in source['others']
 * 1-4 key columns
 * value in last column
 *
 * @param array   $source
 * @uses defsFileHandler::$logtxt
 * @uses defsFileHandler::setupMgnt()
 * @uses defsFileHandler::fileClosure()
 * @throws file read exception
 * @static
 * @return array
 */
  public static function loadData( array $source ) {
    $start  = microtime( TRUE );
    $setup  = ( isset( $source['setup'] )) ? TRUE : FALSE;
    if( FALSE === ( $handle = @fopen( $source['path'], "r" )))
      throw new Exception( sprintf( parent::$logtxt[0], $source['path'] ));
    $output = array();
    while( FALSE !== ( $row = fgetcsv( $handle ))) {
      if( empty( $row[0] ))
        continue;
      $k    = $row[0];
      if( $setup )
        $i  = 0;
      else {
       if(( $source['nodeid'] != $k ) &&
          ( ! isset( $source['others'] ) || ! in_array( $k, $source['others'] )))
          continue;
        $i  = 1;
      }
      $c    = 0;
      $vx   = count( $row ) - 1; // last element always value
      while(( $i < $vx ) && isset( $row[$i] ) && ( ! empty( $row[$i] ) || ( '0' == $row[$i] )))
        $c  = $i++;              // count key elements
      switch( $c ) {
        case 4:
          $output[$k][$row[1]][$row[2]][$row[3]][$row[4]] = $row[$vx];
          break;
        case 3:
          $output[$k][$row[1]][$row[2]][$row[3]]          = $row[$vx];
          break;
        case 2:
          $output[$k][$row[1]][$row[2]]                   = $row[$vx];
          break;
        case 1:
          $output[$k][$row[1]]                            = $row[$vx];
          break;
        case 0:
          $output[$k]                                     = $row[$vx];
          break;
        default:
          break;
      }
    }
    fclose( $handle );
    unset( $handle );
    if( $setup )
      return parent::setupMgnt( $output, $source['nodeid'] );
    return parent::fileClosure( $output, $source, $start );
  }
}
