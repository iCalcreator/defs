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
 * test.null.setup.php
 *
 * Testing a (singleton) setup with an empty setup directive
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
$btnPrfx = 'null.setup';
$msg     =  '<h3>' . basename( __FILE__ ) . '</h3>';
$msg    .= '<p>Testing a (singleton) setup with an null setup directive, will generate PHP error...</p>';
$nodeid    = 'no.setup';
dispLabel( $msg, $nodeid );
/**********                                             ************* */
$msg  =  '<h3>' . basename( __FILE__ ) . '</h3>';
$msg .= '<p>Testing a (singleton) setup with an empty (null) setup directive</p>';
$nodeid    = 'null.setup';
dispLabel( $msg, $nodeid );
$nodeInst  = 'nodeNullSetupB'; // create a defs unique node object instance name
if( isset( $_REQUEST['invoke'] ) && ! empty( $_REQUEST['invoke'] )) {
  dispCode( '$defs = defs::factory( null );' );
  $$nodeInst = defs::factory( null );
}
else {
  dispCode( '$defs = defs::getInstance( null );' );
  $$nodeInst = defs::getInstance( null );
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
