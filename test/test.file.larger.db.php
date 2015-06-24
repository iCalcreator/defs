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
 * test.file.larger.db.php
 *
 * set up
 *  using array( 'nodeid', 'setup' ) as directive to point to setup file
 *
 * data are fetched from database using directives in the setup file
 * NO display of toString()or defs::get() valöues due to the larger number of values
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
$btnPrfx = 'file.larger.db';
dispLabel( '<h3>' . basename( __FILE__ ) . '</h3><p>setup from file, data from database and table with 2000 rows</p>', 'file.larger.db' );
foreach( array( 'largeNode' ) as $nodeid ) {
/**********  define node $nodeid                        ************* */
  $file  = 'setup/' . $nodeid . '.php';
  $setup = array( 'nodeid' => $nodeid,
                  'setup'  => $file
                );
  dispLabel( "<br>set up a '$nodeid' node, using a 'php' file, '{$file}'", $nodeid );
  theTest( $setup, $nodeid, FALSE, 1500000 ); // $dispAll, $usleep
} // end loooop...
/**********                                             ************* */
if( ! isset( $allInOne ))
  endPage();
