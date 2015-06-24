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
 * test.no.setup.php
 *
 * Testing a (singleton) setup with NO setup directive
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
$btnPrfx = 'no.setup';
$msg     =  '<h3>' . basename( __FILE__ ) . '</h3>';
$msg    .= '<p>Testing a (singleton) setup with an NO setup directive, will generate PHP error...</p>';
$nodeid    = 'no.setup';
dispLabel( $msg, $nodeid );

$nodeInst  = 'nodeNullSetupA'; // create a defs unique node object instance name
if( isset( $_REQUEST['invoke'] ) && ! empty( $_REQUEST['invoke'] )) {
  dispCode( '$defs = defs::factory();' );
  $$nodeInst = defs::factory();
}
else {
  dispCode( '$defs = defs::getInstance();' );
  $$nodeInst = defs::getInstance();
}
if( empty( $$nodeInst ))
  dispLabel( 'unvalid $defs created...' );
else {
  dispLabel(  'display all values using toString method' );
  dispCode(   '$res = defs-&gt;toString();' );
  dispResult( '$res', $$nodeInst->toString());
// Kint::dump( $$nodeInst );
  dispResult( 'var_export node obj', var_export( $$nodeInst, TRUE ), $nodeid );
}
/**********                                             ************* */
if( ! isset( $allInOne ))
  endPage();
