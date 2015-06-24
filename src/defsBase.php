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
 * defsBase class
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 1.0 - 2015-05-29
 */
class defsBase {
/**
 * @var string $nodeid     node unique Id
 * @access private
 */
  private $nodeid        = null;
/**
 * @var float $init        node init time (in float sec.msec)
 * @access private
 */
  private $init          = 0;
/**
 * @var float $last        previous node load time (in float sec.msec)
 * @access private
 */
  private $last          = 0;
/**
 * @var float $next        next load time (in float sec.msec)
 * @access private
 */
  private $next          = 0;
/**
 * @var int $ttl           how long time to keep data before next load (milliseconds, 1000 ms = 1 sec), if more than one, the min ttl
 * @access private
 */
  private $ttl           = null;
/**
 * @var array $source      source access arguments
 * @access private
 * @static
 */
  private $source        = array();
/**
 * @var array $values      node definition values
 * @access private
 */
  private $values        = array();
/**
 * @var bool $error        prep error
 * @access private
 */
  private $error         = NULL;
/**
 * @var array $logtxt      log texts
 * @access private
 * @static
 */
  private static $logtxt = array( '%s 210, loaded %d (top) items in %s sec',
                                  '%s 211, %s%s%sFile: %s (%s)%sTrace:%s',
                                );
/**
 * @var array $lock        to prevent (delay) fetch during data source load
 * @access private
 * @static
 */
  private static $lock   = array();
/**
 * constructor
 *
 * Initiated from array
 *
 * @param array $setup
 * @uses defsBase::$init
 * @uses defsBase::$nodeid
 * @uses defsBase::$source
 * @uses defsBase::$ttl
 * @uses defsBase::$last
 * @uses defsBase::$next
 * @uses defsBase::$error
 * @uses defsBase::$lock
 */
  public function __construct( array $setup ) {
    $this->init    = microtime( TRUE );
    $this->source  = $setup['source'];
    $this->nodeid  = $setup['nodeid'];
    if( isset( $setup['ttl'] ))
      $this->ttl   = $setup['ttl'];
    $this->last    = 0;
    $this->next    = 0;
    if( isset( $setup['error'] ))
      $this->error = TRUE;
    defsBase::$lock[(string) $this->init] = FALSE;
  }
/**
 * Get config value using max four keys, no keys gives all values
 * will load if it is 1st load or by checking ttl
 *
 * @param string $k1  key 1 name
 * @param string $k2  key 2 name
 * @param string $k3  key 3 name
 * @param string $k4  key 4 name
 * @uses defsBase::$error
 * @uses defsBase::$source
 * @uses defsBase::$ttl
 * @uses defsBase::$last
 * @uses defsBase::loadData()
 * @uses defsBase::$values
 * @return mixed, string on success, FALSE on not found / failure
 */
  public function get( $k1=null, $k2=null, $k3=null, $k4=null ) {
    if( isset( $this->error ))
      return FALSE;
    $lockCnt = 0;
    $k       = (string) $this->init;
    while(( 3 > $lockCnt++ ) && defsBase::$lock[$k] )
      usleep(500);
    $now     = microtime( TRUE );
    if((( ! empty( $this->next ) && ( $this->next < $now )) || empty( $this->last )) && ! $this->loadData( $now )) {
      defsBase::$lock[$k] = FALSE;
      return FALSE;
    }
    defsBase::$lock[$k] = FALSE;
    $numArgs = func_num_args();
    if(      0 == $numArgs )
      return $this->values;
    elseif(( 1 == $numArgs ) && isset( $this->values[$k1] ))
      return $this->values[$k1];
    elseif(( 2 == $numArgs ) && isset( $this->values[$k1][$k2] ))
      return $this->values[$k1][$k2];
    elseif(( 3 == $numArgs ) && isset( $this->values[$k1][$k2][$k3] ))
      return $this->values[$k1][$k2][$k3];
    elseif(( 4 == $numArgs ) && isset( $this->values[$k1][$k2][$k3][$k4] ))
      return $this->values[$k1][$k2][$k3][$k4];
    return FALSE;
  }
/**
 * load values
 *
 * @param float $now
 * @uses defsBase::$error
 * @uses defsBase::$lock
 * @uses defsBase::init
 * @uses defsBase::$source
 * @uses defsSourceHandler::manageSourceTypes()
 * @uses defsBase::selectSource()
 * @uses defs::log()
 * @uses defsBase::$logtxt
 * @uses defsBase::$last
 * @uses defs::checkTTL()
 * @uses defsBase::$ttl
 * @uses defsBase::srt()
 * @uses defsBase::$nodeid
 * @return bool, TRUE on success, FALSE on error
 */
  private function loadData( $now=null ) {
    if( isset( $this->error ))
      return FALSE;
    $start       = microtime( TRUE );
    defsBase::$lock[(string) $this->init] = TRUE;
                /*** check for sources to update ***/
    $indxs       = array();
    foreach( $this->source as $sx => $source ) {
      if( empty( $source['last'] ) ||
        ( isset( $source['next'] ) && ! empty( $source['next'] ) && ( $source['next'] < $now )))
        $indxs[] = $sx;
    }
    if( empty( $indxs ))
      return TRUE;
                /*** update values for all marked sources ***/
    $hasLoaded   = $ttlUpd = FALSE;
    $lockedKeys  = array();
    foreach( $indxs as $sx ) {
      $start2    = microtime( TRUE );
      try {
        $temp = defsSourceHandler::loadData( $this->source[$sx] );
      }
      catch( Exception $e ) {
        $errCode     = $e->getCode();
        $errCode     = ( empty( $errCode )) ? '' : ' (' . $errCode . ') ';
        defs::log( sprintf( defsBase::$logtxt[1], $this->nodeid, $errCode, $e->getMessage(), PHP_EOL, $e->getFile(), $e->getLine(), PHP_EOL, $e->getTraceAsString()), LOG_ERR );
        $this->error = $this->source[$sx]['error'] = TRUE;
        return FALSE;
      }
      $this->source[$sx]['loadCnt'] += 1;
      if( FALSE !== defsBase::selectSource( $this, $temp, $sx, $lockedKeys, $ttlUpd )) {
        $hasLoaded = TRUE;
        if( 1 < count( $this->source ))
          defs::log( sprintf( defsBase::$logtxt[0], $this->source[$sx]['nodeid'], count( $temp ), number_format(( microtime( TRUE ) - $start2 ), 6 )), LOG_INFO );
      }
      unset( $temp );
      $this->last = $this->source[$sx]['last'] = microtime( TRUE );
      if( isset( $this->source[$sx]['ttl'] ) && ! empty( $this->source[$sx]['ttl'] ))
        $this->source[$sx]['next'] = $now + $this->source[$sx]['ttl'];
    } // end foreach( $indxs as $sx )
    unset( $indxs, $lockedKeys );
    if( $hasLoaded ) {
      if( $ttlUpd )
        $this->ttl  = defs::checkTTL( $this->source );
      if( ! empty( $this->ttl ))
        $this->next = $now + $this->ttl;
      defsBase::srt( $this->values );
      defs::log( sprintf( defsBase::$logtxt[0], $this->nodeid, count( $this->values ), number_format(( microtime( TRUE ) - $start ), 6 )), LOG_INFO );
    }
    return TRUE;
  }
/**
 * selects data from source into obj
 *
 * @param object $obj
 * @param array  $upd
 * @param int    $sx
 * @param array  $lockedKeys
 * @param bool   $ttlUppd
 * @access private
 * @static
 * @return bool  TRUE on any select is done, FALSE on no select
 */
  private static function selectSource( defsBase $obj, array $upd, $sx, array & $lockedKeys, & $ttlUpd ) {
    $selectSts = FALSE;
    $source    = & $obj->source[$sx];
    foreach( $upd as $k => $v ) {
      if( !is_array( $v ))
        continue;
      if( $source['nodeid'] == $k ) {
        foreach( $v as $k2 => $v2 ) {
          if( $source['nodeid'] == $obj->nodeid ) {
            if(( 'ttl' == $k2 ) && ! empty( $v2 )) {
              $source['ttl']  = round((((int) $v2 ) / 1000 ), 6 );
              $ttlUpd         = TRUE;
            }
            else {
              $obj->values[$k2] = $v2; // accept all for the 'base' nodeid
              $lockedKeys[]   = $k2;
              $selectSts      = TRUE;
            }
          } // end if( $source['nodeid'] == $obj->nodeid )
          elseif(( 'ttl' != $k2 ) && ! in_array( $k2, $lockedKeys )) {
            $obj->values[$k2] = $v2;   // for others, accept only if not set in the same 'load'
            $lockedKeys[]     = $k2;
            $selectSts        = TRUE;
          }
        } // end foreach( $v as $k2 => $v2 )
      } // end if( $source['nodeid'] == $k )
      elseif( isset( $source['others'] ) && in_array( $k, $source['others'] )) {
        if( !is_array( $v ))
          continue;
        foreach( $v as $k2 => $v2 ) {
          if(( 'ttl' != $k2 ) && ! in_array( $k2, $lockedKeys )) {
            $obj->values[$k2] = $v2;   // for others, accept only if not set in the same 'load'
            $lockedKeys[]     = $k2;
            $selectSts        = TRUE;
          }
        } // end foreach( $v as $k2 => $v2 )
      } // end elseif( isset( $source['others'] )...
    } // end foreach( $tupd as $k => $v )
    return $selectSts;
  }
/**
 * multidim. array key sort
 *
 * @param array $array
 * @uses defsBase::srt()
 * @access private
 * @static
 */
  private static function srt( & $array ) {
    if( 1 >= count( $array ))
      return;
    foreach( $array as $k => & $v ) {
      if( is_array( $v ))
        defsBase::srt( $v );
    }
    ksort( $array );
  }
/**
 * returns formatted configuration string
 *
 * @param int $kl
 * @uses defsBase::$nodeid
 * @uses defsBase::$init
 * @uses defsBase::toStringDate()
 * @uses defsBase::last
 * @uses defsBase::$ttl
 * @uses defs::$log
 * @uses defsBase::$source
 * @uses defsBase::arrToString()
 * @return string
 */
  public function internalToString( & $kl=0 ) {
    if( 0 == func_num_args())
      $kl = 0;
    $output = array( 'NODEID ' . $this->nodeid . ' __internal__' => null  );
    if( ! empty( $this->init ))
      $output['init']      = defsBase::toStringDate( $this->init );
    if( ! empty( $this->ttl ))
      $output['ttl']       = number_format( $this->ttl, 3 ) . ' sec';
    if( ! empty( $this->last ))
      $output['last load'] = defsBase::toStringDate( $this->last );
    if( ! empty( $this->next ))
      $output['next load'] = defsBase::toStringDate( $this->next );
    if( ! empty( defs::$log ))
      $output['log']       = 'TRUE';
    if( isset( $this->error ))
      $output['error']     = ( $this->error ) ? 'TRUE' : 'FALSE';
    $delim                 = '|';
    foreach( $this->source as $sx => $source ) {
      if( is_array( $source )) {
        foreach( $source as $k => $v ) {
          if( in_array( $k, array( 'error', 'ltn1toutf8' )))
            $v             = ( $v ) ? 'TRUE' : 'FALSE';
          elseif( 'ttl' == $k )
            $v             = number_format( $v, 3 ) . ' sec';
          elseif( 'last' == $k )
            $v             = defsBase::toStringDate( $v );
          elseif( 'next' == $k )
            $v             = defsBase::toStringDate( $v );
          elseif( 'others' == $k )
            $v             = implode( $delim, $v );
          elseif( is_array( $v )) {
            foreach( $v as $k2 => $v2 ) {
              if( is_array( $v2 ))
                $v2        = implode( $delim, $v2 );
              $output[" source[{$sx}][$k]"] = "{$k2} : {$v2}";
            }
            continue;
          }
          $output[" source[{$sx}][$k]"] = $v;
        }
      }
      else {
        if( is_bool( $source ))
          $source = ( $source ) ? 'TRUE' : 'FALSE';
        $output[" source[{$sx}]"] = $source;
      }
    } // end foreach( $this->args as $sx => $source )
    if( empty( $kl ))
      $kl = 0;
    return defsBase::arrToString( $output, $kl );
  }
/**
 * returns formatted data string
 *
 * @param int $kl
 * @uses defsBase::$values
 * @uses defsBase::$nodeid
 * @uses defsBase::arrToString()
 * @return string
 */
  public function valuesToString( & $kl=0 ) {
    if( empty( $this->values ))
      return '';
    if( 0 == func_num_args())
      $kl = 0;
    $delim = '|';
    $output = array( 'NODEID ' . $this->nodeid . ' __data__' => null );
    foreach( $this->values as $k1 => $v1 ) {
      if( is_array( $v1 )) {
        foreach( $v1 as $k2 => $v2 ) {
          if( is_array( $v2 )) {
            foreach( $v2 as $k3 => $v3 ) {
              if( is_array( $v3 )) {
                foreach( $v3 as $k4 => $v4 )
                  $k = $k1 . $delim . $k2 . $delim . $k3 . $delim . $k4;
                $output[$k] = $v4;
              }
              else {
                $k = $k1 . $delim . $k2 . $delim . $k3;
                $output[$k] = $v3;
              }
            }
          } // end if( is_array( $v2 ))
          else {
            $k = $k1 . $delim . $k2;
            $output[$k]     = $v2;
          }
        } // end foreach
      } // end if( is_array( $v ))
      else
        $output[$k1]        = $v1;
    } // end foreach( $this->values as $k1 => $v1 )
    if( empty( $kl ))
      $kl = 0;
    return defsBase::arrToString( $output, $kl );
  }
/**
 * returns formatted configuration and data string
 *
 * @uses defsBase::internalToString()
 * @uses defsBase::valuesToString()
 * @return string
 */
  public function toString() {
    $kl = 0;
    return defsBase::internalToString( $kl ) . defsBase::valuesToString( $kl );
  }
  private static function arrToString( array $data, & $kl ) {
    foreach( $data as $k => $v ) {
      if(( ! empty( $v ) || ( '0' == $v )) && ( $kl < strlen( $k )))
        $kl = strlen( $k );
    }
    $str = '';
    foreach( $data as $k => $v ) {
      if( ! empty( $v ) || ( '0' == $v ))
        $str .= str_pad( $k, $kl ) . ' = ' . $v . PHP_EOL;
      else
        $str .= $k . PHP_EOL;
    }
    return $str;
  }
  private static function toStringDate( $ts ) {
    return date( 'Y-m-d H:i:s', (int) $ts ) . '.' . sprintf( "%06d",( $ts - floor( $ts )) * 1000000 );
  }
}
