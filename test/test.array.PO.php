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
 * test.array.PO.php
 *
 * set up
 *  using array( 'nodeid', 'source'... ) as directive to point to data
 *
 * data are fetched from a PO file using directives in the setup array
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
$setups   = array( array( 'nodeid'  => 'POtest',
                          'source'  => 'po',
                          'path'    => 'data/testPO.txt',
                        ),
                 );
$btnPrfx = 'array.PO';
dispLabel( '<h3>' . basename( __FILE__ ) . '</h3><p>setup from array, data from PO file', 'array.PO' );
foreach( $setups as $setup ) {
  $usleep = ( isset( $setup['ttl'] )) ? ( $setup['ttl'] + 100 ) * 1000  : 0;
/**********  define node $nodeid                        ************* */
  dispLabel( "<br>set up a '{$setup['nodeid']}' node, using setup array", $setup['nodeid'] );
  theTest( $setup, $setup['nodeid'], TRUE, $usleep ); // $dispAll, $usleep
} // end loooop...
/**********                                             ************* */
if( ! isset( $allInOne ))
  endPage();
