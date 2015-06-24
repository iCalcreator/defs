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
 * defsMysqlHandler class
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 1.0 - 2015-05-29
 */
class defsMysqlHandler implements defsSourceInterface {
/**
 * @var array $logtxt       log texts
 * @access private
 * @static
 */
  private static $logtxt  = array( '510, %s, missing arg host/username/passwd/dbname: %s',
                                   ' 511, mysqli_init failed',
                                   ' 512, Connect Error (%s) %s',
                                   ' 513), Setting database options failed, key: \'%s\', value: \'%s\'',
                                   ' 514, SQL error (%d) \'%s\', SQL=%s',
                                   '%s 515, %s, %s',
                                   '516, Empty result, \'%s \'',
                                   '%s 517, fetched %d base keys from db, %s sec',
                                   '%s 518, db value \'%s\' sec is ISO-8859-1 encoded',
                                   '%s 519, db option: %s : %s',
                                 );
/**
 * @var array $sqlStmt      sql statement templates
 * @access private
 * @static
 */
  private static $sqlStmt = array( "SELECT %s FROM `%s` WHERE `%s` IN (%s)",
                                   " ORDER BY `%s` ASC",
                                   ", `%s` ASC",
                                   "`%s`"
                                 );
/**
 * @var array $fixedSQLstmts  fixed sql statements
 * @access private
 * @static
 */
  private static $fixedSQLstmts = array();
/**
 * check required args and, opt, add default parts
 *
 * @param array $source
 * @uses defsMysqlHandler::createSQL()
 * @throw missing arg exception
 * @static
 * @return bool
 */
  public static function checkSourceArgs( array & $source ) {
    if( ! isset( $source['host'] )     || empty( $source['host'] )     ||
        ! isset( $source['username'] ) || empty( $source['username'] ) ||
        ! isset( $source['passwd'] )   || empty( $source['passwd'] )   ||
        ! isset( $source['dbname'] )   || empty( $source['dbname'] ))
      throw new Exception( sprintf( defs::$logtxt[0], $source['nodeid'], var_export( $source, TRUE )));
    if( isset( $source['options'] ) && ! empty( $source['options'] )) {
      static $div = ';';
      foreach( $source['options'] as $k => & $v ) {
        $v2  = explode( ';', $v );
        $v   = array();
        foreach( $v2 as $v3 ) {
          if( ! empty( $v3 )) {
            $v3 = trim( $v3 );
            if( $div != substr( $v3, -1 ))
              $v3 .= $div;
            $v[] = $v3;
          }
        }
      }
    }
    static $dbArgs = array( 'port', 'socket', 'flags' );
    foreach( $dbArgs as $dbArg )
      if( ! isset( $source[$dbArg] ))
        $source[$dbArg] = null;
    if( ! isset( $source['table'] )) { // then use db defaults
      $source['table'] = 'defs';
      $source['major'] = 'nodeid';
      $source['key1']  = 'key1';
      $source['key2']  = 'key2';
      $source['key3']  = 'key3';
      $source['value'] = 'value';
    }
            /*** checking db connection   ***/
    try {
      $mysqli = defsMysqlHandler::mysqliInit();
      defsMysqlHandler::mysqliConnection( $mysqli, $source );
    }
    catch( Exception $e ) {
      throw $e;
    }
            /*** build sql statement ***/
    defsMysqlHandler::$fixedSQLstmts[$source['nodeid']] = defsMysqlHandler::createSQL( $source, $mysqli );
    @$mysqli->close();
    unset( $mysqli );
    return TRUE;
  }
/**
 * create database select statement
 *
 * @param array  $cource
 * @param object $mysqli
 * @uses defsMysqlHandler::$sqlStmt
 * @uses defs::log
 * @uses defsMysqlHandler::$logtxt
 * @access private
 * @static
 * @return string
 */
  private static function createSQL( $source, $mysqli ) {
    $dbCols     = array( sprintf( defsMysqlHandler::$sqlStmt[3], $source['major'] ));
    if( isset( $source['key1'] ))
      $dbCols[] =        sprintf( defsMysqlHandler::$sqlStmt[3], $source['key1'] );
    if( isset( $source['key2'] ))
      $dbCols[] =        sprintf( defsMysqlHandler::$sqlStmt[3], $source['key2'] );
    if( isset( $source['key3'] ))
      $dbCols[] =        sprintf( defsMysqlHandler::$sqlStmt[3], $source['key3'] );
    if( isset( $source['key4'] ))
      $dbCols[] =        sprintf( defsMysqlHandler::$sqlStmt[3], $source['key4'] );
    $dbCols[]   =        sprintf( defsMysqlHandler::$sqlStmt[3], $source['value'] );
    $majors     = array( $source['nodeid'] );
    if( isset( $source['others'] )) {
      foreach( $source['others'] as $other )
        $majors[] = $other;
      $majors   = array_unique( $majors );
    }
    foreach( $majors as & $major )
      $major    = "'" . $mysqli->real_escape_string( $major ) . "'";
    $sql        = sprintf( defsMysqlHandler::$sqlStmt[0], implode( ',', $dbCols ), $source['table'], $source['major'], implode( ',', $majors ));
    $sql       .= sprintf( defsMysqlHandler::$sqlStmt[1], $source['major'] );
    if( isset( $source['key1'] ))
      $sql     .= sprintf( defsMysqlHandler::$sqlStmt[2], $source['key1'] );
    if( isset( $source['key2'] ))
      $sql     .= sprintf( defsMysqlHandler::$sqlStmt[2], $source['key2'] );
    if( isset( $source['key3'] ))
      $sql     .= sprintf( defsMysqlHandler::$sqlStmt[2], $source['key3'] );
    if( isset( $source['key4'] ))
      $sql     .= sprintf( defsMysqlHandler::$sqlStmt[2], $source['key4'] );
    defs::log( sprintf( defsMysqlHandler::$logtxt[5], $source['nodeid'], $mysqli->host_info, $sql ), LOG_DEBUG );
    return $sql;
  }
/**
 * load data from source
 *
 * @param array  $source
 * @uses defsMysqlHandler::mysqliRead()
 * @uses defsMysqlHandler::$logtxt
 * @throws source type not found exception
 * @static
 * @return array
 */
  public static function loadData( array $source ) {
    switch( $source['source'] ) {
      case 'mysqli':
        try {
          return (array) defsMysqlHandler::mysqliRead( $source );
        }
        catch( Exception $e ) {
          throw $e;
        }
        break;
      default:
        throw new Exception( sprintf( defsMysqlHandler::$logtxt[0], $source['source'] ));
        break;
    }
  }
/**
 * create mysqli connection
 *
 * @uses defsMysqlHandler::$logtxt
 * @throws db connect exceptions
 * @access private
 * @static
 */
  private static function mysqliConnection( $mysqli, $source ) {
    if( FALSE === @$mysqli->real_connect( $source['host'],
                                          $source['username'],
                                          $source['passwd'],
                                          $source['dbname'],
                                          $source['port'],
                                          $source['socket'],
                                          $source['flags'] ))
      throw new Exception( sprintf( defsMysqlHandler::$logtxt[2], mysqli_connect_errno(), mysqli_connect_error()));
  }
/**
 * init mysqli
 *
 * @uses defsMysqlHandler::$logtxt
 * @throws db init exceptions
 * @access private
 * @static
 * @return object
 */
  private static function mysqliInit() {
    if( FALSE === ( $mysqli = mysqli_init()))
      throw new Exception( defsMysqlHandler::$logtxt[1] );
    return $mysqli;
  }
/**
 * manage mysqli options
 *
 * @uses defsMysqlHandler::$logtxt
 * @throws db options exception
 * @access private
 * @static
 */
  private static function mysqliOptions( $mysqli, $source ) {
    foreach( $source['options'] as $optKey => $optValues ) {
      foreach( $optValues as $optValue ) {
        if( FALSE === $mysqli->options( $optKey, $optValue ))
          throw new Exception( sprintf( defsMysqlHandler::$logtxt[3], $optKey, $optValue ));
        defs::log( sprintf( defsMysqlHandler::$logtxt[9], $source['nodeid'], $optKey, $optValue ), LOG_DEBUG );
      }
    }
    unset( $optValues, $optValue );
  }
/**
 * fetch values from database using Mysqli
 *
 * @param array   $source
 * @uses defs::log
 * @uses defsMysqlHandler::mysqliInit()
 * @uses defsMysqlHandler::mysqliOptions()
 * @uses defsMysqlHandler::mysqliConnection()
 * @uses defsMysqlHandler::$fixedSQLstmts
 * @uses defsMysqlHandler::createSQL()
 * @uses defsMysqlHandler::$logtxt
 * @uses defsMysqlHandler::existsDBColumn()
 * @throws db exceptions
 * @access private
 * @static
 */
  private static function mysqliRead( array $source ) {
    $start    = microtime( TRUE );
    try {   /***   init db   ***/
      $mysqli = defsMysqlHandler::mysqliInit();
            /***   db options   ***/
      if( isset( $source['options'] ) && is_array( $source['options'] ))
        defsMysqlHandler::mysqliOptions( $mysqli, $source );
            /***   db connect   ***/
      defsMysqlHandler::mysqliConnection( $mysqli, $source );
    }
    catch( Exception $e ) {
      throw $e;
    }
    if( ! isset( defsMysqlHandler::$fixedSQLstmts[$source['nodeid']] ))
      defsMysqlHandler::$fixedSQLstmts[$source['nodeid']] = defsMysqlHandler::createSQL( $source, $mysqli );
            /***   query db   ***/
    if( FALSE === ( $result = @$mysqli->query( defsMysqlHandler::$fixedSQLstmts[$source['nodeid']] )))
      throw new Exception( sprintf( defsMysqlHandler::$logtxt[4], $mysqli->errno, $mysqli->error, defsMysqlHandler::$fixedSQLstmts[$source['nodeid']] ));
    if( 0 == $result->num_rows )
      throw new Exception( sprintf( defsMysqlHandler::$logtxt[6], defsMysqlHandler::$fixedSQLstmts[$source['nodeid']] ));
            /***   manage db output   ***/
    $output     = array();
    while( $row = $result->fetch_assoc()) {
      if(( $source['nodeid'] != $row[$source['major']] ) &&
         ( ! isset( $source['others'] ) || ! in_array( $row[$source['major']], $source['others'] )))
        continue;
      if( isset( $source['ltn1toutf8'] ) && ! empty( $source['ltn1toutf8'] )   && // charset fix...
         ( TRUE === mb_check_encoding( $row[$source['value']], 'ISO-8859-1' )) &&
         ( TRUE !== mb_check_encoding( $row[$source['value']], 'UTF-8' ))) {
        defs::log( sprintf( defsMysqlHandler::$logtxt[8], $source['nodeid'], $row[$source['value']] ), LOG_DEBUG );
        $row[$source['value']] = mb_convert_encoding( $row[$source['value']], "UTF-8", 'ISO-8859-1' );
      }
      $k        = $row[$source['major']];
      if(     defsMysqlHandler::existsDBColumn( 'key4', $source, $row ))
         $output[$k][$row[$source['key1']]][$row[$source['key2']]][$row[$source['key3']]][$row[$source['key4']]] = $row[$source['value']];
      elseif( defsMysqlHandler::existsDBColumn( 'key3', $source, $row ))
         $output[$k][$row[$source['key1']]][$row[$source['key2']]][$row[$source['key3']]] = $row[$source['value']];
      elseif( defsMysqlHandler::existsDBColumn( 'key2', $source, $row ))
         $output[$k][$row[$source['key1']]][$row[$source['key2']]] = $row[$source['value']];
      else
         $output[$k][$row[$source['key1']]] = $row[$source['value']];
    }
            /***   close and return   ***/
    $result->close();
    @$mysqli->close();
    unset( $mysqli );
    $cnt        = 0;
    foreach( $output as $baseKeys )
      $cnt     += count($baseKeys );
    defs::log( sprintf( defsMysqlHandler::$logtxt[7], $source['nodeid'], $cnt, number_format(( microtime( TRUE ) - $start ), 6 )), LOG_DEBUG );
    return $output;
  }
/**
 * check if key is set in source and (db) row, i.e not null
 *
 * @param string $key
 * @param array  $source
 * @param array  $row
 * @access private
 * @static
 */
  private static function existsDBColumn( $key, array $source, array $row ) {
    if( ! isset( $source[$key] ))
      return FALSE;
    if( ! isset( $row[$source[$key]] ))
      return FALSE;
    return ( ! empty( $row[$source[$key]] ) || ( '0' == $row[$source[$key]] )) ? TRUE : FALSE;
  }
}
