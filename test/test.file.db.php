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
 * test.file.db.php
 *
 * set up
 *  using array( 'nodeid', 'setup' ) as directive to point to setup file
 * testing all four types of setup files, *.csv, *.ini, *.php, *.txt
 * nodeid       setup file
 * errChk   none, checking error mgnt
 * advDbA   setup/advDbA.csv
 * advDbB   setup/advDbB.ini
 * advDbC   setup/advDbC.php
 * advDbD   setup/advDbD.txt
 *
 * data are fetched from database using directives in the setup file
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
$btnPrfx = 'file.db';
dispLabel( '<h3>' . basename( __FILE__ ) . '</h3><p>setup from file, data from databaseb<br>5 test incl. errorCheck for error check</p>', 'array.db' );
$exts    = array( 'errorCheck' => 'error', 'advDbA' => 'csv', 'advDbB' => 'ini', 'advDbC' => 'php', 'advDbD' => 'txt' );
$tests   = array( 'errorCheck', 'advDbA', 'advDbB', 'advDbC', 'advDbD' );
// $tests   = array( 'advDbA' );
foreach( $tests as $nix => $nodeid ) {
// foreach( array( 'advDbD' ) as $nodeid ) {
/**********  define node $nodeid                        ************* */
  $file  = 'setup/' . $nodeid . '.' . $exts[$nodeid];
  $setup = array( 'nodeid' => $nodeid,
                  'setup'  => $file
                );
  $prev  = ( 0 < $nix ) ? $tests[$nix-1] : null;
  $next  = ( isset( $tests[$nix+1] )) ? $tests[$nix+1] : null;
  dispLabel( "<br>set up a '$nodeid' node, using a '{$exts[$nodeid]}' file, '{$file}'", $nodeid, $prev, $next );
  theTest( $setup, $nodeid, TRUE, 1000000 ); // $dispAll, $usleep
} // end loooop...
/**********                                             ************* */
if( ! isset( $allInOne ))
  endPage();
