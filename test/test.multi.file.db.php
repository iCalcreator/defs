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
 * test.multi.file.db.php
 *
 * set up
 *  using array( 'nodeid', 'setup' ) as directive to point to setup file
 * nodeid       setup file
 * multiNode  setup/multiNode.php
 * testing all four types of sources (with different pointer and ttl settings):
 * file     'pointer'    => 'advFileE'         no ttl
 * db       'pointer'    => 'advDbC'           ttl  800 msec
 * file     'nodeid'     => 'advFileG'         ttl 1200 msec
 * db       'nodeid'     => 'advDbD'           ttl 1000 msec
 *
 * data are fetched from database using directives in the setup file
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
$btnPrfx = 'multi.file.db';
dispLabel( '<h3>' . basename( __FILE__ ) . '</h3><p>setup from file, source data from file, database, file and database</p>', 'multiNode' );
foreach( array( 'multiNode' ) as $nodeid ) {
/**********  define node $nodeid                        ************* */
  $file  = 'setup/' . $nodeid . '.php';
  $setup = array( 'nodeid' => $nodeid,
                  'setup'  => $file
                );
  dispLabel( "<br>set up a '$nodeid' node, using a 'php' file, '{$file}'", $nodeid );
  theTest( $setup, $nodeid, TRUE, 1000000 ); // $dispAll, $usleep
} // end loooop...
/**********                                             ************* */
if( ! isset( $allInOne ))
  endPage();
