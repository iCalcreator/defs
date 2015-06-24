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
 * defsSourceHandler class
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 1.0 - 2015-05-26
 */
class defsSourceHandler implements defsSourceInterface {
/**
 * @var array handlers    source class handler types
 * @static
 */
  public static $handlers = array( 'csv'    => 'defsCsvHandler',
                                   'file'   => 'defsFileHandler',
                                   'ini'    => 'defsIniHandler',
                                   'php'    => 'defsPhpHandler',
                                   'po'     => 'defsPOHandler',
                                   'txt'    => 'defsTxtHandler',
                                   'mysqli' => 'defsMysqlHandler',
                                 );
/**
 * @var array $logtxt       log texts
 * @access private
 * @static
 */
  private static $logtxt  = array( '310, source type \'%s\' is not supported',
                                   '%s 311, %s, %s',
                                   '312, missing source (and path) argument',
                                 );
/**
 * check required args and, opt, add default parts
 *
 * @param array $args
 * @uses defsSourceHandler::getHandler()
 * @uses defs*hndlr::checkSourceArgs()
 * @static
 * @return bool
 */
  public static function checkSourceArgs( array & $args ) {
    foreach( $args['source'] as & $source ) {
      if( isset( $source['error'] ))
        continue;
      if( ! isset( $source['source'] )) {
        if( isset( $source['path'] ))
          $source['source'] = 'file';
        else {
          defs::log( sprintf( defsSourceHandler::$logtxt[2], $source['nodeid'] ), LOG_ERR );
          $args['error'] = $source['error'] = TRUE;
          return FALSE;
        }
      }
      try {
        $handler = defsSourceHandler::getHandler( $source['source'] );
        $handler::checkSourceArgs( $source );
      }
      catch( Exception $e ) {
        defs::log( sprintf( defsSourceHandler::$logtxt[1], $source['nodeid'], $e->getMessage(), var_export( $source, TRUE )), LOG_ERR );
        $args['error'] = $source['error'] = TRUE;
        return FALSE;
      }
    } // end foreach( $args['source'] as & $source )
    return TRUE;
  }
/**
 * return handler corresponding to source
 *
 * @param string $sourceType
 * @return String
 * @access private
 * @static
 */
  private static function getHandler( $sourceType ) {
    $sourceType = strtolower( $sourceType );
    if( ! isset( defsSourceHandler::$handlers[$sourceType] ))
      throw new Exception( sprintf( defsSourceHandler::$logtxt[0], $sourceType ));
    return defsSourceHandler::$handlers[$sourceType];
  }
/**
 * manage load source data types
 *
 * @param array  $source
 * @uses defsSourceHandler::getHandler()
 * @uses defs*Hndlr::checkSourceArgs()
 * @uses defs*Hndlr::loadData()
 * @throws source type not found or loadData exception
 * @static
 * @return array
 */
  public static function loadData( array $source ) {
    try {
      $sourceType  = $source['source'];
      $handler     = defsSourceHandler::getHandler( $sourceType );
      if( isset( $source['setup'] )) {        // always file source type
        $handler::checkSourceArgs( $source ); // may alter source type
        if( $sourceType != $source['source'] )
          $handler = defsSourceHandler::getHandler( $source['source'] );
      }
      return (array) $handler::loadData( $source );
    }
    catch( Exception $e ) {
      throw $e;
    }
    return array();
  }
}
