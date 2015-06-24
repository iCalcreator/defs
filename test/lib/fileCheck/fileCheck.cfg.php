<?php
/**
 * check and show status (size) of (log) files, allow upload and emptying of the files
 *
 * allow upload (review)       (read rights)
 *       emptying of the files (opt. wites rights)
 *
 * @package    fileCheck
 * @copyright  Copyright (c) 2015 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @author     Kjell-Inge Gustafsson <ical@kigkonsult.se>
 * @link       http://kigkonsult.se/defs/index.php
 * @license    non-commercial use: Creative Commons
 *             Attribution-NonCommercial-NoDerivatives 4.0 International License
 *             (http://creativecommons.org/licenses/by-nc-nd/4.0/)
 * @version    1.0
 *
 * fileCheck Files
 *
 * fileCheck.cfg.php    this file
 * fileCheck.php        frontend script
 * fileCheck.bg.php     Ajax backend script
 * fileCheck.js         javascriptscript
 */
/* *****     files to monitor, with abs. path  */
$fCfiles     = array();
if( FALSE !== ( $errlog = ini_get( 'error_log' )))   // PHP spec. error_log
  $fCfiles[] = $errlog;
$fCfiles[]   = '/opt/work/defs/test/log/defs.log';   // defs err/debug log
/* *****     fileCheck base url          ***** */
$base        = 'http://' . $_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] ) . '/lib/fileCheck/';
/* *****     fileCheck javascript src    ***** */
$fCjsUrl     = $base . 'fileCheck.js';
/* *****     fileCheck ajax url          ***** */
$fCurl       = $base . 'fileCheck.bg.php';
/* *****     (div) box layout            ***** */
$fCboxStyles = array( 'cssFloat'           => 'right',
                      'fontFamily'         => 'monospace',
                      'verticalAlign'      => 'top',
                      'padding'            => '0px',
                      'margin'             => '0px',
                      'borderWidth'        => '0 0 1px 1px', // bottom, left only
                      'borderStyle'        => 'dashed',
                      'borderColor'        => '#dfdfdf',
                      'borderRadius'       => '5px',         // w3c
                      'MozBorderRadius'    => '5px',         // mozilla
                      'WebkitBorderRadius' => '5px',         // webkit
                    );
/* *****     legend alignment            ***** */
$fClegendAlign = 'right'; // 'left';
/* *****     button texts                ***** */
$fClabels    = array( 'legend'         => 'check logs',
                      'refresh'        => 'check',
                      'upload'         => 'upl',
                      'uploadTitle'    => 'upload',
                      'makeEmpty'      => '--',
                      'makeEmptyTitle' => 'empty',
                    );
