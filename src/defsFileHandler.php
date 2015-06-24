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
 * defsFileHandler class
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 1.0 - 2015-05-10
 */
class defsFileHandler implements defsSourceInterface {
/**
 * @var array $logtxt    log texts
 * @access protected
 * @static
 */
  protected static $logtxt  = array( '410, can\'t open \'%s\'',
                                     '411, missing or non-matching nodeid, expected %s, got \'%s\'',
                                     '%s 412, fetched %d base keys from %s, %s sec',
                                     '413, extension missing for \'%s\', can\'t find any file',
                                     '414, file \'%s\' no file, not readable or empty',
                                     '415, source \'path\' missing',
                                   );
/**
 * check required source args and, opt, add default parts
 *
 * @param array $source
 * @uses defs::log()
 * @uses defsFileHandler::$logtxt
 * @uses defsFileHandler::fixExtension()
 * @uses defsFileHandler::isValidFile()
 * @throws file error
 * @static
 * @return bool
 */
  public static function checkSourceArgs( array & $source ) {
    if( ! isset( $source['path'] ))
       throw new Exception( defsFileHandler::$logtxt[5] );
    try {
      defsFileHandler::fixExtension( $source );
    }
    catch( Exception $e ) {
      throw $e;
    }
    if( ! defsFileHandler::isValidFile( $source['path'] ))
      throw new Exception( sprintf( defsFileHandler::$logtxt[4], $source['path'] ));
    return TRUE;
  }
/**
 * check if specific major key[s] is set in source and if so, return data or else empty array
 *
 * @param array  $data
 * @param array  $source
 * @access protected
 * @static
 * @return bool
 */
  final protected static function checkKey( array $data, array $source ) {
    $output    = array();
    $nodeidKey = ( isset( $source['pointer'] )) ? $source['pointer'] : 'nodeid';
    if( isset( $data[$nodeidKey] ) && ( $data[$nodeidKey] = $source['nodeid'] ))
      $output[$data[$nodeidKey]] = array();
    if( isset( $source['others'] )) {
      foreach( $source['others'] as $other ) {
        if( isset( $data[$other] ))
          $output[$other] = array();
      }
    }
    if( empty( $output ))
      return array();
    $hKey      = $data[$nodeidKey];
    foreach( $data as $k => $v ) {
      if( array_key_exists( $k, $output ) && empty( $output[$k] ))
        $hKey  = $v;
      else
        $output[$hKey][$k] = $v;
    }
    return $output;
  }
/**
 * file mngt closure
 *
 * @param array  $data
 * @param array  $source
 * @param float  $start
 * @uses defs::log()
 * @uses defsFileHandler::$logtxt
 * @access protected
 * @static
 * @return array
 */
  final protected static function fileClosure( array $data, array $source, $start ) {
    $cnt    = 0;
    foreach( $data as $baseKeys )
      $cnt += count($baseKeys );
    defs::log( sprintf( defsFileHandler::$logtxt[2], $source['nodeid'], $cnt, $source['path'], number_format(( microtime( TRUE ) - $start ), 6 )), LOG_DEBUG );
    return $data;
  }
/**
 * check for extension, look for and set it and source, if missing
 *
 * @param array $source
 * @uses defsSourceHandler::$handlers
 * @uses defsFileHandler::isValidFile()
 * @uses defsFileHandler::$logtxt
 * @throws file extension error
 * @access private
 * @static
 * @return bool
 * @todo fix validExts, i.e. subset of defsSourceHandler::$handlers
 */
  final private static function fixExtension( array & $source ) {
    $ext = strtolower( pathinfo( $source['path'], PATHINFO_EXTENSION ));
    if( empty( $ext )) {
      $validExts = array_keys( defsSourceHandler::$handlers );
      foreach( $validExts as $x ) {
        if( in_array( $x, array( 'file', 'mysqli' )))
          continue;
        $f = $source['path'] . '.' . $x;
        if( defsFileHandler::isValidFile( $f )) {
          $source['source'] = $x;
          $source['path']   = $f;
          return TRUE;
        }
      }
      if( empty( $ext ))
        throw new Exception( sprintf( defsFileHandler::$logtxt[3], $source['path'] ));
    }
    if( ! isset( $source['source'] ) || empty( $source['source'] ) || ( 'file' == strtolower( $source['source'] )))
      $source['source'] = $ext;
    return TRUE;
  }
/**
 * manages setup directives
 *
 * @param string $file
 * @access private
 * @static
 * @return bool
 */
  final private static function isValidFile( $file ) {
    if( ! is_file( $file  ))
      return FALSE;
    if( ! is_readable( $file ))
      return FALSE;
    $filesize = filesize( $file );
    return ( empty( $filesize )) ? FALSE : TRUE;
  }
/**
 * load data from source
 *
 * @param array  $source
 * @throws source type not found exception
 * @static
 * @return array
 */
  public static function loadData( array $source ) {
    throw new Exception( sprintf( defsFileHandler::$logtxt[3], $source['source'] ));
  }
/**
 * manages setup directives
 *
 * @param array  $setup
 * @param string $nodeid
 * @uses defsFileHandler::$logtxt
 * @throws file missing key word exception
 * @access protected
 * @static
 * @return array
 */
  final protected static function setupMgnt( array $setup, $nodeid ) {
    if( ! isset( $setup['nodeid'] ) || ( $nodeid != $setup['nodeid'] ))
      throw new Exception( sprintf( defsFileHandler::$logtxt[1], $nodeid, var_export( $setup, TRUE )));
    return $setup;
  }
}
