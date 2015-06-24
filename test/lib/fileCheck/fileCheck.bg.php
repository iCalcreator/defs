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
 *  fileCheck.bg.php
 */
/* *****     perform the axaj tasks, if any ** */
if( ! isset( $_REQUEST['fc'] ) || empty( $_REQUEST['fc'] ))
  exit;
require 'fileCheck.cfg.php';
switch( TRUE ) {
  case( is_numeric( $_REQUEST['fc'] )) :
    $fc      = (int) $_REQUEST['fc'] - 1;
    if( isset( $fCfiles[$fc] ) && is_file( $fCfiles[$fc]  ) && is_writeable( $fCfiles[$fc] ))
      file_put_contents( $fCfiles[$fc], '' );
    clearstatcache();
    break;
  case( 'upl' == substr( $_REQUEST['fc'], 0, 3 )) :
    $fc      = substr( $_REQUEST['fc'], 3 );
    if( is_numeric( $fc )) {
      $fc -= 1;
      if( isset( $fCfiles[$fc] )) {
        $filesize = filesize( $fCfiles[$fc] );
        $filename = basename( $fCfiles[$fc] );
        header( 'Expires: 0' );
        header( 'Content-type: text/plain; charset=UTF-8');
        header( 'Content-Length: ' . $filesize );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        header( 'Cache-Control: max-age=10' );
        ob_clean();
        flush();
        readfile( $fCfiles[$fc] );
        exit();
      }
    }
    break;
  default:
    break;
} // end switch( TRUE )
/* *****     Axaj call, return file[-s] size * */
$output   = array();
foreach( $fCfiles as $x => $file ) {
  $output[$x] =  array( 'file' => basename( $file ),
                        'size' => filesize( $file ),
                      );
  $output[$x]['writeable'] = ( is_writeable( $file )) ? 1 : 0;
}
$output   = json_encode( $output );
$filesize = strlen( $output );
header( 'Content-type: application/json; charset=UTF-8');
header( 'Content-Length: '.$filesize );
header( 'Content-Disposition: attachment; filename="result.json"' );
header( 'Cache-Control: max-age=10' );
ob_clean();
flush();
echo $output;
exit();
