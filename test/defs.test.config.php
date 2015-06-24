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
 * defs.test.config.php
 */
date_default_timezone_set( 'Europe/Stockholm' );
$logDir      = __DIR__ . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR;
/**********  place and include XBenchmark performance tool (or not), NOT included
  Examine XBenchmark from
  http://www.phpclasses.org/package/9037-PHP-Log-time-and-resources-during-script-execution.html
                                                            ************* */
// define( 'XBENCHMARK_LOGS_DIR',  $logDir );
// include 'XBenchmark.php';
/**********  include defs, namespace test, NOT included     ************* */
// require 'defsLoader.php';
// $defsFactory = 'defs\\defs';
/**********  invoke defs loader, NOT using namespaces       ************* */
require realpath( __DIR__ . DIRECTORY_SEPARATOR . '..' ) . DIRECTORY_SEPARATOR . 'defs.php';
$defsFactory = 'defs';
/**********  set up PEAR log (OR NOT),
             make a PEAR Log adapt; force log flush when a crash appears  */
$logFile     = $logDir . 'defs.log';
// $logFile     = FALSE;
$prio        = ( isset( $_REQUEST['lp'] )) ? $_REQUEST['lp'] : LOG_DEBUG;
if((FALSE !== $logFile ) && ( -1 < $prio )) {
  require_once 'Log.php';
  class defsLog extends Log { public function _destruct() { $this->flush(); parent::_destruct(); }}
  $defslog   = defsLog::singleton( 'file', $logFile, '', array(), $prio ); // prod: LOG_ERR
  $defsFactory::$log = $defslog;
  $prios     = array( -1          => 'NONE',      //   no logging
                      LOG_EMERG   => 'LOG_EMERG', //   System is unusable
                      LOG_ALERT   => 'LOG_ALERT', //   Immediate action required
                      LOG_CRIT    => 'LOG_CRIT',  //   Critical conditions
                      LOG_ERR     => 'LOG_ERR',
                      LOG_WARNING => 'LOG_WARNING',
                      LOG_NOTICE  => 'LOG_NOTICE',
                      LOG_INFO    => 'LOG_INFO',
                      LOG_DEBUG   => 'LOG_DEBUG',
                    );
}
/**********  include kint debug tool (or not...), NOT included
             more info at http://raveren.github.io/kint/       ********** */
// require '../../includes/kint-0.9/Kint.class.php';
/**********  include fileCheck (or not...)
             display (log files) name and size in HTML form
             for config, examine lib/fileCheck/fileCheck.cfg.php ******** */
$includeFileCheck = TRUE;  // FALSE;
