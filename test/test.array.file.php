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
 * test.array.file.php
 *
 * set up
 *  using array as setup directives,
 *
 * data are fetched from file
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
$btnPrfx  = 'array.file';
dispLabel( '<h3>' . basename( __FILE__ ) . '</h3><p>std setup from array, data from file<br>4 tests</p>', 'array.file' );
$setups   = array( 'advFileE' => array( 'nodeid'  => 'advFileE',
                                        'source'  => array( 'nodeid'  => 'advFileE',
                                                            'ttl'     => 1000,
                                                            'source'  => 'file',
                                                            'path'    => 'data/advFileE.csv',
                                                          ),
                                      ),
                   'advFileF' => array( 'nodeid'  => 'advFileF',
                                        'source'  => 'file',
                                        'path'    => 'data/advFileF.ini',
                                      ),
                   'advFileG' => array( 'nodeid'  => 'advFileG',
                                        'ttl'     => 800,
                                        'source'  => array( array( 'nodeid'  => 'advFileG',
                                                                   'source'  => 'file',
                                                                   'path'    => 'data/advFileG.php',
                                                                 ),
                                                          ),
                                      ),
                   'advFileH' => array( 'nodeid'  => 'advFileH',
                                        'source'  => 'file',
                                        'path'    => 'data/advFileH.txt',
                                      ),
                 );
$nix      = 0;
$tests    = array_keys( $setups );
foreach( $setups as $nodeid => $setup ) {
  $usleep = ( isset( $setup['ttl'] )) ? ( $setup['ttl'] + 100 ) * 1000  : 0;
/**********  define node $nodeid                        ************* */
  $prev = ( 0 < $nix ) ? $tests[$nix-1] : null;
  $next = ( isset( $tests[$nix+1] )) ? $tests[$nix+1] : null;
  dispLabel( "<br>set up a '$nodeid' node, using setup array", $nodeid, $prev, $next );
  theTest( $setup, $nodeid, TRUE, $usleep ); // $dispAll, $usleep
  $nix++;
} // end loooop...
/**********                                             ************* */
if( ! isset( $allInOne ))
  endPage();
