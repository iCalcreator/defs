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
 * index.php
 *
 * Test start up page
 */
if( ! isset( $included ))
  include 'defs.test.inc.php';
/**********                                             ************* */
dispResult( 'defs::getHandlers()', var_export( $defsFactory::getHandlers(), TRUE ));
$defsFactory::addHandler( 'test', 'defsTestHandler' );
dispCode( "defs::addHandler( 'test', 'defsTestHandler' );" );
dispResult( 'defs::getHandlers()', var_export( $defsFactory::getHandlers(), TRUE ));
/**********                                             ************* */
if( ! isset( $allInOne ))
  endPage();
