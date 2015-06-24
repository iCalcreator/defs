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
 * test.simple.php
 *
 * simple set up, using 'nodeid' as directive to point to data file
 * testing all four types of data files, *.csv, *.ini, *.php, *.txt
 * nodeid    datafile
 * errChk    (none)       checking some error mgnt
 * simpleA   simpleA.csv
 * simpleB   simpleB.ini
 * simpleC   simpleC.php
 * simpleD   simpleD.txt
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
$btnPrfx = 'simple';
$msg     =  '<h3>' . basename( __FILE__ ) . '</h3>';
$msg    .= '<p>simple setup, nodeid points directly to data file,<br>simple[A/B/C(D].[csv/ini/php/txt]<br>5 tests incl. errorCheck for error check</p>';
dispLabel( $msg, 'simple' );
$tests = array( 'errorCheck', 'simpleA', 'simpleB', 'simpleC', 'simpleD' );
foreach( $tests as $nix => $nodeid ) {
// foreach( array( 'simpleA' ) as $nodeid ) {
/**********  define node $nodeid                        ************* */
  $prev = ( 0 < $nix ) ? $tests[$nix-1] : null;
  $next = ( isset( $tests[$nix+1] )) ? $tests[$nix+1] : null;
  dispLabel(  "<br>set up node, simple, setting: (string) '$nodeid'", $nodeid, $prev, $next );
  if( 'errorCheck' == $nodeid )
    dispLabel( "No data file ({$nodeid}.*) exist!" );
  theTest( $nodeid, $nodeid, TRUE, 1000000 ); // $dispAll, $usleep
} // end loooop...
/**********                                             ************* */
if( ! isset( $allInOne ))
  endPage();
