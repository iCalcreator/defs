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
 * defs class
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 1.0 - 2015-05-29
 */
class defs {
/**
 * @var string $version
 * @static
 */
  public static $version  = '1.0';
/**
 * @var object $log       any log class supporting 'log' method
 * @static
 */
  public static $log      = null;
/**
 * @var array $logtxt    log texts
 * @access private
 * @static
 */
  private static $logtxt  = array( '%s 110, %s%s%sFile: %s (%s)%sTrace:%s',
                                   '%s 111, %s: %s',
                                   '%s 112, init in %s sec, args:%s',
                                   '%s 113, missing args (%s)',
                                   '%s 114, adapted %d setup items, %s sec',
                                );
/**
 * @var array instances   store node unique instances of defs
 * @access private
 * @static
 */
  private static $instances = array();
/**
 * factory method for creating a instance of this class on a nodeid basis
 *
 * @param mixed $setup  string (nodeid) / array (setup args incl nodeid)
 * @uses defs::prepArgs()
 * @uses defsSourceHandler::loadData()
 * @uses defs::log()
 * @uses defs::$logtxt
 * @uses defs::fixArgs()
 * @uses defsSourceHandler::checkSourceArgs()
 * @uses defsBase::__construct()
 * @return mixed        object instance on success, FALSE on error
 */
  public static function factory( $setup ) {
    $start    = microtime( TRUE );
    $args     = defs::prepArgs( $setup );
    if( isset( $args['setup'] )) {
      try {
        $args = defsSourceHandler::loadData( $args );
      }
      catch( Exception $e ) {
        $errCode     = $e->getCode();
        $errCode     = ( empty( $errCode )) ? '' : ' (' . $errCode . ') ';
        defs::log( sprintf( defs::$logtxt[0], $args['nodeid'], $errCode, $e->getMessage(), PHP_EOL, $e->getFile(), $e->getLine(), PHP_EOL, $e->getTraceAsString()), LOG_ERR );
        return FALSE;
      }
    }
                /*** control nodeid ***/
    if( ! isset( $args['nodeid'] ) || empty( $args['nodeid'] )) {
      defs::log( sprintf( defs::$logtxt[3], '', var_export( $args, TRUE )), LOG_ERR );
      return FALSE;
    }
    $args     = defs::fixArgs( $args );
    if( ! isset( $args['error'] ) && ! defsSourceHandler::checkSourceArgs( $args )) {
      defs::log( sprintf( defs::$logtxt[1], $args['nodeid'], 'check setup', var_export( $setup, TRUE )), LOG_ERR );
      defs::log( sprintf( defs::$logtxt[1], $args['nodeid'], 'check args', var_export(  $args,  TRUE )), LOG_ERR );
    }
    else
      defs::log( sprintf( defs::$logtxt[2], $args['nodeid'], number_format(( microtime( TRUE ) - $start ), 6 ), var_export( $args, TRUE )), LOG_INFO );
    return ( empty( $args['nodeid'] )) ? FALSE : new defsBase( $args );
  }
/**
 * Getter method for creating a (singleton) instance of this class on a nodeid basis
 *
 * @param mixed $setup  string (nodeid) / array (setup args incl nodeid)
 * @uses defs::prepArgs()
 * @uses defs::$instances
 * @uses defsSourceHandler::loadData()
 * @uses defs::log()
 * @uses defs::$logtxt
 * @uses defs::fixArgs()
 * @uses defsSourceHandler::checkSourceArgs()
 * @uses defsBase::__constructor()
 * @return object       defsBase object instance
 */
  public static function getInstance( $setup ) {
    $start    = microtime( TRUE );
    $args     = defs::prepArgs( $setup );
    if( isset( defs::$instances[$args['nodeid']] ))
      return defs::$instances[$args['nodeid']];
    if( isset( $args['setup'] )) {
      try {
        $args = defsSourceHandler::loadData( $args );
      }
      catch( Exception $e ) {
        $errCode     = $e->getCode();
        $errCode     = ( empty( $errCode )) ? '' : ' (' . $errCode . ') ';
        defs::log( sprintf( defs::$logtxt[0], $args['nodeid'], $errCode, $e->getMessage(), PHP_EOL, $e->getFile(), $e->getLine(), PHP_EOL, $e->getTraceAsString()), LOG_ERR );
        $args['error'] = TRUE;
      }
    }
                /*** control nodeid ***/
    if( ! isset( $args['nodeid'] ) || empty( $args['nodeid'] )) {
      defs::log( sprintf( defs::$logtxt[3], '', var_export( $args, TRUE )), LOG_ERR );
      $args['error'] = TRUE;
    }
    $args = defs::fixArgs( $args );
    if( ! isset( $args['error'] ) && ! defsSourceHandler::checkSourceArgs( $args )) {
      defs::log( sprintf( defs::$logtxt[1], $args['nodeid'], 'check setup', var_export( $setup, TRUE )), LOG_ERR );
      defs::log( sprintf( defs::$logtxt[1], $args['nodeid'], 'check args',  var_export( $args,  TRUE )), LOG_ERR );
    }
    defs::$instances[$args['nodeid']] = new defsBase( $args );
    defs::log( sprintf( defs::$logtxt[2], $args['nodeid'], number_format(( microtime( TRUE ) - $start ), 6 ), var_export( $args, TRUE )), LOG_INFO );
    return defs::$instances[$args['nodeid']];
  }
/**
 * check min ttl
 *
 * @param array $sources
 * @static
 * @return mixed  float on success, bool FALSE when not found
 */
  public static function checkTTL( array $sources ) {
    $tempTTL = (float) PHP_INT_MAX;
    foreach( $sources as $source ) {
      if( isset( $source['ttl'] ) && ! empty( $source['ttl'] ) && ( $source['ttl'] < $tempTTL ))
         $tempTTL = $source['ttl'];
    }
    return ( ! empty( $tempTTL ) && ( $tempTTL < PHP_INT_MAX )) ? $tempTTL : 0;
  }
/**
 * arranges setup args array values
 *
 * @param array   $setup
 * @uses defs::log
 * @uses defs::$logtxt
 * @uses defs::checkTTL()
 * @throws missing/invalid arg exception
 * @access private
 * @static
 * @return array
 */
  private static function fixArgs( array $setup ) {
    $start     = microtime( TRUE );
                /*** split args ***/
    $output = $source = $first = array();
    static $ks = array( 'error', 'nodeid', 'ttl' );
    foreach( $setup as $k => $v ) {
      if(( 'source' == $k ) && is_array( $v )) {
        $ak    = array_keys( $v );
        if( is_int( reset( $ak ))) { // multi-dim array of sources
          foreach( $v as $k2 => $v2 )
            $source[] = $v2;
        }
        else
          $source[]   = $v;          // v is single source array
      } // end if(( 'source' == $k ) && is_array( $v ))
      else {
        if( in_array( $k, $ks ) && ! empty( $v ))
          $output[$k] = ( 'ttl' == $k ) ? round((((int) $v ) / 1000), 6 ) : $v;
        $first[$k] = $v;
      }
    } // end foreach( $setup as $k => $v )
    unset( $setup, $ak );
                /*** add source[-s] ***/
    $output['source'] = array();
    if( isset( $first['source'] ))
      $output['source'][] = $first;
    foreach( $source as $s )
      $output['source'][] = $s;
    unset( $first, $source );
                /*** check source[-s] ***/
    foreach( $output['source'] as $sx => & $source ) {
      $source['index']    = $sx;
      $source['loadCnt']  = 0;
      if( ! isset( $source['nodeid'] ) || empty( $source['nodeid'] ))
        $source['nodeid'] = $output['nodeid'];
      if( isset( $source['ttl'] ))
        $source['ttl']    = round((((int) $source['ttl'] ) / 1000), 6 );
      elseif( isset( $output['ttl'] ))
        $source['ttl']    = $output['ttl'];
      if( isset( $source['others'] ) && ! is_array( $source['others']  ))
        $source['others'] = explode( ',', (string) $source['others'] );
    }
    if( FALSE !== ( $ttl = defs::checkTTL( $output['source'] )))
      $output['ttl'] = $ttl;
    defs::log( sprintf( defs::$logtxt[4], $output['nodeid'], count( $output ), number_format(( microtime( TRUE ) - $start ), 6 )), LOG_DEBUG );
    return $output;
  }
/**
 * log message with priority
 *
 * @param string $message
 * @param int    $prio
 * @uses defs::$log
 * @static
 */
  public static function log( $message, $prio=LOG_ERR ) {
    if( ! empty( defs::$log ) && method_exists( defs::$log, 'log' ))
      defs::$log->log( 'defs ' . defs::$version . ' ' . $message, $prio );
  }
/**
 * get handlers
 *
 * @uses defsSourceHandler::$handlers
 * @return array
 * @static
 */
  public static function getHandlers() {
    return defsSourceHandler::$handlers;
  }
/**
 * add handler
 *
 * @param string $handlerType
 * @param string $handler
 * @uses defsSourceHandler::$handlers
 * @static
 */
  public static function addHandler( $handlerType,$handler ) {
    defsSourceHandler::$handlers[$handlerType] = $handler;
  }
/**
 * prepare the setup
 *
 * @param mixed  $setup
 * @access private
 * @static
 */
  private static function prepArgs( $setup ) {
    if( ! is_array( $setup  ))
      $setup           = array( 'nodeid' => $setup );
    if( ! isset( $setup['nodeid'] ))
      $setup['nodeid'] = reset( $setup );
    $setup['nodeid']   = (string) $setup['nodeid'];
    switch( TRUE ) {
      case ( 1 == count( $setup )):
        return array( 'nodeid' => $setup['nodeid'], 'source' => 'file', 'path' => $setup['nodeid'] );
        break;
      case (( 2 == count( $setup )) && isset( $setup['setup'] )):
        $setup['source'] = 'file';
        $setup['path']   = $setup['setup'];
        return $setup;
        break;
      default:
        return $setup;
    }
  }
}
