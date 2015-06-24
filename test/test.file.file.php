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
 * test.file.file.php
 *
 * set up
 *  using array( 'nodeid', 'setup' ) as directive to point to setup file
 * testing all four types of setup files, *.csv, *.ini, *.php, *.txt
 * nodeid       setup file
 * errorCheck   none, checking error mgnt
 * advFileA     setup/advFileA.csv
 * advFileB     setup/advFileB.ini
 * advFileC     setup/advFileC.php
 * advFileD     setup/advFileD.txt
 *
 * data are fetched from csv/ini/php/text files using directives in the setup file
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
$btnPrfx = 'file.file';
dispLabel( '<h3>' . basename( __FILE__ ) . '</h3><p>setup from  file, data from csv/ini/php/txt files<br>5 test incl. errorCheck for error check</p>', 'file.file' );
$exts    = array( 'errorCheck' => 'error', 'advFileA' => 'csv', 'advFileB' => 'ini', 'advFileC' => 'php', 'advFileD' => 'txt' );
$tests   = array( 'errorCheck', 'advFileA', 'advFileB', 'advFileC', 'advFileD' );
foreach( $tests as $nix => $nodeid ) {
// foreach( array( 'advFileD' ) as $nix => $nodeid ) { // test ###
/**********  define node $nodeid                        ************* */
  $file  = 'setup/' . $nodeid . '.' . $exts[$nodeid];
  $setup = array( 'nodeid' => $nodeid,
                  'setup'  => $file
                );
  $prev  = ( 0 < $nix )               ? $tests[$nix-1] : null;
  $next  = ( isset( $tests[$nix+1] )) ? $tests[$nix+1] : null;
  dispLabel( "<br>set up  a '$nodeid' node, data from file, '" . 'data/' . $nodeid . '.' . $exts[$nodeid] . "'", $nodeid, $prev, $next );
  theTest( $setup, $nodeid, TRUE, 1000000 ); // $dispAll, $usleep
} // end loooop...
/**********                                             ************* */
if( ! isset( $allInOne ))
  endPage();
