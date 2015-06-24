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
 * test.array.db.php
 *
 * set up
 *  using array as setup directives,
 *
 * data are fetched from database
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
$btnPrfx  = 'array.db';
dispLabel( '<h3>' . basename( __FILE__ ) . '</h3><p>setup from array, data from db<br>2 tests</p>', 'array.db' );
$setups   = array( 'advDbC' => array( 'nodeid'    => 'advDbC',     // using defs defined database table
                                      'ttl'       => 800,
                                      'source'    => 'mysqli',
                                      'host'      => 'localhost',
                                      'username'  => 'defs',
                                      'passwd'    => 'defs',
                                      'dbname'    => 'defs',
                                    ),
                   'advDbD' => array( 'nodeid'    => 'advDbD',     // other system defined database table
                                      'ttl'       => 1200,
                                      'source'    => 'mysqli',
                                      'host'      => 'localhost',
                                      'username'  => 'defs',
                                      'passwd'    => 'defs',
                                      'dbname'    => 'defs',
                                      'table'     => 'modules',    // here mapping table and column names
                                      'major'     => 'name',
                                      'key1'      => 'primary',
                                      'key2'      => 'second',
                                      'key3'      => 'ix',
                                      'value'     => 'content',
                                    ),
                 );
$nix      = 0;
$tests    = array_keys( $setups );
foreach( $setups as $nodeid => $setup ) {
  $usleep = ( isset( $setup['ttl'] )) ? ( $setup['ttl'] + 201 ) * 1000  : 0;
/**********  define node $nodeid                        ************* */
  $prev   = ( 0 < $nix ) ? $tests[$nix-1] : null;
  $next   = ( isset( $tests[$nix+1] )) ? $tests[$nix+1] : null;
  dispLabel( "<br>set up '$nodeid', using setup array", $nodeid, $prev, $next );
  theTest( $setup, $nodeid, TRUE, $usleep ); // $dispAll, $usleep
  $nix++;
} // end loooop...
/**********                                             ************* */
if( ! isset( $allInOne ))
  endPage();
